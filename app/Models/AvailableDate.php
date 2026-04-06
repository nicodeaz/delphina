<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        $dateStr = is_object($date) ? $date->toDateString() : (string)$date;
        
        $available = self::where('date', $dateStr)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($available->isEmpty()) {
            return [];
        }

        $slots = [];
        
        foreach ($available as $period) {
            $startTime = \DateTime::createFromFormat('H:i', $period->start_time);
            $endTime = \DateTime::createFromFormat('H:i', $period->end_time);
            
            $current = $startTime;
            $serviceEnd = clone $current;
            $serviceEnd->add(new \DateInterval('PT' . $serviceDuration . 'M'));
            
            while ($serviceEnd <= $endTime) {
                $slots[] = $current->format('H:i');
                
                // Move to next 30-minute slot
                $current->add(new \DateInterval('PT30M'));
                $serviceEnd = clone $current;
                $serviceEnd->add(new \DateInterval('PT' . $serviceDuration . 'M'));
            }
        }

        return $slots;
    }

    /**
     * Get available dates for next N days
     */
    public static function nextAvailableDates($days = 30)
    {
        $dates = [];
        $today = now();

        for ($i = 1; $i <= $days; $i++) {
            $date = $today->copy()->addDays($i);
            $hasSlots = self::where('date', $date->toDateString())
                ->where('is_active', true)
                ->exists();
            
            if ($hasSlots) {
                $dates[] = $date->toDateString();
            }
        }

        return $dates;
    }
}
