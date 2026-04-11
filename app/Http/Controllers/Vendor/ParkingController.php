<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ParkingController extends Controller
{
    private function ownedParking(int $id): Parking
    {
        return Parking::where('id', $id)
            ->where('vendor_id', Auth::id())
            ->firstOrFail();
    }

    public function index()
    {
        $parkings = Auth::user()->parkings()->withCount('spots')->withCount('bookings')->get();

        return view('vendor.parkings.index', compact('parkings'));
    }

    public function edit(int $id)
    {
        $parking = $this->ownedParking($id);

        return view('vendor.parkings.edit', compact('parking'));
    }

    public function update(Request $request, int $id)
    {
        $parking = $this->ownedParking($id);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:500',
            'lat'            => 'required|numeric',
            'lng'            => 'required|numeric',
            'price_per_hour' => 'required|numeric|min:0',
            'service_fee'    => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($parking->image) {
                Storage::disk('public')->delete($parking->image);
            }
            $data['image'] = $request->file('image')->store('parkings', 'public');
        }

        $parking->update($data);

        return redirect()->route('vendor.parkings.index')->with('success', 'Parking updated successfully.');
    }
}
