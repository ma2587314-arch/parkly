@extends('layouts.app')
@section('title', 'My Parkings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">My Parkings</h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="parkingsTable">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Address</th>
                    <th>Price/Hour</th><th>Service Fee</th>
                    <th>Spots</th><th>Bookings</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parkings as $parking)
                <tr>
                    <td>{{ $parking->id }}</td>
                    <td class="fw-semibold">{{ $parking->name }}</td>
                    <td>{{ $parking->address }}</td>
                    <td>{{ number_format($parking->price_per_hour, 2) }} EGP</td>
                    <td>{{ number_format($parking->service_fee, 2) }} EGP</td>
                    <td>{{ $parking->spots_count }}</td>
                    <td>{{ $parking->bookings_count }}</td>
                    <td>
                        <a href="{{ route('vendor.parkings.edit', $parking->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No parkings assigned to your account yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>$('#parkingsTable').DataTable({ pageLength: 15, order: [] });</script>
@endpush
