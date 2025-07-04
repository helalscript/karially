<?php

namespace App\Http\Controllers\ApI\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CalendlyController extends Controller
{
    protected $apiUrl = 'https://api.calendly.com';
    protected $token;

    public function __construct()
    {
        $this->token = config('services.calendly.token'); // Ensure you have set this in your services.php config file
    }

    // Fetch coach availability schedule
    public function getAvailability(Request $request, $coachId)
    {
        $coach = Coach::findOrFail($coachId);

        $response = Http::withToken($this->token)
            ->get("{$this->apiUrl}/availability_schedules", [
                'user' => "https://api.calendly.com/users/{$coach->calendly_user_id}"
            ]);

        return response()->json($response->json());
    }

    // Create booking request + store in DB (manual confirmation)
    public function submitBooking(Request $request)
    {
        $request->validate([
            'coach_id' => 'required|exists:coaches,id',
            'start_time' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'coach_id' => $request->coach_id,
            'start_time' => Carbon::parse($request->selected_time),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Booking request submitted.', 'booking' => $booking]);
    }

    // Optional: Admin confirms and creates event via Calendly API
    public function confirmBooking($bookingId)
    {
        $booking = Booking::with('coach')->findOrFail($bookingId);

        // Simulate Calendly event creation (actual endpoint will vary)
        $response = Http::withToken($this->token)->post("{$this->apiUrl}/scheduled_events", [
            'invitee_email' => $booking->user->email,
            'event_type' => $booking->coach->calendly_link,
            'start_time' => Carbon::parse($booking->selected_time)->toIso8601String(),
        ]);

        if ($response->successful()) {
            $booking->update([
                'status' => 'confirmed',
                'calendly_link' => $response['resource']['uri'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Booking confirmed.', 'data' => $booking]);
    }
}
