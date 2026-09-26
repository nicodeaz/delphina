@extends('emails.layout', [
    'title' => 'New booking',
    'preheader' => $appointment->name.' booked '.$appointment->date->format('l j F').' at '.substr($appointment->time, 0, 5).'. Waiting for the €15 deposit.',
])

@php
    $total = $appointments->sum(fn ($a) => (float) ($a->service->price ?? 0));
    $duration = $appointments->sum(fn ($a) => (int) ($a->service->duration ?? 0));
    $agendaUrl = route('admin.agenda', ['month' => $appointment->date->format('Y-m'), 'open' => $appointment->id]);
    $phoneDigits = preg_replace('/\D/', '', (string) $appointment->phone);
    if ($phoneDigits !== '' && ! str_starts_with(trim((string) $appointment->phone), '+')) {
        $phoneDigits = str_starts_with($phoneDigits, '00') ? substr($phoneDigits, 2) : (str_starts_with($phoneDigits, '0') ? '353'.substr($phoneDigits, 1) : $phoneDigits);
    }
    $label = 'font-size:12px; font-weight:600; letter-spacing:2px; text-transform:uppercase; color:#8D8540;';
@endphp

@section('body')
    <p style="margin:0 0 6px; font-size:12px; font-weight:600; letter-spacing:3px; text-transform:uppercase; color:#554F13;">New booking 💅</p>
    <h1 style="margin:0 0 8px; font-size:24px; line-height:32px; color:#2F2A09;">{{ $appointment->name }}</h1>
    <p style="margin:0 0 24px; font-size:15px; line-height:24px; color:#5E5720;">
        {{ $appointment->date->format('l j F') }} at <strong>{{ substr($appointment->time, 0, 5) }}</strong> · {{ $duration }} min
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAF8EF; border-radius:16px;">
        <tr>
            <td style="padding:18px 20px;">
                <p style="margin:0 0 6px; {{ $label }}">Services</p>
                @foreach($appointments as $item)
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding:2px 0; font-size:15px; color:#2F2A09;">{{ $item->service->name ?? 'Service' }}</td>
                            <td align="right" style="padding:2px 0; font-size:15px; color:#2F2A09;">€{{ number_format($item->service->price ?? 0, 2) }}</td>
                        </tr>
                    </table>
                @endforeach
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:8px; border-top:1px solid #F1EDD7;">
                    <tr>
                        <td style="padding-top:8px; font-size:15px; font-weight:700; color:#2F2A09;">Total</td>
                        <td align="right" style="padding-top:8px; font-size:15px; font-weight:700; color:#2F2A09;">€{{ number_format($total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px; font-size:14px; line-height:22px; color:#5E5720;">
        <tr><td style="padding:3px 0; width:90px; {{ $label }}">Phone</td><td style="padding:3px 0;">{{ $appointment->phone ?: '—' }}</td></tr>
        <tr><td style="padding:3px 0; {{ $label }}">Email</td><td style="padding:3px 0;">{{ $appointment->email ?: '—' }}</td></tr>
        @if($appointment->notes)
            <tr><td style="padding:3px 0; vertical-align:top; {{ $label }}">Notes</td><td style="padding:3px 0;">{{ $appointment->notes }}</td></tr>
        @endif
    </table>

    <p style="margin:20px 0 0; padding:12px 16px; border-radius:12px; background-color:#FEF3C7; font-size:14px; line-height:22px; color:#92400E;">
        ⏳ Waiting for the €{{ number_format(\App\Models\Payment::AMOUNT, 0) }} deposit. Check Revolut, then confirm it in the agenda — the client gets the confirmation email then.
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:24px;">
        <tr>
            <td style="border-radius:14px; background-color:#554F13;">
                <a href="{{ $agendaUrl }}" style="display:inline-block; padding:12px 22px; font-size:14px; font-weight:600; color:#ffffff; text-decoration:none;">Open in the agenda</a>
            </td>
            @if($phoneDigits !== '')
                <td style="width:10px;"></td>
                <td style="border-radius:14px; border:1px solid #DDD6AA;">
                    <a href="https://wa.me/{{ $phoneDigits }}" style="display:inline-block; padding:11px 20px; font-size:14px; font-weight:600; color:#554F13; text-decoration:none;">WhatsApp client</a>
                </td>
            @endif
            @if($appointment->email)
                <td style="width:10px;"></td>
                <td style="border-radius:14px; border:1px solid #DDD6AA;">
                    <a href="mailto:{{ $appointment->email }}?subject={{ rawurlencode('Your appointment at Nails by Delphina') }}" style="display:inline-block; padding:11px 20px; font-size:14px; font-weight:600; color:#554F13; text-decoration:none;">Email client</a>
                </td>
            @endif
        </tr>
    </table>
@endsection
