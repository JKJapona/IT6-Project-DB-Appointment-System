@extends('layout')

@section('page_title')
    <a href="{{ route('staff.index') }}" style="text-decoration: none; color: inherit;">Staff</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Add</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Add New Medical Staff</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Onboard new clinical or administrative staff members.</p>
    </div>
    <a href="{{ route('staff.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
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
    <form action="{{ route('staff.store') }}" method="POST">
        @csrf

        <h3 style="margin-bottom: 16px; color: var(--primary-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Personal Information</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required maxlength="50" placeholder="e.g. Jane">
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control" value="{{ old('middle_name') }}" maxlength="50" placeholder="Optional">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required maxlength="50" placeholder="e.g. Smith">
            </div>
        </div>

        <h3 style="margin: 24px 0 16px; color: var(--primary-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Professional Assignment</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="" disabled selected>-- Select Role --</option>
                    @foreach(['Doctor', 'Nurse', 'Admissions Clerk', 'Admin'] as $role)
                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="department_id">Department</label>
                <select name="department_id" id="department_id" class="form-control">
                    <option value="">-- No Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="specialization">Specialization (Optional)</label>
                <input type="text" name="specialization" id="specialization" class="form-control" maxlength="100" placeholder="e.g. Pediatrician, Surgery" value="{{ old('specialization') }}">
                <small style="color: var(--font-muted); margin-top: 4px; display: block;">Only applicable for Doctors or specialized Nurses.</small>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-save"></i> Save Staff Record
            </button>
        </div>
    </form>
</div>
@endsection