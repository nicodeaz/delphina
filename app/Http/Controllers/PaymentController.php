<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function show(Appointment $appointment)
    {
        if (Auth::check() && $appointment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($appointment->payment && $appointment->payment->status === 'paid') {
            return redirect()->route('home')
                ->with('success', 'This appointment has already been paid.');
        }

        $payment = $appointment->payment ?? Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => Payment::AMOUNT,
            'status' => 'pending',
        ]);

        return view('payments.show', [
            'appointment' => $appointment,
            'payment' => $payment,
        ]);
    }

    public function process(Request $request, Appointment $appointment)
    {
        if (Auth::check() && $appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:card,transfer',
        ]);

        $payment = $appointment->payment ?? Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => Payment::AMOUNT,
            'status' => 'pending',
        ]);

        // Simular procesamiento de pago
        $payment->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
        ]);

        // Create Google Calendar events after successful payment
        try {
            $calendarService = app(GoogleCalendarService::class);
            $eventIds = $calendarService->createAppointmentEvents($appointment);

            $appointment->update([
                'status' => 'approved',
                'google_event_id' => $eventIds['admin_event_id'],
                'google_client_event_id' => $eventIds['client_event_id'],
            ]);

            // Send confirmation email with calendar invite
            $this->sendConfirmationEmail($appointment);

        } catch (\Exception $e) {
            Log::error('Failed to create calendar events: ' . $e->getMessage());
            // Still mark as approved but log the error
            $appointment->update(['status' => 'approved']);
        }

        return redirect()->route('home')
            ->with('success', 'Payment completed successfully! Your appointment is confirmed and added to your calendar.');
    }

    private function sendConfirmationEmail(Appointment $appointment)
    {
        try {
            // Generate .ics file content
            $icsContent = $this->generateICSFile($appointment);

            Mail::send('emails.appointment-confirmation', [
                'appointment' => $appointment,
                'icsContent' => $icsContent,
            ], function ($message) use ($appointment, $icsContent) {
                $message->to($appointment->email, $appointment->name)
                        ->subject('Appointment Confirmed - Delfi Nail Technician')
                        ->attachData($icsContent, 'appointment.ics', [
                            'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
                        ]);
            });

        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
    }

    private function generateICSFile(Appointment $appointment): string
    {
        $service = $appointment->service;
        $startDateTime = $appointment->date->format('Y-m-d') . 'T' . $appointment->time . ':00';
        $endDateTime = date('Ymd\THis', strtotime($startDateTime) + ($service->duration * 60));
        $startDateTimeFormatted = date('Ymd\THis', strtotime($startDateTime));

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//Delfi Nail Technician//Appointment//EN\r\n";
        $ics .= "METHOD:REQUEST\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . uniqid() . "@delfinailtechnician.ie\r\n";
        $ics .= "DTSTART:{$startDateTimeFormatted}\r\n";
        $ics .= "DTEND:{$endDateTime}\r\n";
        $ics .= "SUMMARY:{$service->name} - {$appointment->name}\r\n";
        $ics .= "DESCRIPTION:Appointment with Delfi Nail Technician\\nService: {$service->name}\\nDuration: {$service->duration} minutes\\nLocation: Dublin 24, Tallaght\r\n";
        $ics .= "LOCATION:Dublin 24, Tallaght\r\n";
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }
}
