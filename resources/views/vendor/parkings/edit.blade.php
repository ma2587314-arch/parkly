@extends('layouts.app')
@section('title', 'Edit Parking')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('vendor.parkings.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Edit: {{ $parking->name }}</h5>
</div>

<div class="card border-0 shadow-sm" style="max-width:680px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('vendor.parkings.update', $parking->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $parking->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Address</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address', $parking->address) }}" required>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Latitude</label>
                    <input type="number" step="any" name="lat" class="form-control @error('lat') is-invalid @enderror"
                           value="{{ old('lat', $parking->lat) }}" required>
                    @error('lat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Longitude</label>
                    <input type="number" step="any" name="lng" class="form-control @error('lng') is-invalid @enderror"
                           value="{{ old('lng', $parking->lng) }}" required>
                    @error('lng')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price per Hour (EGP)</label>
                    <input type="number" step="0.01" name="price_per_hour"
                           class="form-control @error('price_per_hour') is-invalid @enderror"
                           value="{{ old('price_per_hour', $parking->price_per_hour) }}" required>
                    @error('price_per_hour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Service Fee (EGP)</label>
                    <input type="number" step="0.01" name="service_fee"
                           class="form-control @error('service_fee') is-invalid @enderror"
                           value="{{ old('service_fee', $parking->service_fee) }}" required>
                    @error('service_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Parking Image</label>
                @if($parking->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $parking->image) }}" alt="Current" class="rounded" style="height:100px;object-fit:cover;">
                    </div>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
