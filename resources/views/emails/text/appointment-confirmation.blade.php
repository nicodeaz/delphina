@php
    $total = $appointments->sum(fn ($a) => (float) ($a->service->price ?? 0));
    $deposit = \App\Models\Payment::AMOUNT;
@endphp
Booking confirmed

See you soon, {!! \Illuminate\Support\Str::of($appointment->name)->before(' ') !!}!
Your €{!! number_format($deposit, 0) !!} deposit has been received and your appointment is confirmed.

When: {!! $appointment->date->format('l j F Y') !!} at {!! substr($appointment->time, 0, 5) !!}

Services:
@foreach($appointments as $item)
- {!! $item->service->name ?? 'Service' !!} (€{!! number_format($item->service->price ?? 0, 2) !!})
@endforeach

Total: €{!! number_format($total, 2) !!}
Deposit paid: -€{!! number_format($deposit, 2) !!}
To pay at the studio: €{!! number_format(max(0, $total - $deposit), 2) !!}

Please arrive 5 minutes early. Need to change your appointment? You can reschedule free of charge with more than 24 hours' notice — message Delfi on WhatsApp: https://wa.me/353899409670

Booking policy: {!! route('policies') !!}

Nails by Delphina · Tallaght, Dublin 24
{!! url('/') !!}
