@extends('layouts.admin')

@section('title', 'Purchase Detail')

@section('content')

<style>
/* ===================== PAGE HEADER ===================== */
.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

.page-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 26px;
}

/* ===================== SUMMARY CARD ===================== */
.summary-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 26px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
    margin-bottom: 30px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 18px;
}

.summary-item {
    font-size: 14px;
    color: #334155;
}

.summary-item strong {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ===================== STATUS ===================== */
.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

/* ===================== STATUS INFO ===================== */
.status-info {
    font-size: 13px;
    color: #64748b;
    margin-top: 6px;
}

/* ===================== ITEMS TABLE ===================== */
.items-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
    overflow: hidden;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 14px 12px;
    border-bottom: 1px solid #e5e7eb;
}

.items-table tbody td {
    padding: 14px 12px;
    font-size: 14px;
    color: #0f172a;
    border-bottom: 1px solid #f1f5f9;
}

.items-table tbody tr:hover {
    background: #f9fafb;
}

/* ===================== PRODUCT CELL ===================== */
.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-img {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    background: #f8fafc;
}

/* ===================== TOTAL ROW ===================== */
.total-row td {
    font-weight: 800;
    background: #f8fafc;
}
</style>

<div class="page-title">Purchase Detail</div>
<div class="page-subtitle">
    Detailed overview of a customer's product purchase.
</div>

{{-- ===================== ORDER SUMMARY ===================== --}}
<div class="summary-card">

    <div class="summary-grid">

        <div class="summary-item">
            <strong>Customer Name</strong>
            {{ $purchase->CustomerName }}
        </div>

        <div class="summary-item">
            <strong>Phone Number</strong>
            {{ $purchase->CustomerPhone }}
        </div>

        <div class="summary-item">
            <strong>Delivery Address</strong>
            {{ $purchase->CustomerAddress }}
        </div>

        <div class="summary-item">
            <strong>Order Status</strong>

            @php
                $status = $purchase->PurchaseStatus;
            @endphp

            <span class="status-badge
                {{ $status == 'Completed' ? 'status-completed' : '' }}
                {{ $status == 'Pending' ? 'status-pending' : '' }}
                {{ $status == 'Cancelled' ? 'status-cancelled' : '' }}">
                {{ $status }}
            </span>

            <div class="status-info">
                @if($status == 'Pending')
                    Order created but payment is not yet confirmed.
                @elseif($status == 'Completed')
                    Order fully processed and completed successfully.
                @elseif($status == 'Cancelled')
                    Order cancelled and will not be processed.
                @endif
            </div>
        </div>

        <div class="summary-item">
            <strong>Total Amount</strong>
            RM {{ number_format($purchase->TotalAmount, 2) }}
        </div>

        <div class="summary-item">
            <strong>Purchase Date</strong>
            {{ \Carbon\Carbon::parse($purchase->PurchaseDate)->format('d M Y, h:i A') }}
        </div>

    </div>

</div>

{{-- ===================== PURCHASED ITEMS ===================== --}}
<div class="items-card">

    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="width:100px;">Quantity</th>
                <th style="width:160px;">Unit Price</th>
                <th style="width:160px;">Subtotal</th>
            </tr>
        </thead>

        <tbody>
        @foreach($items as $item)
            <tr>
                <td>
                    <div class="product-cell">
                        <img src="{{ asset('storage/'.$item->Image) }}" class="product-img">
                        {{ $item->ProductName }}
                    </div>
                </td>
                <td>{{ $item->Quantity }}</td>
                <td>RM {{ number_format($item->UnitPrice, 2) }}</td>
                <td>RM {{ number_format($item->Subtotal, 2) }}</td>
            </tr>
        @endforeach

            <tr class="total-row">
                <td colspan="3" align="right">Total</td>
                <td>RM {{ number_format($purchase->TotalAmount, 2) }}</td>
            </tr>
        </tbody>
    </table>

</div>

@endsection
