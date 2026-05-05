@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Hospital Departments</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Manage clinical units and administrative departments.</p>
    </div>
    <a href="{{ route('departments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Department
    </a>
</div>

@if (session('error'))
    <div style="background: #fef2f2; border: 1px solid #dc2626; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.875rem; display: flex; align-items: center; gap: 12px;">
        <i class="bi bi-shield-lock-fill" style="font-size: 1.25rem; color: #dc2626;"></i>
        <div>
            <div style="font-weight: 700;">System Security Restriction</div>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department Name</th>
                <th>Description</th>
                <th>Created At</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $dept)
            <tr>
                <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $dept->department_id }}</span></td>
                <td><strong style="color: var(--primary-blue);">{{ $dept->department_name }}</strong></td>
                <td title="{{ $dept->description }}">{{ Str::limit($dept->description, 60) ?? 'No description' }}</td>
                <td>
                    <small style="color: var(--font-muted);">
                        <i class="bi bi-calendar3"></i> {{ $dept->created_at->format('M d, Y') }}
                    </small>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('departments.show', $dept->department_id) }}" class="btn btn-secondary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('departments.edit', $dept->department_id) }}" class="btn btn-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('departments.destroy', $dept->department_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Deleting this will affect assigned schedules. Continue?')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-building-exclamation" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No departments found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection