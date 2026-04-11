@extends('layouts.app')
@section('title', 'Booking #'.$booking->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Booking #{{ $booking->id }}</h5>
    <div class="d-flex gap-2">
        @if($booking->status !== 'cancelled')
        <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}"
              onsubmit="return confirm('Cancel this booking?')">
            @csrf
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg me-1"></i>Cancel Booking</button>
        </form>
        @endif
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Booking Details</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Status</th><td><span class="badge badge-{{ $booking->status }} rounded-pill px-2">{{ ucfirst($booking->status) }}</span></td></tr>
                    <tr><th>Parking</th><td>{{ $booking->parking->name }}</td></tr>
                    <tr><th>Address</th><td>{{ $booking->parking->address }}</td></tr>
                    <tr><th>Spot</th><td>{{ $booking->spot->spot_number }} ({{ ucfirst($booking->spot->type) }})</td></tr>
                    <tr><th>Start Time</th><td>{{ $booking->start_time->format('d M Y H:i') }}</td></tr>
                    <tr><th>End Time</th><td>{{ $booking->end_time->format('d M Y H:i') }}</td></tr>
                    @if($booking->actual_end_time)
                    <tr><th>Actual End</th><td>{{ $booking->actual_end_time->format('d M Y H:i') }}</td></tr>
                    @endif
                    <tr><th>Parking Fee</th><td>{{ $booking->total_price - $booking->service_fee }} EGP</td></tr>
                    <tr><th>Service Fee</th><td>{{ $booking->service_fee }} EGP</td></tr>
                    <tr><th>Fine</th><td>{{ $booking->fine_amount > 0 ? $booking->fine_amount.' EGP' : '—' }}</td></tr>
                    <tr><th class="fw-bold">Total</th><td class="fw-bold">{{ $booking->total_price }} EGP</td></tr>
                </table>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Customer</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Name</th><td><a href="{{ route('admin.users.show', $booking->customer->id) }}">{{ $booking->customer->name }}</a></td></tr>
                    <tr><th>Email</th><td>{{ $booking->customer->email }}</td></tr>
                    <tr><th>Phone</th><td>{{ $booking->customer->phone }}</td></tr>
                    <tr><th>Car Number</th><td>{{ $booking->customer->car_number }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        @if($booking->payment)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Payment</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Method</th><td>{{ ucfirst($booking->payment->method) }}</td></tr>
                    <tr><th>Status</th><td>{{ ucfirst($booking->payment->status) }}</td></tr>
                    <tr><th>Amount</th><td>{{ $booking->payment->amount }} EGP</td></tr>
                    @if($booking->payment->card_number_last4)
                    <tr><th>Card</th><td>**** **** **** {{ $booking->payment->card_number_last4 }}</td></tr>
                    @endif
                    @if($booking->payment->name_on_card)
                    <tr><th>Name on Card</th><td>{{ $booking->payment->name_on_card }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
        @if($booking->qr_code)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">QR Code</div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/'.$booking->qr_code) }}" width="180" class="img-fluid">
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
