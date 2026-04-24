@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">User Account Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Security credentials and account link information.</p>
    </div>
    <a href="{{ route('user_accounts.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Account ID</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $user->id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">Access Level</span>
            <span class="badge {{ $user->role == 'Admin' ? 'badge-danger' : 'badge-primary' }}">
                <i class="bi bi-shield-lock-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i>
                {{ $user->role }}
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column: User Credentials --}}
        <div>
            <div class="info-section">
                <div class="info-title">Account Information</div>
                <div class="info-content">
                    <strong>Full Name:</strong> {{ $user->name }}<br>
                    <strong>Email Address:</strong> {{ $user->email }}<br>
                    <strong>Status:</strong> <span style="color: #059669; font-weight: 600;">Active</span>
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Linked Profile</div>
                <div class="info-content">
                    @if($user->role == 'Patient' && $user->patient)
                        <strong>Type:</strong> Patient Profile<br>
                        <strong>Linked Name:</strong> {{ $user->patient->first_name }} {{ $user->patient->last_name }}
                    @elseif(($user->role == 'Staff' || $user->role == 'Admin') && $user->staff)
                        <strong>Type:</strong> Staff Record<br>
                        <strong>Linked Name:</strong> {{ $user->staff->first_name }} {{ $user->staff->last_name }}
                    @else
                        <strong>Status:</strong> <span style="color: var(--font-muted);">No profile link found</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: System Metadata --}}
        <div>
            <div class="info-section">
                <div class="info-title">System Info</div>
                <div class="meta-box">
                    <div style="margin-bottom: 8px;">
                        <i class="bi bi-calendar-plus"></i> <strong>Registered:</strong> {{ $user->created_at?->format('M d, Y - h:i A') ?? 'N/A' }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history"></i> <strong>Last Update:</strong> {{ $user->updated_at?->diffForHumans() ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center; margin-top: 20px;">
    <a href="{{ route('user_accounts.edit', $user->id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Edit Account
    </a>
    
    <form action="{{ route('user_accounts.destroy', $user->id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this user account?')">
            <i class="bi bi-trash"></i> Delete Account
        </button>
    </form>
</div>
@endsection