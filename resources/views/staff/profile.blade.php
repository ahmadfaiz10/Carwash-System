@extends('layouts.staff')

@section('title', 'My Profile - AutoShineX')
@section('page_title', 'My Profile')
@section('page_subtitle', 'View and manage your staff account')

@section('content')

@php
    $photoUrl = $user->Image ? asset('storage/staff/'.$user->Image) : null;
@endphp

<style>
    .profile-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 22px;
        align-items: flex-start;
    }

    .profile-main-card {
        flex: 1 1 280px;
        border-radius: 18px;
        padding: 18px 18px 20px;
        background: radial-gradient(circle at top left, #e0f2fe, #eff6ff, #e5e7eb);
        border: 1px solid #bfdbfe;
        box-shadow: 0 12px 30px rgba(15,23,42,0.12);
    }

    .profile-header {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 14px;
    }

    .profile-photo-wrapper {
        position: relative;
        width: 72px;
        height: 72px;
        border-radius: 999px;
        overflow: hidden;
        border: 3px solid rgba(37,99,235,0.75);
        background: radial-gradient(circle, #38bdf8, #0ea5e9, #1d4ed8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e0f2fe;
    }

    .profile-photo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-initial {
        font-size: 30px;
        font-weight: 800;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .profile-role {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .profile-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        padding: 3px 8px;
        border-radius: 999px;
        background: rgba(22,163,74,0.1);
        color: #166534;
        font-size: 11px;
        font-weight: 600;
    }

    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 14px;
        margin-top: 10px;
        font-size: 13px;
    }

    @media (max-width: 650px) {
        .profile-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .profile-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ca3af;
        margin-bottom: 1px;
    }

    .profile-value {
        font-weight: 600;
        color: #111827;
    }

    .profile-sub-card {
        flex: 1 1 230px;
        border-radius: 18px;
        padding: 14px 16px 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 24px rgba(15,23,42,0.06);
    }

    .profile-sub-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .profile-sub-title-icon {
        width: 22px;
        height: 22px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        background: linear-gradient(135deg,#6366f1,#a855f7);
        color: #f9fafb;
    }

    .profile-meta-list {
        margin: 0;
        padding-left: 16px;
        font-size: 12px;
        color: #4b5563;
    }

    .profile-meta-list li {
        margin-bottom: 4px;
    }

    .btn-edit-profile {
        display:inline-flex;
        align-items:center;
        gap:6px;
        margin-top:14px;
        padding:7px 14px;
        font-size:13px;
        border-radius:999px;
        text-decoration:none;
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#e0f2fe;
        font-weight:600;
        box-shadow: 0 6px 14px rgba(37,99,235,0.45);
    }

    .btn-edit-profile i {
        font-size: 14px;
    }

    .profile-status-pill {
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .status-active {
        background: #bbf7d0;
        color:#166534;
    }
    .status-inactive {
        background:#fee2e2;
        color:#b91c1c;
    }
</style>

<div class="profile-wrapper">

    {{-- MAIN PROFILE CARD --}}
    <div class="profile-main-card">

        <div class="profile-header">
            <div class="profile-photo-wrapper">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Profile photo">
                @else
                    <div class="profile-photo-initial">
                        {{ strtoupper(substr($user->FullName ?? 'S', 0, 1)) }}
                    </div>
                @endif
            </div>

            <div>
                <div class="profile-name">
                    {{ $user->FullName ?? 'Staff User' }}
                </div>
                <div class="profile-role">
                    Staff • AutoShineX Carwash Center
                </div>

                <div class="profile-tag">
                    <span style="font-size:10px;">●</span>
                    <span>
                        {{ $user->Status === 'Active' ? 'Active Account' : 'Inactive Account' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="profile-info-grid">
            <div>
                <div class="profile-label">Email</div>
                <div class="profile-value">{{ $user->Email ?? '-' }}</div>
            </div>

            <div>
                <div class="profile-label">Phone</div>
                <div class="profile-value">{{ $user->PhoneNumber ?? '-' }}</div>
            </div>

            <div>
                <div class="profile-label">Username</div>
                <div class="profile-value">{{ $user->UserName ?? '-' }}</div>
            </div>

            <div>
                <div class="profile-label">Role</div>
                <div class="profile-value">{{ $user->UserRole ?? 'Staff' }}</div>
            </div>

            <div style="grid-column: 1 / -1;">
                <div class="profile-label">Address</div>
                <div class="profile-value">{{ $user->Address ?: 'Not provided' }}</div>
            </div>
        </div>

        <a href="{{ route('staff.profile.edit') }}" class="btn-edit-profile">
            <i class="bi bi-pencil-square"></i>
            Edit Profile
        </a>
    </div>

    {{-- SIDE CARD: ACCOUNT DETAILS / META --}}
    <div class="profile-sub-card">
        <div class="profile-sub-title">
            <span class="profile-sub-title-icon">
                <i class="bi bi-shield-lock"></i>
            </span>
            Account Details
        </div>

        <ul class="profile-meta-list">
            <li>
                Status:
                <span class="profile-status-pill {{ $user->Status === 'Active' ? 'status-active' : 'status-inactive' }}">
                    {{ $user->Status ?? 'Unknown' }}
                </span>
            </li>
            <li>
                User ID: <strong>{{ $user->UserID ?? '-' }}</strong>
            </li>
            <li>
                Member since:
                <strong>
                    @if($user->created_at)
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                    @else
                        Not available
                    @endif
                </strong>
            </li>
            <li>
                Last update:
                <strong>
                    @if($user->updated_at)
                        {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y • h:i A') }}
                    @else
                        Not available
                    @endif
                </strong>
            </li>
        </ul>

        <p style="margin-top:10px; font-size:12px; color:#6b7280;">
            Your profile information is used across the staff system for bookings,
            payment notes, and internal records. Make sure your contact details are up to date.
        </p>
    </div>

</div>

@endsection
