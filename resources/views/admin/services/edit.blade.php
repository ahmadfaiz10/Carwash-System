@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')

<style>
/* SAME STYLE AS ADD PRODUCT + ADD SERVICE */

.page-title {
    font-size: 30px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

.page-sub {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 26px;
}

.service-card {
    background: white;
    padding: 35px;
    border-radius: 22px;
    max-width: 1050px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    animation: fadeIn .25s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: #1E88E5;
    font-size: 20px;
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
}

.grid-1 {
    margin-bottom: 28px;
}

@media(max-width:780px){
    .grid-2 {
        grid-template-columns: 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

input, select, textarea {
    padding: 12px 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #cfd9e3;
    font-size: 14px;
    transition: .25s;
}

input:focus, select:focus, textarea:focus {
    border-color:#1E88E5;
    box-shadow:0 0 0 3px rgba(30,136,229,0.25);
    outline:none;
}

textarea { height: 130px; resize: vertical; }

/* IMAGE UPLOAD SYSTEM */

.upload-area {
    border: 2px dashed #bcd7ff;
    background: #f8fafc;
    height: 220px;
    border-radius: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    cursor: pointer;
    transition: .3s;
}

.upload-area:hover {
    background: #eef6ff;
    border-color: #1E88E5;
}

.upload-area i {
    font-size: 48px;
    color: #8fa8c9;
}

.upload-area span {
    margin-top: 8px;
    font-size: 14px;
    font-weight: 600;
    color:#475569;
}

.preview-box {
    margin-top: 16px;
    width: 220px;
    height: 220px;
    display:block;
    overflow:hidden;
    border-radius: 16px;
    box-shadow:0 6px 18px rgba(0,0,0,.12);
}

.preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* BUTTONS */

.btn-row {
    display:flex;
    gap: 14px;
    margin-top: 25px;
}

.btn-primary {
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    padding: 12px 26px;
    border: none;
    color: white;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: .25s;
    box-shadow: 0 8px 20px rgba(30,136,229,.35);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 26px rgba(30,136,229,.45);
}

.btn-cancel {
    padding: 12px 26px;
    background:#e2e8f0;
    color:#475569;
    border-radius:12px;
    font-weight:600;
    text-decoration:none;
}

.btn-cancel:hover { background:#cbd5e1; }

</style>



<!-- ========= PAGE HEADER ========= -->

<div class="page-title">Edit Service</div>
<div class="page-sub">Update the service details below.</div>

<div class="service-card">

    <form action="{{ route('admin.services.update', $service->id) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ========================= BASIC INFO ========================= -->
        <div class="section-title">
            <i class="bi bi-tools"></i> Service Information
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Service Category</label>
                <select name="ServiceCategory" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ $service->ServiceCategory == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" required value="{{ $service->name }}">
            </div>
        </div>

        <div class="grid-1 form-group">
            <label>Description</label>
            <textarea name="description">{{ $service->description }}</textarea>
        </div>


        <!-- ========================= PRICE & DURATION ========================= -->
        <div class="section-title">
            <i class="bi bi-cash-stack"></i> Pricing & Duration
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Price (RM)</label>
                <input type="number" name="price" step="0.01" required
                       value="{{ $service->price }}">
            </div>

            <div class="form-group">
                <label>Duration (Minutes)</label>
                <input type="number" name="duration" min="5" step="5" required
                       value="{{ $service->duration }}">
            </div>
        </div>


        <!-- ========================= STATUS ========================= -->
        <div class="section-title">
            <i class="bi bi-activity"></i> Service Status
        </div>

        <div class="grid-1 form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Active"   {{ $service->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $service->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>



        <!-- ========================= IMAGE UPLOAD ========================= -->
        <div class="section-title">
            <i class="bi bi-camera"></i> Service Image
        </div>

        <label>Current Image</label>
        <div class="preview-box">
            <img src="{{ asset('storage/services/' . $service->image) }}" alt="Service Image">
        </div>

        <label style="margin-top:15px; font-weight:600;">Upload New Image (Optional)</label>

        <div class="upload-area" onclick="document.getElementById('serviceImg').click()">
            <i class="bi bi-cloud-upload"></i>
            <span>Click to upload image</span>
        </div>

        <input type="file" accept="image/*" id="serviceImg" name="image" 
               style="display:none;" onchange="previewImage(this)">

        <div class="preview-box" id="previewBox" style="display:none;"></div>



        <!-- ========================= BUTTON ROW ========================= -->
        <div class="btn-row">
            <a href="{{ route('admin.services.byCategory', $service->ServiceCategory) }}"
               class="btn-cancel">Cancel</a>

            <button class="btn-primary">Update Service</button>
        </div>

    </form>

</div>


<script>
function previewImage(input) {
    const box = document.getElementById('previewBox');

    if (!input.files.length) {
        box.style.display = "none";
        box.innerHTML = "";
        return;
    }

    const img = document.createElement('img');
    img.src = URL.createObjectURL(input.files[0]);

    box.style.display = "block";
    box.innerHTML = "";
    box.appendChild(img);
}
</script>

@endsection
