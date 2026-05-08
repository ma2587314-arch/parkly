<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Spot;

class HardwareController extends Controller
{
    private function authorized(Request $request): bool
    {
        return $request->header('X-Hardware-Key') === env('HARDWARE_KEY');
    }

    public function checkin(Request $request)
    {
        if (!$this->authorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $qrContent = $request->input('qr_content');
        $payload = json_decode($qrContent, true);

        if (!is_array($payload) || !isset($payload['booking_id'])) {
            return response()->json(['error' => 'Invalid QR format'], 422);
        }

        $booking = Booking::where('id', $payload['booking_id'])
                          ->where('status', 'confirmed')
                          ->first();

        if (!$booking || $booking->checked_in) {
            return response()->json(['error' => 'Invalid, used, or not confirmed booking'], 422);
        }

        $booking->update([
            'status'            => 'checked_in',
            'checked_in'        => true,
            'actual_start_time' => now(),
        ]);

        return response()->json(['success' => true, 'booking_id' => $booking->id]);
    }

    public function parkingStatus(Request $request)
    {
        if (!$this->authorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $spots = [
            1 => $request->input('spot1'),
            2 => $request->input('spot2'),
            3 => $request->input('spot3'),
        ];

        foreach ($spots as $index => $occupied) {
            Spot::where('parking_id', $request->input('parking_id'))
                ->where('spot_number', 'R' . $index)
                ->update(['status' => $occupied ? 'inactive' : 'active']);
        }

        return response()->json(['success' => true]);
    }
}



