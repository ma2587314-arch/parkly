@extends('layouts.app')
@section('title', 'Add Spot')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Add New Spot</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.spots.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Parking <span class="text-danger">*</span></label>
                            <select name="parking_id" class="form-select @error('parking_id') is-invalid @enderror" required>
                                <option value="">Select Parking</option>
                                @foreach($parkings as $parking)
                                    <option value="{{ $parking->id }}" {{ old('parking_id') == $parking->id ? 'selected' : '' }}>
                                        {{ $parking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parking_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Spot Number <span class="text-danger">*</span></label>
                            <input type="text" name="spot_number" class="form-control @error('spot_number') is-invalid @enderror"
                                   value="{{ old('spot_number') }}" placeholder="e.g. A-1" required>
                            @error('spot_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="regular" {{ old('type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="vip"     {{ old('type') == 'vip'     ? 'selected' : '' }}>VIP</option>
                                <option value="disabled" {{ old('type') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Spot</button>
                            <a href="{{ route('admin.spots.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
