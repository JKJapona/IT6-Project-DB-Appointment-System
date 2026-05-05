@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">User Accounts</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Access control for hospital staff and patients.</p>
    </div>
    <a href="{{ route('user_accounts.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Create New User
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Linked Profile</th>
                <th>Created</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td><strong>{{ $user->last_name }}, {{ $user->first_name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role == 'Admin' ? 'badge-danger' : ($user->role == 'Staff' ? 'badge-primary' : 'badge-success') }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td>
                    @if($user->staff)
                        {{ $user->staff->first_name }} {{ $user->staff->last_name }} (Staff)
                    @elseif($user->patient)
                        {{ $user->patient->first_name }} {{ $user->patient->last_name }} (Patient)
                    @else
                        <span style="color: #ccc; font-style: italic;">No Link</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('user_accounts.show', ['user_account' => $user->user_id]) }}" class="btn btn-secondary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('user_accounts.edit', ['user_account' => $user->user_id]) }}" class="btn btn-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('user_accounts.destroy', ['user_account' => $user->user_id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" title="Delete" 
                                onclick="return confirm('Are you sure you want to delete this user account?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-shield-lock" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No user accounts found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection