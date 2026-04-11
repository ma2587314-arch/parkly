<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorAccountController;
use App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

Route::middleware(['auth', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Parkings
    Route::resource('parkings', ParkingController::class);
    Route::post('parkings/{id}/vendor-account', [ParkingController::class, 'storeVendorAccount'])->name('parkings.vendor-account.store');
    Route::put('parkings/{id}/vendor-account', [ParkingController::class, 'updateVendorAccount'])->name('parkings.vendor-account.update');
    Route::post('parkings/{id}/vendor-account/toggle', [ParkingController::class, 'toggleVendorAccount'])->name('parkings.vendor-account.toggle');

    // Spots
    Route::resource('spots', SpotController::class);

    // Bookings
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('users/{id}/block', [UserController::class, 'block'])->name('users.block');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Vendor Accounts
    Route::get('vendors', [VendorAccountController::class, 'index'])->name('vendors.index');
    Route::get('vendors/create', [VendorAccountController::class, 'create'])->name('vendors.create');
    Route::post('vendors', [VendorAccountController::class, 'store'])->name('vendors.store');
    Route::get('vendors/{id}', [VendorAccountController::class, 'show'])->name('vendors.show');
    Route::post('vendors/{id}/block', [VendorAccountController::class, 'block'])->name('vendors.block');
    Route::post('vendors/{id}/assign-parkings', [VendorAccountController::class, 'assignParking'])->name('vendors.assign-parkings');

    // API Documentation
    Route::get('api-docs', fn () => view('api-docs.index'))->name('api-docs');
});

// Vendor portal
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/', [Vendor\DashboardController::class, 'index'])->name('dashboard');

    // Parkings (view + edit own only)
    Route::get('parkings', [Vendor\ParkingController::class, 'index'])->name('parkings.index');
    Route::get('parkings/{id}/edit', [Vendor\ParkingController::class, 'edit'])->name('parkings.edit');
    Route::put('parkings/{id}', [Vendor\ParkingController::class, 'update'])->name('parkings.update');

    // Spots (full CRUD scoped to vendor)
    Route::resource('spots', Vendor\SpotController::class);

    // Bookings (read-only)
    Route::get('bookings', [Vendor\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{id}', [Vendor\BookingController::class, 'show'])->name('bookings.show');

    // Profile
    Route::get('profile', [Vendor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [Vendor\ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
