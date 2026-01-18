@extends('layouts.customer')
@section('title', 'Edit Profile')
@section('fullwidth', true)

@section('content')

<style>
/* PAGE TITLE */
.page-header {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
}

.page-subtext {
    color: #64748b;
    margin-bottom: 30px;
}

/* GRID LAYOUT – SAME AS ADMIN */
.edit-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 25px;
    margin-top: 20px;
}

/* LEFT SIDE PHOTO CARD */
.left-card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    text-align: center;
}

.avatar-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #dbeafe;
    margin-bottom: 12px;
}

.upload-btn {
    background: #e2e8f0;
    border: none;
    padding: 8px 15px;
    border-radius: 10px;
    font-size: 14px;
    cursor: pointer;
    font-weight: 600;
}
.upload-btn:hover {
    background: #cbd5e1;
}

/* RIGHT FORM CARD – SAME AS ADMIN */
.form-card {
    background: white;
    padding: 30px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #0f172a;
}

.input-group {
    margin-bottom: 18px;
}

.input-group label {
    font-weight: 600;
    font-size: 14px;
    color: #334155;
    margin-bottom: 6px;
}

.input-group input,
.input-group textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    transition: .25s;
    font-size: 14px;
}

.input-group input:focus,
.input-group textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.25);
    outline: none;
}

textarea {
    height: 100px;
    resize: vertical;
}

/* SAVE BUTTON */
.save-btn {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border: none;
    color: white;
    padding: 12px 22px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    width: 220px;
    margin-top: 15px;
    box-shadow: 0 6px 16px rgba(37,99,235,0.35);
}

.save-btn:hover {
    transform: translateY(-2px);
}

/* BACK LINK */
.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
}
.back-link:hover {
    text-decoration: underline;
}
</style>


<div>
    <h2 class="page-header">Edit Profile</h2>
    <p class="page-subtext">Update your personal and account information.</p>
</div>

<div class="edit-layout">

    {{-- LEFT AVATAR CARD --}}
    <div class="left-card">

        <img id="previewAvatar"
            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->FullName) }}&background=dbeafe&color=1e3a8a"
            class="avatar-preview">

        <input type="file" id="avatarInput" name="Image" form="editForm" accept="image/*"
               style="display:none;" onchange="previewImage(event)">

        <button type="button" class="upload-btn"
            onclick="document.getElementById('avatarInput').click()">
            Change Photo
        </button>

    </div>

    {{-- RIGHT FORM CARD --}}
    <div class="form-card">

        <form id="editForm" action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section-title">Basic Information</div>

            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="FullName" value="{{ Auth::user()->FullName }}" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="PhoneNumber" value="{{ Auth::user()->PhoneNumber }}" required>
            </div>

            <div class="section-title">Account Details</div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="Email" value="{{ Auth::user()->Email }}" required>
            </div>

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="UserName" value="{{ Auth::user()->UserName }}" required>
            </div>

            <div class="section-title">Address</div>

            <div class="input-group">
                <label>Address</label>
                <textarea name="Address">{{ Auth::user()->Address }}</textarea>
            </div>

            <button class="save-btn">Save Changes</button>

        </form>

        <a href="{{ route('customer.profile') }}" class="back-link">← Back to Profile</a>

    </div>

</div>

<script>
function previewImage(event){
    document.getElementById('previewAvatar').src =
        URL.createObjectURL(event.target.files[0]);
}
</script>

@endsection
