<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'car_number'    => $user->car_number,
            'gender'        => $user->gender,
            'birthday'      => $user->birthday?->format('d-m-Y'),
            'profile_photo' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:customers,email,' . $user->id,
            'phone'    => 'sometimes|string|max:20',
            'birthday' => 'sometimes|date',
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated.',
            'user'    => $user->fresh(),
        ]);
    }

    public function uploadPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();

        if ($user->profile_photo) {
            Storage::delete('public/' . $user->profile_photo);
        }

        $path = $request->file('photo')->store('profile_photos', 'public');

        $user->update(['profile_photo' => $path]);

        return response()->json([
            'message'       => 'Profile photo updated.',
            'profile_photo' => asset('storage/' . $path),
        ]);
    }
}
