@extends('layouts.admin')

@section('title', 'Product Purchases')

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
    margin-bottom: 24px;
}

/* ===================== TABLE CARD ===================== */
.table-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

/* ===================== TABLE ===================== */
.purchase-table {
    width: 100%;
    border-collapse: collapse;
}

.purchase-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 14px 12px;
    border-bottom: 1px solid #e5e7eb;
}

.purchase-table tbody td {
    padding: 14px 12px;
    font-size: 14px;
    color: #0f172a;
    border-bottom: 1px solid #f1f5f9;
}

.purchase-table tbody tr:hover {
    background: #f9fafb;
}

/* ===================== BADGES ===================== */
.status-badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

/* ===================== ACTION LINK ===================== */
.action-link {
    color: #2563eb;
    font-weight: 700;
    text-decoration: none;
}

.action-link:hover {
    text-decoration: underline;
}

/* ===================== EMPTY STATE ===================== */
.empty-row td {
    text-align: center;
    color: #64748b;
    padding: 26px;
    font-size: 14px;
}
</style>

<div class="page-title">Product Purchases</div>
<div class="page-subtitle">
    View and manage all customer product purchase transactions.
</div>

<div class="table-card">

    <table class="purchase-table">
        <thead>
            <tr>
                <th>Purchase ID</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Purchase Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @forelse($purchases as $p)
            <tr>
                <td>#{{ $p->PurchaseID }}</td>
                <td>{{ $p->CustomerName }}</td>
                <td>{{ $p->CustomerPhone }}</td>
                <td>RM {{ number_format($p->TotalAmount, 2) }}</td>
                <td>
                    <span class="status-badge 
                        {{ $p->PurchaseStatus == 'Completed' ? 'status-completed' : 'status-pending' }}">
                        {{ $p->PurchaseStatus }}
                    </span>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($p->PurchaseDate)->format('d M Y') }}
                </td>
                <td>
                    <a href="{{ route('admin.product.purchase.detail', $p->PurchaseID) }}"
                       class="action-link">
                        View Details
                    </a>
                </td>
            </tr>
        @empty
            <tr class="empty-row">
                <td colspan="7">No product purchases recorded yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

@endsection
