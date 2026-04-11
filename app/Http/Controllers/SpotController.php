<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Spot;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    public function index()
    {
        $spots    = Spot::with('parking')->latest()->paginate(20);
        $parkings = Parking::orderBy('name')->get();

        return view('spots.index', compact('spots', 'parkings'));
    }

    public function create()
    {
        $parkings = Parking::orderBy('name')->get();

        return view('spots.create', compact('parkings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parking_id'  => 'required|exists:parkings,id',
            'spot_number' => 'required|string|max:20',
            'type'        => 'required|in:regular,vip,disabled',
            'status'      => 'required|in:active,inactive',
        ]);

        Spot::create($data);

        return redirect()->route('admin.spots.index')->with('success', 'Spot created successfully.');
    }

    public function show(int $id)
    {
        $spot = Spot::with('parking')->findOrFail($id);

        return view('spots.show', compact('spot'));
    }

    public function edit(int $id)
    {
        $spot     = Spot::findOrFail($id);
        $parkings = Parking::orderBy('name')->get();

        return view('spots.edit', compact('spot', 'parkings'));
    }

    public function update(Request $request, int $id)
    {
        $spot = Spot::findOrFail($id);

        $data = $request->validate([
            'parking_id'  => 'required|exists:parkings,id',
            'spot_number' => 'required|string|max:20',
            'type'        => 'required|in:regular,vip,disabled',
            'status'      => 'required|in:active,inactive',
        ]);

        $spot->update($data);

        return redirect()->route('admin.spots.index')->with('success', 'Spot updated successfully.');
    }

    public function destroy(int $id)
    {
        Spot::findOrFail($id)->delete();

        return redirect()->route('admin.spots.index')->with('success', 'Spot deleted.');
    }
}
