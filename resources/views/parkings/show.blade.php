@extends('layouts.app')
@section('title', $parking->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ $parking->name }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.parkings.edit', $parking->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('admin.parkings.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                @if($parking->image)
                    <img src="{{ asset('storage/'.$parking->image) }}" class="img-fluid rounded mb-3">
                @endif
                <table class="table table-sm">
                    <tr><th>Address</th><td>{{ $parking->address }}</td></tr>
                    <tr><th>Lat / Lng</th><td>{{ $parking->lat }}, {{ $parking->lng }}</td></tr>
                    <tr><th>Price/hr</th><td>{{ $parking->price_per_hour }} EGP</td></tr>
                    <tr><th>Service Fee</th><td>{{ $parking->service_fee }} EGP</td></tr>
                    <tr><th>Total Spots</th><td>{{ $parking->spots_count }}</td></tr>
                    <tr><th>Total Bookings</th><td>{{ $parking->bookings_count }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Vendor Account --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3 d-flex align-items-center justify-content-between">
                <span><i class="bi bi-person-badge me-1"></i> Vendor Account</span>
                @if($parking->vendor)
                    <span class="badge rounded-pill {{ $parking->vendor->is_blocked ? 'bg-danger' : 'bg-success' }} px-3">
                        {{ $parking->vendor->is_blocked ? 'Disabled' : 'Active' }}
                    </span>
                @else
                    <span class="badge rounded-pill bg-secondary px-3">Not Created</span>
                @endif
            </div>
            <div class="card-body">
                @if($parking->vendor)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                             style="width:38px;height:38px;flex-shrink:0;">
                            {{ strtoupper(substr($parking->vendor->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $parking->vendor->name }}</div>
                            <div class="text-muted small">{{ $parking->vendor->email }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-muted small mb-2">No vendor account — managed by super admin.</p>
                @endif
                <a href="{{ route('admin.parkings.edit', $parking->id) }}" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="bi bi-pencil me-1"></i> Manage Vendor Account
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Spots
                <a href="{{ route('admin.spots.create') }}" class="btn btn-sm btn-primary float-end"><i class="bi bi-plus"></i> Add Spot</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Spot Number</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($parking->spots as $spot)
                        <tr>
                            <td>{{ $spot->id }}</td>
                            <td class="fw-semibold">{{ $spot->spot_number }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($spot->type) }}</span></td>
                            <td>
                                <span class="badge {{ $spot->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($spot->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.spots.edit', $spot->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No spots added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
