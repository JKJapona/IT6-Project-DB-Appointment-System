@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Hospital Services</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Manage medical services, clinical departments, and standard pricing.</p>
    </div>
    <a href="{{ route('services.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add New Service
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Department</th>
                <th>Standard Price</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $service->service_id }}</span></td>
                <td>
                    <strong style="color: var(--primary-blue);">
                        {{ $service->service_name }}
                    </strong>
                </td>
                <td>
                    <span class="badge badge-secondary" style="font-weight: 500;">
                        <i class="bi bi-building"></i> {{ $service->department->department_name }}
                    </span>
                </td>
                <td>
                    <strong style="font-size: 1rem;">₱{{ number_format($service->price, 2) }}</strong>
                </td>
                <td>
                    <span class="badge badge-success" style="font-size: 0.75rem;">
                        <i class="bi bi-check-circle"></i> Active
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('services.show', $service->service_id) }}" class="btn btn-secondary" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('services.edit', $service->service_id) }}" class="btn btn-secondary" title="Edit Service">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('services.destroy', $service->service_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this service? Existing invoices referencing this service may be affected.')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-mortal" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No services found in the catalog.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection