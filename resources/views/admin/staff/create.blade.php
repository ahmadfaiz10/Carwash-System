@extends('layouts.admin')

@section('title', 'Add New Staff')

@section('content')

<style>
/* Fix layout overlap issues */
.staff-form-wrapper * {
    box-sizing: border-box !important;
}

.staff-header {
    background: linear-gradient(135deg, #007bff, #00e0ff);
    padding: 28px;
    border-radius: 18px;
    text-align: center;
    color: white;
    margin-bottom: 35px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.staff-header h2 { font-weight: 700; }

.staff-form-wrapper {
    background: #ffffff;
    border-radius: 18px;
    padding: 35px;
    max-width: 800px;
    margin: auto;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 22px rgba(0,0,0,0.12);
}

/* Image Upload Box */
.avatar-section {
    text-align: center;
    margin-bottom: 30px;
}

.avatar-preview {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #dbeafe;
    margin-bottom: 10px;
}

.upload-btn {
    background: #e5e7eb;
    display: inline-block;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
}
.upload-btn:hover {
    background: #cbd5e1;
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 26px;
}
@media(max-width: 768px) {
    .grid-2 { grid-template-columns: 1fr; }
}

.input-group {
    margin-bottom: 22px;
}
.input-group label {
    font-weight: 600;
    color: #003b73;
    margin-bottom: 6px;
}

.input-group input,
.input-group select {
    padding: 12px 14px;
    width: 100%;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    font-size: 15px;
}
.input-group input:focus,
.input-group select:focus {
    border-color: #007bff;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
    outline: none;
}

.section-title {
    font-weight: 700;
    font-size: 17px;
    color: #003b73;
    margin-bottom: 15px;
    margin-top: 25px;
}

.error-box {
    background: #ffe2e2;
    color: #b91c1c;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 18px;
    font-size: 14px;
    border: 1px solid #ffb5b5;
}

.submit-btn {
    margin-top: 10px;
    padding: 13px 20px;
    width: 100%;
    background: #007bff;
    border: none;
    color: white;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 5px 18px rgba(0,123,255,0.35);
}
.submit-btn:hover {
    background: #005fc9;
    transform: translateY(-2px);
}
</style>


<!-- HEADER -->
<div class="staff-header">
    <h2>👨‍🔧 Add New Staff</h2>
    <p>Register a staff member to help manage daily operations.</p>
</div>

<div class="staff-form-wrapper">

    @if ($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- IMAGE UPLOAD -->
        <div class="avatar-section">
            <img id="previewImage"
                 src="{{ asset('default-avatar.png') }}"
                 class="avatar-preview">

            <br>

            <label class="upload-btn">
                Upload Photo
                <input type="file" name="Image" accept="image/*"
                       onchange="previewStaffImage(event)"
                       style="display: none;">
            </label>
        </div>

        <script>
        function previewStaffImage(event) {
            document.getElementById('previewImage').src =
                URL.createObjectURL(event.target.files[0]);
        }
        </script>


        <!-- BASIC INFO -->
        <div class="section-title">👤 Basic Information</div>

        <div class="grid-2">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="FullName" value="{{ old('FullName') }}" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="PhoneNumber" value="{{ old('PhoneNumber') }}" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="Email" value="{{ old('Email') }}" required>
            </div>

            <div class="input-group">
                <label>Address (optional)</label>
                <input type="text" name="Address" value="{{ old('Address') }}">
            </div>
        </div>

        <!-- LOGIN DETAILS -->
        <div class="section-title">🔐 Login Details</div>

        <div class="grid-2">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="UserName" value="{{ old('UserName') }}" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="UserPassword" required>
            </div>
        </div>

        <!-- ROLE -->
        <div class="section-title">📝 Staff Role (Optional)</div>

        <div class="input-group">
            <label>Role</label>
            <select name="role">
                <option value="">-- Select Role --</option>
                <option value="Staff">Staff</option>
                <option value="Senior Staff">Senior Staff</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Manager">Manager</option>
            </select>
        </div>

        <button class="submit-btn">Create Staff</button>

    </form>

</div>

@endsection
