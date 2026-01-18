@extends('layouts.staff')

@section('title', 'Bookings')

@section('content')

@php
    use Carbon\Carbon;

    // Safety defaults
    $filter             = $filter             ?? 'all';
    $customerName       = $customerName       ?? '';
    $totalBookings      = $totalBookings      ?? 0;
    $todayBookingsCount = $todayBookingsCount ?? 0;
    $pendingCount       = $pendingCount       ?? 0;
    $completedCount     = $completedCount     ?? 0;
    $cancelledCount     = $cancelledCount     ?? 0;
    $todayBookings      = $todayBookings      ?? collect();
@endphp

<style>
/* ====== PAGE CONTAINER (NEW) ====== */
.bookings-page {
    max-width: 1200px;      /* keep inside screen */
    margin: 0 auto;         /* center inside card */
    box-sizing: border-box;
}

/* ================== TOP STATS ================== */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

@media (max-width: 992px) {
    .stats-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 600px) {
    .stats-row {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 18px;
    background: #0f172a;
    color: white;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.4);
    border: 1px solid rgba(148, 163, 184, .5);
    text-decoration: none;
}

.stat-card .stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
}

.stat-card .stat-info span {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .03em;
    opacity: .8;
}

.stat-card .stat-info h4 {
    margin: 2px 0 0 0;
    font-size: 20px;
}

