@extends('layouts.app')
@section('title', 'Parkings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Parkings</h5>
    <a href="{{ route('admin.parkings.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Parking</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="parkingsTable">
            <thead>
                <tr><th>#</th><th>Image</th><th>Name</th><th>Address</th><th>Price/hr</th><th>Service Fee</th><th>Spots</th><th>Bookings</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($parkings as $parking)
                <tr>
                    <td>{{ $parking->id }}</td>
                    <td>
                        @if($parking->image)
                            <img src="{{ asset('storage/'.$parking->image) }}" width="50" height="40" style="object-fit:cover;border-radius:6px;">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $parking->name }}</td>
                    <td class="text-muted small">{{ $parking->address }}</td>
                    <td>{{ $parking->price_per_hour }} EGP</td>
                    <td>{{ $parking->service_fee }} EGP</td>
                    <td>{{ $parking->spots_count }}</td>
                    <td>{{ $parking->bookings_count }}</td>
                    <td>
                        <a href="{{ route('admin.parkings.show', $parking->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.parkings.edit', $parking->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.parkings.destroy', $parking->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this parking?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $parkings->links() }}</div>
</div>
@endsection
@push('scripts')
<script>$('#parkingsTable').DataTable({ paging: false, order: [] });</script>
@endpush
