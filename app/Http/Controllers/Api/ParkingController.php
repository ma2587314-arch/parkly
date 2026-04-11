<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Parking::withCount('reviews')->withAvg('reviews', 'rating');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $parkings = $query->get()->map(function ($parking) {
            return [
                'id'             => $parking->id,
                'name'           => $parking->name,
                'address'        => $parking->address,
                'lat'            => $parking->lat,
                'lng'            => $parking->lng,
                'price_per_hour' => $parking->price_per_hour,
                'service_fee'    => $parking->service_fee,
                'image'          => $parking->image ? asset('storage/' . $parking->image) : null,
                'rating'         => round($parking->reviews_avg_rating ?? 0, 1),
                'review_count'   => $parking->reviews_count,
            ];
        });

        return response()->json($parkings);
    }

    public function show(int $id): JsonResponse
    {
        $parking = Parking::withCount('reviews')->withAvg('reviews', 'rating')->findOrFail($id);

        return response()->json([
            'id'             => $parking->id,
            'name'           => $parking->name,
            'address'        => $parking->address,
            'lat'            => $parking->lat,
            'lng'            => $parking->lng,
            'price_per_hour' => $parking->price_per_hour,
            'service_fee'    => $parking->service_fee,
            'image'          => $parking->image ? asset('storage/' . $parking->image) : null,
            'rating'         => round($parking->reviews_avg_rating ?? 0, 1),
            'review_count'   => $parking->reviews_count,
        ]);
    }

    /**
     * GET /api/parkings/nearest?lat=30.0444&lng=31.2357
     *
     * Returns ALL parkings sorted by distance (km) using the Haversine formula.
     */
    public function nearest(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $lat = (float) $request->lat;
        $lng = (float) $request->lng;

        $parkings = Parking::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->addSelect(\Illuminate\Support\Facades\DB::raw("( 6371 * ACOS(
                COS(RADIANS({$lat})) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS({$lng})) +
                SIN(RADIANS({$lat})) * SIN(RADIANS(lat))
            ) ) AS distance_km"))
            ->orderBy('distance_km')
            ->get()
            ->map(function ($parking) {
                return [
                    'id'             => $parking->id,
                    'name'           => $parking->name,
                    'address'        => $parking->address,
                    'lat'            => $parking->lat,
                    'lng'            => $parking->lng,
                    'price_per_hour' => $parking->price_per_hour,
                    'service_fee'    => $parking->service_fee,
                    'image'          => $parking->image ? asset('storage/' . $parking->image) : null,
                    'rating'         => round($parking->reviews_avg_rating ?? 0, 1),
                    'review_count'   => $parking->reviews_count,
                    'distance_km'    => round($parking->distance_km, 2),
                ];
            });

        return response()->json([
            'lat'   => $lat,
            'lng'   => $lng,
            'count' => $parkings->count(),
            'data'  => $parkings,
        ]);
    }
}