/* Color variations */
.stat-total   { background: linear-gradient(135deg, #1d2671, #c33764); }
.stat-total .stat-icon { background: rgba(15, 23, 42, 0.35); }

.stat-today   { background: linear-gradient(135deg, #0ea5e9, #22c55e); }
.stat-today .stat-icon { background: rgba(15, 23, 42, 0.35); }

.stat-pending { background: linear-gradient(135deg, #f97316, #facc15); }
.stat-pending .stat-icon { background: rgba(15, 23, 42, 0.35); }

.stat-complete { background: linear-gradient(135deg, #22c55e, #16a34a); }
.stat-complete .stat-icon { background: rgba(15, 23, 42, 0.35); }

/* ================== FILTER + SEARCH BAR ================== */
.filter-search-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}

.filter-bar {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.filter-link {
    padding:6px 12px;
    border-radius:999px;
    color:#0f172a;
    background:#e2e8f0;
    text-decoration:none;
    font-weight:600;
    font-size:12px;
    border:1px solid transparent;
}

.filter-link.active {
    background:#1e3a8a;
    color:white;
    border-color:#1e40af;
}

.search-box form {
    display:flex;
    gap:6px;
    align-items:center;
}

.search-input {
    padding:6px 10px;
    border-radius:999px;
    border:1px solid #cbd5e1;
    font-size:13px;
    min-width:180px;
}

.search-btn {
    padding:6px 12px;
    border-radius:999px;
    border:none;
    background:#1d4ed8;
    color:white;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
}

/* ================== TODAY QUEUE STRIP ================== */
.today-strip {
    margin-bottom:16px;
    padding:12px 14px;
    border-radius:18px;
    background: linear-gradient(120deg, rgba(15,23,42,1), rgba(56,189,248,.9));
    color:white;
    position:relative;
    overflow:hidden;
}

.today-strip::before {
    content:'';
    position:absolute;
    inset:0;
    background:url("{{ asset('images/staffDashboard.png') }}") center/cover no-repeat;
    opacity:0.12;
    pointer-events:none;
}

.today-strip-header {
    position:relative;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:6px;
}

.today-strip-header h3 {
    margin:0;
    font-size:15px;
}

.today-strip-header span {
    font-size:12px;
    opacity:0.9;
}

.today-strip-list {
    position:relative;
    display:flex;
    gap:10px;
    overflow-x:auto;
    padding-bottom:4px;
}

.today-chip {
    min-width:200px;
    background:rgba(15,23,42,0.7);
    border-radius:12px;
    padding:8px 10px;
    font-size:12px;
    border:1px solid rgba(148,163,184,0.7);
}

/* ================== TABLE ================== */
.table-wrapper {
    background:white;
    padding:18px;
    border-radius:16px;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
    overflow-x:auto; /* keep inside container */
}

table {
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

thead {
    background:linear-gradient(90deg,#0f172a,#1e3a8a);
    color:white;
}

th, td { padding:10px; white-space:nowrap; }

tbody tr:nth-child(even){
    background:#f8fafc;
}

/* BADGES */
.badge {
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}
.badge-green  { background:#d1fae5; color:#065f46; }
.badge-yellow { background:#fef9c3; color:#92400e; }
.badge-red    { background:#fee2e2; color:#b91c1c; }
.badge-gray   { background:#e5e7eb; color:#374151; }

.status {
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}
.status-pending   { background:#fff7c2; color:#865c00; }
.status-completed { background:#d1fae5; color:#065f46; }
.status-cancelled { background:#fee2e2; color:#b91c1c; }

.btn-complete {
    background:#22c55e;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
    font-weight:700;
}

.btn-complete:hover {
    background:#16a34a;
}

/* Customer box */
.details-box {
    background:#f1f5f9;
    padding:6px;
    border-radius:8px;
    font-size:12px;
    margin-top:5px;
}

/* Responsive tweaks */
@media (max-width: 768px) {
    .table-wrapper {
        padding: 10px;
    }
    th, td {
        padding: 8px;
        font-size: 12px;
    }
}
</style>

<div class="bookings-page"><!-- NEW WRAPPER -->

    {{-- ========== STAT CARDS ========== --}}
    <div class="stats-row">
        <div class="stat-card stat-total">
            <div class="stat-icon">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div class="stat-info">
                <span>Total Bookings</span>
                <h4>{{ $totalBookings }}</h4>
            </div>
        </div>

        {{-- CLICKABLE — filters to today's bookings --}}
        <a href="{{ route('staff.bookings', ['filter' => 'today', 'customer_name' => $customerName]) }}"
           class="stat-card stat-today">
            <div class="stat-icon">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div class="stat-info">
                <span>Today’s Bookings</span>
                <h4>{{ $todayBookingsCount }}</h4>
            </div>
        </a>

        <div class="stat-card stat-pending">
            <div class="stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <span>Pending</span>
                <h4>{{ $pendingCount }}</h4>
            </div>
        </div>

        <div class="stat-card stat-complete">
            <div class="stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="stat-info">
                <span>Completed</span>
                <h4>{{ $completedCount }}</h4>
            </div>
        </div>
    </div>

    {{-- ========== TODAY’S QUEUE STRIP ========== --}}
    <div class="today-strip">
        <div class="today-strip-header">
            <div>
                <h3>Today’s Service Queue</h3>
                <span>{{ now()->format('d M Y') }} • AutoShineX Carwash</span>
            </div>
            <div style="font-size:12px;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-lightning-charge-fill"></i> Live bookings
            </div>
        </div>

        @if($todayBookings->count())
            <div class="today-strip-list">
                @foreach($todayBookings as $tb)
                    <div class="today-chip">
                        <strong>{{ $tb->BookingTime ? Carbon::parse($tb->BookingTime)->format('h:i A') : 'Time N/A' }}</strong><br>
                        {{ $tb->CustomerName ?? 'Walk-in Customer' }} <br>
                        <small>{{ $tb->ServiceName ?? 'Service' }} • Plate: <strong>{{ $tb->PlateNumber ?? 'N/A' }}</strong></small>
                    </div>
                @endforeach
            </div>
        @else
            <p style="position:relative;margin:0;font-size:13px;">
                No bookings scheduled for today yet. You’re all set!
            </p>
        @endif
    </div>

    {{-- ========== FILTERS + SEARCH ========== --}}
    <div class="filter-search-row">
        <div class="filter-bar">

            <a href="{{ route('staff.bookings', ['filter'=>'all', 'customer_name'=>$customerName]) }}"
               class="filter-link {{ $filter=='all'?'active':'' }}">
                All
            </a>

            <a href="{{ route('staff.bookings', ['filter'=>'today', 'customer_name'=>$customerName]) }}"
               class="filter-link {{ $filter=='today'?'active':'' }}">
                Today
            </a>

            <a href="{{ route('staff.bookings', ['filter'=>'pending', 'customer_name'=>$customerName]) }}"
               class="filter-link {{ $filter=='pending'?'active':'' }}">
                Pending
            </a>

            <a href="{{ route('staff.bookings', ['filter'=>'completed', 'customer_name'=>$customerName]) }}"
               class="filter-link {{ $filter=='completed'?'active':'' }}">
                Completed
            </a>

            <a href="{{ route('staff.bookings', ['filter'=>'cancelled', 'customer_name'=>$customerName]) }}"
               class="filter-link {{ $filter=='cancelled'?'active':'' }}">
                Cancelled
            </a>

        </div>

        <div class="search-box">
            <form action="{{ route('staff.bookings') }}" method="GET">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input
                    type="text"
                    name="customer_name"
                    class="search-input"
                    placeholder="Search by customer name..."
                    value="{{ $customerName }}"
                >
                <button class="search-btn" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    {{-- ========== MAIN TABLE ========== --}}
    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                
                <th>Customer</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Plate No.</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @forelse($bookings as $b)

                @php
                    $map = strtolower($b->payment_status ?? '');
                    $payBadge = "badge-gray";
                    $payText = "No Payment";

                    if ($map == "paid" || $map == "verified") {
                        $payBadge="badge-green"; $payText="Paid";
                    } elseif ($map == "pending") {
                        $payBadge="badge-yellow"; $payText="Pending Review";
                    } elseif ($map == "awaiting cash payment") {
                        $payBadge="badge-yellow"; $payText="Cash Payment";
                    } elseif ($map == "failed" || $map == "rejected") {
                        $payBadge="badge-red"; $payText="Rejected";
                    }
                @endphp

                <tr>
                    

                    <td>
                        <strong>{{ $b->CustomerName ?? 'Unknown' }}</strong>
                        <div class="details-box">
                            Phone: {{ $b->CustomerPhone ?? '-' }} <br>
                            Email: {{ $b->CustomerEmail ?? '-' }}
                        </div>
                    </td>

                    <td>
                        <strong>{{ $b->ServiceName ?? 'N/A' }}</strong><br>
                        <small class="text-muted">{{ $b->ServiceDescription ?? '' }}</small>
                    </td>

                    <td>{{ $b->BookingDate ?? '-' }}</td>
                    <td>{{ $b->BookingTime ? Carbon::parse($b->BookingTime)->format('h:i A') : '-' }}</td>

                    <td><strong>{{ $b->PlateNumber ?? 'N/A' }}</strong></td>

                    <td><span class="badge {{ $payBadge }}">{{ $payText }}</span></td>

                    <td>
                        <span class="status status-{{ strtolower($b->BookingStatus) }}">
                            {{ $b->BookingStatus ?? 'Unknown' }}
                        </span>
                    </td>

                    <td>
                        @if(($b->BookingStatus ?? '') !== 'Completed')
                            <form
                                action="{{ route('staff.bookings.updateStatus', ['id'=>$b->BookingID, 'status'=>'Completed']) }}"
                                method="POST"
                            >
                                @csrf
                                <button class="btn-complete" type="submit">
                                    Mark Completed
                                </button>
                            </form>
                        @else
                            <span style="font-size:11px; color:#6b7280;">Done</span>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:20px; color:#6b7280;">
                        No bookings found for this filter.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div> {{-- /.bookings-page --}}

@endsection
