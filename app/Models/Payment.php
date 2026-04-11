<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'method',
        'status',
        'name_on_card',
        'card_number_last4',
        'expiration_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
