<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ParkingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SpotController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Auth (public)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
Route::post('try-another-way', [AuthController::class, 'tryAnotherWay']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

// Protected
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // Parkings
    Route::get('parkings/nearest', [ParkingController::class, 'nearest']);
    Route::get('parkings', [ParkingController::class, 'index']);
    Route::get('parkings/{id}', [ParkingController::class, 'show']);

    // Spots availability
    Route::get('parkings/{parkingId}/spots/available', [SpotController::class, 'available']);

    // Reviews
    Route::post('parkings/{parkingId}/reviews', [ReviewController::class, 'store']);

    // Bookings
    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::post('bookings/{id}/checkin', [BookingController::class, 'checkin']);
    Route::post('bookings/{id}/checkout', [BookingController::class, 'checkout']);

    // Profile
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'update']);
    Route::post('profile/photo', [UserController::class, 'uploadPhoto']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead']);
});

 HEAD
use App\Http\Controllers\HardwareController;

Route::post('/hardware/verify-qr', [HardwareController::class, 'verifyQr']);

use App\Http\Controllers\HardwareController;

Route::post('/hardware/verify-qr', [HardwareController::class, 'verifyQr']);
 b4e861d9cc0585485b79305c133c59c5d15b8136


 use App\Http\Controllers\HardwareController;

Route::post('/hardware/verify-qr', [HardwareController::class, 'verifyQr']);