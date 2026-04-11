@extends('layouts.app')
@section('title', 'Add Spot')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('vendor.spots.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Add Spot</h5>
</div>

<div class="card border-0 shadow-sm" style="max-width:520px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('vendor.spots.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Parking</label>
                <select name="parking_id" class="form-select @error('parking_id') is-invalid @enderror" required>
                    <option value="">— Select Parking —</option>
                    @foreach($parkings as $p)
                        <option value="{{ $p->id }}" {{ old('parking_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('parking_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Spot Number</label>
                <input type="text" name="spot_number" class="form-control @error('spot_number') is-invalid @enderror"
                       value="{{ old('spot_number') }}" placeholder="e.g. A1" required>
                @error('spot_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">— Select Type —</option>
                    @foreach(['regular','vip','disabled'] as $t)
                        <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active"   {{ old('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-1"></i> Create Spot
            </button>
        </form>
    </div>
</div>
@endsection
