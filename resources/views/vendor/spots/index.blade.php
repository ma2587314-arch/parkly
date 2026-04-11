@extends('layouts.app')
@section('title', 'Spots')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Spots</h5>
    <a href="{{ route('vendor.spots.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Spot
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="spotsTable">
            <thead>
                <tr><th>#</th><th>Parking</th><th>Number</th><th>Type</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($spots as $spot)
                <tr>
                    <td>{{ $spot->id }}</td>
                    <td>{{ $spot->parking->name }}</td>
                    <td class="fw-semibold">{{ $spot->spot_number }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($spot->type) }}</span></td>
                    <td>
                        @if($spot->status === 'active')
                            <span class="badge badge-confirmed">Active</span>
                        @else
                            <span class="badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('vendor.spots.edit', $spot->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('vendor.spots.destroy', $spot->id) }}"
                              onsubmit="return confirm('Delete this spot?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No spots yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $spots->links() }}
@endsection

@push('scripts')
<script>$('#spotsTable').DataTable({ pageLength: 20, order: [], paging: false });</script>
@endpush
