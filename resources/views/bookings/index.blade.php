@extends('layouts.app')
@section('title', 'Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Bookings</h5>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="bookingsTable">
            <thead>
                <tr>
                    <th>#</th><th>User</th><th>Parking</th><th>Spot</th>
                    <th>Start</th><th>End</th><th>Total</th><th>Fine</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->parking->name }}</td>
                    <td>{{ $booking->spot->spot_number }}</td>
                    <td class="small">{{ $booking->start_time->format('d M Y H:i') }}</td>
                    <td class="small">{{ $booking->end_time->format('d M Y H:i') }}</td>
                    <td>{{ $booking->total_price }} EGP</td>
                    <td>{{ $booking->fine_amount > 0 ? $booking->fine_amount.' EGP' : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }} rounded-pill px-2">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                        @if($booking->status !== 'cancelled')
                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}" class="d-inline"
                              onsubmit="return confirm('Cancel this booking?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $bookings->links() }}</div>
</div>
@endsection
@push('scripts')
<script>$('#bookingsTable').DataTable({ paging: false, order: [] });</script>
@endpush
