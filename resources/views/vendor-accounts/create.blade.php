@extends('layouts.app')
@section('title', 'Create Vendor Account')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.vendors.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">Create Vendor Account</h5>
</div>

<div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.vendors.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 6 characters" required>
                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password_confirmation">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            @if($parkings->count())
            <div class="mb-4">
                <label class="form-label fw-semibold">Assign Parking</label>
                <p class="text-muted small mb-2">Only unassigned parkings are shown.</p>
                <select name="parking_id" class="form-select @error('parking_id') is-invalid @enderror">
                    <option value="">— No parking assigned yet —</option>
                    @foreach($parkings as $parking)
                        <option value="{{ $parking->id }}" {{ old('parking_id') == $parking->id ? 'selected' : '' }}>
                            {{ $parking->name }} — {{ $parking->address }}
                        </option>
                    @endforeach
                </select>
                @error('parking_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @else
            <div class="alert alert-info py-2 small mb-4">
                <i class="bi bi-info-circle me-1"></i>
                All parkings are currently assigned. Create a parking first or reassign from the vendor's profile.
            </div>
            @endif

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-person-plus me-1"></i> Create Vendor
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
</script>
@endpush
