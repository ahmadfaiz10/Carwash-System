@extends('layouts.customer')

@section('title', 'Services & Packages')

@section('content')

<style>
/* ================= PAGE BASE ================= */
body {
    background: #f8fafc !important;
    font-family: 'Poppins', sans-serif;
}

/* ================= FULL WIDTH WRAPPER ================= */
.services-wrapper {
    width: 100%;
    max-width: none;
    padding: 0 24px 40px;
}

/* ================= HEADER ================= */
.services-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 26px;
}

.services-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
}

.services-subtitle {
    font-size: 14px;
    color: #475569;
    max-width: 520px;
    margin-top: 6px;
}

/* ================= STATS ================= */
.services-stats {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 16px;
    min-width: 140px;
}

.stat-label {
    font-size: 11px;
    color: #64748b;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
}

/* ================= TOOLBAR ================= */
.services-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    width: 100%;
    max-width: 320px;
}

.search-box input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border-radius: 999px;
    border: 1px solid #cbd5f5;
    font-size: 13px;
}

.search-box i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #94a3b8;
}

/* ================= GRID ================= */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 22px;
}

/* ================= CARD ================= */
.service-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 34px rgba(15,23,42,0.08);
    border-color: #38bdf8;
}

/* ================= ICON ================= */
.service-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: #0ea5e9;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

/* ================= TEXT ================= */
.service-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
}

.service-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.4;
}

/* ================= FOOTER ================= */
.service-footer {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.service-tag {
    font-size: 11px;
    font-weight: 600;
    color: #0284c7;
    background: #e0f2fe;
    padding: 4px 10px;
    border-radius: 999px;
}

.service-arrow {
    font-size: 18px;
    color: #0ea5e9;
}

/* ================= EMPTY ================= */
.services-empty {
    text-align: center;
    padding: 100px 0;
    color: #64748b;
}
</style>

<div class="services-wrapper">

    {{-- HEADER --}}
    <div class="services-header">
        <div>
            <div class="services-title">Car Wash Services & Packages</div>
            <div class="services-subtitle">
                Choose a category to explore professional car wash and detailing services.
            </div>
        </div>

        {{-- STATS --}}
        <div class="services-stats">
            <div class="stat-card">
                <div class="stat-label">Categories</div>
                <div class="stat-value">{{ $categories->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Available Services</div>
                <div class="stat-value">Multiple</div>
            </div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="services-toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search service category…" disabled>
        </div>
    </div>

    {{-- GRID --}}
    @if ($categories->isEmpty())
        <div class="services-empty">
            <i class="bi bi-inbox" style="font-size:50px;"></i>
            <p class="mt-3">No service categories available</p>
        </div>
    @else
        <div class="services-grid">
            @foreach ($categories as $cat)
                <a href="{{ route('customer.services.byCategory', $cat->id) }}"
                   class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-car-front-fill"></i>
                    </div>

                    <div class="service-title">
                        {{ $cat->name }}
                    </div>

                    <div class="service-desc">
                        Explore all services and packages available in this category.
                    </div>

                    <div class="service-footer">
                        <span class="service-tag">
                            <i class="bi bi-list-check"></i>
                            View services
                        </span>
                        <span class="service-arrow">
                            <i class="bi bi-arrow-right-circle"></i>
                        </span>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

</div>

@endsection
