<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'parking_id',
        'spot_id',
        'start_time',
        'end_time',
        'total_price',
        'service_fee',
        'status',
        'qr_code',
        'checked_in',
        'checked_out',
        'actual_start_time',
        'actual_end_time',
        'fine_amount',
    ];

    protected function casts(): array
    {
        return [
            'start_time'        => 'datetime',
            'end_time'          => 'datetime',
            'actual_start_time' => 'datetime',
            'actual_end_time'   => 'datetime',
            'total_price'       => 'float',
            'service_fee'       => 'float',
            'fine_amount'       => 'float',
            'checked_in'        => 'boolean',
            'checked_out'       => 'boolean',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
