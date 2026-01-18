@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')

<style>
.staff-card {
    background:white;
    padding:20px;
    border-radius:18px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

.staff-table {
    width:100%;
    border-collapse: collapse;
}

.staff-table th {
    padding:12px;
    text-align:left;
    background:#f0f4ff;
    font-weight:700;
    color:#003b73;
    font-size:14px;
}

.staff-table td {
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}

.avatar {
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #d4e2ff;
}

.badge {
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
}

.badge-role { background:#dbeafe; color:#1e40af; }
.badge-status-active { background:#dcfce7; color:#166534; }
.badge-status-inactive { background:#fee2e2; color:#991b1b; }

.action-btn {
    padding:6px 12px;
    font-size:12px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
}

.btn-edit { background:#3b82f6; color:white; }
.btn-edit:hover { background:#2563eb; }

.btn-toggle-active { background:#10b981; color:white; }
.btn-toggle-inactive { background:#ef4444; color:white; }
</style>

<div class="welcome">
    <h2>Staff Management</h2>
    <p>View, edit, activate or deactivate staff accounts.</p>
</div>

<div style="text-align:right; margin-bottom:15px;">
    <a href="{{ route('staff.create') }}" class="btn btn-primary" 
        style="padding:8px 14px; border-radius:10px; font-weight:600;">
        + Add New Staff
    </a>
</div>

<div class="staff-card">
    <table class="staff-table">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Status</th>
                <th style="width:180px;">Actions</th>
            </tr>
        </thead>
        <tbody>

            @forelse($staff as $s)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="{{ $s->Image ? asset('storage/staff/'.$s->Image) : asset('default-avatar.png') }}"
                             class="avatar">

                        <div>
                            <div style="font-weight:700;">{{ $s->FullName }}</div>
                        </div>
                    </div>
                </td>

                <td>
                    <div>{{ $s->Email }}</div>
                    <div style="font-size:12px; color:#64748b;">{{ $s->PhoneNumber }}</div>
                </td>

                <td>
                    <span class="badge badge-role">{{ $s->UserRole }}</span>
                </td>

                <td>
                    @if($s->Status == 'Active')
                        <span class="badge badge-status-active">Active</span>
                    @else
                        <span class="badge badge-status-inactive">Inactive</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('staff.edit', $s->UserID) }}" class="action-btn btn-edit">Edit</a>

                    @if($s->Status == 'Active')
                        <a href="{{ route('staff.deactivate', $s->UserID) }}" 
                           class="action-btn btn-toggle-inactive"
                           onclick="return confirm('Deactivate this staff?')">
                           Deactivate
                        </a>
                    @else
                        <a href="{{ route('staff.activate', $s->UserID) }}" 
                           class="action-btn btn-toggle-active"
                           onclick="return confirm('Activate this staff?')">
                           Activate
                        </a>
                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:20px; color:#6b7280;">
                    No staff accounts found.
                </td>
            </tr>
            @endforelse

        </tbody>
    </table>
</div>

@endsection
