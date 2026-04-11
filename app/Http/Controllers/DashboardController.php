<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Parking;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => Customer::count(),
            'total_parkings' => Parking::count(),
            'total_bookings' => Booking::count(),
            'total_revenue'  => Booking::whereIn('status', ['confirmed', 'checked_in', 'completed'])->sum('total_price'),
            'recent_bookings' => Booking::with(['customer', 'parking', 'spot'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('dashboard.index', compact('stats'));
    }
}
