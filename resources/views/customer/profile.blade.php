@extends('layouts.customer')
@section('title', 'My Profile')
@section('fullwidth', true)

@section('content')

<style>
body {
    background:#f4f7fb;
    font-family:"Poppins", sans-serif;
}

/* PAGE HEADER */
.profile-header {
    margin-bottom: 25px;
}

.profile-header h1 {
    font-size: 30px;
    font-weight: 800;
    color:#0f172a;
}

.profile-header p {
    color:#64748b;
    margin-top:4px;
}

/* LAYOUT */
.profile-grid {
    display:grid;
    grid-template-columns:320px 1fr;
    gap:30px;
}

/* LEFT CARD */
.profile-card {
    background:white;
    border-radius:18px;
    padding:30px 25px;
    text-align:center;
    box-shadow:0 10px 28px rgba(15,23,42,0.12);
}

.profile-avatar {
    width:120px;
    height:120px;
    border-radius:50%;
    border:4px solid #e0f2fe;
    margin-bottom:12px;
}

.profile-name {
    font-size:20px;
    font-weight:700;
    color:#0f172a;
}

.profile-role {
    font-size:14px;
    color:#64748b;
    margin-top:-4px;
}

.profile-divider {
    margin:20px 0;
    border-top:1px solid #e5e7eb;
}

.profile-mini {
    font-size:14px;
    color:#334155;
    line-height:1.6;
}

/* BUTTON */
.profile-btn {
    margin-top:20px;
    width:100%;
    padding:12px;
    border-radius:12px;
    border:none;
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:white;
    font-weight:700;
    cursor:pointer;
    box-shadow:0 6px 16px rgba(37,99,235,0.35);
}

.profile-btn:hover {
    transform:translateY(-2px);
}

/* RIGHT PANEL */
.profile-details {
    background:white;
    border-radius:18px;
    padding:30px;
    box-shadow:0 10px 28px rgba(15,23,42,0.08);
}

.section-title {
    font-size:18px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:18px;
}

/* INFO GRID */
.info-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px 22px;
}

.info-item label {
    font-size:13px;
    font-weight:600;
    color:#64748b;
}

.info-value {
    margin-top:4px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    padding:12px 14px;
    border-radius:10px;
    color:#1e293b;
    font-weight:500;
}

/* FULL WIDTH */
.info-full {
    grid-column:1 / -1;
}
.profile-avatar {
    width:120px;
    height:120px;
    border-radius:50%;
    border:4px solid #e0f2fe;
    margin:0 auto 12px;
    display:block;
}

</style>

<div class="profile-header">
    <h1>My Profile</h1>
    <p>Manage your personal information and contact details</p>
</div>

<div class="profile-grid">

    {{-- LEFT PROFILE CARD --}}
    <div class="profile-card">
        <img class="profile-avatar"
             src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->FullName) }}&background=e0f2fe&color=1e3a8a">

        <div class="profile-name">{{ Auth::user()->FullName }}</div>
        <div class="profile-role">Customer Account</div>

        <div class="profile-divider"></div>

        <div class="profile-mini">
            <strong>Email</strong><br>
            {{ Auth::user()->Email }} <br><br>

            <strong>Phone</strong><br>
            {{ Auth::user()->PhoneNumber }}
        </div>

        <button class="profile-btn"
            onclick="window.location='{{ route('customer.profile.edit') }}'">
            ✏️ Edit Profile
        </button>
    </div>

    {{-- RIGHT DETAILS --}}
    <div class="profile-details">
        <div class="section-title">Account Information</div>

        <div class="info-grid">

            <div class="info-item">
                <label>Full Name</label>
                <div class="info-value">{{ Auth::user()->FullName }}</div>
            </div>

            <div class="info-item">
                <label>Username</label>
                <div class="info-value">{{ Auth::user()->UserName }}</div>
            </div>

            <div class="info-item">
                <label>Email Address</label>
                <div class="info-value">{{ Auth::user()->Email }}</div>
            </div>

            <div class="info-item">
                <label>Phone Number</label>
                <div class="info-value">{{ Auth::user()->PhoneNumber }}</div>
            </div>

            <div class="info-item info-full">
                <label>Address</label>
                <div class="info-value">
                    {{ Auth::user()->Address ?: 'No address provided yet' }}
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
