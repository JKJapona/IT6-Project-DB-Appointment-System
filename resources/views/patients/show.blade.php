@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Patient Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Comprehensive record and medical history overview.</p>
    </div>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Patient ID</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $patient->patient_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">System Status</span>
            <span class="badge badge-success">
                <i class="bi bi-person-check-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i>
                Registered
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Personal Information</div>
                <div class="info-content">
                    <strong>Full Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}<br>
                    <strong>Contact:</strong> {{ $patient->contact_number ?: 'N/A' }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Medical History</div>
                <div class="info-content" style="line-height: 1.6; color: var(--font-main);">
                    {{ $patient->medical_history ?: 'No medical history recorded for this patient.' }}
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">System Info</div>
                <div class="meta-box">
                    <div style="margin-bottom: 6px;">
                        <i class="bi bi-calendar-plus"></i> <strong>Registered:</strong> {{ $patient->created_at->format('M d, Y - h:i A') }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history"></i> <strong>Last Update:</strong> {{ $patient->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center; margin-top: 20px;">
    <a href="{{ route('patients.edit', $patient->patient_id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Edit Patient
    </a>
    
    <form action="{{ route('patients.destroy', $patient->patient_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this patient record?')">
            <i class="bi bi-trash"></i> Delete Record
        </button>
    </form>
</div>
@endsection