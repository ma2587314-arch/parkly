<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Parking;
use App\Models\Payment;
use App\Models\Spot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['parking', 'spot', 'payment'])
            ->latest()
            ->get()
            ->map(fn ($b) => $this->formatBooking($b));

        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'parking_id'       => 'required|exists:parkings,id',
            'spot_id'          => 'required|exists:spots,id',
            'start_time'       => 'required|date|after_or_equal:now',
            'end_time'         => 'required|date|after:start_time',
            'payment_method'   => 'required|in:visa,mastercard,apple_pay',
            'name_on_card'     => 'required_unless:payment_method,apple_pay|string|max:255',
            'card_number'      => 'required_unless:payment_method,apple_pay|string|min:13|max:19',
            'expiration_date'  => 'required_unless:payment_method,apple_pay|string|max:7',
        ]);

        $parking = Parking::findOrFail($data['parking_id']);
        $spot    = Spot::where('id', $data['spot_id'])
            ->where('parking_id', $data['parking_id'])
            ->where('status', 'active')
            ->firstOrFail();

        // Check spot availability (overlap)
        $conflict = Booking::where('spot_id', $data['spot_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                  ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('start_time', '<=', $data['start_time'])
                         ->where('end_time', '>=', $data['end_time']);
                  });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['message' => 'This spot is not available for the selected time range.'], 422);
        }

        $start       = \Carbon\Carbon::parse($data['start_time']);
        $end         = \Carbon\Carbon::parse($data['end_time']);
        $hours       = max(1, ceil($start->diffInMinutes($end) / 60));
        $parkingFee  = $parking->price_per_hour * $hours;
        $serviceFee  = $parking->service_fee;
        $totalPrice  = $parkingFee + $serviceFee;

        $booking = DB::transaction(function () use ($request, $data, $parking, $parkingFee, $serviceFee, $totalPrice) {
            $booking = Booking::create([
                'customer_id' => $request->user()->id,
                'parking_id' => $data['parking_id'],
                'spot_id'    => $data['spot_id'],
                'start_time' => $data['start_time'],
                'end_time'   => $data['end_time'],
                'total_price' => $totalPrice,
                'service_fee' => $serviceFee,
                'status'     => 'confirmed',
            ]);

            // Generate QR code stored as PNG in storage
            $qrContent  = json_encode([
                'booking_id'  => $booking->id,
                'customer_id' => $request->user()->id,
                'parking_id'  => $data['parking_id'],
                'spot_id'    => $data['spot_id'],
                'start_time' => $data['start_time'],
                'end_time'   => $data['end_time'],
            ]);

            $qrPath = 'qrcodes/booking_' . $booking->id . '.png';
            \Storage::disk('public')->put($qrPath, QrCode::format('png')->size(300)->generate($qrContent));

            $booking->update(['qr_code' => $qrPath]);

            // Store last 4 digits only — never store full card number
            $last4 = isset($data['card_number']) ? substr(preg_replace('/\D/', '', $data['card_number']), -4) : null;

            Payment::create([
                'booking_id'        => $booking->id,
                'amount'            => $booking->total_price,
                'method'            => $data['payment_method'],
                'status'            => 'completed',
                'name_on_card'      => $data['name_on_card'] ?? null,
                'card_number_last4' => $last4,
                'expiration_date'   => $data['expiration_date'] ?? null,
            ]);

            Notification::create([
                'customer_id' => $request->user()->id,
                'title'   => 'Booking Confirmed',
                'message' => 'Your booking at ' . \App\Models\Parking::find($data['parking_id'])->name . ' has been confirmed.',
            ]);

            return $booking;
        });

        $booking->load(['parking', 'spot', 'payment']);

        return response()->json([
            'message' => 'Booking confirmed.',
            'booking' => $this->formatBooking($booking),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $booking = $request->user()
            ->bookings()
            ->with(['parking', 'spot', 'payment'])
            ->findOrFail($id);

        return response()->json($this->formatBooking($booking));
    }

    public function checkin(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'qr_image' => 'required|image|max:5120',
        ]);

        $decoded = (new \Zxing\QrReader($request->file('qr_image')->getRealPath(), \Zxing\QrReader::SOURCE_TYPE_FILE))->text();
        $payload = $decoded ? json_decode($decoded, true) : null;

        if (
            ! is_array($payload)
            || ! isset($payload['booking_id'], $payload['customer_id'])
            || (int) $payload['booking_id']  !== $id
            || (int) $payload['customer_id'] !== $request->user()->id
        ) {
            return response()->json(['message' => 'Invalid QR code.'], 422);
        }

        $booking = Booking::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->firstOrFail();

        if ($booking->status !== 'confirmed') {
            return response()->json(['message' => 'Booking is not in a confirmed state.'], 422);
        }

        if ($booking->checked_in) {
            return response()->json(['message' => 'QR code already used for check-in.'], 422);
        }

        $booking->update([
            'checked_in'        => true,
            'status'            => 'checked_in',
            'actual_start_time' => now(),
        ]);

        return response()->json([
            'message'       => 'Check-in successful.',
            'booking_id'    => $booking->id,
            'checked_in_at' => $booking->actual_start_time,
        ]);
    }

    public function checkout(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'qr_image' => 'required|image|max:5120',
        ]);

        $decoded = (new \Zxing\QrReader($request->file('qr_image')->getRealPath(), \Zxing\QrReader::SOURCE_TYPE_FILE))->text();
        $payload = $decoded ? json_decode($decoded, true) : null;

        if (
            ! is_array($payload)
            || ! isset($payload['booking_id'], $payload['customer_id'])
            || (int) $payload['booking_id']  !== $id
            || (int) $payload['customer_id'] !== $request->user()->id
        ) {
            return response()->json(['message' => 'Invalid QR code.'], 422);
        }

        $booking = Booking::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->firstOrFail();

        if ($booking->status !== 'checked_in') {
            return response()->json(['message' => 'You must check in before checking out.'], 422);
        }

        if ($booking->checked_out) {
            return response()->json(['message' => 'QR code already used for check-out.'], 422);
        }

        $actualEnd  = now();
        $bookedEnd  = $booking->end_time;
        $fineAmount = 0;

        if ($actualEnd->gt($bookedEnd)) {
            $extraHours = ceil($bookedEnd->diffInMinutes($actualEnd) / 60);
            $fineRate   = (float) config('parkly.fine_per_extra_hour', 10);
            $fineAmount = $extraHours * $fineRate;
        }

        $booking->update([
            'checked_out'     => true,
            'status'          => 'completed',
            'actual_end_time' => $actualEnd,
            'fine_amount'     => $fineAmount,
        ]);

        if ($fineAmount > 0) {
            Notification::create([
                'customer_id' => $booking->customer_id,
                'title'       => 'Fine Applied',
                'message'     => "A fine of {$fineAmount} EGP has been applied to booking #{$booking->id} for exceeding the booked duration.",
            ]);
        }

        return response()->json([
            'message'        => 'Check-out successful.',
            'checked_out_at' => $actualEnd,
            'fine_amount'    => $fineAmount,
        ]);
    }

    private function formatBooking(Booking $booking): array
    {
        return [
            'id'              => $booking->id,
            'parking'         => [
                'id'      => $booking->parking->id,
                'name'    => $booking->parking->name,
                'address' => $booking->parking->address,
            ],
            'spot'            => [
                'id'           => $booking->spot->id,
                'spot_number'  => $booking->spot->spot_number,
            ],
            'start_time'      => $booking->start_time,
            'end_time'        => $booking->end_time,
            'actual_end_time' => $booking->actual_end_time,
            'total_price'     => $booking->total_price,
            'service_fee'     => $booking->service_fee,
            'fine_amount'     => $booking->fine_amount,
            'status'          => $booking->status,
            'qr_code_url'     => $booking->qr_code ? asset('storage/' . $booking->qr_code) : null,
            'payment'         => $booking->payment ? [
                'method'            => $booking->payment->method,
                'status'            => $booking->payment->status,
                'card_number_last4' => $booking->payment->card_number_last4,
            ] : null,
        ];
    }
}
