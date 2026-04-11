@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('admin.users.block', $user->id) }}">
            @csrf
            <button class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-outline-danger' }}">
                <i class="bi {{ $user->is_blocked ? 'bi-unlock me-1' : 'bi-lock me-1' }}"></i>
                {{ $user->is_blocked ? 'Unblock User' : 'Block User' }}
            </button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" width="90" height="90"
                         class="rounded-circle mb-3" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:90px;height:90px;font-size:2rem;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                <table class="table table-sm text-start">
                    <tr><th>Phone</th><td>{{ $user->phone ?? '—' }}</td></tr>
                    <tr><th>Car Number</th><td>{{ $user->car_number }}</td></tr>
                    <tr><th>Gender</th><td>{{ $user->gender ? ucfirst($user->gender) : '—' }}</td></tr>
                    <tr><th>Birthday</th><td>{{ $user->birthday ? $user->birthday->format('d M Y') : '—' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge {{ $user->is_blocked ? 'bg-danger' : 'bg-success' }}">{{ $user->is_blocked ? 'Blocked' : 'Active' }}</span></td></tr>
                    <tr><th>Joined</th><td>{{ $user->created_at->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Booking History ({{ $user->bookings_count }})</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Parking</th><th>Spot</th><th>Start</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($user->bookings()->with(['parking','spot'])->latest()->take(20)->get() as $booking)
                        <tr>
                            <td><a href="{{ route('admin.bookings.show', $booking->id) }}">#{{ $booking->id }}</a></td>
                            <td>{{ $booking->parking->name }}</td>
                            <td>{{ $booking->spot->spot_number }}</td>
                            <td class="small">{{ $booking->start_time->format('d M Y H:i') }}</td>
                            <td>{{ $booking->total_price }} EGP</td>
                            <td><span class="badge badge-{{ $booking->status }} rounded-pill px-2">{{ ucfirst($booking->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No bookings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
