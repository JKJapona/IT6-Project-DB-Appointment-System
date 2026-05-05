@extends('layout')

@section('page_title')
    <a href="{{ route('appointments.index') }}" style="text-decoration: none; color: inherit;">Appointments</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Details</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Appointment Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Detailed overview and administrative history for this record.</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Reference Number</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $appointment->reference_number }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">Booking Status</span>
            <span class="badge {{ $appointment->status == 'Completed' ? 'badge-info' : ($appointment->status == 'Cancelled' ? 'badge-danger' : ($appointment->status == 'Confirmed' ? 'badge-success' : 'badge-pending')) }}" 
                style="{{ $appointment->status == 'Completed' ? 'background-color: rgba(0, 123, 255, 0.1); color: #004085;' : '' }}">
                <i class="bi bi-circle-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px; {{ $appointment->status == 'Completed' ? 'color: #007bff;' : '' }}"></i>
                {{ $appointment->status }}
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Patient Information</div>
                <div class="info-content">
                    <strong>PhilHealth ID:</strong> {{ $appointment->patient->philhealth_id ?: 'N/A' }}<br>
                    <strong>Name:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->middle_name }} {{ $appointment->patient->last_name }} {{ $appointment->patient->suffix !== 'None' ? $appointment->patient->suffix : '' }}<br>
                    <strong>Gender:</strong> {{ $appointment->patient->gender }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Assigned Medical Staff</div>
                <div class="info-content">
                    <strong>Doctor:</strong> Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}<br>
                    <strong>Specialization:</strong> {{ $appointment->doctor->specialization ?: 'General Practice' }}
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Schedule & Department</div>
                <div class="info-content">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->schedule->schedule_date)->format('F d, Y') }}<br>
                    <strong>Department:</strong> {{ $appointment->schedule->department->department_name }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Consultation Data</div>
                <div class="info-content">
                    <strong>Chief Complaint:</strong><br>
                    <span style="color: var(--font-main);">{{ $appointment->chief_complaint ?: 'No complaint recorded.' }}</span>
                </div>
            </div>

            <div class="meta-box">
                <div style="margin-bottom: 4px;">
                    <i class="bi bi-clock-history"></i> <strong>Booked On:</strong> {{ \Carbon\Carbon::parse($appointment->booking_timestamp)->format('M d, Y - h:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center; margin-top: 20px;">
    <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Update Status
    </a>
    
    @if($appointment->status == 'Completed' || $appointment->invoice)
        <a href="{{ route('invoices.show', $appointment->invoice->invoice_id) }}" class="btn" style="background: #10b981; color: white; border: none;">
            <i class="bi bi-file-earmark-medical"></i> View Invoice
        </a>
    @endif

    <form action="{{ route('appointments.destroy', $appointment->appointment_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel and delete this appointment record?')">
            <i class="bi bi-trash"></i> Delete Record
        </button>
    </form>
</div>
@endsection