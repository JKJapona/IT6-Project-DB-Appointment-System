@extends('layout')

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
            <span class="badge {{ $appointment->status == 'Completed' ? 'badge-success' : ($appointment->status == 'Cancelled' ? 'badge-danger' : 'badge-pending') }}">
                <i class="bi bi-circle-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i>
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
                    <strong>Name:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}<br>
                    <strong>Contact:</strong> {{ $appointment->patient->contact_number }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Assigned Medical Staff</div>
                <div class="info-content">
                    <strong>Doctor:</strong> Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}<br>
                    <strong>Specialization:</strong> {{ $appointment->doctor->specialization }}
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

            <div class="meta-box">
                <div style="margin-bottom: 4px;">
                    <i class="bi bi-clock-history"></i> <strong>Booked On:</strong> {{ $appointment->booking_timestamp }}
                </div>
                <div>
                    <i class="bi bi-person-check"></i> <strong>Processed By:</strong> {{ $appointment->processor->first_name ?? 'System' }} {{ $appointment->processor->last_name ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center;">
    <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Update Status
    </a>
    
    @if($appointment->status == 'Completed')
        <a href="{{ route('invoices.show', $appointment->invoice->invoice_id ?? '#') }}" class="btn" style="background: #10b981; color: white; border: none;">
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