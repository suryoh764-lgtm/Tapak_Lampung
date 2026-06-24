<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripTag extends Model
{
    protected $fillable = ['trip_id', 'tag'];
    public $timestamps = false;

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
