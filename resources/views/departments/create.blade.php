@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Add New Department</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Register a new medical or administrative unit.</p>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
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
    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="department_name">Department Name</label>
                <input type="text" name="department_name" id="department_name" class="form-control" required maxlength="100" value="{{ old('department_name') }}" placeholder="e.g. Cardiology">
            </div>
        </div>

        <div class="form-row" style="margin-top: 15px;">
            <div class="form-group form-group-full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter a brief description of the department's responsibilities...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-building-add"></i> Save Department
            </button>
        </div>
    </form>
</div>
@endsection