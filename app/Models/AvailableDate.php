<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableDate extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get all time slots for a specific date
     */
    public static function slotsForDate($date, $serviceDuration = 60)
    {
        // Convert $date to ensure it's a string in YYYY-MM-DD format
        $dateStr = is_object($date) ? $date->toDateString() : (string) $date;

        $available = self::whereDate('date', $dateStr)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($available->isEmpty()) {
            return [];
        }

        $slots = [];

        foreach ($available as $period) {
            $startTime = \DateTime::createFromFormat('H:i', substr($period->start_time, 0, 5));
            $endTime = \DateTime::createFromFormat('H:i', substr($period->end_time, 0, 5));
            $intervalMinutes = 30;

            $current = $startTime;
            $serviceEnd = clone $current;
            $serviceEnd->add(new \DateInterval('PT'.$serviceDuration.'M'));

            while ($serviceEnd <= $endTime) {
                $slots[] = [
                    'time' => $current->format('H:i'),
                    'available' => true,
                ];

                // Move to next slot
                $current->add(new \DateInterval('PT'.$intervalMinutes.'M'));
                $serviceEnd = clone $current;
                $serviceEnd->add(new \DateInterval('PT'.$serviceDuration.'M'));
            }
        }

        $uniqueSlots = [];
        foreach ($slots as $slot) {
            $timeKey = $slot['time'];
            if (! isset($uniqueSlots[$timeKey])) {
                $uniqueSlots[$timeKey] = $slot;
            }
        }

        return array_values($uniqueSlots);
    }

    /**
     * Get available dates for next N days
     */
    public static function nextAvailableDates($days = 30)
    {
        $from = now()->addDay()->toDateString();
        $until = now()->addDays($days)->toDateString();

        return self::where('is_active', true)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $until)
            ->orderBy('date')
            ->get(['date'])
            ->map(fn (self $availableDate) => $availableDate->date->toDateString())
            ->unique()
            ->values()
            ->all();
    }
}
