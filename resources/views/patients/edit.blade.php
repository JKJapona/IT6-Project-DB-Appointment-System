@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit Patient Profile</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Updating record for <span style="color: var(--primary-blue); font-weight: 700;">ID: #{{ $patient->patient_id }}</span></p>
    </div>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
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
    <form action="{{ route('patients.update', $patient->patient_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required maxlength="50" value="{{ old('first_name', $patient->first_name) }}">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required maxlength="50" value="{{ old('last_name', $patient->last_name) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control" maxlength="15" value="{{ old('contact_number', $patient->contact_number) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="medical_history">Medical History</label>
                <textarea name="medical_history" id="medical_history" class="form-control" rows="5" placeholder="Enter allergies, surgeries, or chronic conditions...">{{ old('medical_history', $patient->medical_history) }}</textarea>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('patients.index') }}" class="btn btn-secondary" style="padding: 12px 24px;">Cancel Changes</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-check-circle"></i> Update Patient Record
            </button>
        </div>
    </form>
</div>
@endsection