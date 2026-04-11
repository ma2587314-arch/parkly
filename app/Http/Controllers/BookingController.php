<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'parking', 'spot', 'payment'])
            ->latest()
            ->paginate(20);

        return view('bookings.index', compact('bookings'));
    }

    public function show(int $id)
    {
        $booking = Booking::with(['customer', 'parking', 'spot', 'payment'])->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(int $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking is already cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking cancelled.');
    }
}
