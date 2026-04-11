@extends('layouts.app')
@section('title', 'Add Parking')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Add New Parking</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.parkings.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="lat" class="form-control @error('lat') is-invalid @enderror"
                                   value="{{ old('lat') }}" required>
                            @error('lat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="lng" class="form-control @error('lng') is-invalid @enderror"
                                   value="{{ old('lng') }}" required>
                            @error('lng')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price per Hour (EGP) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price_per_hour" class="form-control @error('price_per_hour') is-invalid @enderror"
                                   value="{{ old('price_per_hour') }}" required>
                            @error('price_per_hour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Fee (EGP) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="service_fee" class="form-control @error('service_fee') is-invalid @enderror"
                                   value="{{ old('service_fee', '5.00') }}" required>
                            @error('service_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Parking</button>
                            <a href="{{ route('admin.parkings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
