@extends('layout')

@section('page_title')
    <a href="{{ route('user_accounts.index') }}" style="text-decoration: none; color: inherit;">User Accounts</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Add</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Add New User</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Register a new user into the hospital system.</p>
    </div>
    <a href="{{ route('user_accounts.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
    </a>
</div>

{{-- Error Block --}}
@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.875rem;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="details-card">
    <form action="{{ route('user_accounts.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required placeholder="First name">
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control" value="{{ old('middle_name') }}" placeholder="Optional">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required placeholder="Last name">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="user@hospital.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Minimum 6 characters">
            </div>

            <div class="form-group">
                <label for="role">Role / Access Level</label>
                <select name="role" id="role" class="form-control" required onchange="toggleIdFields()">
                    <option value="" disabled selected>Select a role...</option>
                    <option value="Patient" {{ old('role') == 'Patient' ? 'selected' : '' }}>Patient</option>
                    <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group-full" id="staff_selection" style="display: none;">
                <label for="staff_id">Link to Staff Member</label>
                <select name="staff_id" id="staff_id" class="form-control">
                    <option value="">-- Select Staff Record --</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->staff_id }}" {{ old('staff_id') == $member->staff_id ? 'selected' : '' }}>
                            {{ $member->last_name }}, {{ $member->first_name }} ({{ $member->role }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-group-full" id="patient_selection" style="display: none;">
                <label for="patient_id">Link to Patient Profile</label>
                <select name="patient_id" id="patient_id" class="form-control">
                    <option value="">-- Select Patient Profile --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->patient_id }}" {{ old('patient_id') == $patient->patient_id ? 'selected' : '' }}>
                            {{ $patient->last_name }}, {{ $patient->first_name }} {{ $patient->suffix !== 'None' ? $patient->suffix : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-person-plus"></i> Save User Account
            </button>
        </div>
    </form>
</div>

<script>
    function toggleIdFields() {
        const role = document.getElementById('role').value;
        const staffDiv = document.getElementById('staff_selection');
        const patientDiv = document.getElementById('patient_selection');
        
        staffDiv.style.display = (role === 'Staff' || role === 'Admin') ? 'block' : 'none';
        patientDiv.style.display = (role === 'Patient') ? 'block' : 'none';

        // Clear values of hidden fields to prevent accidental submissions
        if (staffDiv.style.display === 'none') document.getElementById('staff_id').value = "";
        if (patientDiv.style.display === 'none') document.getElementById('patient_id').value = "";
    }
    window.onload = toggleIdFields;
</script>
@endsection