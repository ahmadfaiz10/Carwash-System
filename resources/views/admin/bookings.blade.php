@extends('layouts.admin')
@php use Carbon\Carbon; @endphp

@section('title', 'Manage Bookings')

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<style>
body{
    background:#f8fafc !important;
    font-family:'Poppins',sans-serif;
}
.booking-container{
    background:#ffffff;
    border-radius:20px;
    padding:24px;
    border:1px solid #e5e7eb;
}

/* ---------- HEADERS ---------- */
.page-intro h2{font-size:26px;font-weight:800;color:#0f172a;}
.page-intro p{font-size:14px;color:#64748b;margin-top:6px;}

.hero-box{
    background:linear-gradient(135deg,#1e40af,#2563eb);
    padding:26px;
    border-radius:20px;
    color:white;
    margin:20px 0;
}
.hero-title{font-size:24px;font-weight:800;}
.hero-stats{margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;}
.chip{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    background:rgba(255,255,255,0.2);
    font-weight:600;
}

/* ---------- METRICS ---------- */
.metric-row{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
    margin-bottom:28px;
}
.metric-box{
    background:white;
    border:1px solid #e5e7eb;
    border-radius:16px;
    padding:18px;
}
.metric-title{font-size:12px;color:#64748b;text-transform:uppercase;}
.metric-value{font-size:28px;font-weight:800;margin-top:4px;}

/* ---------- FILTER ---------- */
.filter-bar{
    background:white;
    padding:16px;
    border-radius:16px;
    border:1px solid #e5e7eb;
    margin-bottom:26px;
}
.filter-bar input,.filter-bar select{
    padding:10px 12px;
    border-radius:12px;
    border:1px solid #cbd5e1;
}
.btn-filter{
    background:#2563eb;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:12px;
    font-weight:700;
}

/* ---------- QUEUE ---------- */
.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:30px;
}
.section-header h3{font-size:20px;font-weight:800;}
.section-helper{font-size:13px;color:#64748b;margin-bottom:12px;}

.queue-card{
    background:white;
    border-radius:16px;
    padding:20px;
    border:1px solid #e5e7eb;
    display:flex;
    justify-content:space-between;
    gap:20px;
    margin-bottom:14px;
    position:relative;
}
.queue-card::before{
    content:"";
    position:absolute;
    left:0;top:0;bottom:0;
    width:6px;
    background:#f97316;
    border-radius:6px 0 0 6px;
}

.q-name{font-size:17px;font-weight:800;}
.q-plate{font-weight:600;color:#475569;}
.q-service{font-weight:700;}
.q-desc{font-size:13px;color:#64748b;}

.pill{
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
    display:inline-block;
}
.pill-pend{background:#fff7ed;color:#9a3412;}
.pill-pay{background:#d1fae5;color:#065f46;}
.pill-cash{background:#fef9c3;color:#854d0e;}
.pill-none{background:#e5e7eb;color:#475569;}

.btn-complete{
    background:#22c55e;
    border:none;
    padding:8px 16px;
    border-radius:12px;
    font-weight:700;
    color:white;
}

/* ---------- HISTORY ---------- */
.history-table thead th{
    background:#0f172a;
    color:white;
    font-size:13px;
    padding:12px;
}
.history-table td{
    padding:12px;
    font-size:13px;
}
</style>

<div class="booking-container">

{{-- PAGE INTRO --}}
<div class="page-intro">
    <h2>Booking Management</h2>
    <p>Monitor daily bookings, manage queues, and review previous services.</p>
</div>

{{-- HERO --}}
<div class="hero-box">
    <div class="hero-title">Today’s Overview</div>
    <div class="hero-stats">
        <span class="chip">{{ $pendingBookings }} Pending</span>
        <span class="chip">{{ $completedBookings }} Completed</span>
        <span class="chip">{{ $cancelledBookings }} Cancelled</span>
        <span class="chip">{{ $paidPayments }}/{{ $paidPayments+$unpaidPayments }} Paid</span>
    </div>
</div>

{{-- METRICS --}}
<div class="metric-row">
    <div class="metric-box"><div class="metric-title">Pending</div><div class="metric-value">{{ $pendingBookings }}</div></div>
    <div class="metric-box"><div class="metric-title">Completed</div><div class="metric-value">{{ $completedBookings }}</div></div>
    <div class="metric-box"><div class="metric-title">Cancelled</div><div class="metric-value">{{ $cancelledBookings }}</div></div>
    <div class="metric-box"><div class="metric-title">Payments</div><div class="metric-value">{{ $paidPayments }}/{{ $paidPayments+$unpaidPayments }}</div></div>
</div>

{{-- FILTER --}}
<div class="filter-bar">
<form method="GET" action="{{ route('admin.bookings') }}">
    <input type="text" name="customer_name" placeholder="Search customer..." value="{{ request('customer_name') }}">
    <select name="filter">
        <option value="all">All</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <button class="btn-filter">Apply</button>
</form>
</div>

{{-- PENDING QUEUE --}}
<div class="section-header">
    <h3>Pending Queue</h3>
</div>
<div class="section-helper">Live bookings waiting to be served.</div>

@php $pending = $bookings->where('BookingStatus','Pending'); @endphp

@forelse($pending as $b)
<div class="queue-card">
    <div>
        <div class="q-name">{{ $b->customer->CustomerName }}</div>
        <div class="q-plate">{{ $b->PlateNumber }}</div>
    </div>

    <div>
        <div class="q-service">{{ $b->service->name }}</div>
        <div class="q-desc">{{ $b->service->description }}</div>
        <span class="pill pill-pend">Pending</span>

        @if($b->payment_status=='Paid'||$b->payment_status=='Verified')
            <span class="pill pill-pay">Paid</span>
        @elseif($b->payment_status=='Awaiting Cash Payment')
            <span class="pill pill-cash">Cash</span>
        @else
            <span class="pill pill-none">No Payment</span>
        @endif
    </div>

    <div>
        <div>{{ Carbon::parse($b->BookingDate)->format('d M Y') }}</div>
        <div>{{ Carbon::parse($b->BookingTime)->format('h:i A') }}</div>
        <strong>RM{{ number_format($b->service->price,2) }}</strong>

        <form method="POST" action="{{ route('admin.markCompleted',$b->BookingID) }}">
            @csrf @method('PUT')
            <button class="btn-complete">Complete</button>
        </form>
    </div>
</div>
@empty
<p class="text-muted">No pending bookings.</p>
@endforelse

{{-- BOOKING HISTORY --}}
<div class="section-header">
    <h3>Booking History</h3>
</div>
<div class="section-helper">All previous completed and cancelled bookings.</div>

<div class="table-responsive">
<table class="table history-table text-center">
<thead>
<tr>
    <th>#</th><th>Customer</th><th>Service</th><th>Plate</th>
    <th>Date</th><th>Time</th><th>Status</th><th>Payment</th>
</tr>
</thead>
<tbody>
@forelse($bookings as $i=>$b)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $b->customer->CustomerName }}</td>
    <td>{{ $b->service->name }}</td>
    <td>{{ $b->PlateNumber }}</td>
    <td>{{ Carbon::parse($b->BookingDate)->format('d M Y') }}</td>
    <td>{{ Carbon::parse($b->BookingTime)->format('h:i A') }}</td>
    <td>
        @if($b->BookingStatus=='Pending')
            <span class="pill pill-pend">Pending</span>
        @elseif($b->BookingStatus=='Completed')
            <span class="pill pill-pay">Completed</span>
        @else
            <span class="pill pill-none">Cancelled</span>
        @endif
    </td>
    <td>
        @if($b->payment_status=='Paid'||$b->payment_status=='Verified')
            <span class="pill pill-pay">{{ $b->payment_status }}</span>
        @elseif($b->payment_status=='Awaiting Cash Payment')
            <span class="pill pill-cash">Cash</span>
        @else
            <span class="pill pill-none">No Record</span>
        @endif
    </td>
</tr>
@empty
<tr><td colspan="8">No booking records found.</td></tr>
@endforelse
</tbody>
</table>
</div>

</div>
@endsection
