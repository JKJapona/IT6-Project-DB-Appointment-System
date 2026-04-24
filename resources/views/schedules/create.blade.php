@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Create New Schedule</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Set daily booking capacity for a specific department.</p>
    </div>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
    </a>
</div>

@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.875rem;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="bi bi-exclamation-triangle-fill"></i> Validation Errors</div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="details-card">
    <form action="{{ route('schedules.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="department_id">Assign to Department</label>
                <select name="department_id" id="department_id" class="form-control" required>
                    <option value="" disabled selected>-- Select a Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="schedule_date">Schedule Date</label>
                <input type="date" name="schedule_date" id="schedule_date" class="form-control" value="{{ old('schedule_date', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="max_capacity">Max Capacity (Slots)</label>
                <input type="number" name="max_capacity" id="max_capacity" class="form-control" min="1" placeholder="e.g. 15" value="{{ old('max_capacity', 10) }}" required>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-calendar-check"></i> Save Schedule
            </button>
        </div>
    </form>
</div>
@endsection