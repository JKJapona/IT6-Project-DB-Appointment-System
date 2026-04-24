@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Department Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Detailed configuration and system records.</p>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Internal ID</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $department->department_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">System Status</span>
            <span class="badge badge-success">
                <i class="bi bi-check-circle-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i>
                Active Unit
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Department Information</div>
                <div class="info-content">
                    <strong>Official Name:</strong> {{ $department->department_name }}<br>
                    <strong>Functional Scope:</strong> Clinical Operations
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Core Description</div>
                <div class="info-content" style="line-height: 1.6;">
                    {{ $department->description ?: 'No description provided for this department.' }}
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">System Records</div>
                <div class="meta-box">
                    <div style="margin-bottom: 6px;">
                        <i class="bi bi-calendar-plus"></i> <strong>Registered:</strong> {{ $department->created_at->format('M d, Y - h:i A') }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history"></i> <strong>Last Updated:</strong> {{ $department->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center;">
    <a href="{{ route('departments.edit', $department->department_id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Edit Department
    </a>
    
    <form action="{{ route('departments.destroy', $department->department_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this entire department?')">
            <i class="bi bi-trash"></i> Delete
        </button>
    </form>
</div>
@endsection