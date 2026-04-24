@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Appointments</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Manage and monitor patient clinical visits.</p>
    </div>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-calendar-plus"></i> Book Appointment
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Ref #</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Dept / Date</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $app)
            <tr>
                <td><strong style="color: var(--primary-blue);">{{ $app->reference_number }}</strong></td>
                <td>
                    <div style="font-weight: 600;">{{ $app->patient->first_name }} {{ $app->patient->last_name }}</div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-person-badge" style="color: var(--font-muted);"></i>
                        Dr. {{ $app->doctor->last_name }}
                    </div>
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $app->schedule->department->department_name }}</div>
                    <small style="color: var(--font-muted);">
                        <i class="bi bi-clock" style="font-size: 0.75rem;"></i> 
                        {{ \Carbon\Carbon::parse($app->schedule->schedule_date)->format('M d, Y') }}
                    </small>
                </td>
                <td>
                    <span class="badge {{ $app->status == 'Completed' ? 'badge-success' : ($app->status == 'Cancelled' ? 'badge-danger' : 'badge-pending') }}">
                        {{ $app->status }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('appointments.show', $app->appointment_id) }}" class="btn btn-secondary" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('appointments.edit', $app->appointment_id) }}" class="btn btn-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('appointments.destroy', $app->appointment_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this record?')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-calendar-x" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No appointments found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection