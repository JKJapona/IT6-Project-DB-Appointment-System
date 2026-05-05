@extends('layout')

@section('page_title')
    <a href="{{ route('appointments.index') }}" style="text-decoration: none; color: inherit;">Appointments</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Edit</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit Appointment</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">
            Reference: <span style="color: var(--primary-blue); font-weight: 700;">#{{ $appointment->reference_number }}</span>
        </p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

{{-- 1. Database Trigger / Session Error Alert --}}
@if (session('error'))
    <div style="background: #fef2f2; border: 1px solid #dc26268c; color: #991b1b; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.875rem; display: flex; align-items: center; gap: 12px;">
        <i class="bi bi-shield-lock-fill" style="font-size: 1.25rem; color: #dc2626;"></i>
        <div>
            <div style="font-weight: 700;">Database Trigger Restriction</div>
            {{ session('error') }}
        </div>
    </div>
@endif

{{-- 2. Success Message --}}
@if (session('success'))
    <div style="background: #f0fdf4; border: 1px solid #16a34a; color: #166534; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.875rem;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- 3. Standardized Validation Error Block --}}
@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.875rem;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:</div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="details-card">
    <form action="{{ route('appointments.update', $appointment->appointment_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="patient_id">Patient</label>
                <select name="patient_id" id="patient_id" class="form-control" required>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->patient_id }}" 
                            {{ old('patient_id', $appointment->patient_id) == $patient->patient_id ? 'selected' : '' }}>
                            {{ $patient->last_name }}, {{ $patient->first_name }} {{ $patient->suffix !== 'None' ? $patient->suffix : '' }} (ID: {{ $patient->patient_id }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="assigned_doctor_id">Assigned Doctor</label>
                <select name="assigned_doctor_id" id="assigned_doctor_id" class="form-control" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->staff_id }}" 
                            {{ old('assigned_doctor_id', $appointment->assigned_doctor_id) == $doctor->staff_id ? 'selected' : '' }}>
                            Dr. {{ $doctor->last_name }} ({{ $doctor->specialization ?: 'General Practice' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="schedule_id">Schedule Slot</label>
                <select name="schedule_id" id="schedule_id" class="form-control" required>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->schedule_id }}" 
                            {{ old('schedule_id', $appointment->schedule_id) == $schedule->schedule_id ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('M d, Y') }} - {{ $schedule->department->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="chief_complaint">Chief Complaint</label>
                <textarea name="chief_complaint" id="chief_complaint" class="form-control" rows="3" placeholder="Describe the primary reason for the visit...">{{ old('chief_complaint', $appointment->chief_complaint) }}</textarea>
            </div>
        </div>

        <div class="form-row" style="margin-top: 10px;">
            <div class="form-group form-group-full" style="background: var(--sidebar-accent); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <label for="status" style="color: var(--primary-blue);">Administrative Status</label>
                <select name="status" id="status" class="form-control" required style="border-color: var(--primary-blue);">
                    @foreach(['Pending', 'Confirmed', 'Completed', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('status', $appointment->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <small style="margin-top: 8px; color: var(--font-muted); display: block;">
                    <i class="bi bi-info-circle"></i> Updating status to <strong>Completed</strong> will enable invoice generation in the accounting module.
                </small>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary" style="padding: 12px 24px;">Cancel Changes</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-save"></i> Save Updates
            </button>
        </div>
    </form>
</div>
@endsection