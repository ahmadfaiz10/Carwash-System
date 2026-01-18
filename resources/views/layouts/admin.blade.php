<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','AutoShineX')</title>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root{
    --primary:#1E88E5;
    --primary-dark:#1565C0;
    --sky:#48C6FF;
    --sidebar-bg:#102032;
}

*{ box-sizing:border-box }

html,body{
    margin:0;
    height:100%;
    font-family:Poppins,sans-serif;
    background:#ffffff;
}

/* ================= SIDEBAR ================= */
.sidebar{
    position:fixed;
    top:0; left:0;
    width:260px;
    height:100vh;
    background:var(--sidebar-bg);
    display:flex;
    flex-direction:column;
    z-index:1000;
}

/* SCROLLABLE MENU */
.sidebar-top{
    flex:1;
    overflow-y:auto;
    padding:28px 22px 10px;
}

/* FIXED BOTTOM */
.sidebar-bottom{
    padding:18px 22px;
    border-top:1px solid rgba(255,255,255,.12);
}

/* HEADER */
.sidebar-header{
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:28px;
}
.sidebar-header img{ width:48px;height:48px }
.sidebar-header h1{
    color:#fff;
    font-size:20px;
    margin:0;
}

/* MENU */
.menu-title{
    font-size:12px;
    color:#94a3b8;
    text-transform:uppercase;
    margin:18px 0 10px;
}

.side-link{
    display:flex;
    align-items:center;
    gap:14px;
    padding:12px 14px;
    border-radius:12px;
    text-decoration:none;
    font-size:14px;
    color:#d7e6f4;
    margin-bottom:8px;
}

.side-link i{
    font-size:18px;
    color:var(--sky);
}

.side-link:hover{
    background:rgba(255,255,255,.08);
}

.side-link.active{
    background:linear-gradient(135deg,var(--primary),var(--primary-dark));
    color:#fff;
}
.side-link.active i{ color:#fff }

/* ================= PAGE ================= */
.page-wrapper{
    margin-left:260px;
    min-height:100vh;
    display:flex;
    flex-direction:column;
    background:#ffffff;
}

/* TOP HEADER */
.page-header{
    padding:26px 40px;
    border-bottom:1px solid #e5e7eb;
    background:#ffffff;
}

.page-header h2{
    margin:0;
    font-size:26px;
    font-weight:700;
    color:#0f172a;
}

/* FULL WIDTH CONTENT */
.page-content{
    flex:1;
    padding:30px 40px;
    background:#ffffff;
}

/* FOOTER */
footer{
    padding:18px 0 14px;
    text-align:center;
    font-size:14px;
    color:#64748b;
    border-top:1px solid #e5e7eb;
}

/* MOBILE */
@media(max-width:1100px){
    .sidebar{ transform:translateX(-100%) }
    .sidebar.show{ transform:translateX(0) }
    .page-wrapper{ margin-left:0 }
    .page-header,
    .page-content{ padding:20px }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside id="sidebar" class="sidebar">

    <div class="sidebar-top">

        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}">
            <h1>AutoShineX</h1>
        </div>

        <div class="menu-title">Main Menu</div>

        <a href="{{ route('admin.dashboard') }}" class="side-link {{ request()->routeIs('admin.dashboard')?'active':'' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('admin.bookings') }}" class="side-link {{ request()->routeIs('admin.bookings*')?'active':'' }}">
            <i class="bi bi-calendar-check"></i> Manage Booking
        </a>

        <a href="{{ route('admin.services') }}" class="side-link {{ request()->routeIs('admin.services*')?'active':'' }}">
            <i class="bi bi-tools"></i> Manage Services
        </a>

        <a href="{{ route('admin.products.categories') }}" class="side-link {{ request()->routeIs('admin.products*')?'active':'' }}">
            <i class="bi bi-box-seam"></i> Manage Products
        </a>

        <a href="{{ route('admin.product.purchases') }}" class="side-link {{ request()->routeIs('admin.product.purchases')?'active':'' }}">
            <i class="bi bi-bag-check"></i> Product Purchases
        </a>

        <a href="{{ route('admin.payments') }}" class="side-link {{ request()->routeIs('admin.payments*')?'active':'' }}">
            <i class="bi bi-cash-coin"></i> Payments
        </a>

        <a href="{{ route('staff.index') }}" class="side-link {{ request()->routeIs('staff.*')?'active':'' }}">
            <i class="bi bi-people"></i> Manage Staff
        </a>

    </div>

    <div class="sidebar-bottom">
        <div class="menu-title">User</div>

        <a href="{{ route('admin.profile') }}" class="side-link">
            <i class="bi bi-person-circle"></i> Profile
        </a>

        <a href="#" class="side-link"
           onclick="event.preventDefault(); if(confirm('Logout now?')) document.getElementById('logout-form').submit();">
            <i class="bi bi-door-open"></i> Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

</aside>

<!-- PAGE -->
<div class="page-wrapper">

    

    <main class="page-content">
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} AutoShineX Carwash Center • All Rights Reserved
    </footer>

</div>

</body>
</html>
