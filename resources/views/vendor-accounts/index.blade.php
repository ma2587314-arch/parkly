@extends('layouts.app')
@section('title', 'Vendor Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Vendor Accounts</h5>
    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Vendor
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Email</th>
                    <th>Parkings</th><th>Status</th><th>Created</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $vendor)
                <tr>
                    <td>{{ $vendor->id }}</td>
                    <td class="fw-semibold">{{ $vendor->name }}</td>
                    <td>{{ $vendor->email }}</td>
                    <td>
                        @forelse($vendor->parkings as $p)
                            <span class="badge bg-secondary me-1">{{ $p->name }}</span>
                        @empty
                            <span class="text-muted small">None</span>
                        @endforelse
                    </td>
                    <td>
                        @if($vendor->is_blocked)
                            <span class="badge badge-cancelled">Suspended</span>
                        @else
                            <span class="badge badge-confirmed">Active</span>
                        @endif
                    </td>
                    <td>{{ $vendor->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.vendors.block', $vendor->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm {{ $vendor->is_blocked ? 'btn-outline-success' : 'btn-outline-warning' }}"
                                        title="{{ $vendor->is_blocked ? 'Reactivate' : 'Suspend' }}">
                                    <i class="bi bi-{{ $vendor->is_blocked ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No vendor accounts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $vendors->links() }}
@endsection


