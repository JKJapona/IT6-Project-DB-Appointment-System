@extends('layout')

@section('page_title')
    <a href="{{ route('services.index') }}" style="text-decoration: none; color: inherit;">Services</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Edit</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit Service</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Update service details or adjust pricing for the hospital catalog.</p>
    </div>
    <a href="{{ route('services.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
    </a>
</div>

{{-- Validation Errors --}}
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
    <form action="{{ route('services.update', $service->service_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            {{-- Service Name --}}
            <div class="form-group form-group-full">
                <label for="service_name">Service Name</label>
                <input type="text" name="service_name" id="service_name" class="form-control" required maxlength="100" value="{{ old('service_name', $service->service_name) }}">
            </div>
        </div>

        <div class="form-row">
            {{-- Department Selection --}}
            <div class="form-group">
                <label for="department_id">Clinical Department</label>
                <select name="department_id" id="department_id" class="form-control" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}" {{ old('department_id', $service->department_id) == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Price --}}
            <div class="form-group">
                <label for="price">Standard Price (₱)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required min="0" value="{{ old('price', $service->price) }}">
            </div>
        </div>

        {{-- Form Actions --}}
        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-check-lg"></i> Update Service Item
            </button>
        </div>
    </form>
</div>
@endsection