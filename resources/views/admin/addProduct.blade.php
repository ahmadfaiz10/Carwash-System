@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

<style>
/* ========================= GENERAL PAGE ============================= */

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

/* ====================== MAIN FORM CARD ============================ */

.product-card {
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

/* ========================= SECTION TITLES =========================== */

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

/* ========================= GRID LAYOUT ============================== */

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

/* ========================= INPUTS / FORMS =========================== */

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
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #cfd9e3;
    font-size: 14px;
    transition: .25s;
    box-sizing: border-box;
}

input:focus, textarea:focus, select:focus {
    border-color:#1E88E5;
    box-shadow:0 0 0 3px rgba(30,136,229,0.25);
    outline:none;
}

textarea {
    height: 140px;
    resize: vertical;
}

/* ========================= IMAGE UPLOAD ============================= */

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
    text-align: center;
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
    margin-top: 10px;
    font-size: 14px;
    font-weight: 600;
    color:#475569;
}

.preview-box {
    margin-top: 16px;
    width: 220px;
    height: 220px;
    display:none;
    overflow:hidden;
    border-radius: 16px;
    box-shadow:0 6px 18px rgba(0,0,0,.12);
}

.preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ========================= BUTTONS ============================= */

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

.btn-cancel:hover {
    background:#cbd5e1;
}

</style>


<!-- ============================ PAGE TOP ============================ -->

<div class="page-title">Add New Product</div>
<div class="page-sub">Register your carwash items into inventory.</div>

<div class="product-card">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ===================== SECTION 1 ===================== -->
        <div class="section-title">
            <i class="bi bi-info-circle"></i> Basic Information
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="ProductName" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="Category" required>
                    <option hidden>Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-1 form-group">
            <label>Description</label>
            <textarea name="Description"></textarea>
        </div>

        <!-- ===================== SECTION 2 ===================== -->
        <div class="section-title">
            <i class="bi bi-cash-coin"></i> Price & Stock
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Price (RM)</label>
                <input type="number" name="Price" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="StockQuantity" required>
            </div>
        </div>

        <!-- ===================== AVAILABILITY ===================== -->
<div class="section-title">
    <i class="bi bi-toggle-on"></i> Availability
</div>

<div class="grid-1 form-group">
    <label>Availability Status</label>
    <select name="AvailabilityStatus" required>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>
</div>


        <!-- ===================== SECTION 3 ===================== -->
        <div class="section-title">
            <i class="bi bi-camera"></i> Product Image
        </div>

        <label style="font-weight:600;">Upload Image</label>

        <div class="upload-area" onclick="document.getElementById('productImg').click()">
            <i class="bi bi-cloud-upload"></i>
            <span>Click to upload product image</span>
        </div>

        <input type="file" accept="image/*" id="productImg" name="Image" style="display:none;" onchange="previewImage(this)">
        <div class="preview-box" id="previewBox"></div>

        <!-- ===================== ACTION BUTTONS ===================== -->
        <div class="btn-row">
<a href="{{ route('admin.products.byCategory', $categories->first()) }}" class="btn-cancel">
    Cancel
</a>
            <button class="btn-primary">Save Product</button>
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
