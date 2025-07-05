<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
        // You can add middleware or other initializations here if needed
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'per_page' => 'integer|min:1|max:100',
            'search_date' => 'nullable|date_format:Y-m-d',
            'search_status' => 'nullable|string|in:pending,upcoming,confirmed,cancelled,re_scheduled',
        ]);
        try {
            $bookings = $this->bookingService->index($request);
            return Helper::jsonResponse(true, 'Bookings fetched successfully.', 200, $bookings, true);
        } catch (\Exception $e) {
            Log::error("BookingController::index - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch bookings.', 500, [$e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'coach_id' => 'required|exists:coaches,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $booking = $this->bookingService->store($validatedData);
            return Helper::jsonResponse(true, 'Booking created successfully.', 201, $booking);
        } catch (\Exception $e) {
            Log::error("BookingController::store - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to create booking.', 500, [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $booking = $this->bookingService->show($id);
            return Helper::jsonResponse(true, 'Booking details fetched successfully.', 200, $booking);
        } catch (\Exception $e) {
            Log::error("BookingController::show - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch booking details.', 500, [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'start_time' => 'sometimes|required|date_format:Y-m-d\TH:i',
            'notes' => 'nullable|string|max:500',
        ]);
        try {
            $booking = $this->bookingService->update($id, $validatedData);
            return Helper::jsonResponse(true, 'Booking updated successfully.', 200, $booking);
        } catch (\Exception $e) {
            Log::error("BookingController::update - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to update booking.', 500, [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->bookingService->destroy($id);
            return Helper::jsonResponse(true, 'Booking deleted successfully.', 200);
        } catch (\Exception $e) {
            Log::error("BookingController::destroy - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to delete booking.', 500, [$e->getMessage()]);
        }
    }

    public function cancelBooking(string $id)
    {
        try {
            $this->bookingService->cancelBooking($id);
            return Helper::jsonResponse(true, 'Booking cancelled successfully.', 200);
        } catch (\Exception $e) {
            Log::error("BookingController::cancleBooking - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to cancel booking.', 500, [$e->getMessage()]);
        }
    }
}
