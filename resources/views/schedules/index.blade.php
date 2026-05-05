@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Department Schedules</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Manage appointment availability and booking limits for clinical units.</p>
    </div>
    <a href="{{ route('schedules.create') }}" class="btn btn-primary">
        <i class="bi bi-calendar-plus"></i> Create New Schedule
    </a>
</div>

@if (session('error'))
    <div style="background: #fef2f2; border: 1px solid #dc2626; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.875rem; display: flex; align-items: center; gap: 12px;">
        <i class="bi bi-shield-lock-fill" style="font-size: 1.25rem; color: #dc2626;"></i>
        <div>
            <div style="font-weight: 700;">System Security Restriction</div>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Date</th>
                <th>Capacity</th>
                <th>Booked</th>
                <th>Availability</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
            <tr>
                <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $schedule->schedule_id }}</span></td>
                <td><strong style="color: var(--primary-blue);">{{ $schedule->department->department_name }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('M d, Y') }}</td>
                <td>{{ $schedule->max_capacity }}</td>
                <td>{{ $schedule->current_booked }}</td>
                <td>
                    @php $remaining = $schedule->max_capacity - $schedule->current_booked; @endphp
                    @if($remaining <= 0)
                        <span class="badge badge-danger" style="font-weight: 700;">FULL</span>
                    @else
                        <span class="badge badge-success">{{ $remaining }} Slots Left</span>
                    @endif
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('schedules.show', $schedule->schedule_id) }}" class="btn btn-secondary" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('schedules.edit', $schedule->schedule_id) }}" class="btn btn-secondary" title="Edit Schedule">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('schedules.destroy', $schedule->schedule_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this schedule?')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-calendar-x" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No schedules found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection