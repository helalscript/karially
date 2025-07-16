<?php

namespace App\Services\API\V1\User;

use App\Models\Package;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function index($request)
    {
        try {
            $per_page = $request->get('per_page', 10);
            $payments = Payment::where('user_id', $this->user->id)
                ->where('payable_type', Package::class) // <- Filter for Package
                ->with('payable:id,title,slug,billing_cycle,trial_period_days,price,status')
                ->select('id', 'user_id', 'payable_id', 'payable_type', 'payment_method', 'amount', 'status', 'created_at', 'updated_at')
                ->paginate($per_page);
            return $payments;
        } catch (Exception $e) {
            Log::error("PaymentService::index" . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Display a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        try {
            $payment = Payment::query()
                ->where([['id', $id], ['user_id', $this->user->id], ['payable_type', Package::class]])
                ->with(['payable:id,title,slug,billing_cycle,trial_period_days,price,status'])
                ->select('id', 'user_id', 'payable_id', 'payable_type', 'payment_method', 'amount', 'status', 'created_at', 'updated_at')
                ->first();
            if (!$payment) {
                throw new Exception("Payment not found");
            }
            return $payment;
        } catch (Exception $e) {
            Log::error("PaymentService::show" . $e->getMessage());
            throw $e;
        }
    }

}