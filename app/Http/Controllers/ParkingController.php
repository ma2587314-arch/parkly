<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParkingController extends Controller
{
    public function index()
    {
        $parkings = Parking::withCount('spots')->withCount('bookings')->latest()->paginate(15);

        return view('parkings.index', compact('parkings'));
    }

    public function create()
    {
        return view('parkings.create');
    }

    public function store(Request $request)
    {
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
            $data['image'] = $request->file('image')->store('parkings', 'public');
        }

        Parking::create($data);

        return redirect()->route('admin.parkings.index')->with('success', 'Parking created successfully.');
    }

    public function show(int $id)
    {
        $parking = Parking::with('vendor')->withCount('spots')->withCount('bookings')->findOrFail($id);

        return view('parkings.show', compact('parking'));
    }

    public function edit(int $id)
    {
        $parking = Parking::with('vendor')->findOrFail($id);

        return view('parkings.edit', compact('parking'));
    }

    public function update(Request $request, int $id)
    {
        $parking = Parking::findOrFail($id);

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

        return redirect()->route('admin.parkings.index')->with('success', 'Parking updated successfully.');
    }

    public function destroy(int $id)
    {
        $parking = Parking::findOrFail($id);

        if ($parking->image) {
            Storage::disk('public')->delete($parking->image);
        }

        $parking->delete();

        return redirect()->route('admin.parkings.index')->with('success', 'Parking deleted.');
    }

    public function storeVendorAccount(Request $request, int $id)
    {
        $parking = Parking::findOrFail($id);

        if ($parking->vendor_id) {
            return back()->with('error', 'This parking already has a vendor account.');
        }

        $data = $request->validate([
            'vendor_name'                  => 'required|string|max:255',
            'vendor_email'                 => 'required|email|unique:users,email',
            'vendor_password'              => 'required|min:6|confirmed',
            'vendor_password_confirmation' => 'required',
        ]);

        $vendor = User::create([
            'name'     => $data['vendor_name'],
            'email'    => $data['vendor_email'],
            'password' => bcrypt($data['vendor_password']),
            'role'     => 'vendor',
        ]);

        $parking->update(['vendor_id' => $vendor->id]);

        return back()->with('success', 'Vendor account created successfully.');
    }

    public function updateVendorAccount(Request $request, int $id)
    {
        $parking = Parking::findOrFail($id);
        $vendor  = User::findOrFail($parking->vendor_id);

        $data = $request->validate([
            'vendor_name'     => 'required|string|max:255',
            'vendor_email'    => 'required|email|unique:users,email,' . $vendor->id,
            'vendor_password' => 'nullable|min:6|confirmed',
        ]);

        $update = [
            'name'  => $data['vendor_name'],
            'email' => $data['vendor_email'],
        ];

        if (!empty($data['vendor_password'])) {
            $update['password'] = bcrypt($data['vendor_password']);
        }

        $vendor->update($update);

        return back()->with('success', 'Vendor account updated successfully.');
    }

    public function toggleVendorAccount(int $id)
    {
        $parking = Parking::findOrFail($id);
        $vendor  = User::findOrFail($parking->vendor_id);

        $vendor->update(['is_blocked' => !$vendor->is_blocked]);

        return back()->with('success', $vendor->is_blocked ? 'Vendor account disabled.' : 'Vendor account enabled.');
    }
}
