<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Only reachable via the admin.appointments.cancel route (admin-guarded),
     * so the caller is always staff cancelling a guest or registered booking
     * on their behalf.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        if (! $appointment->canBeCancelled()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This appointment cannot be cancelled.'], 422);
            }

            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointments = $appointment->group_id
            ? Appointment::where('group_id', $appointment->group_id)->get()
            : collect([$appointment]);

        $appointments->each->update(['status' => 'cancelled']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}
