@extends('layouts.app')
@section('title', 'Edit Parking')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Parking Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Edit Parking — {{ $parking->name }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.parkings.update', $parking->id) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $parking->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $parking->address) }}" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" name="lat" class="form-control @error('lat') is-invalid @enderror"
                                   value="{{ old('lat', $parking->lat) }}" required>
                            @error('lat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" name="lng" class="form-control @error('lng') is-invalid @enderror"
                                   value="{{ old('lng', $parking->lng) }}" required>
                            @error('lng')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price per Hour (EGP)</label>
                            <input type="number" step="0.01" name="price_per_hour" class="form-control @error('price_per_hour') is-invalid @enderror"
                                   value="{{ old('price_per_hour', $parking->price_per_hour) }}" required>
                            @error('price_per_hour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Fee (EGP)</label>
                            <input type="number" step="0.01" name="service_fee" class="form-control @error('service_fee') is-invalid @enderror"
                                   value="{{ old('service_fee', $parking->service_fee) }}" required>
                            @error('service_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Image</label>
                            @if($parking->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$parking->image) }}" height="80" style="border-radius:8px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Parking</button>
                            <a href="{{ route('admin.parkings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Vendor Account --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-person-badge me-1"></i> Vendor Account</span>
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
                    {{-- Current account summary --}}
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-4">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                             style="width:42px;height:42px;flex-shrink:0;">
                            {{ strtoupper(substr($parking->vendor->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $parking->vendor->name }}</div>
                            <div class="text-muted small">{{ $parking->vendor->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.parkings.vendor-account.toggle', $parking->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $parking->vendor->is_blocked ? 'btn-success' : 'btn-warning' }}">
                                <i class="bi bi-{{ $parking->vendor->is_blocked ? 'toggle-on' : 'toggle-off' }} me-1"></i>
                                {{ $parking->vendor->is_blocked ? 'Enable Account' : 'Disable Account' }}
                            </button>
                        </form>
                    </div>

                    {{-- Update account --}}
                    <form method="POST" action="{{ route('admin.parkings.vendor-account.update', $parking->id) }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="vendor_name"
                                       class="form-control @error('vendor_name') is-invalid @enderror"
                                       value="{{ old('vendor_name', $parking->vendor->name) }}" required>
                                @error('vendor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="vendor_email"
                                       class="form-control @error('vendor_email') is-invalid @enderror"
                                       value="{{ old('vendor_email', $parking->vendor->email) }}" required>
                                @error('vendor_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">New Password</label>
                                <div class="input-group">
                                    <input type="password" id="vp_new" name="vendor_password"
                                           class="form-control @error('vendor_password') is-invalid @enderror"
                                           placeholder="Leave blank to keep current">
                                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="vp_new">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('vendor_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="vp_new_confirm" name="vendor_password_confirmation"
                                           class="form-control" placeholder="Repeat new password">
                                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="vp_new_confirm">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="bi bi-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>

                @else
                    {{-- Create new account --}}
                    <p class="text-muted small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        No vendor account for this parking yet. Fill in the details below to create one.
                    </p>
                    <form method="POST" action="{{ route('admin.parkings.vendor-account.store', $parking->id) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="vendor_name"
                                       class="form-control @error('vendor_name') is-invalid @enderror"
                                       value="{{ old('vendor_name') }}" required>
                                @error('vendor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="vendor_email"
                                       class="form-control @error('vendor_email') is-invalid @enderror"
                                       value="{{ old('vendor_email') }}" required>
                                @error('vendor_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <input type="password" id="vp_create" name="vendor_password"
                                           class="form-control @error('vendor_password') is-invalid @enderror"
                                           placeholder="Min 6 characters" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="vp_create">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('vendor_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="vp_create_confirm" name="vendor_password_confirmation"
                                           class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="vp_create_confirm">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-sm px-4">
                                    <i class="bi bi-person-plus me-1"></i> Create Vendor Account
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
        </div>

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
