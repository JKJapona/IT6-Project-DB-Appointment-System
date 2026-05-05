@extends('layout')

@section('page_title')
    <a href="{{ route('patients.index') }}" style="text-decoration: none; color: inherit;">Patients</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Add</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Add New Patient</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Register a new patient into the hospital system.</p>
    </div>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
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
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        
        <h3 style="margin-bottom: 16px; color: var(--primary-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Personal Information</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="philhealth_id">PhilHealth ID</label>
                <input type="text" name="philhealth_id" id="philhealth_id" class="form-control" maxlength="20" value="{{ old('philhealth_id') }}" placeholder="e.g. 12-345678901-2">
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required value="{{ old('date_of_birth') }}">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required maxlength="50" value="{{ old('first_name') }}" placeholder="e.g. John">
            </div>
            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control" maxlength="50" value="{{ old('middle_name') }}" placeholder="e.g. Quinto">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required maxlength="50" value="{{ old('last_name') }}" placeholder="e.g. Doe">
            </div>
            <div class="form-group">
                <label for="suffix">Suffix</label>
                <select name="suffix" id="suffix" class="form-control">
                    @foreach(['None', 'Jr', 'Sr', 'I', 'II', 'III', 'IV'] as $suffix)
                        <option value="{{ $suffix }}" {{ old('suffix') == $suffix ? 'selected' : '' }}>{{ $suffix }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 style="margin: 24px 0 16px; color: var(--primary-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Parental Information</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="fathers_first_name">Father's First Name</label>
                <input type="text" name="fathers_first_name" id="fathers_first_name" class="form-control" maxlength="50" value="{{ old('fathers_first_name') }}" placeholder="Father's First Name">
            </div>
            <div class="form-group">
                <label for="fathers_middle_name">Father's Middle Name</label>
                <input type="text" name="fathers_middle_name" id="fathers_middle_name" class="form-control" maxlength="50" value="{{ old('fathers_middle_name') }}" placeholder="Father's Middle Name">
            </div>
            <div class="form-group">
                <label for="fathers_last_name">Father's Last Name</label>
                <input type="text" name="fathers_last_name" id="fathers_last_name" class="form-control" maxlength="50" value="{{ old('fathers_last_name') }}" placeholder="Father's Last Name">
            </div>
            <div class="form-group">
                <label for="fathers_suffix">Suffix</label>
                <select name="fathers_suffix" id="fathers_suffix" class="form-control">
                    @foreach(['None', 'Jr', 'Sr', 'I', 'II', 'III', 'IV'] as $suffix)
                        <option value="{{ $suffix }}" {{ old('fathers_suffix') == $suffix ? 'selected' : '' }}>{{ $suffix }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="mothers_first_name">Mother's First Name</label>
                <input type="text" name="mothers_first_name" id="mothers_first_name" class="form-control" maxlength="50" value="{{ old('mothers_first_name') }}" placeholder="Mother's First Name">
            </div>
            <div class="form-group">
                <label for="mothers_middle_name">Mother's Middle Name</label>
                <input type="text" name="mothers_middle_name" id="mothers_middle_name" class="form-control" maxlength="50" value="{{ old('mothers_middle_name') }}" placeholder="Mother's Middle Name">
            </div>
            <div class="form-group">
                <label for="mothers_last_name">Mother's Last Name</label>
                <input type="text" name="mothers_last_name" id="mothers_last_name" class="form-control" maxlength="50" value="{{ old('mothers_last_name') }}" placeholder="Mother's Last Name">
            </div>
            <div class="form-group">
                <label for="mothers_suffix">Suffix</label>
                <select name="mothers_suffix" id="mothers_suffix" class="form-control">
                    @foreach(['None', 'Jr', 'Sr', 'I', 'II', 'III', 'IV'] as $suffix)
                        <option value="{{ $suffix }}" {{ old('mothers_suffix') == $suffix ? 'selected' : '' }}>{{ $suffix }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-save"></i> Save Patient Record
            </button>
        </div>
    </form>
</div>
@endsection