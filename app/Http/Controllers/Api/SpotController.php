<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Parking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    public function available(Request $request, int $parkingId): JsonResponse
    {
        $request->validate([
            'start_time' => 'required|date|after_or_equal:now',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $parking = Parking::findOrFail($parkingId);

        $bookedSpotIds = Booking::where('parking_id', $parkingId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })
            ->pluck('spot_id');

        $spots = $parking->spots()
            ->where('status', 'active')
            ->get()
            ->map(function ($spot) use ($bookedSpotIds) {
                return [
                    'id'           => $spot->id,
                    'spot_number'  => $spot->spot_number,
                    'type'         => $spot->type,
                    'is_available' => ! $bookedSpotIds->contains($spot->id),
                ];
            });

        return response()->json($spots);
    }
}
