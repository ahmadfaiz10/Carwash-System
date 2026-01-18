<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','AutoShineX')</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background:#ffffff;
            font-family:Poppins, sans-serif;
            color:#0f172a;
            overflow-x:hidden;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(56,189,248,0.15);
            border-left: 3px solid #38bdf8;
        }
    </style>
</head>

<body>

{{-- ================= SIDEBAR ================= --}}
<aside class="fixed inset-y-0 left-0 w-64 bg-[#0b1220] text-white flex flex-col z-40">

    {{-- LOGO --}}
    <div class="px-6 py-6 border-b border-slate-700">
        <div class="flex flex-col items-center gap-3">
            <img src="{{ asset('images/logo.png') }}"
                 class="w-14 h-14 rounded-xl border border-slate-600 object-contain">

            <div class="text-center leading-tight">
                <div class="font-extrabold text-lg tracking-wide">AutoShineX</div>
                <div class="text-xs text-slate-400">Customer Portal</div>
            </div>
        </div>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 px-3 py-5 space-y-1 text-sm font-medium">

        <a href="{{ route('home') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-speedometer text-lg"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('customer.bookings') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-calendar-check text-lg"></i>
            <span>My Booking</span>
        </a>

        <a href="{{ route('customer.services') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-droplet-half text-lg"></i>
            <span>Services</span>
        </a>

        <div class="mt-5 mb-2 px-4 text-[11px] text-slate-400 tracking-wider uppercase">
            Products
        </div>

        <a href="{{ route('customer.products.categories') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-cart-plus text-lg"></i>
            <span>Buy Products</span>
        </a>

        <a href="{{ route('customer.mypurchase') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-receipt text-lg"></i>
            <span>My Purchases</span>
        </a>

        <a href="{{ route('customer.mypayments') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-credit-card text-lg"></i>
            <span>Payments</span>
        </a>

        <a href="{{ route('customer.profile') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-md">
            <i class="bi bi-person-circle text-lg"></i>
            <span>Profile</span>
        </a>
    </nav>

    {{-- USER + LOGOUT --}}
    <div class="px-4 py-4 border-t border-slate-700 bg-[#0a1020]">

        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-sky-500 flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->FullName ?? 'A',0,1)) }}
            </div>

            <div class="leading-tight">
                <div class="text-sm font-semibold">
                    {{ Auth::user()->FullName ?? 'Customer' }}
                </div>
                <div class="text-xs text-slate-400">Logged in</div>
            </div>
        </div>

        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center gap-3 px-4 py-2 rounded-md text-red-300 hover:bg-red-900/30 transition">
            <i class="bi bi-box-arrow-right"></i>
            <span class="font-semibold">Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

</aside>

{{-- ================= MAIN ================= --}}
<div class="ml-64 min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between sticky top-0 z-30">
        <h1 class="text-lg font-semibold">@yield('title')</h1>

        <a href="{{ route('customer.profile') }}"
           class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold">
            {{ strtoupper(substr(Auth::user()->FullName ?? 'A',0,1)) }}
        </a>
    </header>

    <main class="flex-1 px-6 py-6">
        @yield('content')
    </main>

    <footer class="text-center text-xs text-slate-400 py-4 border-t">
        © {{ date('Y') }} AutoShineX — All Rights Reserved.
    </footer>

</div>

</body>
</html>
