<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = ['user_id', 'service_id', 'date', 'time', 'status', 'name', 'email', 'phone', 'preferred_contact', 'notes', 'group_id'];
    protected $casts = [
        'date' => 'date',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', '!=', 'cancelled')
                     ->where('date', '>=', Carbon::today());
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">Pending</span>',
            'approved' => '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Approved</span>',
            'rejected' => '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">Rejected</span>',
            'cancelled' => '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">Cancelled</span>',
        ];
        return $badges[$this->status] ?? '';
    }

    public function isPaid(): bool
    {
        return $this->payment && $this->payment->status === 'paid';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'approved']) && $this->date->isFuture();
    }
}
