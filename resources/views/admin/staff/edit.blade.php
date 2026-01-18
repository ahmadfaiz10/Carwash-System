@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')

<style>
:root {
    --blue: #007bff;
    --aqua: #00e0ff;
    --dark: #0f172a;
}

/* HEADER BANNER */
.page-header {
    background: linear-gradient(135deg, var(--blue), var(--aqua));
    color:white;
    padding:30px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
    margin-bottom:35px;
}
.page-header h2 { font-weight:700; margin-bottom:5px; }



/* CARD */
.card {
    background:white;
    padding:35px;
    border-radius:22px;
    max-width:900px;
    margin:auto;
    border:1px solid #e2e8f0;
    box-shadow:0 12px 30px rgba(0,0,0,0.08);
}

/* SECTION TITLE */
.section-title {
    font-size:18px;
    font-weight:700;
    color:var(--dark);
    margin-bottom:15px;
    display:flex;
    align-items:center;
    gap:10px;
}
.section-title i {
    color:var(--blue);
    font-size:22px;
}

/* GRID LAYOUT */
.grid-2 {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
    margin-bottom:28px;
}
@media(max-width:768px){
    .grid-2 { grid-template-columns:1fr; }
}

/* INPUTS */
.input-group { display:flex; flex-direction:column; gap:6px; }
.input-group label {
    font-weight:600;
    font-size:14px;
    color:#334155;
}
.input-group input, .input-group select {
    padding:12px;
    border-radius:12px;
    border:1px solid #cdd8e6;
    background:#f8fafc;
}
.input-group input:focus, .input-group select:focus {
    border-color:var(--blue);
    box-shadow:0 0 0 3px rgba(0,123,255,0.25);
    outline:none;
}

/* AVATAR */
.avatar-section {
    text-align:center;
    margin-bottom:30px;
}
.avatar-preview {
    width:140px;
    height:140px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #d4e2ff;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
    margin-bottom:12px;
}

/* BUTTON */
.submit-btn {
    padding:14px 30px;
    background: linear-gradient(135deg, var(--blue), #0063d1);
    border:none;
    border-radius:12px;
    color:white;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    margin-top:20px;
    box-shadow:0 8px 20px rgba(0,123,255,0.35);
    transition:.25s;
}
.submit-btn:hover { transform:translateY(-3px); }

</style>

<!-- PAGE HEADER -->
<div class="page-header">
    <h2>👤 Edit Staff Account</h2>
    <p>Update profile details, login access, and staff role.</p>
</div>




<div class="card">

    <form action="{{ route('staff.update', $staff->UserID) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- AVATAR -->
        <div class="avatar-section">
            <img id="preview"
                 src="{{ $staff->Image ? asset('storage/staff/'.$staff->Image) : asset('default-avatar.png') }}"
                 class="avatar-preview">

            <br>
            <input type="file" name="Image" accept="image/*"
                   onchange="previewImg(event)"
                   style="margin-top:12px; font-weight:600;">
        </div>

        <script>
            function previewImg(event){
                document.getElementById('preview').src = URL.createObjectURL(event.target.files[0]);
            }
        </script>


        <!-- SECTION 1 -->
        <div class="section-title"><i class="bi bi-person-lines-fill"></i> Basic Information</div>

        <div class="grid-2">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="FullName" value="{{ $staff->FullName }}" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="PhoneNumber" value="{{ $staff->PhoneNumber }}" required>
            </div>
        </div>


        <div class="grid-2">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="Email" value="{{ $staff->Email }}" required>
            </div>

            <div class="input-group">
                <label>Address</label>
                <input type="text" name="Address" value="{{ $staff->Address }}">
            </div>
        </div>



        <!-- SECTION 2 -->
        <div class="section-title" style="margin-top:35px;">
            <i class="bi bi-shield-lock"></i> Login Credentials
        </div>

        <div class="grid-2">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="UserName" value="{{ $staff->UserName }}" required>
            </div>

            <div class="input-group">
                <label>New Password (Optional)</label>
                <input type="password" name="UserPassword">
            </div>
        </div>



        <!-- SECTION 3 -->
        <div class="section-title" style="margin-top:35px;">
            <i class="bi bi-patch-check"></i> Staff Role
        </div>

        <div class="input-group">
            <label>Role</label>
            <select name="UserRole" required>
                <option value="Staff" {{ $staff->UserRole=='Staff'?'selected':'' }}>Staff</option>
                <option value="Senior Staff" {{ $staff->UserRole=='Senior Staff'?'selected':'' }}>Senior Staff</option>
                <option value="Supervisor" {{ $staff->UserRole=='Supervisor'?'selected':'' }}>Supervisor</option>
                <option value="Manager" {{ $staff->UserRole=='Manager'?'selected':'' }}>Manager</option>
            </select>
        </div>


        <button class="submit-btn">💾 Save Changes</button>

    </form>
</div>

@endsection
