<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'organizer_name',
        'organizer_avatar',
        'schedule_date',
        'duration',
        'current_quota',
        'max_quota',
        'rating',
        'reviews_count',
        'price',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'price'         => 'decimal:2',
    ];

    public function tags()
    {
        return $this->hasMany(TripTag::class);
    }

    public function getSlotsLeftAttribute()
    {
        return $this->max_quota - $this->current_quota;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute()
    {
        $img = $this->attributes['image_path'] ?? null;
        if (!$img) return null;
        if (str_starts_with($img, 'http') || str_starts_with($img, 'images/')) {
            return asset($img);
        }
        return asset('storage/' . $img);
    }
}
