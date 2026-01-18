@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')

<style>

    /* PAGE TITLE */
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .page-sub {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* MAIN CARD */
    .product-form-wrapper {
        background: white;
        padding: 32px;
        border-radius: 24px;
        max-width: 1100px;
        margin: auto;
        box-shadow: 0 10px 28px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        animation: fadeIn .25s ease;
        box-sizing: border-box;
    }

    @keyframes fadeIn {
        from { opacity:0; transform: translateY(10px); }
        to   { opacity:1; transform: translateY(0); }
    }

    /* SECTION TITLE */
    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #0f172a;
    }

    .form-section-title i { color: #1E88E5; }

    /* GRID FIX — PREVENTS MIXING */
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
        gap: 24px;
        margin-bottom: 26px;
        width: 100%;
    }

    .form-grid-1 {
        margin-bottom: 26px;
        width: 100%;
    }

    /* INPUT CONTROLS */
    label {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
        display: block;
        color:#334155;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid #cfd9e3;
        background: #f8fafc;
        font-size: 14px;
        transition: .25s;
        box-sizing: border-box;
    }

    input:focus, select:focus, textarea:focus {
        border-color:#1E88E5;
        box-shadow:0 0 0 3px rgba(30,136,229,0.25);
        outline:none;
    }

    textarea {
        height: 140px;
        resize: vertical;
    }

    /* IMAGE PREVIEW */
    .img-preview {
        width: 220px;
        height: 220px;
        border-radius: 18px;
        overflow:hidden;
        margin-top: 16px;
        background:#f1f5f9;
        display:flex;
        justify-content:center;
        align-items:center;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .img-preview img {
        width:100%; height:100%;
        object-fit:cover;
    }

    /* IMAGE UPLOAD */
    .upload-box {
        border: 2px dashed #bcd7ff;
        background: #f8fafc;
        height: 200px;
        border-radius: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .3s;
        text-align:center;
    }

    .upload-box:hover {
        background:#eef6ff;
        border-color:#1E88E5;
    }

    .upload-box i {
        font-size: 50px;
        color:#8fa8c9;
    }

    .upload-box span {
        font-size: 14px;
        font-weight:600;
        margin-top: 10px;
        color:#475569;
    }

    /* BUTTONS */
    .btn-row {
        margin-top: 30px;
        display:flex;
        gap: 14px;
        flex-wrap:wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        padding: 13px 28px;
        border: none;
        color: white;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 8px 18px rgba(30,136,229,.35);
        transition: .25s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(30,136,229,.45);
    }

    .btn-cancel {
        background:#e2e8f0;
        color:#475569;
        padding:13px 28px;
        border-radius:12px;
        font-weight:600;
        text-decoration:none;
    }

    .btn-cancel:hover {
        background:#cbd5e1;
    }

</style>


<div class="page-title">Edit Product</div>
<div class="page-sub">Modify product details and keep your inventory updated.</div>

<div class="product-form-wrapper">

    <form action="{{ route('admin.products.update', $product->ProductID) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ========== SECTION 1 — BASIC INFO ========== -->
        <div class="form-section-title">
            <i class="bi bi-info-circle"></i> Basic Information
        </div>

        <div class="form-grid-2">
            <div>
                <label>Product Name</label>
                <input type="text" name="ProductName" value="{{ $product->ProductName }}" required>
            </div>

            <div>
                <label>Category</label>
                <select name="Category">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected($product->Category == $cat)>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-grid-1">
            <label>Description</label>
            <textarea name="Description">{{ $product->Description }}</textarea>
        </div>

        <!-- ========== SECTION 2 — PRICE & STOCK ========== -->
        <div class="form-section-title">
            <i class="bi bi-cash-stack"></i> Price & Stock
        </div>

        <div class="form-grid-2">
            <div>
                <label>Price (RM)</label>
                <input type="number" step="0.01" name="Price" value="{{ $product->Price }}" required>
            </div>

            <div>
                <label>Stock Quantity</label>
                <input type="number" name="StockQuantity" value="{{ $product->StockQuantity }}" required>
            </div>
        </div>


        <!-- ========== SECTION 3 — IMAGE ========== -->
        <div class="form-section-title">
            <i class="bi bi-camera"></i> Product Image
        </div>

        <label>Current Image</label>
        <div class="img-preview" id="previewBox">
            @if($product->Image)
                <img src="{{ asset('storage/' . $product->Image) }}">
            @else
                <i class="bi bi-image" style="font-size:40px; color:#94a3b8;"></i>
            @endif
        </div>

        <label style="margin-top:16px;">Change Image</label>
        <div class="upload-box" onclick="document.getElementById('prodImg').click()">
            <i class="bi bi-cloud-upload"></i>
            <span>Click to upload new image</span>
        </div>

        <input type="file" id="prodImg" name="Image" accept="image/*" style="display:none;" onchange="previewEditImg(this)">


        <!-- ========== BUTTONS ========== -->
        <div class="btn-row">
            <a href="{{ route('admin.products.byCategory', $product->Category) }}" class="btn-cancel">Cancel</a>
            <button class="btn-primary">Update Product</button>
        </div>

    </form>

</div>


<script>
function previewEditImg(input){
    const box = document.getElementById('previewBox');

    if (!input.files.length){
        return;
    }

    const img = document.createElement('img');
    img.src = URL.createObjectURL(input.files[0]);

    box.innerHTML = "";
    box.appendChild(img);
}
</script>

@endsection
