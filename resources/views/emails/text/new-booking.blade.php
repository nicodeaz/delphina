@php
    $total = $appointments->sum(fn ($a) => (float) ($a->service->price ?? 0));
    $duration = $appointments->sum(fn ($a) => (int) ($a->service->duration ?? 0));
    $phoneDigits = preg_replace('/\D/', '', (string) $appointment->phone);
    if ($phoneDigits !== '' && ! str_starts_with(trim((string) $appointment->phone), '+')) {
        $phoneDigits = str_starts_with($phoneDigits, '00') ? substr($phoneDigits, 2) : (str_starts_with($phoneDigits, '0') ? '353'.substr($phoneDigits, 1) : $phoneDigits);
    }
@endphp
New booking: {!! $appointment->name !!}

{!! $appointment->date->format('l j F') !!} at {!! substr($appointment->time, 0, 5) !!} · {!! $duration !!} min

Services:
@foreach($appointments as $item)
- {!! $item->service->name ?? 'Service' !!} (€{!! number_format($item->service->price ?? 0, 2) !!})
@endforeach
Total: €{!! number_format($total, 2) !!}

Phone: {!! $appointment->phone ?: '—' !!}
@if($phoneDigits !== '')
WhatsApp: https://wa.me/{!! $phoneDigits !!}
@endif
Email: {!! $appointment->email ?: '—' !!}
@if($appointment->notes)
Notes: {!! $appointment->notes !!}
@endif

Waiting for the €{!! number_format(\App\Models\Payment::AMOUNT, 0) !!} deposit. Check Revolut, then confirm it in the agenda — the client gets the confirmation email then.

Open in the agenda: {!! route('admin.agenda', ['month' => $appointment->date->format('Y-m'), 'open' => $appointment->id]) !!}
