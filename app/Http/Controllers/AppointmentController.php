<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function myAppointments()
    {
        $appointments = auth()->user()->appointments()
            ->with(['service', 'payment'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('appointments.my', compact('appointments'));
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        // Delete Google Calendar events before cancelling
        try {
            $calendarService = app(GoogleCalendarService::class);
            $calendarService->deleteAppointmentEvents($appointment);
        } catch (\Exception $e) {
            Log::error('Failed to delete calendar events: ' . $e->getMessage());
            // Continue with cancellation even if calendar deletion fails
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        return view('appointments.show', compact('appointment'));
    }
}
