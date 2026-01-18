@extends('layouts.customer')
@php use Illuminate\Support\Str; @endphp
@section('title', 'Products in ' . $category)

@section('content')

<style>
body{
    background:#f8fafc !important;
    font-family:Poppins, sans-serif;
}

/* ================= LAYOUT ================= */
.page-container{
    display:flex;
    gap:28px;
    padding:24px 24px 60px;
}

/* ================= SIDEBAR ================= */
.category-sidebar{
    width:260px;
    background:#ffffff;
    border-radius:20px;
    padding:22px;
    box-shadow:0 12px 30px rgba(15,23,42,.1);
    position:sticky;
    top:90px;
    height:fit-content;
}

.category-sidebar h3{
    font-size:14px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:16px;
    text-transform:uppercase;
    letter-spacing:1px;
}

.category-item{
    padding:10px 14px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    color:#334155;
    text-decoration:none;
    display:block;
    margin-bottom:8px;
    transition:.25s ease;
}

.category-item:hover{
    background:#e0f2fe;
    color:#0369a1;
}

.category-item.active{
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:#ffffff;
}

/* ================= PRODUCT SECTION ================= */
.product-section{
    flex:1;
}

/* INTRO */
.shop-intro{
    background:#ffffff;
    border-radius:22px;
    padding:26px;
    margin-bottom:22px;
    box-shadow:0 12px 30px rgba(15,23,42,.1);
}

.shop-intro h2{
    font-size:26px;
    font-weight:900;
    color:#0f172a;
}

.shop-intro p{
    font-size:14px;
    color:#64748b;
    margin-top:6px;
}

/* TRUST BAR */
.trust-bar{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:14px;
    margin-top:18px;
}

.trust-item{
    background:#f1f5f9;
    padding:14px;
    border-radius:14px;
    font-size:13px;
    font-weight:600;
    color:#0f172a;
    text-align:center;
}

/* GRID */
.product-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:26px;
}

/* ================= PRODUCT CARD ================= */
.product-card{
    background:#ffffff;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 14px 40px rgba(15,23,42,.12);
    transition:.3s ease;
    display:flex;
    flex-direction:column;
}

.product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 28px 60px rgba(15,23,42,.18);
}

/* ===== IMAGE FIX (IMPORTANT PART) ===== */
.product-image{
    width:100%;
    aspect-ratio:1 / 1;           /* PERFECT SQUARE */
    background:#f1f5f9;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.product-image img{
    width:100%;
    height:100%;
    object-fit:contain;           /* 👈 FULL IMAGE, NO CROP */
    padding:12px;
}

/* INFO */
.product-info{
    padding:18px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.product-info h4{
    font-size:17px;
    font-weight:800;
    margin-bottom:6px;
}

.product-info p{
    font-size:13px;
    color:#64748b;
    flex:1;
}

.price{
    font-size:18px;
    font-weight:900;
    margin:12px 0 6px;
}

/* STOCK */
.stock{
    font-size:13px;
    font-weight:700;
    margin-bottom:12px;
}
.stock.ok{color:#16a34a;}
.stock.low{color:#f59e0b;}
.stock.out{color:#dc2626;}

/* BUY AREA */
.quantity-control{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:12px;
}

.qty-btn{
    width:32px;
    height:32px;
    border-radius:8px;
    border:none;
    background:#e0f2fe;
    font-weight:900;
    cursor:pointer;
}

.qty-input{
    width:50px;
    text-align:center;
    border-radius:8px;
    border:1px solid #cbd5e1;
    font-weight:700;
}

.buy-btn{
    width:100%;
    padding:12px;
    border-radius:999px;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:#fff;
    font-weight:900;
    cursor:pointer;
}

.buy-btn:hover{transform:scale(1.03);}
.buy-btn.disabled{
    background:#9ca3af;
    cursor:not-allowed;
}
</style>

<div class="page-container">

    {{-- SIDEBAR --}}
    <aside class="category-sidebar">
        <h3>Categories</h3>
        @foreach ($categories as $cat)
            <a href="{{ route('customer.products.byCategory', $cat->Category) }}"
               class="category-item {{ $category === $cat->Category ? 'active' : '' }}">
                {{ $cat->Category }}
            </a>
        @endforeach
    </aside>

    {{-- PRODUCTS --}}
    <section class="product-section">

        {{-- INTRO --}}
        <div class="shop-intro">
            <h2>{{ $category }}</h2>
            <p>Browse our carefully selected products designed for quality and everyday use.</p>

            <div class="trust-bar">
                <div class="trust-item">✔ Secure Checkout</div>
                <div class="trust-item">✔ Genuine Products</div>
                <div class="trust-item">✔ Easy Payment</div>
                <div class="trust-item">✔ Customer Verified</div>
            </div>
        </div>

        {{-- GRID --}}
        <div class="product-grid">

            @forelse ($products as $product)
            <div class="product-card">

                <div class="product-image">
                    <img src="{{ asset('storage/' . $product->Image) }}"
                         alt="{{ $product->ProductName }}">
                </div>

                <div class="product-info">
                    <h4>{{ $product->ProductName }}</h4>
                    <p>{{ Str::limit($product->Description, 90) }}</p>

                    <div class="price">RM {{ number_format($product->Price,2) }}</div>

                    @if ($product->StockQuantity > 5)
                        <div class="stock ok">In Stock</div>
                    @elseif ($product->StockQuantity > 0)
                        <div class="stock low">Only {{ $product->StockQuantity }} left</div>
                    @else
                        <div class="stock out">Out of Stock</div>
                    @endif

                    @if ($product->StockQuantity > 0)
                    <form action="{{ route('customer.checkout.review') }}" method="GET">
                        <input type="hidden" name="product_id" value="{{ $product->ProductID }}">
                        <input type="hidden" name="quantity" class="qty-hidden" value="1">

                        <div class="quantity-control">
                            <button type="button" class="qty-btn" onclick="changeQty(this,-1)">−</button>
                            <input type="number" class="qty-input" value="1" min="1"
                                   max="{{ $product->StockQuantity }}"
                                   onchange="syncQty(this)">
                            <button type="button" class="qty-btn" onclick="changeQty(this,1)">+</button>
                        </div>

                        <button type="submit" class="buy-btn">Buy Now</button>
                    </form>
                    @else
                        <button class="buy-btn disabled" disabled>Unavailable</button>
                    @endif
                </div>

            </div>
            @empty
                <p>No products available.</p>
            @endforelse

        </div>
    </section>
</div>

<script>
function changeQty(btn, delta){
    const input = btn.parentElement.querySelector('.qty-input');
    const max = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if(val < 1) val = 1;
    if(val > max) val = max;
    input.value = val;
    syncQty(input);
}
function syncQty(input){
    input.closest('form').querySelector('.qty-hidden').value = input.value;
}
</script>

@endsection
