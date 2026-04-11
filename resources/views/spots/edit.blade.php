@extends('layouts.app')
@section('title', 'Edit Spot')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Edit Spot — {{ $spot->spot_number }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.spots.update', $spot->id) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Parking <span class="text-danger">*</span></label>
                            <select name="parking_id" class="form-select @error('parking_id') is-invalid @enderror" required>
                                @foreach($parkings as $parking)
                                    <option value="{{ $parking->id }}" {{ old('parking_id', $spot->parking_id) == $parking->id ? 'selected' : '' }}>
                                        {{ $parking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parking_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Spot Number</label>
                            <input type="text" name="spot_number" class="form-control @error('spot_number') is-invalid @enderror"
                                   value="{{ old('spot_number', $spot->spot_number) }}" required>
                            @error('spot_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="regular"  {{ old('type', $spot->type) == 'regular'  ? 'selected' : '' }}>Regular</option>
                                <option value="vip"      {{ old('type', $spot->type) == 'vip'      ? 'selected' : '' }}>VIP</option>
                                <option value="disabled" {{ old('type', $spot->type) == 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active"   {{ old('status', $spot->status) == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $spot->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Spot</button>
                            <a href="{{ route('admin.spots.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
