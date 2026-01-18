@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('content')

@php use Carbon\Carbon; @endphp

<style>
/* ========= GLOBAL LAYOUT ========= */
.dashboard-page {
    max-width: 1200px;
    margin: 0 auto;
}

/* ========= HERO HEADER ========= */
.hero-wrapper {
    position: relative;
    width: 100%;
    height: 240px;
    border-radius: 18px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.25);
}

.hero-bg {
    width: 100%;
    height: 100%;
    background-image: url("{{ asset('images/staffDashboard.png') }}");
    background-size: cover;
    background-position: center;
    filter: brightness(0.65);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        120deg,
        rgba(15,23,42,0.9),
        rgba(8,47,73,0.5),
        transparent
    );
}

.hero-text {
    position: absolute;
    bottom: 24px;
    left: 24px;
    color: white;
}

.hero-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-shadow: 0 3px 8px rgba(0,0,0,0.7);
}

.hero-subtitle {
    font-size: 14px;
    opacity: 0.95;
    margin-top: 4px;
}

/* ========= STAT CARDS ========= */
.stats-container {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.stat-card {
    flex: 1;
    min-width: 220px;
    padding: 16px 18px;
    border-radius: 16px;
    color: white;
    box-shadow: 0 5px 18px rgba(0,0,0,0.18);
    position: relative;
    overflow: hidden;
    transition: 0.25s ease;
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0.25;
    mix-blend-mode: screen;
    background: radial-gradient(circle at top right, white, transparent 55%);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 26px rgba(0,0,0,0.30);
}

/* Brand/Theme colors (carwash vibe) */
.stat-blue  { background: linear-gradient(135deg, #0f172a, #1d4ed8); } /* navy / blue */
.stat-amber { background: linear-gradient(135deg, #92400e, #f59e0b); } /* orange / yellow */
.stat-teal  { background: linear-gradient(135deg, #0f766e, #14b8a6); } /* teal / aqua */
.stat-red   { background: linear-gradient(135deg, #9f1239, #f97373); } /* magenta / pinkish */

/* Icon inside stat cards */
.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 999px;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    font-size: 22px;
}

.stat-text {
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 26px;
    font-weight: 800;
    line-height: 1.1;
}

.stat-label {
    font-size: 13px;
    margin-top: 3px;
}

/* ========= SECTION CARDS ========= */
.dashboard-box {
    background: #ffffff;
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 22px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15,23,42,0.06);
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 10px;
}

/* ========= TABLE DESIGN ========= */
.table-wrapper {
    background: linear-gradient(135deg, #eff6ff, #f9fafb);
    padding: 10px;
    border-radius: 14px;
    border: 1px solid #dbe2f0;
    overflow-x: auto; /* responsive horizontally */
}

table {
    width: 100%;
    font-size: 13px;
    border-collapse: collapse;
    min-width: 600px; /* so scrolling makes sense on mobile */
}

thead th {
    background: #1e3a8a;
    color: white;
    padding: 8px;
    font-weight: 600;
    text-align: left;
}

tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.9);
}

td {
    padding: 7px 8px;
    border-bottom: 1px solid #e5e7eb;
}

/* ========= Status Badges ========= */
.status {
    padding: 4px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

.status-pending {
    background: #fff4ce;
    color: #92400e;
}
.status-completed {
    background: #dcfce7;
    color: #166534;
}
.status-cancelled {
    background: #fee2e2;
    color: #b91c1c;
}

/* ========= Recent Activity ========= */
.activity-item {
    padding: 10px 0;
    border-bottom: 1px dashed #e5e7eb;
}

.activity-main {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
}

.activity-sub {
    font-size: 12px;
    color: #64748b;
}

/* ========= RESPONSIVE ========= */
@media (max-width: 992px) {
    .hero-wrapper {
        height: 200px;
    }

    .hero-title {
        font-size: 24px;
    }

    .stats-container {
        gap: 12px;
    }

    .stat-card {
        min-width: 47%;
        padding: 14px;
    }
}

@media (max-width: 768px) {
    .hero-wrapper {
        height: 180px;
        margin-bottom: 20px;
    }

    .hero-title {
        font-size: 20px;
    }

    .hero-subtitle {
        font-size: 12px;
    }

    .stats-container {
        flex-direction: column;
    }

    .stat-card {
        min-width: 100%;
    }

    table {
        font-size: 12px;
        min-width: 520px;
    }
}

@media (max-width: 480px) {
    .hero-text {
        left: 16px;
        bottom: 16px;
    }

    .hero-title {
        font-size: 18px;
    }

    .hero-subtitle {
        display: none; /* keep header clean on very small screens */
    }

    .dashboard-box {
        padding: 14px;
    }
}
</style>


<div class="dashboard-page">

    {{-- ========================================================= --}}
    {{--                       HERO HEADER                         --}}
    {{-- ========================================================= --}}
    <div class="hero-wrapper">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>

        <div class="hero-text">
            <div class="hero-title">Staff Control Panel</div>
            <div class="hero-subtitle">
                Live overview of today’s bookings, queue, and activity.
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{--                        TODAY STATS                        --}}
    {{-- ========================================================= --}}
    <div class="stats-container">

        <div class="stat-card stat-blue">
            <div class="stat-icon">📅</div>
            <div class="stat-text">
                <div class="stat-value">{{ $todayBookings }}</div>
                <div class="stat-label">Bookings Today</div>
            </div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-icon">⏳</div>
            <div class="stat-text">
                <div class="stat-value">{{ $pendingBookings }}</div>
                <div class="stat-label">Pending Bookings</div>
            </div>
        </div>

        <div class="stat-card stat-teal">
            <div class="stat-icon">✅</div>
            <div class="stat-text">
                <div class="stat-value">{{ $completedBookings }}</div>
                <div class="stat-label">Completed Bookings</div>
            </div>
        </div>

        <div class="stat-card stat-red">
            <div class="stat-icon">⚠️</div>
            <div class="stat-text">
                <div class="stat-value">{{ $cancelledToday }}</div>
                <div class="stat-label">Cancelled Today</div>
            </div>
        </div>

    </div>



    {{-- ========================================================= --}}
    {{--                  UPCOMING BOOKINGS TODAY                  --}}
    {{-- ========================================================= --}}
    <div class="dashboard-box">
        <div class="section-title">Upcoming Bookings Today</div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Plate No.</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($upcomingBookings as $b)
                    <tr>
                        <td>{{ Carbon::parse($b->BookingTime)->format('h:i A') }}</td>
                        <td>{{ $b->customer->CustomerName ?? 'N/A' }}</td>
                        <td>{{ $b->service->name ?? 'N/A' }}</td>
                        <td>{{ $b->PlateNumber ?? 'N/A' }}</td>
                        <td><span class="status status-pending">Pending</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#6b7280;">
                            No upcoming bookings for today.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- ========================================================= --}}
    {{--                       TODAY’S QUEUE                       --}}
    {{-- ========================================================= --}}
    <div class="dashboard-box">
        <div class="section-title">Today’s Queue</div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Plate No.</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($todayQueue as $b)
                    <tr>
                        <td>{{ Carbon::parse($b->BookingTime)->format('h:i A') }}</td>
                        <td>{{ $b->customer->CustomerName ?? 'N/A' }}</td>
                        <td>{{ $b->service->name ?? 'N/A' }}</td>
                        <td>{{ $b->PlateNumber ?? 'N/A' }}</td>
                        <td>
                            <span class="status status-{{ strtolower($b->BookingStatus) }}">
                                {{ $b->BookingStatus }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#6b7280;">
                            No bookings in the queue.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- ========================================================= --}}
    {{--                  RECENTLY COMPLETED TODAY                 --}}
    {{-- ========================================================= --}}
    <div class="dashboard-box">
        <div class="section-title">Recently Completed (Today)</div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Plate No.</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentCompleted as $b)
                    <tr>
                        <td>{{ Carbon::parse($b->BookingTime)->format('h:i A') }}</td>
                        <td>{{ $b->customer->CustomerName ?? 'N/A' }}</td>
                        <td>{{ $b->service->name ?? 'N/A' }}</td>
                        <td>{{ $b->PlateNumber ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#6b7280;">
                            No completed bookings yet today.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- ========================================================= --}}
    {{--                     RECENT ACTIVITY LOG                   --}}
    {{-- ========================================================= --}}
    <div class="dashboard-box">
        <div class="section-title">Recent Activity</div>

        @forelse ($recentActivity as $item)
            <div class="activity-item">
                <div class="activity-main">
                    {{ $item['status'] }} • {{ $item['customer'] }} • {{ $item['service'] }}
                </div>
                <div class="activity-sub">
                    Plate: {{ $item['plate'] }} &nbsp; | &nbsp; {{ $item['time'] }}
                </div>
            </div>
        @empty
            <p style="color:#6b7280;">No recent activity.</p>
        @endforelse
    </div>

</div>

@endsection
