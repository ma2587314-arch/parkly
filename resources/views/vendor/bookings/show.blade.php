@extends('layouts.app')
@section('title', 'Booking #' . $booking->id)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('vendor.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Booking #{{ $booking->id }}</h5>
    <span class="badge badge-{{ $booking->status }} rounded-pill px-3">{{ ucfirst($booking->status) }}</span>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold py-3">Customer</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Name</dt>
                    <dd class="col-7">{{ $booking->customer->name }}</dd>
                    <dt class="col-5 text-muted">Email</dt>
                    <dd class="col-7">{{ $booking->customer->email }}</dd>
                    <dt class="col-5 text-muted">Phone</dt>
                    <dd class="col-7">{{ $booking->customer->phone }}</dd>
                    <dt class="col-5 text-muted">Car Number</dt>
                    <dd class="col-7">{{ $booking->customer->car_number }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold py-3">Booking Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Parking</dt>
                    <dd class="col-7">{{ $booking->parking->name }}</dd>
                    <dt class="col-5 text-muted">Spot</dt>
                    <dd class="col-7">{{ $booking->spot->spot_number }} ({{ ucfirst($booking->spot->type) }})</dd>
                    <dt class="col-5 text-muted">Start</dt>
                    <dd class="col-7">{{ $booking->start_time->format('d M Y H:i') }}</dd>
                    <dt class="col-5 text-muted">End</dt>
                    <dd class="col-7">{{ $booking->end_time->format('d M Y H:i') }}</dd>
                    @if($booking->actual_end_time)
                    <dt class="col-5 text-muted">Actual End</dt>
                    <dd class="col-7">{{ $booking->actual_end_time->format('d M Y H:i') }}</dd>
                    @endif
                    <dt class="col-5 text-muted">Total</dt>
                    <dd class="col-7">{{ number_format($booking->total_price, 2) }} EGP</dd>
                    @if($booking->fine_amount > 0)
                    <dt class="col-5 text-muted text-danger">Fine</dt>
                    <dd class="col-7 text-danger">{{ number_format($booking->fine_amount, 2) }} EGP</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    @if($booking->payment)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">Payment</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Method</dt>
                    <dd class="col-7">{{ ucfirst(str_replace('_', ' ', $booking->payment->method)) }}</dd>
                    <dt class="col-5 text-muted">Status</dt>
                    <dd class="col-7">{{ ucfirst($booking->payment->status) }}</dd>
                    @if($booking->payment->card_number_last4)
                    <dt class="col-5 text-muted">Card (last 4)</dt>
                    <dd class="col-7">**** {{ $booking->payment->card_number_last4 }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    @endif
    @if($booking->qr_code)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">QR Code</div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $booking->qr_code) }}" alt="QR" style="width:160px;">
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
