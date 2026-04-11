<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = Customer::withCount('bookings')->latest()->paginate(20);

        return view('users.index', compact('users'));
    }

    public function show(int $id)
    {
        $user = Customer::withCount('bookings')->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function block(int $id)
    {
        $user = Customer::findOrFail($id);

        $user->update(['is_blocked' => ! $user->is_blocked]);

        $action = $user->is_blocked ? 'blocked' : 'unblocked';

        return redirect()->back()->with('success', "User {$action} successfully.");
    }
}
