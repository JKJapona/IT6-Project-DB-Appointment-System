@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Book New Appointment</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Fill in the details to schedule a patient visit.</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
    </a>
</div>

{{-- Validation Errors --}}
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
    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        {{-- Patient Selection - Full Width --}}
        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="patient_id">Select Patient</label>
                <select name="patient_id" id="patient_id" class="form-control" required>
                    <option value="" disabled selected>-- Search Patient by Name or ID --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->patient_id }}" {{ old('patient_id') == $patient->patient_id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }} (ID: {{ $patient->patient_id }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            {{-- Doctor Selection --}}
            <div class="form-group">
                <label for="assigned_doctor_id">Assign Doctor</label>
                <select name="assigned_doctor_id" id="assigned_doctor_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Practitioner --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->staff_id }}" {{ old('assigned_doctor_id') == $doctor->staff_id ? 'selected' : '' }}>
                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} ({{ $doctor->specialization }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Schedule Selection --}}
            <div class="form-group">
                <label for="schedule_id">Select Schedule Date</label>
                <select name="schedule_id" id="schedule_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Available Date --</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->schedule_id }}" {{ old('schedule_id') == $schedule->schedule_id ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('M d, Y') }} - {{ $schedule->department->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="meta-box" style="margin-top: 8px;">
            <p style="margin: 0; display: flex; gap: 10px; align-items: flex-start;">
                <i class="bi bi-info-circle-fill" style="color: var(--primary-blue); font-size: 1.1rem;"></i>
            <span>
                <strong>Auto-Generation:</strong> A unique Reference Number will be automatically generated upon submission. 
                The appointment status will be set to 
                <span class="badge badge-pending" style="display: inline-flex; align-items: center; vertical-align: middle; line-height: 1; padding: 4px 8px; margin: 0 2px;">
                    Pending
                </span> 
                by default.
            </span>
            </p>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-check2-circle"></i> Confirm Booking
            </button>
        </div>
    </form>
</div>
@endsection