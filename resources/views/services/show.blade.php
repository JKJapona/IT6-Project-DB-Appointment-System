@extends('layout')

@section('page_title')
    <a href="{{ route('services.index') }}" style="text-decoration: none; color: inherit;">Services</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Details</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Service Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Detailed configuration and pricing for this hospital service.</p>
    </div>
    <a href="{{ route('services.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Service ID</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $service->service_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">Catalog Status</span>
            <span class="badge badge-success">
                <i class="bi bi-check-circle-fill" style="font-size: 0.5rem; vertical-align: middle; margin-right: 5px;"></i>
                Active
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Service Information</div>
                <div class="info-content">
                    <strong>Service Name:</strong> {{ $service->service_name }}<br>
                    <strong>Department:</strong> {{ $service->department->department_name }}<br>
                    <strong>Standard Price:</strong> 
                    <span style="font-size: 1.125rem; font-weight: 700; color: var(--primary-blue);">
                        ₱{{ number_format($service->price, 2) }}
                    </span>
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Department Description</div>
                <div class="info-content" style="line-height: 1.6; color: var(--font-main);">
                    @if($service->department->description)
                        {{ $service->department->description }}
                    @else
                        <p style="margin: 0; color: var(--font-muted);">No additional department description provided.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">System Info</div>
                <div class="meta-box">
                    <div style="margin-bottom: 6px;">
                        <i class="bi bi-calendar-plus"></i> <strong>Created:</strong> {{ $service->created_at ? $service->created_at->format('M d, Y - h:i A') : 'N/A' }}
                    </div>
                    @if($service->updated_at)
                    <div>
                        <i class="bi bi-clock-history"></i> <strong>Last Update:</strong> {{ $service->updated_at->diffForHumans() }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Usage Statistics</div>
                <div class="info-content">
                    {{-- This assumes you have an invoiceItems relationship defined --}}
                    <span style="font-size: 0.875rem; color: var(--font-muted);">
                        Total times billed: <strong>{{ $service->invoiceItems->count() ?? 0 }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center; margin-top: 20px;">
    <a href="{{ route('services.edit', $service->service_id) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Edit Service
    </a>
    
    <form action="{{ route('services.destroy', $service->service_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this service? This may affect historical billing records if not handled correctly.')">
            <i class="bi bi-trash"></i> Delete Service
        </button>
    </form>
</div>
@endsection