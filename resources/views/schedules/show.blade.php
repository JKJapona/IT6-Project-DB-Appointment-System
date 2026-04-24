@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Schedule Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Detailed occupancy and registration status for this slot.</p>
    </div>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Schedule Reference</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#SCH-{{ $schedule->schedule_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">Availability Status</span>
            @if($schedule->current_booked >= $schedule->max_capacity)
                <span class="badge badge-danger"><i class="bi bi-lock-fill"></i> FULLY BOOKED</span>
            @else
                <span class="badge badge-success"><i class="bi bi-check-circle-fill"></i> OPEN FOR BOOKING</span>
            @endif
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Basic Information</div>
                <div class="info-content">
                    <strong>Department:</strong> {{ $schedule->department->department_name }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('l, F d, Y') }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Capacity Status</div>
                <div class="meta-box" style="background: var(--sidebar-accent); border-color: var(--primary-blue);">
                    <div style="font-size: 1.1rem; margin-bottom: 8px;">
                        <strong>{{ $schedule->current_booked }}</strong> / <strong>{{ $schedule->max_capacity }}</strong> Slots Filled
                    </div>
                    <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                        @php $percent = ($schedule->current_booked / $schedule->max_capacity) * 100; @endphp
                        <div style="width: {{ $percent }}%; height: 100%; background: var(--primary-blue);"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">System Records</div>
                <div class="meta-box">
                    <div style="margin-bottom: 6px;">
                        <i class="bi bi-calendar-event"></i> <strong>Created:</strong> {{ $schedule->created_at->format('M d, Y') }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history"></i> <strong>Last Update:</strong> {{ $schedule->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center;">
    <a href="{{ route('appointments.index', ['schedule_id' => $schedule->schedule_id]) }}" class="btn btn-primary">
        <i class="bi bi-list-ul"></i> View Appointments
    </a>
    
    <a href="{{ route('schedules.edit', $schedule->schedule_id) }}" class="btn btn-secondary">
        <i class="bi bi-pencil"></i> Edit Details
    </a>

    <form action="{{ route('schedules.destroy', $schedule->schedule_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Deleting this schedule will affect existing appointments. Proceed?')">
            <i class="bi bi-trash"></i> Delete Schedule
        </button>
    </form>
</div>
@endsection