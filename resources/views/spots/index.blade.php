@extends('layouts.app')
@section('title', 'Spots')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Spots</h5>
    <a href="{{ route('admin.spots.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Spot</a>
</div>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="parking_id" class="form-select form-select-sm">
                    <option value="">All Parkings</option>
                    @foreach($parkings as $p)
                        <option value="{{ $p->id }}" {{ request('parking_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="spotsTable">
            <thead>
                <tr><th>#</th><th>Parking</th><th>Spot Number</th><th>Type</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($spots as $spot)
                <tr>
                    <td>{{ $spot->id }}</td>
                    <td>{{ $spot->parking->name }}</td>
                    <td class="fw-semibold">{{ $spot->spot_number }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($spot->type) }}</span></td>
                    <td>
                        <span class="badge {{ $spot->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($spot->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.spots.edit', $spot->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.spots.destroy', $spot->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this spot?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $spots->links() }}</div>
</div>
@endsection
@push('scripts')
<script>$('#spotsTable').DataTable({ paging: false, order: [] });</script>
@endpush
