<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Panel - AutoShineX')</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #1E88E5;
            --primary-dark: #1565C0;
            --sky: #48C6FF;

            --sidebar-bg: #102032;
            --page-bg: #F2F6FA;

            --card-bg: white;
            --card-border: #d9e2ec;

            --text-light: #e2e8f0;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: Poppins, sans-serif;
            background: var(--page-bg);
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            display: flex;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
    width: 260px;
    height: 100vh;
    background: var(--sidebar-bg);
    padding: 28px 22px;

    position: fixed;
    top: 0; left: 0;
    z-index: 4000;

    display: flex;
    flex-direction: column;

    transition: width .25s ease, transform .25s ease;
    overflow: hidden;
}

        /* DESKTOP collapse (when toggled) */
        .sidebar.collapse-desktop {
            width: 80px;
            padding: 28px 10px;
        }
        .sidebar.collapse-desktop .sidebar-text,
        .sidebar.collapse-desktop .menu-title {
            display: none;
        }

        /* MOBILE: hidden by default, slide in when .show */
        @media(max-width:1100px){
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }

        .sidebar-header img {
            height: 48px;
            width: 48px;
        }

        .sidebar-header h1 {
            margin: 0;
            color: white;
            font-size: 20px;
            font-weight: 700;
        }

        .menu-title {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;

            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 8px;

            font-size: 14px;
            font-weight: 500;

            color: #d7e6f4;
            transition: background .2s ease, box-shadow .2s ease;
        }

        .side-link:hover {
            background: rgba(255,255,255,0.08);
        }

        .side-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.25);
        }

        .side-link i {
            font-size: 18px;
            color: var(--sky);
        }
        .side-link.active i {
            color: white;
        }

        .sidebar-text {
            /* just marker for collapsible text; no extra styles needed */
        }

        /* ===================== MOBILE OVERLAY ===================== */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 3500;
        }

        /* ===================== MENU BUTTON (HAMBURGER) ===================== */
        .menu-toggle {
            position: fixed;
            top: 18px; left: 18px;
            width: 48px; height: 48px;

            background: var(--primary);
            color: white;
            border-radius: 14px;

            display: none;
            align-items: center;
            justify-content: center;

            font-size: 24px;
            cursor: pointer;
            z-index: 5000;
        }

        @media(max-width:1100px){
            .menu-toggle { display: flex; }
        }

        /* ===================== PAGE WRAPPER ===================== */
        .page-wrapper {
    flex: 1;
    min-height: 100vh;

    margin-left: 260px;
    padding: 35px 50px;

    display: flex;
    flex-direction: column;

    transition: margin-left .25s ease;
}


        /* DESKTOP collapse: adjust content margin */
        .sidebar.collapse-desktop ~ .page-wrapper {
            margin-left: 80px;
        }

        /* MOBILE full width */
        @media(max-width:1100px){
            .page-wrapper {
                margin-left: 0 !important;
                padding: 25px 20px;
                width: 100%;
            }
        }

        /* ===================== HEADER BAR ===================== */
        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 22px 28px;
            border-radius: 18px;
            color: white;
            margin-bottom: 28px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .page-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        /* ===================== CONTENT CARD ===================== */
        .content-card {
            background: var(--card-bg);
            padding: 28px;
            border-radius: 22px;
            border: 1px solid var(--card-border);
            box-shadow: 0 10px 32px rgba(0,0,0,0.08);

            width: 100%;
            box-sizing: border-box;
        }

        @media(max-width:600px){
            .content-card { padding: 18px; }
        }

        /* ===================== FOOTER ===================== */
        footer {
            margin-top: 20px;
            padding-top: 18px;
            text-align: center;
            color: #7b8794;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- MOBILE OVERLAY -->
    <div id="overlay" class="overlay" onclick="closeSidebar()"></div>

    <!-- MENU TOGGLE BUTTON -->
    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </div>

    <!-- SIDEBAR -->
   <aside id="sidebar" class="sidebar">

    {{-- TOP --}}
    <div>
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h1 class="sidebar-text">AutoShineX</h1>
        </div>

        <div class="menu-title sidebar-text">Staff Menu</div>

        <a href="{{ route('staff.dashboard') }}"
           class="side-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="{{ route('staff.bookings') }}"
           class="side-link {{ request()->routeIs('staff.bookings*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span class="sidebar-text">Bookings</span>
        </a>

        <a href="{{ route('staff.payments') }}"
           class="side-link {{ request()->routeIs('staff.payments*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span class="sidebar-text">Payments</span>
        </a>

        <a href="{{ route('staff.products') }}"
           class="side-link {{ request()->routeIs('staff.products') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span class="sidebar-text">Products</span>
        </a>

        <a href="{{ route('staff.profile') }}"
           class="side-link {{ request()->routeIs('staff.profile*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <span class="sidebar-text">Profile</span>
        </a>
    </div>

    {{-- BOTTOM (PUSHED DOWN) --}}
    <div style="margin-top:auto;">

        <div class="menu-title sidebar-text">Account</div>

        <a href="#"
           class="side-link"
           onclick="event.preventDefault(); if(confirm('Logout now?')) document.getElementById('logout-form').submit();">
            <i class="bi bi-door-open"></i>
            <span class="sidebar-text">Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>

        <div class="sidebar-text"
             style="margin-top:20px; font-size:12px; color:#94a3b8; text-align:center;">
            © {{ date('Y') }} AutoShineX<br>
            Staff Panel
        </div>
    </div>

</aside>


    <!-- PAGE WRAPPER -->
    <div class="page-wrapper">

        <div class="page-header">
            <h2>@yield('title')</h2>
        </div>

       <div class="content-card">
    @yield('content')
</div>

<footer style="margin-top:auto;">
    © {{ date('Y') }} AutoShineX • Staff Panel
</footer>

    </div>

<script>
function toggleSidebar(){
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (window.innerWidth < 1100) {
        // MOBILE: slide in/out
        sidebar.classList.toggle('show');
        overlay.style.display = sidebar.classList.contains('show') ? "block" : "none";
    } else {
        // DESKTOP: collapse / expand width
        sidebar.classList.toggle('collapse-desktop');
    }
}

function closeSidebar(){
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.remove('show');
    overlay.style.display = "none";
}
</script>

</body>
</html>
