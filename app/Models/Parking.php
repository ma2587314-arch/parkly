<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Parking extends Model
{
    protected $fillable = [
        'name',
        'address',
        'lat',
        'lng',
        'price_per_hour',
        'service_fee',
        'image',
        'vendor_id',
    ];

    protected function casts(): array
    {
        return [
            'lat'            => 'float',
            'lng'            => 'float',
            'price_per_hour' => 'float',
            'service_fee'    => 'float',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
