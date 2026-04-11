@extends('layouts.app')
@section('title', 'Bookings')

@section('content')
<h5 class="fw-bold mb-3">Bookings</h5>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="bookingsTable">
            <thead>
                <tr>
                    <th>#</th><th>Customer</th><th>Parking</th><th>Spot</th>
                    <th>Start</th><th>End</th><th>Total</th><th>Status</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->parking->name }}</td>
                    <td>{{ $booking->spot->spot_number }}</td>
                    <td>{{ $booking->start_time->format('d M Y H:i') }}</td>
                    <td>{{ $booking->end_time->format('d M Y H:i') }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} EGP</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }} rounded-pill px-2">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('vendor.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $bookings->links() }}
@endsection

@push('scripts')
<script>
$('#bookingsTable').DataTable({
    pageLength: 20,
    order: [],
    paging: false,
    language: { emptyTable: 'No bookings found.' }
});
</script>
@endpush
