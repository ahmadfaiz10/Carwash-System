@extends('layouts.customer')

@section('title', 'Services')

@section('content')

<style>
/* ================= PAGE BASE ================= */
body {
    background: #f8fafc !important;
    font-family: 'Poppins', sans-serif;
}

/* ================= LAYOUT ================= */
.svc-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    width: 100%;
}

/* ================= SIDEBAR ================= */
.svc-sidebar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    position: sticky;
    top: 96px;
    height: fit-content;
}

.svc-sidebar-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.svc-sidebar-sub {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 14px;
}

.svc-sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13px;
    text-decoration: none;
    color: #334155;
    border: 1px solid transparent;
    transition: 0.2s ease;
}

.svc-sidebar a:hover {
    background: #f1f5f9;
}

.svc-sidebar a.active {
    background: #e0f2fe;
    border-color: #38bdf8;
    color: #0369a1;
    font-weight: 600;
}

/* ================= RIGHT CONTENT ================= */
.svc-content {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* ================= HEADER ================= */
.svc-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 16px;
}

.svc-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
}

.svc-subtitle {
    font-size: 14px;
    color: #64748b;
    max-width: 520px;
}

.svc-tag {
    background: #e0f2fe;
    color: #0369a1;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 999px;
}

/* ================= GRID ================= */
.svc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 22px;
}

/* ================= SERVICE CARD ================= */
.service-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: 0.2s ease;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 34px rgba(15,23,42,0.08);
    border-color: #38bdf8;
}

/* ================= IMAGE ================= */
.service-image {
    width: 100%;
    height: 180px;
    background: #e2e8f0;
    overflow: hidden;
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ================= BODY ================= */
.service-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

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

/* ================= META ================= */
.service-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 6px;
}

.service-price {
    font-size: 20px;
    font-weight: 800;
    color: #0284c7;
}

.service-duration {
    font-size: 11px;
    font-weight: 600;
    color: #0369a1;
    background: #e0f2fe;
    padding: 4px 10px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* ================= FOOTER ================= */
.service-footer {
    margin-top: auto;
    padding: 14px 16px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.service-note {
    font-size: 12px;
    color: #64748b;
}

.book-btn {
    background: linear-gradient(135deg,#0ea5e9,#0284c7);
    color: #ffffff;
    padding: 7px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 10px 22px rgba(14,165,233,0.35);
}

.book-btn:hover {
    box-shadow: 0 14px 30px rgba(14,165,233,0.55);
}
</style>

<div class="svc-layout">

    {{-- SIDEBAR --}}
    <aside class="svc-sidebar">
        <div class="svc-sidebar-title">Service Categories</div>
        <div class="svc-sidebar-sub">
            Browse packages by category
        </div>

        @foreach ($categories as $cat)
            <a href="{{ route('customer.services.byCategory', $cat->id) }}"
               class="{{ $selectedCategory == $cat->id ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                {{ $cat->name }}
            </a>
        @endforeach
    </aside>

    {{-- CONTENT --}}
    <section class="svc-content">

        <div class="svc-header">
            <div>
                <div class="svc-title">Available Services</div>
                <div class="svc-subtitle">
                    Select a service below to view details and proceed with booking.
                </div>
            </div>

            <div class="svc-tag">
                <i class="bi bi-droplet-half"></i>
                {{ optional($categories->firstWhere('id', $selectedCategory))->name }}
            </div>
        </div>

        {{-- SERVICES GRID --}}
        @if ($services->isEmpty())
            <div class="text-center py-5 text-slate-500">
                No services available in this category.
            </div>
        @else
            <div class="svc-grid">
                @foreach ($services as $service)
                    <div class="service-card">

                        <div class="service-image">
                            <img src="{{ $service->image
                                ? asset('storage/'.$service->image)
                                : asset('images/default-service.png') }}">
                        </div>

                        <div class="service-body">
                            <div class="service-title">{{ $service->name }}</div>
                            <div class="service-desc">
                                {{ $service->description }}
                            </div>

                            <div class="service-meta">
                                <div class="service-price">
                                    RM {{ number_format($service->price,2) }}
                                </div>
                                <div class="service-duration">
                                    <i class="bi bi-stopwatch"></i>
                                    {{ $service->duration }} mins
                                </div>
                            </div>
                        </div>

                        <div class="service-footer">
                            <div class="service-note">
                                Suitable for most vehicles
                            </div>

                            <a href="{{ route('customer.bookServices', $service->id) }}"
                               class="book-btn">
                                <i class="bi bi-calendar-plus"></i>
                                Book Now
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </section>

</div>

@endsection
