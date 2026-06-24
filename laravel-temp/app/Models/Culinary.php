<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culinary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'description', 'image_path',
        'spice_level', 'outlet_count', 'outlet_type',
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function getImageUrlAttribute(): string
    {
        $img = $this->image_path ?? '';
        if (str_starts_with($img, 'http') || str_starts_with($img, 'images/')) {
            return asset($img);
        }
        return asset('storage/' . $img);
    }
}
