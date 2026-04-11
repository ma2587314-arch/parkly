@extends('layouts.app')
@section('title', 'Vendor: ' . $vendor->name)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.vendors.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">{{ $vendor->name }}</h5>
    @if($vendor->is_blocked)
        <span class="badge badge-cancelled">Suspended</span>
    @else
        <span class="badge badge-confirmed">Active</span>
    @endif
</div>

<div class="row g-3">
    {{-- Info + Actions --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold py-3">Account Info</div>
            <div class="card-body">
                <dl class="row mb-3">
                    <dt class="col-5 text-muted">Name</dt><dd class="col-7">{{ $vendor->name }}</dd>
                    <dt class="col-5 text-muted">Email</dt><dd class="col-7">{{ $vendor->email }}</dd>
                    <dt class="col-5 text-muted">Joined</dt><dd class="col-7">{{ $vendor->created_at->format('d M Y') }}</dd>
                </dl>
                <form method="POST" action="{{ route('admin.vendors.block', $vendor->id) }}">
                    @csrf
                    <button class="btn btn-sm w-100 {{ $vendor->is_blocked ? 'btn-outline-success' : 'btn-outline-warning' }}">
                        <i class="bi bi-{{ $vendor->is_blocked ? 'unlock' : 'lock' }} me-1"></i>
                        {{ $vendor->is_blocked ? 'Reactivate Account' : 'Suspend Account' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">Stats</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Spots</span>
                    <span class="fw-semibold">{{ $stats['spots'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Bookings</span>
                    <span class="fw-semibold">{{ $stats['bookings'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Revenue</span>
                    <span class="fw-semibold text-success">{{ number_format($stats['revenue'], 2) }} EGP</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Assign Parking --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold py-3">Assigned Parking</div>
            <div class="card-body">
                @if($assignedParking)
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-4">
                    <i class="bi bi-p-square-fill text-primary fs-3"></i>
                    <div>
                        <div class="fw-semibold">{{ $assignedParking->name }}</div>
                        <div class="text-muted small">{{ $assignedParking->address }}</div>
                    </div>
                </div>
                @else
                <p class="text-muted small mb-4">No parking assigned yet.</p>
                @endif

                <form method="POST" action="{{ route('admin.vendors.assign-parkings', $vendor->id) }}">
                    @csrf
                    <label class="form-label fw-semibold">Change Parking</label>
                    <select name="parking_id" class="form-select mb-3">
                        <option value="">— Remove assignment —</option>
                        @foreach($availableParkings as $p)
                            <option value="{{ $p->id }}" {{ $assignedParking?->id == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} — {{ $p->address }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i> Save Assignment
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">Parking Stats</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Spots</span>
                    <span class="fw-semibold">{{ $stats['spots'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Bookings</span>
                    <span class="fw-semibold">{{ $stats['bookings'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Revenue</span>
                    <span class="fw-semibold text-success">{{ number_format($stats['revenue'], 2) }} EGP</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
