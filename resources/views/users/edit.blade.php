@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit User Account</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">
            Modify existing credentials and access levels for <strong>{{ $user->name }}</strong>.
        </p>
    </div>
    <a href="{{ route('user_accounts.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
    </a>
</div>

{{-- Error Validation Block --}}
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
    <form action="{{ route('user_accounts.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 6 characters">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="role">Role / Access Level</label>
                <select name="role" id="role" class="form-control" required onchange="toggleIdFields()">
                    <option value="Patient" {{ old('role', $user->role) == 'Patient' ? 'selected' : '' }}>Patient</option>
                    <option value="Staff" {{ old('role', $user->role) == 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            {{-- Staff Selection --}}
            <div class="form-group" id="staff_selection" style="display: none;">
                <label for="staff_id">Link to Staff Member</label>
                <select name="staff_id" id="staff_id" class="form-control">
                    <option value="">-- Select Staff --</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->staff_id }}" {{ old('staff_id', $user->staff_id) == $member->staff_id ? 'selected' : '' }}>
                            {{ $member->first_name }} {{ $member->last_name }} ({{ $member->role }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Patient Selection --}}
            <div class="form-group" id="patient_selection" style="display: none;">
                <label for="patient_id">Link to Patient Profile</label>
                <select name="patient_id" id="patient_id" class="form-control">
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->patient_id }}" {{ old('patient_id', $user->patient_id) == $patient->patient_id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-save"></i> Update Account
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
    }
    window.onload = toggleIdFields;
</script>
@endsection