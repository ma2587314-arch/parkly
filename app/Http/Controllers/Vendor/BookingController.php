<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    private function ownedParkingIds(): \Illuminate\Support\Collection
    {
        return Auth::user()->parkings()->pluck('id');
    }

    public function index()
    {
        $bookings = Booking::with(['customer', 'parking', 'spot'])
            ->whereIn('parking_id', $this->ownedParkingIds())
            ->latest()
            ->paginate(20);

        return view('vendor.bookings.index', compact('bookings'));
    }

    public function show(int $id)
    {
        $booking = Booking::with(['customer', 'parking', 'spot', 'payment'])
            ->whereIn('parking_id', $this->ownedParkingIds())
            ->findOrFail($id);

        return view('vendor.bookings.show', compact('booking'));
    }
}
