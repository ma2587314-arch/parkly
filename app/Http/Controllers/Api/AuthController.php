<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PasswordResetOtp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:customers,email',
            'phone'      => 'required|string|max:20',
            'password'   => 'required|string|min:6',
            'car_number' => 'required|string|max:20',
            'gender'     => 'required|in:male,female',
        ]);

        $data['password'] = Hash::make($data['password']);

        $customer  = Customer::create($data);
        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'token'   => $token,
            'user'    => $customer,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'      => 'required|email',
            'password'   => 'required|string',
            'car_number' => 'required|string',
        ]);

        $customer = Customer::where('email', $data['email'])->first();

        if (
            ! $customer
            || ! Hash::check($data['password'], $customer->password)
            || strtolower($customer->car_number) !== strtolower($data['car_number'])
        ) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($customer->is_blocked) {
            return response()->json(['message' => 'Your account has been blocked.'], 403);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => $customer,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'method'     => 'required|in:email,phone',
        ]);

        $field = $data['method'] === 'email' ? 'email' : 'phone';
        $customer  = Customer::where($field, $data['identifier'])->first();

        if (! $customer) {
            return response()->json(['message' => 'No account found with that ' . $data['method'] . '.'], 404);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::where('identifier', $data['identifier'])
            ->where('method', $data['method'])
            ->delete();

        PasswordResetOtp::create([
            'identifier' => $data['identifier'],
            'method'     => $data['method'],
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($data['method'] === 'email') {
            Mail::raw("Your Parkly password reset code is: {$code}", function ($message) use ($data) {
                $message->to($data['identifier'])->subject('Parkly Password Reset Code');
            });
        }
        // SMS delivery handled by external provider — code stored for verification

        return response()->json(['message' => 'OTP sent to your ' . $data['method'] . '.']);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'method'     => 'required|in:email,phone',
        ]);

        $field = $data['method'] === 'email' ? 'email' : 'phone';
        $customer  = Customer::where($field, $data['identifier'])->first();

        if (! $customer) {
            return response()->json(['message' => 'No account found.'], 404);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::where('identifier', $data['identifier'])
            ->where('method', $data['method'])
            ->delete();

        PasswordResetOtp::create([
            'identifier' => $data['identifier'],
            'method'     => $data['method'],
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($data['method'] === 'email') {
            Mail::raw("Your Parkly password reset code is: {$code}", function ($message) use ($data) {
                $message->to($data['identifier'])->subject('Parkly Password Reset Code');
            });
        }

        return response()->json(['message' => 'OTP resent to your ' . $data['method'] . '.']);
    }

    public function tryAnotherWay(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier'     => 'required|string',
            'current_method' => 'required|in:email,phone',
        ]);

        $newMethod = $data['current_method'] === 'email' ? 'phone' : 'email';

        $customer = Customer::where('email', $data['identifier'])
            ->orWhere('phone', $data['identifier'])
            ->first();

        if (! $customer) {
            return response()->json(['message' => 'No account found.'], 404);
        }

        $newIdentifier = $newMethod === 'email' ? $customer->email : $customer->phone;

        if (! $newIdentifier) {
            return response()->json(['message' => 'No ' . $newMethod . ' on file for this account.'], 422);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::where('identifier', $data['identifier'])->delete();

        PasswordResetOtp::create([
            'identifier' => $newIdentifier,
            'method'     => $newMethod,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($newMethod === 'email') {
            Mail::raw("Your Parkly password reset code is: {$code}", function ($message) use ($newIdentifier) {
                $message->to($newIdentifier)->subject('Parkly Password Reset Code');
            });
        }

        return response()->json([
            'message'        => 'OTP sent via ' . $newMethod . '.',
            'new_method'     => $newMethod,
            'new_identifier' => $newIdentifier,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'method'     => 'required|in:email,phone',
            'code'       => 'required|string|size:6',
        ]);

        $otp = PasswordResetOtp::where('identifier', $data['identifier'])
            ->where('method', $data['method'])
            ->where('code', $data['code'])
            ->first();

        if (! $otp || $otp->isExpired()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        return response()->json(['message' => 'OTP verified. You may now reset your password.']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'identifier'   => 'required|string',
            'method'       => 'required|in:email,phone',
            'code'         => 'required|string|size:6',
            'new_password' => 'required|string|min:6',
        ]);

        $otp = PasswordResetOtp::where('identifier', $data['identifier'])
            ->where('method', $data['method'])
            ->where('code', $data['code'])
            ->first();

        if (! $otp || $otp->isExpired()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        $field = $data['method'] === 'email' ? 'email' : 'phone';
        $customer  = Customer::where($field, $data['identifier'])->first();

        if (! $customer) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $customer->update(['password' => Hash::make($data['new_password'])]);

        $otp->delete();

        return response()->json(['message' => 'Password reset successfully.']);
    }
}
