<?php

namespace App\Services\API\V1\User;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BookingService
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
            $searchDate = $request->get('search_date', null);
            $serachStatus = $request->get('search_status', null);
            $data = $this->user->bookings()
                ->with([
                    'coach' =>
                        fn($query) =>
                        $query->select('id', 'name', 'avatar', 'email', 'category_id')
                            ->with([
                                'category' =>
                                    fn($query) =>
                                    $query->select('id', 'name')
                            ])
                ])
                ->when($searchDate, function ($query) use ($searchDate) {
                    return $query->whereDate('start_time', $searchDate);
                })
                ->when($serachStatus, function ($query) use ($serachStatus) {
                    return $query->where('status', $serachStatus);
                })
                ->select('id', 'coach_id', 'start_time', 'notes', 'status')
                ->latest()
                ->paginate($per_page);
            return $data;

        } catch (Exception $e) {
            Log::error("BookingService::index" . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Store a new resource.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function store(array $validatedData)
    {
        try {
            $booking = $this->user->bookings()->create([
                'coach_id' => $validatedData['coach_id'],
                'start_time' => $validatedData['start_time'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'pending', // Default status
            ]);

            return $booking;

        } catch (Exception $e) {
            Log::error("BookingService::store" . $e->getMessage());
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
            $booking = $this->user->bookings()
                ->with([
                    'coach' =>
                        fn($query) =>
                        $query->select('id', 'name', 'avatar', 'email', 'category_id')
                            ->with([
                                'category' =>
                                    fn($query) =>
                                    $query->select('id', 'name')
                            ])
                ])
                ->select('id', 'coach_id', 'start_time', 'notes', 'status')
                ->find($id);
            if (!$booking) {
                throw new Exception("Booking not found");
            }

            return $booking;

        } catch (Exception $e) {
            Log::error("BookingService::show" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a specific resource.
     *
     * @param int $id
     * @param array $validatedData
     * @return mixed
     */
    public function update(int $id, array $validatedData)
    {
        try {

        } catch (Exception $e) {
            Log::error("BookingService::update" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        try {
            // Logic to delete a specific resource
        } catch (Exception $e) {
            Log::error("BookingService::destroy" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel a specific booking.
     *
     * @param int $id
     * @return mixed
     */
    public function cancelBooking(int $id)
    {
        try {
            $booking = $this->user->bookings()
                ->find($id);
            if (!$booking) {
                throw new Exception("Booking not found");
            }
            if ($booking->status === 'cancelled') {
                return $booking;  // No need to cancel again if already cancelled
            }
            if ($booking->status === 'completed') {
                throw new Exception("Cannot cancel a completed booking");
            }
            $booking->status = 'cancelled';
            $booking->save();
            return $booking;
        } catch (Exception $e) {
            Log::error("BookingService::cancelBooking" . $e->getMessage());
            throw $e;
        }
    }

}