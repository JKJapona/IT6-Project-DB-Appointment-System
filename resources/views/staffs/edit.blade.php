@extends('layout')

@section('page_title')
    <a href="{{ route('staff.index') }}" style="text-decoration: none; color: inherit;">Staff</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Edit</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit Staff Profile</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Updating record for <span style="color: var(--primary-blue); font-weight: 700;">ID: #{{ $staffMember->staff_id }}</span></p>
    </div>
    <a href="{{ route('staff.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Directory
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
    <form action="{{ route('staff.update', $staffMember->staff_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <h3 style="margin-bottom: 16px; color: var(--primary-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Personal Information</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required maxlength="50" value="{{ old('first_name', $staffMember->first_name) }}">
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control" maxlength="50" value="{{ old('middle_name', $staffMember->middle_name) }}">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required maxlength="50" value="{{ old('last_name', $staffMember->last_name) }}">
            </div>
        </div>

        <h3 style="margin: 24px 0 16px; color: var(--primary-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Professional Assignment</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    @foreach(['Doctor', 'Nurse', 'Admissions Clerk', 'Admin'] as $role)
                        <option value="{{ $role }}" {{ old('role', $staffMember->role) == $role ? 'selected' : '' }}>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="department_id">Department</label>
                <select name="department_id" id="department_id" class="form-control">
                    <option value="">-- No Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}" {{ old('department_id', $staffMember->department_id) == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="specialization">Specialization (Optional)</label>
                <input type="text" name="specialization" id="specialization" class="form-control" maxlength="100" value="{{ old('specialization', $staffMember->specialization) }}" placeholder="e.g. Cardiology, Pediatrics">
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('staff.index') }}" class="btn btn-secondary" style="padding: 12px 24px;">Cancel Changes</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-check-circle"></i> Update Profile
            </button>
        </div>
    </form>
</div>
@endsection