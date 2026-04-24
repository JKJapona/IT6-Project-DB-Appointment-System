@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Staff Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Personnel profile and assigned clinical department.</p>
    </div>
    <a href="{{ route('staff.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Directory
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Staff ID</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $staffMember->staff_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">Employment Status</span>
            <span class="badge badge-success">
                <i class="bi bi-patch-check-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i>
                Active Personnel
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Personal Information</div>
                <div class="info-content">
                    <strong>Full Name:</strong> {{ $staffMember->first_name }} {{ $staffMember->last_name }}<br>
                    <strong>System Role:</strong> {{ $staffMember->role }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Clinical Expertise</div>
                <div class="info-content">
                    <strong>Assigned Unit:</strong> {{ $staffMember->department->department_name ?? 'Unassigned' }}<br>
                    <strong>Specialization:</strong> {{ $staffMember->specialization ?: 'General Practice / N/A' }}
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Administrative Info</div>
                <div class="meta-box">
                    <div style="margin-bottom: 6px;">
                        <i class="bi bi-calendar-check"></i> <strong>Joined:</strong> {{ $staffMember->created_at->format('M d, Y') }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history"></i> <strong>Last Profile Update:</strong> {{ $staffMember->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center;">
    <a href="{{ route('staff.edit', $staffMember->staff_id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Edit Profile
    </a>
    
    <form action="{{ route('staff.destroy', $staffMember->staff_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Remove this staff record?')">
            <i class="bi bi-trash"></i> Delete Record
        </button>
    </form>
</div>
@endsection