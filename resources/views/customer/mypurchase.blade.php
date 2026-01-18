@extends('layouts.customer')

@section('title', 'My Purchases')

@section('content')

<style>
body {
    background:#f8fafc !important;
    font-family:Poppins, sans-serif;
}

/* ================= FULL WIDTH ================= */
.page-wrapper {
    width:100%;
    padding:24px 24px 60px;
}

/* ================= HEADER ================= */
.page-header h2 {
    font-size:26px;
    font-weight:800;
    color:#0f172a;
}
.page-header p {
    font-size:14px;
    color:#64748b;
    margin-top:4px;
}

/* ================= ORDER CARD ================= */
.order-card {
    background:#ffffff;
    border-radius:20px;
    padding:24px;
    margin-top:26px;
    box-shadow:0 14px 40px rgba(15,23,42,.1);
}

/* TOP */
.order-top {
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px dashed #e5e7eb;
    padding-bottom:16px;
}

.order-meta {
    font-size:14px;
    color:#334155;
}

.order-id {
    font-weight:800;
    color:#0f172a;
}

.badge {
    padding:6px 16px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
}
.badge-completed {
    background:#dcfce7;
    color:#166534;
}
.badge-pending {
    background:#fef3c7;
    color:#92400e;
}

/* ================= INFO ROW ================= */
.info-row {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:16px;
    margin:18px 0;
}

.info-box {
    background:#f1f5f9;
    padding:14px;
    border-radius:14px;
    font-size:13px;
}

.info-box strong {
    display:block;
    color:#0f172a;
    margin-bottom:4px;
}

/* ================= TABLE ================= */
table {
    width:100%;
    border-collapse:collapse;
    margin-top:12px;
}

thead {
    background:#e0f2fe;
}

th, td {
    padding:14px;
    font-size:14px;
    text-align:left;
}

th {
    color:#0369a1;
    font-weight:800;
}

tbody tr {
    border-bottom:1px solid #e5e7eb;
}

.product-info {
    display:flex;
    align-items:center;
    gap:12px;
}

.product-info img {
    width:52px;
    height:52px;
    border-radius:10px;
    object-fit:cover;
    border:1px solid #e5e7eb;
}

/* ================= TOTAL ================= */
.order-total {
    display:flex;
    justify-content:flex-end;
    margin-top:18px;
    font-size:16px;
    font-weight:900;
    color:#0f172a;
}

/* ================= EMPTY ================= */
.empty-box {
    background:#ffffff;
    border-radius:20px;
    padding:48px;
    margin-top:40px;
    text-align:center;
    color:#64748b;
    box-shadow:0 14px 40px rgba(15,23,42,.1);
}
</style>

<div class="page-wrapper">

    <div class="page-header">
        <h2>🛍 My Purchases</h2>
        <p>Track your orders, delivery details, and payment history.</p>
    </div>

    @if($purchases->isEmpty())
        <div class="empty-box">
            <h3>No purchases yet</h3>
            <p>Your completed orders will appear here.</p>
        </div>
    @else

        @foreach($purchases as $purchase)
        <div class="order-card">

            {{-- ORDER HEADER --}}
            <div class="order-top">
                <div class="order-meta">
                    <div class="order-id">
                        Order #{{ $purchase->PurchaseID }}
                    </div>
                    {{ \Carbon\Carbon::parse($purchase->PurchaseDate)->format('d M Y, h:i A') }}
                </div>

                <span class="badge {{ $purchase->PurchaseStatus === 'Completed' ? 'badge-completed' : 'badge-pending' }}">
                    {{ strtoupper($purchase->PurchaseStatus) }}
                </span>
            </div>

            {{-- INFO --}}
            <div class="info-row">
                <div class="info-box">
                    <strong>Delivery Type</strong>
                    {{ $purchase->DeliveryType ?? 'Pickup' }}
                </div>

                <div class="info-box">
                    <strong>Delivery Address</strong>
                    {{ $purchase->DeliveryAddress ?? 'Pickup at store' }}
                </div>

                <div class="info-box">
                    <strong>Total Paid</strong>
                    RM {{ number_format($purchase->TotalAmount,2) }}
                </div>
            </div>

            {{-- ITEMS --}}
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="{{ asset('storage/' . $item->Image) }}">
                                {{ $item->ProductName }}
                            </div>
                        </td>
                        <td>{{ $item->Quantity }}</td>
                        <td>RM {{ number_format($item->UnitPrice,2) }}</td>
                        <td>RM {{ number_format($item->Subtotal,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- TOTAL --}}
            <div class="order-total">
                Grand Total: RM {{ number_format($purchase->TotalAmount,2) }}
            </div>

        </div>
        @endforeach

    @endif

</div>

@endsection
