@extends('layouts.customer')

@php use Carbon\Carbon; @endphp

@section('title', 'My Dashboard')
@section('fullwidth', true)

@section('content')

<style>
/* =======================
   PAGE BASE
======================= */
.dashboard-wrapper{
    width:100%;
    min-height:100vh;
    background:#ffffff;
    padding:32px 36px 60px;
}

/* =======================
   MAIN GRID
======================= */
.dashboard-grid{
    max-width:1500px;
    margin:0 auto;
    display:grid;
    grid-template-columns: 2.3fr 1.2fr;
    gap:28px;
}

/* =======================
   CARD SYSTEM
======================= */
.card{
    background:#ffffff;
    border-radius:16px;
    border:1px solid #e5e7eb;
    padding:24px;
}

.card.soft{
    background:#f9fafb;
}

.card.shadow{
    box-shadow:0 10px 28px rgba(0,0,0,.06);
}

/* =======================
   HERO
======================= */
.hero{
    border-left:6px solid #0ea5e9;
}

.hero-badge{
    display:inline-flex;
    gap:8px;
    font-size:12px;
    padding:6px 14px;
    border-radius:999px;
    background:#e0f2fe;
    color:#0369a1;
    font-weight:600;
    margin-bottom:12px;
}

.hero-title{
    font-size:28px;
    font-weight:800;
    color:#0f172a;
}

.hero-sub{
    font-size:14px;
    color:#64748b;
    margin-top:4px;
}

/* =======================
   STATS
======================= */
.stats{
    margin-top:22px;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
}

.stat{
    background:#ffffff;
    border-radius:14px;
    border:1px solid #e5e7eb;
    padding:18px;
}

.stat-label{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#64748b;
}

.stat-value{
    font-size:24px;
    font-weight:800;
    margin-top:6px;
}

/* =======================
   ACTION BUTTONS
======================= */
.actions{
    margin-top:24px;
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn-primary{
    background:#0ea5e9;
    color:#ffffff;
    padding:10px 22px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}

.btn-outline{
    background:#ffffff;
    border:1px solid #cbd5e1;
    color:#0f172a;
    padding:10px 22px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}

/* =======================
   SECTION HEADER
======================= */
.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
}

.section-title{
    font-size:17px;
    font-weight:700;
    color:#0f172a;
}

.section-link{
    font-size:13px;
    color:#0ea5e9;
    text-decoration:none;
}

/* =======================
   LIST SYSTEM
======================= */
.list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.list-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 18px;
    border-radius:14px;
    background:#ffffff;
    border:1px solid #e5e7eb;
}

.list-title{
    font-size:14px;
    font-weight:600;
}

.list-meta{
    font-size:12px;
    color:#64748b;
    margin-top:4px;
}

/* =======================
   BADGES
======================= */
.badge{
    font-size:11px;
    padding:4px 10px;
    border-radius:999px;
    font-weight:600;
}

