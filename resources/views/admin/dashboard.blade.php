@extends('layouts.admin')
@section('title','Dashboard')

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    body {
        background: #e7edf2 !important;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 32px;
        position: relative;
    }

    /* ============================================================
       WATER CURVED BACKGROUND (TOP WAVES)
    ============================================================= */
    .water-bg {
        position: absolute;
        top: -80px;
        left: 0;
        width: 100%;
        z-index: -1;
    }

    .wave-svg {
        width: 100%;
        height: 300px;
    }

    /* ============================================================
       HERO IMAGE
    ============================================================= */
    .hero-image {
        width: 100%;
        height: 240px;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        border: 1px solid #cbd5df;
    }
    .hero-image img {
        width: 100%; height: 100%; object-fit: cover;
    }

    /* ============================================================
       HERO PANEL
    ============================================================= */
    .hero {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(6px);
        padding: 24px 30px;
        border-radius: 16px;
        border: 1px solid #d9dde1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
    }
    .hero-title {
        font-size: 26px;
        font-weight: 700;
        color: #1b2938;
    }
    .hero-subtitle {
        color: #5f6975;
        font-size: 14px;
    }
    .hero-meta {
        text-align: right;
        color: #37506a;
        font-size: 14px;
    }
    .hero-meta .pill {
        background: #d7ecfc;
        border-radius: 999px;
        padding: 6px 12px;
        border: 1px solid #b7d8f5;
        color: #0a4c88;
        margin-bottom: 5px;
        display: inline-block;
    }

    /* ============================================================
       KPI CARDS (COLORED)
    ============================================================= */
    .kpi-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
    }

    .kpi-card {
        border-radius: 16px;
        padding: 20px;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.10);
        transition: 0.25s ease;
    }
    .kpi-card:hover { transform: translateY(-4px); }

    .kpi-icon {
        width: 46px; height: 46px; border-radius: 12px;
        background: rgba(255,255,255,0.25);
        display:flex; justify-content:center; align-items:center;
        font-size:20px; margin-bottom:10px;
    }

    .primary  { background: linear-gradient(135deg,#1d8cf8,#65b4fe); }
    .success  { background: linear-gradient(135deg,#19c667,#7be8a5); }
    .purple   { background: linear-gradient(135deg,#8854f3,#b598ff); }
    .orange   { background: linear-gradient(135deg,#f78a1f,#ffb36a); }

    .kpi-label { font-size: 14px; opacity: .9; }
    .kpi-value { font-size: 28px; font-weight: 700; }
    .kpi-meta { display:flex; justify-content:space-between; font-size:12px; opacity:.9; }
    .kpi-pill {
        background: rgba(255,255,255,0.35);
        padding: 4px 10px;
        border-radius: 999px;
        color: #fff;
    }

    /* ============================================================
       PANELS (NEUTRAL)
    ============================================================= */
    .panel {
        background: #f1f4f7;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid #d0d4d9;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    .panel-title { font-size:16px; font-weight:600; color:#1b2938; }
    .panel-subtitle { font-size:13px; color:#6c7480; margin-bottom:12px; }

    /* ============================================================
       TODAY METRICS
    ============================================================= */
    .today-grid {
        display:grid; grid-template-columns:1fr 1fr; gap:18px;
    }
    .today-metric {
        background: #e6eaee;
        border: 1px solid #cfd4da;
        border-radius: 14px;
        padding: 16px;
    }
    .today-metric .label { font-size:13px; color:#555; }
    .today-metric .value { font-size:22px; font-weight:700; color:#222; }

    /* ============================================================
       HEAT MAP TABLE
    ============================================================= */
    .heat-table { width:100%; border-collapse:separate; border-spacing:0 6px; }
    .heat-table th {
        text-align:left; padding:8px; font-size:13px; color:#475569;
    }
    .heat-row {
        background:#f9fafb;
        border:1px solid #d6dade;
        border-radius:14px;
        box-shadow:0 2px 6px rgba(0,0,0,0.05);
    }
    .heat-row td {
        padding:10px; color:#1e293b; border-bottom:1px solid #eceff2;
    }
    .heat-row td:last-child { border-bottom:none; }

    /* ============================================================
       ACTIVITY + INVENTORY
    ============================================================= */
    .bottom-grid {
        display:grid; gap:18px; grid-template-columns:1fr 1fr;
    }

    .activity-item,
    .inventory-item {
        background: #edf0f3;
        border: 1px solid #d0d5db;
        border-radius: 14px;
        padding: 14px;
        display:flex; gap:12px; margin-bottom:8px;
    }

    .activity-icon {
        width:38px; height:38px;
        background:#cfe6ff;
        border-radius:999px;
        color:#0a4c88;
        display:flex; justify-content:center; align-items:center;
        font-size:16px;
    }

    .inventory-thumb {
        width:50px; height:50px;
        border-radius:12px; overflow:hidden;
    }
    .inventory-thumb img { width:100%; height:100%; object-fit:cover; }

    .status-badge {
        padding:4px 10px;
        border-radius:999px;
        font-size:11px; font-weight:600;
        text-transform:capitalize;
    }
    .status-completed { background:#c9f7d9; color:#0a7a2c; }
    .status-pending   { background:#fff3b0; color:#a07800; }
    .status-cancelled { background:#ffd2d2; color:#a32121; }

    @media(max-width:900px){
        .today-grid, .bottom-grid { grid-template-columns:1fr; }
    }
</style>


<!-- ============================================================
     CURVED WATER BACKGROUND
============================================================= -->
<div class="water-bg">
    <svg class="wave-svg" viewBox="0 0 1440 320">
        <path fill="#bde0fe" fill-opacity="1"
              d="M0,160L60,144C120,128,240,96,360,112C480,128,600,192,720,186.7C840,181,960,107,1080,112C1200,117,1320,203,1380,245.3L1440,288L1440,0
                 L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0
                 C240,0,120,0,60,0L0,0Z">
        </path>
    </svg>
</div>


<div class="dashboard-wrapper">

    <!-- HERO IMAGE (YOUR IMAGE HERE) -->
    <div class="hero-image">
        <img src="{{ asset('images/ownerDashboard.png') }}" alt="Carwash Banner">
    </div>

    <!-- HERO PANEL -->
    <div class="hero">
        <div>
            <div class="hero-title">Welcome, {{ Auth::user()->UserName }} 👋</div>
            <div class="hero-subtitle">AutoShineX performance summary</div>
        </div>
        <div class="hero-meta">
            <div class="pill">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</div>
            Bookings Today: <strong>{{ $todayBookings }}</strong><br>
            Revenue: <strong>RM {{ number_format($todayRevenue,2) }}</strong>
        </div>
    </div>


    <!-- KPI CARDS -->
    <div class="kpi-grid">

        <div class="kpi-card primary">
            <div class="kpi-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="kpi-label">Total Bookings</div>
            <div class="kpi-value kpi-counter" data-target="{{ $totalBookings }}">0</div>
            <div class="kpi-meta">
                <span>Pending: {{ $pendingBookings }}</span>
                <span class="kpi-pill">{{ $todayBookings }} today</span>
            </div>
        </div>

        <div class="kpi-card success">
            <div class="kpi-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="kpi-label">Total Revenue</div>
            <div class="kpi-value kpi-counter" data-target="{{ (int)$totalRevenue }}">0</div>
            <div class="kpi-meta">
                <span>{{ $verifiedPayments }} verified</span>
                <span class="kpi-pill">Today RM {{ number_format($todayRevenue,2) }}</span>
            </div>
        </div>

        <div class="kpi-card purple">
            <div class="kpi-icon"><i class="fa-solid fa-layer-group"></i></div>
            <div class="kpi-label">Services</div>
            <div class="kpi-value">{{ $activeServices }}</div>
            <div class="kpi-meta">
                <span>Unique: {{ $todayUniqueCustomers }}</span>
                <span class="kpi-pill">{{ $todayCompletedBookings }} done</span>
            </div>
        </div>

        <div class="kpi-card orange">
            <div class="kpi-icon"><i class="fa-solid fa-box-open"></i></div>
            <div class="kpi-label">Inventory Items</div>
            <div class="kpi-value">{{ $totalProducts }}</div>
            <div class="kpi-meta">
                <span>Low: {{ $lowStockCount }}</span>
                <span class="kpi-pill">Check stock</span>
            </div>
        </div>

    </div>


    <!-- TODAY OVERVIEW -->
    <div class="today-grid">

        <div class="panel">
            <div class="panel-title">Today Overview</div>
            <div class="panel-subtitle">Daily performance summary</div>

            <div class="today-metrics">
                <div class="today-metric">
                    <span class="label">Bookings</span>
                    <span class="value">{{ $todayBookings }}</span>
                </div>
                <div class="today-metric">
                    <span class="label">Completed</span>
                    <span class="value">{{ $todayCompletedBookings }}</span>
                </div>
                <div class="today-metric">
                    <span class="label">Pending</span>
                    <span class="value">{{ $todayPendingBookings }}</span>
                </div>
                <div class="today-metric">
                    <span class="label">Revenue</span>
                    <span class="value">RM {{ number_format($todayRevenue,2) }}</span>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title">System Health</div>
            <div class="panel-subtitle">Payments • Services • Stock</div>

            <div class="today-metrics">
                <div class="today-metric">
                    <span class="label">Payments</span>
                    <span class="value">{{ $totalPayments }}</span>
                </div>
                <div class="today-metric">
                    <span class="label">Verified</span>
                    <span class="value">{{ $verifiedPayments }}</span>
                </div>
                <div class="today-metric">
                    <span class="label">Active Services</span>
                    <span class="value">{{ $activeServices }}</span>
                </div>
                <div class="today-metric">
                    <span class="label">Low Stock</span>
                    <span class="value">{{ $lowStockCount }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- HEAT MAP -->
    <div class="panel">
        <div class="panel-title">7-Day Booking Heat Map</div>
        <div class="panel-subtitle">Booking trends this week</div>

        <table class="heat-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Pending</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($weekData as $w)
                <tr class="heat-row">
                    <td>{{ $w['date'] }}</td>
                    <td>{{ $w['pending'] }}</td>
                    <td>{{ $w['completed'] }}</td>
                    <td>{{ $w['cancelled'] }}</td>
                    <td><strong>{{ $w['total'] }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <!-- ACTIVITY + INVENTORY -->
    <div class="bottom-grid">

        <!-- Activity -->
        <div class="panel">
            <div class="panel-title">Recent Activity</div>
            <div class="panel-subtitle">Latest bookings</div>

            @foreach($recentBookings as $b)
            <div class="activity-item">
                <div class="activity-icon"><i class="fa-solid fa-car"></i></div>

                <div style="flex:1;">
                    <div style="font-weight:600; color:#1b2938">
                        {{ $b->customer->CustomerName ?? 'Unknown' }}
                    </div>
                    <div style="font-size:12px; color:#6b7280">
                        {{ $b->service->name ?? 'Service' }} •
                        {{ \Carbon\Carbon::parse($b->BookingDate)->format('d M') }},
                        {{ substr($b->BookingTime,0,5) }}
                    </div>
                </div>

                <span class="status-badge status-{{ strtolower($b->BookingStatus) }}">
                    {{ $b->BookingStatus }}
                </span>
            </div>
            @endforeach
        </div>

        <!-- Inventory -->
        <div class="panel">
            <div class="panel-title">Inventory</div>
            <div class="panel-subtitle">Stock overview</div>

            @foreach($products as $p)
            <div class="inventory-item">
                <div class="inventory-thumb">
                    @if($p->Image)
                        <img src="{{ asset('storage/'.$p->Image) }}">
                    @else
                        <img src="https://via.placeholder.com/60x60?text=No+Img">
                    @endif
                </div>

                <div style="flex:1;">
                    <div style="font-weight:600; color:#1b2938">
                        {{ $p->ProductName }}
                    </div>
                    <div style="font-size:12px; color:#6b7280">
                        {{ $p->Category }} • RM{{ number_format($p->Price,2) }}
                    </div>
                </div>

                <div style="
                    font-weight:700;
                    color:{{ $p->StockQuantity<=5 ? '#a32121' : '#0a7a2c' }}">
                    {{ $p->StockQuantity }}
                </div>
            </div>
            @endforeach
        </div>

    </div>

</div>


<!-- KPI COUNTER -->
<script>
document.querySelectorAll('.kpi-counter').forEach(kpi=>{
    let target = +kpi.dataset.target;
    let current = 0;
    let step = Math.max(1, Math.floor(target/60));
    function update(){
        current += step;
        if(current >= target){
            kpi.textContent = target.toLocaleString();
        } else {
            kpi.textContent = current.toLocaleString();
            requestAnimationFrame(update);
        }
    }
    update();
});
</script>

@endsection
