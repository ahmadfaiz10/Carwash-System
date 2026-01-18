@extends('layouts.customer')
@php use Illuminate\Support\Str; @endphp
@section('title', 'Product Categories')

@section('content')

<style>
body {
    background:#f8fafc !important;
    font-family:Poppins, sans-serif;
}

/* ================= PAGE ================= */
.products-wrapper {
    padding: 24px 24px 60px;
}

/* ================= HEADER ================= */
.products-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:26px;
}

.products-title {
    font-size:28px;
    font-weight:800;
    color:#0f172a;
}

.products-subtitle {
    font-size:14px;
    color:#64748b;
    margin-top:4px;
}

/* ================= SEARCH ================= */
.search-box {
    position:relative;
    width:260px;
}

.search-box input {
    width:100%;
    padding:10px 14px 10px 42px;
    border-radius:999px;
    border:1px solid #cbd5e1;
    font-size:14px;
}

.search-box i {
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#64748b;
}

/* ================= GRID ================= */
.category-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(230px, 1fr));
    gap:22px;
}

/* ================= CARD ================= */
.category-card {
    background:#ffffff;
    border-radius:20px;
    padding:22px;
    box-shadow:0 12px 30px rgba(15,23,42,0.1);
    transition:.3s ease;
    text-decoration:none;
    color:#0f172a;
    position:relative;
    overflow:hidden;
}

.category-card:hover {
    transform:translateY(-6px);
    box-shadow:0 24px 50px rgba(15,23,42,0.18);
}

/* ================= ICON ================= */
.category-icon {
    width:54px;
    height:54px;
    border-radius:16px;
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    margin-bottom:16px;
}

/* ================= TEXT ================= */
.category-name {
    font-size:17px;
    font-weight:800;
    margin-bottom:4px;
}

.category-desc {
    font-size:13px;
    color:#64748b;
}

/* ================= BADGE ================= */
.category-badge {
    position:absolute;
    top:18px;
    right:18px;
    background:#ecfdf3;
    color:#166534;
    font-size:12px;
    font-weight:700;
    padding:4px 10px;
    border-radius:999px;
}

/* ================= EMPTY ================= */
.empty-box {
    background:#ffffff;
    border-radius:18px;
    padding:50px;
    text-align:center;
    color:#64748b;
    box-shadow:0 12px 30px rgba(15,23,42,0.1);
}
</style>

<div class="products-wrapper">

    {{-- HEADER --}}
    <div class="products-header">
        <div>
            <div class="products-title">Product Categories</div>
            <div class="products-subtitle">
                Browse categories and find the products you need
            </div>
        </div>

        {{-- SEARCH --}}
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchCategory" placeholder="Search category...">
        </div>
    </div>

    {{-- GRID --}}
    @if($categories->count())
    <div class="category-grid" id="categoryGrid">

        @foreach ($categories as $cat)
        <a href="{{ route('customer.products.byCategory', $cat->Category) }}"
           class="category-card"
           data-name="{{ strtolower($cat->Category) }}">

            <div class="category-badge">
                Available
            </div>

            <div class="category-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="category-name">
                {{ $cat->Category ?? 'Uncategorized' }}
            </div>

            <div class="category-desc">
                View all products in this category
            </div>

        </a>
        @endforeach

    </div>
    @else

    <div class="empty-box">
        <h4>No Categories Found</h4>
        <p>Products will appear here once they are available.</p>
    </div>

    @endif

</div>

{{-- SEARCH FILTER --}}
<script>
document.getElementById("searchCategory").addEventListener("input", function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll(".category-card").forEach(card => {
        card.style.display = card.dataset.name.includes(keyword) ? "block" : "none";
    });
});
</script>

@endsection
