<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorAccountController extends Controller
{
    public function index()
    {
        $vendors = User::where('role', 'vendor')->with('parkings')->latest()->paginate(20);

        return view('vendor-accounts.index', compact('vendors'));
    }

    public function create()
    {
        $parkings = Parking::whereNull('vendor_id')->orderBy('name')->get();

        return view('vendor-accounts.create', compact('parkings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'parking_id' => 'nullable|exists:parkings,id',
        ]);

        $vendor = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'vendor',
        ]);

        if (! empty($data['parking_id'])) {
            Parking::where('id', $data['parking_id'])->update(['vendor_id' => $vendor->id]);
        }

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor account created successfully.');
    }

    public function show(int $id)
    {
        $vendor          = User::where('role', 'vendor')->with('parkings')->findOrFail($id);
        $assignedParking = $vendor->parkings->first();
        $parkingId       = $assignedParking?->id;

        // Parkings available: unassigned + currently assigned to this vendor
        $availableParkings = Parking::where(function ($q) use ($vendor) {
            $q->whereNull('vendor_id')->orWhere('vendor_id', $vendor->id);
        })->orderBy('name')->get();

        $stats = [
            'spots'    => $parkingId ? \App\Models\Spot::where('parking_id', $parkingId)->count() : 0,
            'bookings' => $parkingId ? \App\Models\Booking::where('parking_id', $parkingId)->count() : 0,
            'revenue'  => $parkingId ? \App\Models\Booking::where('parking_id', $parkingId)
                              ->where('status', 'completed')->sum('total_price') : 0,
        ];

        return view('vendor-accounts.show', compact('vendor', 'stats', 'assignedParking', 'availableParkings'));
    }

    public function block(int $id)
    {
        $vendor             = User::where('role', 'vendor')->findOrFail($id);
        $vendor->is_blocked = ! $vendor->is_blocked;
        $vendor->save();

        $status = $vendor->is_blocked ? 'suspended' : 'reactivated';

        return back()->with('success', "Vendor account {$status}.");
    }

    public function assignParking(Request $request, int $id)
    {
        $vendor = User::where('role', 'vendor')->findOrFail($id);

        $data = $request->validate([
            'parking_id' => 'nullable|exists:parkings,id',
        ]);

        // Unassign current parking from this vendor
        Parking::where('vendor_id', $vendor->id)->update(['vendor_id' => null]);

        if (! empty($data['parking_id'])) {
            Parking::where('id', $data['parking_id'])->update(['vendor_id' => $vendor->id]);
        }

        return back()->with('success', 'Parking assignment updated.');
    }
}
