@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Users</h5>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="usersTable">
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Car Number</th><th>Gender</th><th>Bookings</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>{{ $user->car_number }}</td>
                    <td>{{ $user->gender ? ucfirst($user->gender) : '—' }}</td>
                    <td>{{ $user->bookings_count }}</td>
                    <td>
                        <span class="badge {{ $user->is_blocked ? 'bg-danger' : 'bg-success' }}">
                            {{ $user->is_blocked ? 'Blocked' : 'Active' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                        <form method="POST" action="{{ route('admin.users.block', $user->id) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm {{ $user->is_blocked ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                <i class="bi {{ $user->is_blocked ? 'bi-unlock' : 'bi-lock' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $users->links() }}</div>
</div>
@endsection
@push('scripts')
<script>$('#usersTable').DataTable({ paging: false, order: [] });</script>
@endpush
