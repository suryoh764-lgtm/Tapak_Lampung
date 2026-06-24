<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'culinary_id', 'name', 'address', 'district', 'phone',
        'open_time', 'close_time', 'open_days', 'price_range',
        'rating', 'reviews_count', 'description', 'image_path',
        'maps_url', 'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'rating'  => 'float',
    ];

    public function culinary()
    {
        return $this->belongsTo(Culinary::class);
    }

    public function getImageUrlAttribute(): string
    {
        $img = $this->image_path ?? '';
        if (!$img) return $this->culinary->image_url ?? '';
        if (str_starts_with($img, 'http') || str_starts_with($img, 'images/')) {
            return asset($img);
        }
        return asset('storage/' . $img);
    }

    public function getOpenHoursAttribute(): string
    {
        return $this->open_time . ' – ' . $this->close_time;
    }

    public function getIsOpenNowAttribute(): bool
    {
        $now   = now()->format('H:i');
        $open  = $this->open_time;
        $close = $this->close_time;
        return $now >= $open && $now <= $close && $this->is_open;
    }
}
