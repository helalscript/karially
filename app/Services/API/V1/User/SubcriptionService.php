<?php

namespace App\Services\API\V1\User;

use App\Models\Package;
use App\Models\Pakage;
use App\Models\Payment;
use App\Models\Subcription;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook as StripeWebhook;
use Stripe\Product as StripeProduct;
use Stripe\Price as StripePrice;
use Stripe\Subscription as StripeSubscription;
use Stripe\Transfer as StripeTransfer;

class SubcriptionService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function getPackages($request)
    {
        try {
            $per_page = $request->get('per_page', 10);
            $billing_cycle = $request->get('billing_cycle', 'monthly');
            $data = Package::with(['features'])
                ->when($billing_cycle, function ($query) use ($billing_cycle) {
                    return $query->where('billing_cycle', $billing_cycle);
                })
                ->select('id', 'title', 'slug', 'trial_period_days', 'billing_cycle', 'price', 'status')
                ->where('status', 'active')
                ->latest()
                ->paginate($per_page);
            return $data;

        } catch (Exception $e) {
            Log::error("SubcriptionService::index" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retrieve a single package by its ID.
     *
     * @param int $id
     * @return mixed
     * @throws Exception if the package is not found.
     */

    public function getSinglePackages($id)
    {
        try {
            $data = Package::with(['features'])
                ->select('id', 'title', 'slug', 'trial_period_days', 'billing_cycle', 'price', 'status')
                ->latest()
                ->where('status', 'active')
                ->find($id);
            if (!$data) {
                throw new Exception("Package not found");
            }
            return $data;
        } catch (Exception $e) {
            Log::error("SubcriptionService::index" . $e->getMessage());
            throw $e;
        }
    }




    public function getMySubscription()
    {
        try {
            $subscription = Subcription::where('user_id', $this->user->id)
                ->select('id', 'package_id', 'status', 'started_at', 'ended_at', 'stripe_subscription_id')
                ->with(['package', 'package.features'])
                // ->where('status', 'active')
                ->get();
            return $subscription;
        } catch (Exception $e) {
            Log::error('PaymentController::getMySubscription', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch my subscription'], 500);
        }
    }

    /**
     * Create a Stripe Checkout session for a plan and redirect to checkout page
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function createSubscription($validatedData, $id)
    {
        // Fetch the plan and features
        $package = Package::with('features')->find($id);
        if (!$package) {
            throw new Exception('Plan item not found.');
        }

        try {
            DB::beginTransaction();
            // $feature = $package->features->first();
            $packageName = $package->title;
            $unitAmount = $package->price * 100;
            $billing_cycle = $package->billing_cycle;

            // Check or create product
            $existingProducts = StripeProduct::all(['limit' => 100]);
            $product = collect($existingProducts->data)->firstWhere('name', $packageName);

            if (!$product) {
                $product = StripeProduct::create([
                    'name' => $packageName,
                    'description' => 'This is a ' . $package->billing_cycle . ' plan.',
                ]);
            }

            // Check if a matching price already exists
            $existingPrices = StripePrice::all(['product' => $product->id, 'limit' => 100]);
            $price = collect($existingPrices->data)->firstWhere(function ($p) use ($unitAmount) {
                return $p->unit_amount === $unitAmount &&
                    $p->currency === 'usd' &&
                    $p->recurring->interval === 'month';
            });

            // Create new price if not found
            if (!$price) {
                $recurringInterval = match ($billing_cycle) {
                    'lifetime' => null,
                    'yearly' => ['interval' => 'year'],
                    'monthly' => ['interval' => 'month'],
                    'weekly' => ['interval' => 'week'],
                    default => throw new Exception('Invalid Billing Cycle'),
                };

                $price = StripePrice::create([
                    'unit_amount' => $unitAmount,
                    'currency' => 'usd',
                    'recurring' => $recurringInterval,
                    'product' => $product->id,
                ]);
            }

            // Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'mode' => 'subscription',
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ]
                ],
                'subscription_data' => [
                    'trial_period_days' => $package->trial_period_days ?? null,
                ],
                'success_url' => $validatedData['success_url'],
                'cancel_url' => $validatedData['cancel_url'],
                'metadata' => [
                    'type' => 'subscription',
                    'package_id' => $package->id,
                    'user_id' => $this->user->id,
                    'payment_type' => 'subscription_payment',
                ],
            ]);

            // Store payment record as pending
            $package->payments()->create([
                'user_id' => $this->user->id,
                'amount' => $package->price,
                'currency' => 'usd',
                'status' => 'pending',
                'stripe_payment_id' => $session->id,
            ]);
            DB::commit();
            return $session;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Stripe Error: ' . $e->getMessage());
            throw new Exception('Failed to create payment session: ' . $e->getMessage());
        }
    }


    /**
     * Handle incoming Stripe webhook events.
     */
    public function handleWebhook($request)
    {
        Log::info('SubcriptionService::handleWebhook- Stripe webhook received');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = StripeWebhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('SubcriptionService::handleWebhook-Stripe webhook error: Invalid payload');
            // return response()->json(['status' => 'invalid payload'], 400);
            throw new Exception('Invalid payload: ' . $e->getMessage(), 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('SubcriptionService::handleWebhook-Stripe webhook error: Invalid signature');
            // return response()->json(['status' => 'invalid signature'], 400);
            throw new Exception('Invalid signature: ' . $e->getMessage(), 400);
        }

        // Dispatch by event type
        switch ($event->type) {
            case 'checkout.session.completed':
                Log::info('SubcriptionService::handleWebhook- Stripe checkout session completed');
                $this->successPayment($event->data->object);
                break;
            case 'checkout.session.expired':
            case 'checkout.session.async_payment_failed':
                Log::info('SubcriptionService::handleWebhook- Stripe checkout session expired or failed');
                $this->cancelPayment($event->data->object);
                break;
            case 'invoice.paid':
                $this->handleInvoicePaid($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            default:
                Log::info("Unhandled Stripe event type: " . $event->type);
        }

        return response()->json(['status' => 'webhook handled']);
    }

    /**
     * Handles successful payment processing by updating the payment status
     * and creating a local subscription record. Retrieves the Stripe subscription
     * to determine the subscription end date if available.
     *
     * @param object $session The Stripe session object containing metadata
     *                        and subscription information.
     */
    private function successPayment($session)
    {

        if ($session->metadata->payment_type === 'subscription_payment') {
            $payment = Payment::where('stripe_payment_id', $session->id)->first();

            if (!$payment) {
                Log::warning('SubcriptionService::successPayment- Payment not found for session: ' . $session->id);
                return;
            }

            $subscriptionId = $session->subscription ?? null;
            $endsAt = null;

            if ($subscriptionId) {
                try {
                    $stripeSubscription = StripeSubscription::retrieve($subscriptionId);
                    $current_period_end = $stripeSubscription->items->data[0]->current_period_end ?? null;
                    if (!empty($current_period_end)) {
                        $endsAt = Carbon::createFromTimestamp($current_period_end)->format('Y-m-d H:i:s');
                    }

                } catch (\Exception $e) {
                    Log::error("Failed to retrieve Stripe subscription: " . $e->getMessage());
                }
            }

            // Update payment record
            $payment->update([
                'status' => 'completed',
            ]);

            // Create local subscription record
            Subcription::create([
                'user_id' => $session->metadata->user_id,
                'package_id' => $session->metadata->package_id ?? null,
                'stripe_subscription_id' => $subscriptionId,
                'started_at' => now(),
                'ended_at' => $endsAt,
                'status' => 'active',
            ]);

            Log::info("SubcriptionService::successPayment- Payment completed. Subscription ends at: " . ($endsAt ?? 'N/A'));
        }

    }


    /**
     * Handles a Stripe Checkout Session cancellation event.
     *
     * @param  \Stripe\Checkout\Session  $session
     * @return void
     */
    private function cancelPayment($session)
    {
        $payment = Payment::where('stripe_payment_id', $session->id)->first();

        if ($payment) {
            $payment->update([
                'status' => 'canceled',
            ]);
            Log::info('SubcriptionService::cancelPayment- Payment marked as canceled: ' . $payment->id);
        } else {
            Log::warning('SubcriptionService::cancelPayment- Payment not found for canceled session: ' . $session->id);
        }
    }

    private function handleInvoicePaid($invoice)
    {
        if (isset($invoice->subscription)) {
            try {
                $stripeSub = StripeSubscription::retrieve($invoice->subscription);
                $endsAt = Carbon::createFromTimestamp($stripeSub->current_period_end);

                Subcription::where('stripe_subscription_id', $stripeSub->id)
                    ->update([
                        'ended_at' => $endsAt,
                        'status' => 'active',
                    ]);

                Log::info('SubcriptionService::handleInvoicePaid- Subscription renewed: ' . $stripeSub->id);
            } catch (\Exception $e) {
                Log::error("SubcriptionService::handleInvoicePaid- Failed to update ended_at on invoice.paid: " . $e->getMessage());
            }
        }
    }

    private function handleSubscriptionDeleted($stripeSub)
    {
        Subcription::where('stripe_subscription_id', $stripeSub->id)
            ->update([
                'status' => 'cancelled',
                'ended_at' => now(),
            ]);

        Log::info('SubcriptionService::handleSubscriptionDeleted- Subscription marked as cancelled from Stripe: ' . $stripeSub->id);
    }

    private function handleSubscriptionUpdated($stripeSub)
    {

        $cancelAt = null;
        $current_period_end = $stripeSub->items->data[0]->current_period_end ?? null;
        if (!empty($current_period_end)) {
            $cancelAt = Carbon::createFromTimestamp($current_period_end)->format('Y-m-d H:i:s');
        }
        Subcription::where('stripe_subscription_id', $stripeSub->id)
            ->update([
                'ended_at' => $cancelAt,
            ]);

        Log::info('SubcriptionService::handleSubscriptionUpdated- Subscription updated (possibly scheduled cancel): ' . $stripeSub->id);
    }

}