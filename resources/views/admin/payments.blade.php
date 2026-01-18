@extends('layouts.admin')

@section('title', 'Payment Management')

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
/* ===================== PAGE BASE ===================== */
.page-wrap {
    background:#ffffff;
    min-height:100vh;
}

/* ===================== HEADER ===================== */
.page-header {
    margin-bottom: 26px;
}
.page-header h2 {
    font-size: 26px;
    font-weight: 700;
    color:#0f172a;
}
.page-header p {
    margin:0;
    color:#64748b;
    font-size:14px;
}

/* ===================== KPI ROW ===================== */
.kpi-row {
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:18px;
    margin-bottom:26px;
}
.kpi-card {
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:18px;
}
.kpi-label {
    font-size:13px;
    color:#64748b;
}
.kpi-value {
    font-size:26px;
    font-weight:700;
    margin-top:4px;
}
.kpi-green { color:#16a34a; }
.kpi-yellow { color:#ca8a04; }
.kpi-red { color:#dc2626; }

/* ===================== FILTER BAR ===================== */
.filter-bar {
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:16px;
    margin-bottom:24px;
}
.filter-bar input,
.filter-bar select {
    height:44px;
    border-radius:10px;
    border:1px solid #d1d5db;
    padding:0 14px;
}
.filter-bar button {
    height:44px;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-weight:600;
    border:none;
    padding:0 26px;
}

/* ===================== TABLE ===================== */
.table-wrap {
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    overflow:hidden;
}
.payment-table {
    width:100%;
    border-collapse:collapse;
}
.payment-table thead {
    background:#f8fafc;
}
.payment-table th {
    padding:14px;
    font-size:13px;
    text-transform:uppercase;
    color:#475569;
    border-bottom:1px solid #e5e7eb;
}
.payment-table td {
    padding:14px;
    font-size:14px;
    border-bottom:1px solid #f1f5f9;
}
.payment-table tbody tr:hover {
    background:#f9fafb;
}

/* ===================== STATUS ===================== */
.status-pill {
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
}
.st-unpaid { background:#fef9c3; color:#854d0e; }
.st-cash   { background:#fff7ed; color:#9a3412; }
.st-paid   { background:#dcfce7; color:#166534; }
.st-failed { background:#fee2e2; color:#b91c1c; }

/* ===================== ACTIONS ===================== */
.action-btn {
    padding:6px 14px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    border:none;
    color:white;
    cursor:pointer;
}
.btn-approve { background:#16a34a; }
.btn-reject  { background:#dc2626; }
</style>

<div class="page-wrap">

    {{-- ================= HEADER ================= --}}
    <div class="page-header">
        <h2>Payment Management</h2>
        <p>Review, verify and manage all customer payments</p>
    </div>
    

    {{-- ================= KPI ================= --}}
    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kpi-label">Pending / Cash Payments</div>
            <div class="kpi-value kpi-yellow">{{ $countPending }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Verified Payments</div>
            <div class="kpi-value kpi-green">{{ $countPaid }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Failed Payments</div>
            <div class="kpi-value kpi-red">{{ $countFailed }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Today Revenue</div>
            <div class="kpi-value kpi-green">
                RM {{ number_format($todayRevenue,2) }}
            </div>
        </div>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.payments') }}" class="d-flex gap-3 flex-wrap">
            <input type="text"
                   name="search"
                   placeholder="Search customer..."
                   value="{{ request('search') }}"
                   class="flex-grow-1">

            <select name="method">
                <option value="">All Methods</option>
                <option value="online" @selected(request('method')=='online')>Online</option>
                <option value="cash" @selected(request('method')=='cash')>Cash</option>
            </select>

            <button>Apply Filter</button>
        </form>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="table-wrap">
        <table class="payment-table text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse($payments as $i=>$p)

                @php
                    $status = $p->PaymentStatus;
                    $cls = match($status){
                        'Unpaid' => 'st-unpaid',
                        'Awaiting Cash Payment' => 'st-cash',
                        'Paid' => 'st-paid',
                        'Failed' => 'st-failed',
                        default => 'st-failed'
                    };
                @endphp

                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <strong>{{ $p->CustomerName }}</strong><br>
                        <small class="text-muted">{{ $p->CustomerPhone }}</small>
                    </td>
                    <td class="text-start">
                        <strong>{{ $p->ServiceName }}</strong><br>
                        <small class="text-muted">{{ $p->ServiceDescription }}</small>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->BookingDate)->format('d M Y') }}<br>
                        <small>{{ \Carbon\Carbon::parse($p->BookingTime)->format('h:i A') }}</small>
                    </td>
                    <td><strong>RM {{ number_format($p->Amount,2) }}</strong></td>
                    <td>{{ ucfirst($p->PaymentMethod) }}</td>
                    <td><span class="status-pill {{ $cls }}">{{ $status }}</span></td>
                    <td>
                        @if(in_array($status,['Unpaid','Awaiting Cash Payment']))
                            <a href="{{ route('payment.approve',$p->PaymentID) }}"
                               class="action-btn btn-approve">Approve</a>

                            <a href="{{ route('payment.reject',$p->PaymentID) }}"
                               class="action-btn btn-reject">Reject</a>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-muted py-4">
                        No payment records found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
