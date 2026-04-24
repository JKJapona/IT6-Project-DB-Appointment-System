@extends('layout')

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

{{-- Error Block (Same as edit) --}}
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
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Neo Dominique">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. user@hospital.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Minimum 6 characters">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="role">Role / Access Level</label>
                <select name="role" id="role" class="form-control" required onchange="toggleIdFields()">
                    <option value="" disabled selected>Select a role...</option>
                    <option value="Patient" {{ old('role') == 'Patient' ? 'selected' : '' }}>Patient</option>
                    <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="form-group" id="staff_selection" style="display: none;">
                <label for="staff_id">Link to Staff Member</label>
                <select name="staff_id" id="staff_id" class="form-control">
                    <option value="">-- Select Staff --</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->staff_id }}" {{ old('staff_id') == $member->staff_id ? 'selected' : '' }}>
                            {{ $member->first_name }} {{ $member->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="patient_selection" style="display: none;">
                <label for="patient_id">Link to Patient Profile</label>
                <select name="patient_id" id="patient_id" class="form-control">
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->patient_id }}" {{ old('patient_id') == $patient->patient_id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
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
        document.getElementById('staff_selection').style.display = (role === 'Staff' || role === 'Admin') ? 'block' : 'none';
        document.getElementById('patient_selection').style.display = (role === 'Patient') ? 'block' : 'none';
    }
    window.onload = toggleIdFields;
</script>
@endsection