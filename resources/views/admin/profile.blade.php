@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')

<style>

/* PAGE GRID */
.profile-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 25px;
    margin-top: 25px;
}

/* LEFT PROFILE CARD */
.left-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    text-align: center;
}

.avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #dbeafe;
    margin-bottom: 10px;
}

.left-card h3 {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
}

.left-card p {
    color: #64748b;
    margin-top: -4px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
    color: #0f172a;
}

/* RIGHT PANEL */
.right-panel {
    background: white;
    border-radius: 16px;
    padding: 30px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

/* INFO ITEM */
.info-group {
    margin-bottom: 18px;
}

.info-group label {
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 4px;
}

.info-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 12px 14px;
    border-radius: 10px;
    color: #1e293b;
}

/* ACTION BUTTON */
.edit-btn {
    width: 220px;
    background: linear-gradient(135deg, #007bff, #005fcc);
    color: white;
    padding: 12px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    box-shadow: 0 6px 16px rgba(0,123,255,0.35);
    margin-top: 10px;
}

.edit-btn:hover {
    transform: translateY(-2px);
}

/* MAIN TITLE */
.page-header {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
}

.page-subtext {
    color: #64748b;
    margin-bottom: 25px;
}
</style>

<div>
   
    <p class="page-subtext">Manage your account details and personal information.</p>
</div>

<div class="profile-layout">

    <!-- LEFT SIDE -->
    <div class="left-card">

        <img src="{{ Auth::user()->Image ? asset('storage/staff/'.Auth::user()->Image) : asset('default-avatar.png') }}"
             class="avatar">

        <h3>{{ Auth::user()->FullName }}</h3>
        <p>{{ Auth::user()->UserRole }}</p>

        <hr style="margin: 20px 0;">

        <div>
            <p><strong>Email:</strong><br>{{ Auth::user()->Email }}</p>
            <p><strong>Phone:</strong><br>{{ Auth::user()->PhoneNumber }}</p>
        </div>

        <button class="edit-btn"
            onclick="window.location='{{ route('admin.profile.edit') }}'">
            Edit Profile
        </button>
    </div>

    <!-- RIGHT SIDE / DETAILS -->
    <div class="right-panel">

        <div class="section-title">Account Details</div>

        <div class="info-group">
            <label>Full Name</label>
            <div class="info-box">{{ Auth::user()->FullName }}</div>
        </div>

        <div class="info-group">
            <label>Phone Number</label>
            <div class="info-box">{{ Auth::user()->PhoneNumber }}</div>
        </div>

        <div class="info-group">
            <label>Email</label>
            <div class="info-box">{{ Auth::user()->Email }}</div>
        </div>

        <div class="info-group">
            <label>Username</label>
            <div class="info-box">{{ Auth::user()->UserName }}</div>
        </div>

        <div class="info-group">
            <label>Address</label>
            <div class="info-box">{{ Auth::user()->Address ?: 'Not provided' }}</div>
        </div>

    </div>

</div>

@endsection
