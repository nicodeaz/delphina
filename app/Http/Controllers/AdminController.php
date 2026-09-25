<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $active = fn () => Appointment::whereNotIn('status', ['cancelled', 'rejected']);
        // One booking can be several rows (one per service) sharing a group_id.
        $countBookings = fn ($query) => $query->get(['id', 'group_id'])
            ->unique(fn (Appointment $a) => $a->group_id ?: "appointment-{$a->id}")
            ->count();

        $bookingsThisMonth = $countBookings($active()->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString().' 23:59:59']));
        $upcomingBookings = $countBookings(Appointment::whereIn('status', ['pending', 'approved'])->whereDate('date', '>=', today()));
        $depositsCollected = (float) Payment::where('status', 'paid')->sum('amount');

        $totalRevenue = Appointment::query()
            ->leftJoin('services', 'appointments.service_id', '=', 'services.id')
            ->leftJoin('payments', 'payments.appointment_id', '=', 'appointments.id')
            ->whereNotIn('appointments.status', ['cancelled', 'rejected'])
            ->selectRaw("SUM(CASE WHEN appointments.status = 'completed' THEN COALESCE(services.price, 0) WHEN payments.status = 'paid' THEN COALESCE(payments.amount, 0) ELSE 0 END) as total")
            ->value('total') ?? 0;

        $totalServiceValue = Appointment::query()
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->whereNotIn('appointments.status', ['cancelled', 'rejected'])
            ->sum('services.price');

        $remainingBalance = max(0, (float) $totalServiceValue - (float) $totalRevenue);

        // Monthly bookings for the current year (by appointment date; SQLite-specific strftime)
        $monthlyAppointmentsRaw = $active()
            ->whereRaw("strftime('%Y', date) = ?", [date('Y')])
            ->get(['id', 'group_id', 'date'])
            ->unique(fn (Appointment $a) => $a->group_id ?: "appointment-{$a->id}")
            ->countBy(fn (Appointment $a) => (int) $a->date->format('n'))
            ->all();

        $monthlyAppointments = array_fill(1, 12, 0);
        foreach ($monthlyAppointmentsRaw as $month => $count) {
            $monthlyAppointments[$month] = $count;
        }

        $monthlyRevenueRaw = Appointment::query()
            ->leftJoin('services', 'appointments.service_id', '=', 'services.id')
            ->leftJoin('payments', 'payments.appointment_id', '=', 'appointments.id')
            ->whereNotIn('appointments.status', ['cancelled', 'rejected'])
            ->whereRaw("strftime('%Y', appointments.date) = ?", [date('Y')])
            ->selectRaw("strftime('%m', appointments.date) as month, SUM(CASE WHEN appointments.status = 'completed' THEN COALESCE(services.price, 0) WHEN payments.status = 'paid' THEN COALESCE(payments.amount, 0) ELSE 0 END) as total")
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($monthlyRevenueRaw as $month => $total) {
            $monthlyRevenue[(int) $month] = (float) $total;
        }

        $popularServices = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->whereNotIn('appointments.status', ['cancelled', 'rejected'])
            ->selectRaw('services.name, COUNT(*) as count')
            ->groupBy('services.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->pluck('count', 'name')
            ->toArray();

        return view('admin.dashboard', compact(
            'bookingsThisMonth',
            'upcomingBookings',
            'depositsCollected',
            'totalRevenue',
            'remainingBalance',
            'monthlyAppointments',
            'monthlyRevenue',
            'popularServices'
        ));
    }

    /**
     * One list for every booking (grouped, not per service row) with the
     * filters Delfi needs day to day. Replaces the old appointments and
     * payments tables; each row opens the same modal as the agenda.
     */
    public function appointments(Request $request)
    {
        $filters = [
            'upcoming' => 'Upcoming',
            'deposit' => 'Waiting for deposit',
            'past' => 'Past',
            'cancelled' => 'Cancelled',
            'all' => 'All',
        ];
        $filter = array_key_exists($request->query('filter'), $filters) ? $request->query('filter') : 'upcoming';
        $search = trim((string) $request->query('q', ''));

        $query = Appointment::with(['user', 'service', 'payment']);

        match ($filter) {
            'upcoming' => $query->whereDate('date', '>=', today())->whereIn('status', ['pending', 'approved']),
            'deposit' => $query->whereDate('date', '>=', today())->where('status', 'pending'),
            'past' => $query->whereDate('date', '<', today())->whereNotIn('status', ['cancelled', 'rejected']),
            'cancelled' => $query->whereIn('status', ['cancelled', 'rejected']),
            'all' => $query,
        };

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $ascending = in_array($filter, ['upcoming', 'deposit'], true);
        $query->orderBy('date', $ascending ? 'asc' : 'desc')->orderBy('time', $ascending ? 'asc' : 'desc');

        $bookings = $this->groupBookings($query->limit(600)->get());

        if ($filter === 'deposit') {
            $bookings = $bookings->filter(fn ($booking) => $booking['payment_status'] !== 'paid')->values();
        }

        return view('admin.appointments', [
            'bookingsByDate' => $bookings->take(200)->groupBy('date'),
            'total' => $bookings->count(),
            'filters' => $filters,
            'filter' => $filter,
            'search' => $search,
        ]);
    }

    public function agenda(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));

        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            abort(404);
        }

        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $currentMonth->copy()->endOfMonth();

        // The grid shows whole weeks, so include the spill-over days too.
        $gridStart = $currentMonth->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $appointments = Appointment::with(['user', 'service', 'payment'])
            ->whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString().' 23:59:59'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $appointmentsByDate = $this->groupBookings($appointments)
            ->groupBy('date')
            ->map(fn ($bookings) => $bookings->sortBy('time')->values());

        $availabilityByDate = AvailableDate::whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString().' 23:59:59'])
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn (AvailableDate $availableDate) => $availableDate->date->toDateString());

        // Bookings still waiting for the €15 deposit, across all future dates,
        // so they can be checked against Revolut and confirmed in one place.
        $awaitingDeposit = $this->groupBookings(
            Appointment::with(['user', 'service', 'payment'])
                ->where('status', 'pending')
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('time')
                ->get()
        )->filter(fn ($booking) => $booking['payment_status'] !== 'paid')->values();

        $services = Service::orderBy('name')->get(['id', 'name', 'price', 'duration']);

        return view('admin.agenda', [
            'appointmentsByDate' => $appointmentsByDate,
            'availabilityByDate' => $availabilityByDate,
            'awaitingDeposit' => $awaitingDeposit,
            'services' => $services,
            'currentMonth' => $currentMonth,
        ]);
    }

    /**
     * Collapse appointment rows into bookings (one per group_id) in the shape
     * the agenda's Alpine modals work with.
     */
    private function groupBookings($appointments)
    {
        return $appointments
            ->groupBy(fn (Appointment $appointment) => $appointment->group_id ?: "appointment-{$appointment->id}")
            ->map(function ($booking) {
                $firstAppointment = $booking->first();
                $payment = $booking->pluck('payment')->filter()->first()
                    ?? ($firstAppointment->group_id ? Payment::where('group_id', $firstAppointment->group_id)->first() : null);

                return [
                    'id' => $firstAppointment->id,
                    'appointment_ids' => $booking->pluck('id')->values(),
                    'service_ids' => $booking->pluck('service_id')->values(),
                    'date' => $firstAppointment->date->toDateString(),
                    'time' => substr($firstAppointment->time, 0, 5),
                    'duration' => $booking->sum(fn (Appointment $a) => (int) ($a->service->duration ?? 0)),
                    'customer' => $firstAppointment->name ?: $firstAppointment->user?->name ?: 'Guest client',
                    'name' => $firstAppointment->name ?: $firstAppointment->user?->name,
                    'email' => $firstAppointment->email ?: $firstAppointment->user?->email,
                    'phone' => $firstAppointment->phone,
                    'notes' => $firstAppointment->notes,
                    'services' => $booking->pluck('service.name')->filter()->join(', '),
                    'total_price' => $booking->sum(fn (Appointment $a) => (float) ($a->service->price ?? 0)),
                    'status' => $firstAppointment->status,
                    'balance_status' => $firstAppointment->balance_status,
                    'payment_status' => $payment?->status,
                    'payment_id' => $payment?->id,
                ];
            })
            ->values();
    }

    /**
     * Bookings Delfi takes herself (Instagram DMs, WhatsApp, walk-ins).
     * Same fan-out as the public flow: one row per service plus one shared
     * deposit payment, which can already be marked as paid.
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'integer|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'deposit_paid' => 'boolean',
            'force' => 'boolean',
        ]);

        $services = Service::whereIn('id', $validated['service_ids'])->get();
        $duration = (int) $services->sum('duration');

        if (! $request->boolean('force') && ($conflict = $this->findConflict($validated['date'], $validated['time'], $duration))) {
            return response()->json([
                'conflict' => true,
                'message' => "This overlaps with {$conflict->name} at ".substr($conflict->time, 0, 5).'.',
            ], 409);
        }

        $depositPaid = $request->boolean('deposit_paid');
        $groupId = uniqid('booking_', true);
        $appointments = collect();

        foreach ($services as $service) {
            $appointments->push(Appointment::create([
                'service_id' => $service->id,
                'date' => $validated['date'],
                'time' => $validated['time'],
                'status' => $depositPaid ? 'approved' : 'pending',
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'preferred_contact' => 'whatsapp',
                'notes' => $validated['notes'] ?? null,
                'group_id' => $groupId,
            ]));
        }

        Payment::create([
            'appointment_id' => $appointments->first()->id,
            'amount' => Payment::AMOUNT,
            'status' => $depositPaid ? 'paid' : 'pending',
            'payment_method' => 'manual',
            'group_id' => $groupId,
        ]);

        return response()->json(['success' => true, 'id' => $appointments->first()->id]);
    }

    public function updateBooking(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'force' => 'boolean',
        ]);

        $siblings = $this->groupSiblings($appointment)->load('service');
        $duration = (int) $siblings->sum(fn (Appointment $a) => $a->service->duration ?? 0);

        if (! $request->boolean('force') && ($conflict = $this->findConflict($validated['date'], $validated['time'], $duration, $siblings->pluck('id')->all()))) {
            return response()->json([
                'conflict' => true,
                'message' => "This overlaps with {$conflict->name} at ".substr($conflict->time, 0, 5).'.',
            ], 409);
        }

        $siblings->each->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyBooking(Appointment $appointment)
    {
        $siblings = $this->groupSiblings($appointment);

        if ($appointment->group_id) {
            Payment::where('group_id', $appointment->group_id)->delete();
        }

        Payment::whereIn('appointment_id', $siblings->pluck('id'))->delete();
        $siblings->each->delete();

        return response()->json(['success' => true]);
    }

    /**
     * First active booking that overlaps [time, time + duration) on $date,
     * ignoring the rows of the booking being edited.
     */
    private function findConflict(string $date, string $time, int $duration, array $ignoreIds = []): ?Appointment
    {
        $start = strtotime($time);
        $end = $start + max($duration, 1) * 60;

        return Appointment::with('service')
            ->whereDate('date', $date)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->whereNotIn('id', $ignoreIds)
            ->get()
            ->groupBy(fn (Appointment $a) => $a->group_id ?: "appointment-{$a->id}")
            ->map(function ($rows) {
                $first = $rows->first();
                $first->setAttribute('block_minutes', $rows->sum(fn ($a) => $a->service->duration ?? 0));

                return $first;
            })
            ->first(function (Appointment $a) use ($start, $end) {
                $aStart = strtotime(substr($a->time, 0, 5));
                $aEnd = $aStart + $a->block_minutes * 60;

                return $start < $aEnd && $end > $aStart;
            });
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
        ]);

        $appointment = Appointment::findOrFail($id);
        $this->groupSiblings($appointment)->each->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $validated['status']]);
        }

        return back()->with('success', 'Appointment status updated successfully');
    }

    public function updateBalanceStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'balance_status' => 'required|in:unpaid,paid',
        ]);

        $this->groupSiblings($appointment)->each->update(['balance_status' => $validated['balance_status']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'balance_status' => $validated['balance_status']]);
        }

        return back()->with('success', 'Balance status updated successfully');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $this->groupSiblings($appointment)->each->update(['date' => $validated['date']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'date' => $validated['date']]);
        }

        return back()->with('success', 'Appointment rescheduled successfully');
    }

    /**
     * A single booking can fan out into several appointment rows sharing a
     * group_id (one per selected service). Status/balance/date changes made
     * from the agenda or appointment list act on the whole booking, not just
     * the row that happened to be clicked.
     */
    private function groupSiblings(Appointment $appointment)
    {
        if (! $appointment->group_id) {
            return collect([$appointment]);
        }

        return Appointment::where('group_id', $appointment->group_id)->get();
    }
}
