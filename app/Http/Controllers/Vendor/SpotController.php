<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    private function ownedParkingIds(): \Illuminate\Support\Collection
    {
        return Auth::user()->parkings()->pluck('id');
    }

    private function ownedSpot(int $id): Spot
    {
        return Spot::whereIn('parking_id', $this->ownedParkingIds())
            ->where('id', $id)
            ->firstOrFail();
    }

    public function index()
    {
        $spots    = Spot::with('parking')
            ->whereIn('parking_id', $this->ownedParkingIds())
            ->latest()
            ->paginate(20);
        $parkings = Auth::user()->parkings()->orderBy('name')->get();

        return view('vendor.spots.index', compact('spots', 'parkings'));
    }

    public function create()
    {
        $parkings = Auth::user()->parkings()->orderBy('name')->get();

        return view('vendor.spots.create', compact('parkings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parking_id'  => 'required|exists:parkings,id',
            'spot_number' => 'required|string|max:20',
            'type'        => 'required|in:regular,vip,disabled',
            'status'      => 'required|in:active,inactive',
        ]);

        // Ensure the parking belongs to this vendor
        if (! $this->ownedParkingIds()->contains($data['parking_id'])) {
            abort(403);
        }

        Spot::create($data);

        return redirect()->route('vendor.spots.index')->with('success', 'Spot created successfully.');
    }

    public function edit(int $id)
    {
        $spot     = $this->ownedSpot($id);
        $parkings = Auth::user()->parkings()->orderBy('name')->get();

        return view('vendor.spots.edit', compact('spot', 'parkings'));
    }

    public function update(Request $request, int $id)
    {
        $spot = $this->ownedSpot($id);

        $data = $request->validate([
            'parking_id'  => 'required|exists:parkings,id',
            'spot_number' => 'required|string|max:20',
            'type'        => 'required|in:regular,vip,disabled',
            'status'      => 'required|in:active,inactive',
        ]);

        if (! $this->ownedParkingIds()->contains($data['parking_id'])) {
            abort(403);
        }

        $spot->update($data);

        return redirect()->route('vendor.spots.index')->with('success', 'Spot updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->ownedSpot($id)->delete();

        return redirect()->route('vendor.spots.index')->with('success', 'Spot deleted.');
    }
}
