<?php

namespace App\Http\Controllers;

use App\Models\AvailableDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailableDateController extends Controller
{
    /**
     * Hours are managed from the agenda modals; this keeps old links working.
     */
    public function index()
    {
        return redirect()->route('admin.agenda');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $availableDate = AvailableDate::create($request->only([
            'date',
            'start_time',
            'end_time',
            'notes',
        ]) + ['is_active' => true]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'available_date' => $availableDate]);
        }

        return redirect()->route('admin.agenda')
            ->with('success', 'Available date created successfully.');
    }

    /**
     * Opens the same hours on many days at once ("Mon–Fri 09:00–18:00 for
     * the next 4 weeks"), so the schedule can be set up in one step instead
     * of one block per day.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'weekdays' => 'required|array|min:1',
            'weekdays.*' => 'integer|between:0,6',
            'blocks' => 'required|array|min:1|max:4',
            'blocks.*.start_time' => 'required|date_format:H:i',
            'blocks.*.end_time' => 'required|date_format:H:i',
            'replace_existing' => 'boolean',
        ]);

        foreach ($validated['blocks'] as $index => $block) {
            if ($block['end_time'] <= $block['start_time']) {
                return response()->json([
                    'message' => 'Each time block must end after it starts.',
                    'errors' => ["blocks.$index.end_time" => ['Must be after the start time.']],
                ], 422);
            }
        }

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        if ($start->diffInDays($end) > 185) {
            return response()->json([
                'message' => 'Please choose a range of 6 months or less.',
                'errors' => ['end_date' => ['Please choose a range of 6 months or less.']],
            ], 422);
        }

        $weekdays = array_map('intval', $validated['weekdays']);
        $created = 0;
        $days = 0;

        foreach (CarbonPeriod::create($start, $end) as $day) {
            if (! in_array($day->dayOfWeek, $weekdays, true)) {
                continue;
            }

            $days++;

            if ($request->boolean('replace_existing')) {
                AvailableDate::whereDate('date', $day->toDateString())->delete();
            }

            foreach ($validated['blocks'] as $block) {
                $exists = AvailableDate::whereDate('date', $day->toDateString())
                    ->where('start_time', $block['start_time'])
                    ->where('end_time', $block['end_time'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                AvailableDate::create([
                    'date' => $day->toDateString(),
                    'start_time' => $block['start_time'],
                    'end_time' => $block['end_time'],
                    'is_active' => true,
                ]);
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'created' => $created,
            'days' => $days,
            'message' => $days
                ? "Opened hours on {$days} day".($days === 1 ? '' : 's').'.'
                : 'No days in that range matched the selected weekdays.',
        ]);
    }

    public function update(Request $request, AvailableDate $availableDate)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $availableDate->update($request->only([
            'date',
            'start_time',
            'end_time',
            'notes',
        ]));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'available_date' => $availableDate]);
        }

        return redirect()->route('admin.agenda')
            ->with('success', 'Available date updated successfully.');
    }

    public function destroy(Request $request, AvailableDate $availableDate)
    {
        $availableDate->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.agenda')
            ->with('success', 'Available date deleted successfully.');
    }
}
