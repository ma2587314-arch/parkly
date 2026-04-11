<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user        = Auth::user();
        $parkingIds  = $user->parkings()->pluck('id');

        $stats = [
            'total_parkings' => $parkingIds->count(),
            'total_spots'    => \App\Models\Spot::whereIn('parking_id', $parkingIds)->count(),
            'total_bookings' => Booking::whereIn('parking_id', $parkingIds)->count(),
            'total_revenue'  => Booking::whereIn('parking_id', $parkingIds)
                ->whereIn('status', ['confirmed', 'checked_in', 'completed'])
                ->sum('total_price'),
            'recent_bookings' => Booking::with(['customer', 'parking', 'spot'])
                ->whereIn('parking_id', $parkingIds)
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('vendor.dashboard.index', compact('stats'));
    }
}
