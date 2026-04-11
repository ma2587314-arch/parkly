<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['admin' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $admin->id,
            'current_password'      => 'nullable|string',
            'new_password'          => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (! Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $admin->name  = $data['name'];
        $admin->email = $data['email'];

        if ($request->filled('new_password')) {
            $admin->password = Hash::make($data['new_password']);
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
