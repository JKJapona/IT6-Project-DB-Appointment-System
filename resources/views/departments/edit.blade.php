@extends('layout')

@section('page_title')
    <a href="{{ route('departments.index') }}" style="text-decoration: none; color: inherit;">Departments</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Edit</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit Department</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Update information for <span style="color: var(--primary-blue); font-weight: 700;">ID: #{{ $department->department_id }}</span></p>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
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
    <form action="{{ route('departments.update', $department->department_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="department_name">Department Name</label>
                <input type="text" name="department_name" id="department_name" class="form-control" required maxlength="100" value="{{ old('department_name', $department->department_name) }}">
            </div>
        </div>

        <div class="form-row" style="margin-top: 15px;">
            <div class="form-group form-group-full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $department->description) }}</textarea>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('departments.index') }}" class="btn btn-secondary" style="padding: 12px 24px;">Cancel Changes</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-check-circle"></i> Update Department
            </button>
        </div>
    </form>
</div>
@endsection