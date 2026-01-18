@extends('layouts.customer')

@section('title', 'Checkout Review')

@section('content')

<style>
body{
    background:#f8fafc !important;
    font-family:Poppins, sans-serif;
}

/* ================= PAGE ================= */
.checkout-container{
    width:100%;
    padding:30px 30px 60px;
}

/* ================= TITLE ================= */
.checkout-title{
    font-size:28px;
    font-weight:900;
    margin-bottom:6px;
    color:#0f172a;
}
.checkout-sub{
    font-size:14px;
    color:#64748b;
    margin-bottom:28px;
}

/* ================= GRID ================= */
.checkout-grid{
    display:grid;
    grid-template-columns:1fr 1.4fr;
    gap:28px;
}

/* FULL WIDTH RESPONSIVE */
@media(max-width:1000px){
    .checkout-grid{
        grid-template-columns:1fr;
    }
}

/* ================= CARD ================= */
.card{
    background:#ffffff;
    border-radius:18px;
    padding:24px;
    box-shadow:0 14px 32px rgba(15,23,42,.08);
}

/* ================= CUSTOMER ================= */
.info-row{
    font-size:14px;
    margin-bottom:10px;
}
.info-row strong{
    color:#0f172a;
}

.edit-link{
    display:inline-block;
    margin-top:10px;
    font-size:13px;
    font-weight:700;
    color:#2563eb;
    text-decoration:none;
}

/* ================= ORDER TABLE ================= */
.order-table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:18px;
}
.order-table th{
    background:#f1f5f9;
    padding:12px;
    font-size:13px;
    text-align:left;
}
.order-table td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
    font-size:14px;
}

/* ================= SECTION ================= */
.section-title{
    font-size:16px;
    font-weight:800;
    margin-bottom:12px;
}

/* ================= OPTIONS ================= */
.option{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    margin-bottom:10px;
    cursor:pointer;
}
.option input{
    accent-color:#2563eb;
}

/* ================= TOTAL ================= */
.total-box{
    background:#f8fafc;
    border-radius:14px;
    padding:16px;
    margin-top:18px;
}
.total-row{
    display:flex;
    justify-content:space-between;
    font-size:14px;
    margin-bottom:6px;
}
.total-final{
    font-size:22px;
    font-weight:900;
    margin-top:10px;
}

/* ================= BUTTON ================= */
.pay-btn{
    width:100%;
    padding:15px;
    margin-top:18px;
    border-radius:999px;
    border:none;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#ffffff;
    font-weight:900;
    font-size:16px;
    cursor:pointer;
}

.pay-btn:hover{
    transform:scale(1.02);
}

/* ================= TRUST ================= */
.trust{
    font-size:12px;
    color:#64748b;
    text-align:center;
    margin-top:12px;
}
</style>

<div class="checkout-container">

    <div class="checkout-title">Checkout</div>
    <div class="checkout-sub">
        Review your order details and complete payment securely.
    </div>

    <div class="checkout-grid">

        {{-- ================= LEFT : CUSTOMER INFO ================= --}}
        <div class="card">
            <div class="section-title">Customer Information</div>

            <div class="info-row"><strong>Name:</strong> {{ $user->FullName }}</div>
            <div class="info-row"><strong>Email:</strong> {{ $user->Email }}</div>
            <div class="info-row"><strong>Phone:</strong> {{ $user->PhoneNumber }}</div>
            <div class="info-row"><strong>Address:</strong> {{ $user->Address ?? 'Please update profile' }}</div>

            <a href="{{ route('customer.profile.edit') }}" class="edit-link">
                ✏ Update Profile
            </a>
        </div>

        {{-- ================= RIGHT : ORDER ================= --}}
        <div class="card">

            <div class="section-title">Order Summary</div>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th width="80">Qty</th>
                        <th width="120">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>RM {{ number_format($item->subtotal,2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <form action="{{ route('customer.checkout.confirm') }}" method="POST">
                @csrf

                <input type="hidden" name="product_id" value="{{ $cartItems[0]->product_id }}">
                <input type="hidden" name="quantity" value="{{ $cartItems[0]->quantity }}">

                {{-- DELIVERY --}}
                <div class="section-title">Delivery Method</div>

                <label class="option">
                    <input type="radio" name="delivery_type" value="pickup" checked>
                    Pickup at store (Free)
                </label>

                <label class="option">
                    <input type="radio" name="delivery_type" value="delivery">
                    Light Delivery (+ RM 3.00)
                </label>

                {{-- PAYMENT --}}
                <div class="section-title" style="margin-top:18px;">Payment Method</div>

                <label class="option">
                    <input type="radio" name="payment_method" value="stripe" required>
                    Credit / Debit Card
                </label>

                <label class="option">
                    <input type="radio" name="payment_method" value="toyyibpay">
                    Online Banking (FPX)
                </label>

                {{-- TOTAL --}}
                <div class="total-box">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>RM {{ number_format($total,2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Delivery</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <div class="total-final">
                        Total: RM {{ number_format($total,2) }}
                    </div>
                </div>

                <button type="submit" class="pay-btn">
                    Confirm & Pay
                </button>

                <div class="trust">
                    🔒 Secure checkout · Encrypted payment · No card stored
                </div>

            </form>
        </div>

    </div>
</div>

@endsection
