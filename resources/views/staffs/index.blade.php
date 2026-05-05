@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Medical Staff Directory</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Registry of doctors, nurses, and administrative personnel.</p>
    </div>
    <a href="{{ route('staff.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Add Staff
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
            <th>Full Name</th>
            <th>Role / Specialization</th>
            <th>Department</th>
            <th style="text-align: right;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($staff as $s)
        <tr>
            <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $s->staff_id }}</span></td>
            <td>
                <strong style="color: var(--primary-blue);">
                    {{ $s->first_name }} {{ $s->middle_name }} {{ $s->last_name }}
                </strong>
            </td>
            <td>
                <span class="badge" style="background: var(--sidebar-accent); color: var(--primary-blue); border: 1px solid var(--border-color); margin-bottom: 4px; display: inline-block;">
                    {{ $s->role }}
                </span>
                @if($s->specialization)
                    <div style="font-size: 0.75rem; color: var(--font-muted);">{{ $s->specialization }}</div>
                @endif
            </td>
            <td>
                @if($s->department)
                    <span title="{{ $s->department->description }}">{{ $s->department->department_name }}</span>
                @else
                    <span style="color: var(--font-muted);">N/A</span>
                @endif
            </td>
            <td style="text-align: right;">
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <a href="{{ route('staff.show', $s->staff_id) }}" class="btn btn-secondary" title="View Profile">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('staff.edit', $s->staff_id) }}" class="btn btn-secondary" title="Edit Record">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('staff.destroy', $s->staff_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this record? This may affect assigned appointments.')" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center; padding: 40px; color: var(--font-muted);">
                <i class="bi bi-person-badge" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                No staff members found in the system.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection