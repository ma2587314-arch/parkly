<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $fillable = [
        'parking_id',
        'spot_number',
        'type',
        'status',
    ];

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
