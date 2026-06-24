<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'image_path',
        'label',
        'rating',
        'likes_count',
        'category',
        'distance_km',
        'travel_time',
        'entrance_fee',
        'best_time'
    ];

    /**
     * Get the destination image path.
     * Maps 'image' from SQLite or 'image_path' from MySQL.
     */
    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ?? $this->attributes['image'] ?? null;
    }

    /**
     * Get the destination likes count.
     * Maps 'likes' from SQLite or 'likes_count' from MySQL.
     */
    public function getLikesCountAttribute()
    {
        return $this->attributes['likes_count'] ?? $this->attributes['likes'] ?? 0;
    }

    /**
     * Get the destination category.
     * Maps 'type' from SQLite or 'category' from MySQL.
     */
    public function getCategoryAttribute()
    {
        return $this->attributes['category'] ?? $this->attributes['type'] ?? 'Pantai';
    }

    /**
     * Get the destination label (Hidden Gem, Populer, etc.).
     * Maps 'category' from SQLite or 'label' from MySQL.
     */
    public function getLabelAttribute()
    {
        return $this->attributes['label'] ?? ($this->attributes['category'] === 'Hidden Gem' || $this->attributes['category'] === 'Populer' ? $this->attributes['category'] : 'Hidden Gem');
    }
}
