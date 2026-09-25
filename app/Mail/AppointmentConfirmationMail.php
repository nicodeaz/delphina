<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function build(): self
    {
        // A booking with several services is several rows sharing a group_id.
        $appointments = $this->appointment->group_id
            ? Appointment::with('service')->where('group_id', $this->appointment->group_id)->orderBy('id')->get()
            : collect([$this->appointment->loadMissing('service')]);

        return $this->subject('Your appointment is confirmed · Nails by Delphina')
            ->view('emails.appointment-confirmation', [
                'appointment' => $this->appointment,
                'appointments' => $appointments,
            ]);
    }
}
