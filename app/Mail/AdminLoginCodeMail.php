<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminLoginCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code, public int $minutes) {}

    public function build(): self
    {
        return $this->subject("{$this->code} is your Delphina Studio login code")
            ->view('emails.login-code')
            ->text('emails.text.login-code')
            ->with([
                'code' => $this->code,
                'minutes' => $this->minutes,
            ]);
    }
}
