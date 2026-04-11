<div id="sidebar" class="d-flex flex-column">
    <div class="brand">Park<span>ly</span></div>
    <nav class="nav flex-column mt-2">
        @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.parkings.index') }}" class="nav-link {{ request()->routeIs('admin.parkings.*') ? 'active' : '' }}">
                <i class="bi bi-p-square me-2"></i> Parkings
            </a>
            <a href="{{ route('admin.spots.index') }}" class="nav-link {{ request()->routeIs('admin.spots.*') ? 'active' : '' }}">
                <i class="bi bi-grid me-2"></i> Spots
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Bookings
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> App Users
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                <i class="bi bi-shop me-2"></i> Vendors
            </a>
            <a href="{{ route('admin.api-docs') }}" class="nav-link {{ request()->routeIs('admin.api-docs') ? 'active' : '' }}">
                <i class="bi bi-braces me-2"></i> API Docs
            </a>
        @elseif(auth()->user()->isVendor())
            <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('vendor.parkings.index') }}" class="nav-link {{ request()->routeIs('vendor.parkings.*') ? 'active' : '' }}">
                <i class="bi bi-p-square me-2"></i> My Parkings
            </a>
            <a href="{{ route('vendor.spots.index') }}" class="nav-link {{ request()->routeIs('vendor.spots.*') ? 'active' : '' }}">
                <i class="bi bi-grid me-2"></i> Spots
            </a>
            <a href="{{ route('vendor.bookings.index') }}" class="nav-link {{ request()->routeIs('vendor.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Bookings
            </a>
        @endif
    </nav>
    <div class="mt-auto p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-secondary w-100 text-white border-secondary">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</div>
