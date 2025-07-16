<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $payments = $this->paymentService->index($request);
            return Helper::jsonResponse(true, 'Payments fetched successfully', 200, $payments, true);
        } catch (Exception $e) {
            Log::error("PaymentController::index" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch payments', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $payment = $this->paymentService->show($id);
            return Helper::jsonResponse(true, 'Payment fetched successfully', 200, $payment);
        } catch (Exception $e) {
            Log::error("PaymentController::show" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch payment', 500);
        }
    }

    public function getInvoice()
    {
        //
    }
}
