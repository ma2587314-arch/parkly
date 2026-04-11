<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Parkly Admin — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary: #1a73e8; }
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        #sidebar {
            width: var(--sidebar-width); height: 100vh; position: fixed;
            top: 0; left: 0; background: #1e2a3a; overflow-y: auto; z-index: 1000;
        }
        #sidebar .brand { padding: 1.5rem 1.25rem; color: #fff; font-size: 1.4rem; font-weight: 700; letter-spacing: 1px; }
        #sidebar .brand span { color: #1a73e8; }
        #sidebar .nav-link { color: #a4b3c6; padding: .65rem 1.25rem; border-radius: 8px; margin: 2px 10px; transition: all .2s; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background: #1a73e8; color: #fff; }
        #sidebar .nav-link i { width: 22px; }
        #main { margin-left: var(--sidebar-width); }
        #navbar { background: #fff; border-bottom: 1px solid #e5e9f0; padding: .75rem 1.5rem; position: sticky; top: 0; z-index: 999; }
        .stat-card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
        .stat-card .icon-box { width: 52px; height: 52px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
        .table thead th { background: #f8fafc; border: none; font-weight: 600; font-size: .82rem; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; }
        .badge-pending    { background: #fff3cd; color: #856404; }
        .badge-confirmed  { background: #d1fae5; color: #065f46; }
        .badge-cancelled  { background: #fee2e2; color: #991b1b; }
        .content-area { padding: 1.75rem; }
    </style>
    @stack('styles')
</head>
<body>
    @include('includes.sidebar')
    <div id="main">
        @include('includes.navbar')
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
