@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small">Total Users</div>
                    <div class="fw-bold fs-4">{{ number_format($stats['total_users']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-p-square-fill"></i></div>
                <div>
                    <div class="text-muted small">Total Parkings</div>
                    <div class="fw-bold fs-4">{{ number_format($stats['total_parkings']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <div class="text-muted small">Total Bookings</div>
                    <div class="fw-bold fs-4">{{ number_format($stats['total_bookings']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-info bg-opacity-10 text-info"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="text-muted small">Total Revenue</div>
                    <div class="fw-bold fs-4">{{ number_format($stats['total_revenue'], 2) }} EGP</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold py-3">Recent Bookings</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="recentTable">
            <thead>
                <tr>
                    <th>#ID</th><th>User</th><th>Parking</th><th>Spot</th>
                    <th>Start</th><th>End</th><th>Total</th><th>Status</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['recent_bookings'] as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->parking->name }}</td>
                    <td>{{ $booking->spot->spot_number }}</td>
                    <td>{{ $booking->start_time->format('d M Y H:i') }}</td>
                    <td>{{ $booking->end_time->format('d M Y H:i') }}</td>
                    <td>{{ $booking->total_price }} EGP</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }} rounded-pill px-2">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#recentTable').DataTable({ paging: false, searching: false, info: false, order: [] });
</script>
@endpush
