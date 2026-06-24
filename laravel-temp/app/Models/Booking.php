<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'type',
        'trip_id', 'restaurant_id',
        'name', 'phone', 'email',
        'participants_count', 'total_price',
        'booking_date', 'notes',
        'status', 'confirmed_at',
    ];

    protected $casts = [
        'booking_date'  => 'date',
        'total_price'   => 'float',
        'confirmed_at'  => 'datetime',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Helper: label badge status
    public function getStatusLabelAttribute(): array
    {
        return match($this->status) {
            'paid'      => ['💳 Sudah Dibayar',    '#fffbeb', '#d97706', '#fcd34d'],
            'confirmed' => ['✅ Dikonfirmasi',       '#f0fdf4', '#15803d', '#86efac'],
            'cancelled' => ['❌ Dibatalkan',         '#fef2f2', '#dc2626', '#fca5a5'],
            default     => ['⏳ Menunggu Pembayaran','#f8fafc', '#64748b', '#cbd5e1'],
        };
    }
}
