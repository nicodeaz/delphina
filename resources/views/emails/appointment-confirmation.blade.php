@extends('emails.layout', [
    'title' => 'Your appointment is confirmed',
    'preheader' => 'See you on '.$appointment->date->format('l j F').' at '.substr($appointment->time, 0, 5).'.',
])

@php
    $total = $appointments->sum(fn ($a) => (float) ($a->service->price ?? 0));
    $duration = $appointments->sum(fn ($a) => (int) ($a->service->duration ?? 0));
    $deposit = \App\Models\Payment::AMOUNT;
    $firstName = \Illuminate\Support\Str::of($appointment->name)->before(' ');
@endphp

@section('body')
    <p style="margin:0 0 6px; font-size:12px; font-weight:600; letter-spacing:3px; text-transform:uppercase; color:#554F13;">Booking confirmed</p>
    <h1 style="margin:0 0 16px; font-size:24px; line-height:32px; color:#2F2A09;">See you soon, {{ $firstName }}!</h1>
    <p style="margin:0 0 24px; font-size:15px; line-height:24px; color:#5E5720;">
        Your €{{ number_format($deposit, 0) }} deposit has been received and your appointment is confirmed.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAF8EF; border-radius:16px;">
        <tr>
            <td style="padding:18px 20px;">
                <p style="margin:0 0 4px; font-size:12px; font-weight:600; letter-spacing:2px; text-transform:uppercase; color:#8D8540;">When</p>
                <p style="margin:0 0 16px; font-size:17px; font-weight:700; color:#2F2A09;">
                    {{ $appointment->date->format('l j F Y') }} at {{ substr($appointment->time, 0, 5) }}
                </p>
                <p style="margin:0 0 4px; font-size:12px; font-weight:600; letter-spacing:2px; text-transform:uppercase; color:#8D8540;">Services · {{ $duration }} min</p>
                @foreach($appointments as $item)
                    <p style="margin:0 0 2px; font-size:15px; color:#2F2A09;">{{ $item->service->name ?? 'Service' }}</p>
                @endforeach
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px; font-size:14px; color:#5E5720;">
        <tr><td style="padding:4px 0;">Total</td><td align="right" style="padding:4px 0;">€{{ number_format($total, 2) }}</td></tr>
        <tr><td style="padding:4px 0;">Deposit paid</td><td align="right" style="padding:4px 0;">−€{{ number_format($deposit, 2) }}</td></tr>
        <tr><td style="padding:8px 0 0; font-weight:700; color:#2F2A09; border-top:1px solid #F1EDD7;">To pay at the studio</td><td align="right" style="padding:8px 0 0; font-weight:700; color:#2F2A09; border-top:1px solid #F1EDD7;">€{{ number_format(max(0, $total - $deposit), 2) }}</td></tr>
    </table>

    <p style="margin:24px 0 0; font-size:14px; line-height:22px; color:#5E5720;">
        Please arrive 5 minutes early. Need to change your appointment? You can reschedule free of charge with more than 24 hours' notice — just message Delfi on
        <a href="https://wa.me/353899409670" style="color:#554F13; font-weight:600;">WhatsApp</a>.
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:24px;">
        <tr>
            <td style="border-radius:14px; background-color:#554F13;">
                <a href="{{ route('policies') }}" style="display:inline-block; padding:12px 22px; font-size:14px; font-weight:600; color:#ffffff; text-decoration:none;">Booking policy</a>
            </td>
        </tr>
    </table>
@endsection