.badge-success{ background:#dcfce7;color:#166534; }
.badge-warning{ background:#fef3c7;color:#92400e; }
.badge-info{ background:#dbeafe;color:#1e40af; }

/* =======================
   CAR CARD
======================= */
.car-img{
    width:100%;
    border-radius:14px;
    margin:18px 0;
    border:1px solid #e5e7eb;
}

.car-meta{
    display:flex;
    justify-content:space-between;
    font-size:13px;
    color:#475569;
}

/* =======================
   RESPONSIVE
======================= */
@media(max-width:1024px){
    .dashboard-grid{ grid-template-columns:1fr; }
}
</style>

<div class="dashboard-wrapper">

<div class="dashboard-grid">

{{-- LEFT COLUMN --}}
<div>

    {{-- HERO --}}
    <div class="card hero shadow">
        <div class="hero-badge">✨ Welcome back, {{ $user->FullName }}</div>
        <div class="hero-title">Dashboard Overview</div>
        <div class="hero-sub">Monitor your bookings, purchases and payments in one place.</div>

        <div class="stats">
            <div class="stat">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value">{{ $totalBookings }}</div>
            </div>
            <div class="stat">
                <div class="stat-label">Completed</div>
                <div class="stat-value" style="color:#16a34a">{{ $completedBookings }}</div>
            </div>
            <div class="stat">
                <div class="stat-label">Pending</div>
                <div class="stat-value" style="color:#eab308">{{ $pendingBookings }}</div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('customer.services') }}" class="btn-primary">New Booking</a>
            <a href="{{ route('customer.bookings') }}" class="btn-outline">My Bookings</a>
            <a href="{{ route('customer.products.categories') }}" class="btn-outline">Shop Products</a>
        </div>
    </div>

    {{-- UPCOMING BOOKING --}}
    <div class="card shadow" style="margin-top:26px;">
        <div class="section-header">
            <div class="section-title">Upcoming Booking</div>
        </div>

        @if($upcomingBooking)
            <div class="list-item">
                <div>
                    <div class="list-title">{{ $upcomingBooking->ServiceName }}</div>
                    <div class="list-meta">
                        {{ Carbon::parse($upcomingBooking->BookingDate)->format('d M Y') }},
                        {{ Carbon::parse($upcomingBooking->BookingTime)->format('h:i A') }}
                        • Plate: {{ $upcomingBooking->PlateNumber }}
                    </div>
                </div>
                <div>
                    RM{{ number_format($upcomingBooking->ServicePrice,2) }}<br>
                    <span class="badge badge-info">{{ $upcomingBooking->BookingStatus }}</span>
                </div>
            </div>
        @else
            <div class="list-meta">No upcoming bookings.</div>
        @endif
    </div>

    {{-- RECENT BOOKINGS --}}
    <div class="card shadow" style="margin-top:26px;">
        <div class="section-header">
            <div class="section-title">Recent Bookings</div>
            <a href="{{ route('customer.bookings') }}" class="section-link">View all</a>
        </div>

        <div class="list">
            @forelse($recentBookings as $b)
                <div class="list-item">
                    <div>
                        <div class="list-title">{{ $b->ServiceName }}</div>
                        <div class="list-meta">
                            {{ Carbon::parse($b->BookingDate)->format('d M Y') }} •
                            {{ Carbon::parse($b->BookingTime)->format('h:i A') }}
                        </div>
                    </div>
                    <div>
                        RM{{ number_format($b->ServicePrice,2) }}<br>
                        <span class="badge {{ $b->BookingStatus==='Completed'?'badge-success':'badge-warning' }}">
                            {{ $b->BookingStatus }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="list-meta">No recent bookings.</div>
            @endforelse
        </div>
    </div>

</div>

{{-- RIGHT COLUMN --}}
<div>

    {{-- CAR --}}
    <div class="card shadow">
        <div class="section-title">AquaFlow Premium Wash</div>
        <div class="list-meta">Service overview</div>

        <img src="{{ asset('images/dashboard-car.png') }}" class="car-img">

        <div class="car-meta">
            <span>Next slot:
                <strong>{{ $upcomingBooking ? Carbon::parse($upcomingBooking->BookingTime)->format('h:i A') : 'Available' }}</strong>
            </span>
            <span>Plate:
                <strong>{{ $upcomingBooking->PlateNumber ?? '-' }}</strong>
            </span>
        </div>
    </div>

    {{-- PAYMENTS --}}
    <div class="card shadow" style="margin-top:26px;">
        <div class="section-header">
            <div class="section-title">Recent Payments</div>
            <a href="{{ route('customer.mypayments') }}" class="section-link">View all</a>
        </div>

        <div class="list">
            @forelse($recentPayments as $p)
                <div class="list-item">
                    <div>
                        <div class="list-title">{{ ucfirst($p->PaymentMethod) }}</div>
                        <div class="list-meta">
                            {{ Carbon::parse($p->created_at)->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div>
                        RM{{ number_format($p->Amount,2) }}<br>
                        <span class="badge badge-success">{{ $p->PaymentStatus }}</span>
                    </div>
                </div>
            @empty
                <div class="list-meta">No payments found.</div>
            @endforelse
        </div>
    </div>

</div>

</div>
</div>

@endsection
