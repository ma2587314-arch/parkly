<div id="navbar" class="d-flex align-items-center justify-content-between">
    <h6 class="mb-0 fw-semibold text-secondary">@yield('title', 'Dashboard')</h6>
    <div class="d-flex align-items-center gap-3">
        @if(auth()->user()->isSuperAdmin())
            <span class="badge bg-primary small">Super Admin</span>
            <a href="{{ route('admin.profile.edit') }}" class="text-muted small text-decoration-none">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </a>
        @elseif(auth()->user()->isVendor())
            <span class="badge bg-success small">Vendor</span>
            <a href="{{ route('vendor.profile.edit') }}" class="text-muted small text-decoration-none">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </a>
        @endif
    </div>
</div>
