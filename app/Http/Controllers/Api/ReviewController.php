<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, int $parkingId): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Parking::findOrFail($parkingId);

        $review = Review::updateOrCreate(
            ['customer_id' => $request->user()->id, 'parking_id' => $parkingId],
            ['rating'  => $request->rating]
        );

        return response()->json([
            'message' => 'Review submitted.',
            'review'  => $review,
        ], 201);
    }
}
