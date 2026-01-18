@extends('layouts.admin')

@section('title', 'List of Services')

@section('content')

<style>
body{
    background:#f8fafc !important;
    font-family:'Poppins',sans-serif;
}

/* ================= PAGE INTRO ================= */
.page-header{
    margin-bottom:26px;
}
.page-header h2{
    font-size:26px;
    font-weight:700;
    color:#0f172a;
}
.page-header p{
    color:#64748b;
    font-size:14px;
    margin-top:4px;
}

/* ================= LAYOUT ================= */
.service-layout{
    display:grid;
    grid-template-columns:260px 1fr;
    gap:24px;
}

/* ================= SIDEBAR ================= */
.package-sidebar{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:16px;
}

.package-title{
    font-size:14px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:12px;
}

.sidebar-list{
    list-style:none;
    padding:0;
    margin:0;
}

.sidebar-list li{
    margin-bottom:6px;
}

.sidebar-list a{
    display:block;
    padding:10px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    color:#334155;
}

.sidebar-list a:hover{
    background:#f1f5f9;
}

.sidebar-list .active a{
    background:#2563eb;
    color:#ffffff;
}

/* ================= SUMMARY ================= */
.summary-box{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:18px;
    margin-bottom:20px;
}

.summary-row{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
}

.summary-item{
    font-size:14px;
    color:#475569;
}

.summary-item strong{
    font-size:20px;
    color:#0f172a;
}

/* ================= HEADER ================= */
.page-subheader{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:14px;
}

.page-subheader h4{
    margin:0;
    font-size:20px;
    font-weight:700;
    color:#0f172a;
}

.helper-text{
    font-size:13px;
    color:#64748b;
    margin-bottom:18px;
}

.add-service-btn{
    background:#16a34a;
    color:#ffffff;
    padding:8px 14px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
}

.add-service-btn:hover{
    background:#15803d;
}

/* ================= SERVICE CARD ================= */
.service-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:18px;
    display:grid;
    grid-template-columns:140px 1fr;
    gap:18px;
    margin-bottom:16px;
}

/* IMAGE */
.service-image{
    width:140px;
    height:140px;
    border-radius:10px;
    border:1px solid #e5e7eb;
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.service-image img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.no-image{
    font-size:28px;
    color:#94a3b8;
}

/* INFO */
.service-name{
    font-size:18px;
    font-weight:700;
    color:#0f172a;
}

.service-desc{
    font-size:14px;
    color:#475569;
    margin:6px 0 10px;
}

/* META */
.service-meta{
    display:flex;
    gap:12px;
    margin-bottom:14px;
}

.price-tag{
    background:#e0f2fe;
    color:#075985;
    font-weight:700;
    padding:6px 12px;
    border-radius:8px;
    font-size:14px;
}

.duration-tag{
    background:#f1f5f9;
    color:#334155;
    font-weight:600;
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
}

/* ACTIONS */
.service-actions{
    display:flex;
    gap:10px;
}

.btn-edit{
    background:#2563eb;
    color:#ffffff;
    padding:7px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}

.btn-edit:hover{
    background:#1d4ed8;
}

.btn-delete{
    background:#ffffff;
    border:1px solid #dc2626;
    color:#dc2626;
    padding:7px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
}

.btn-delete:hover{
    background:#dc2626;
    color:#ffffff;
}

/* EMPTY */
.empty-state{
    background:#ffffff;
    border:1px dashed #cbd5e1;
    border-radius:14px;
    padding:40px;
    text-align:center;
    color:#64748b;
}
</style>

{{-- ================= PAGE INTRO ================= --}}
<div class="page-header">
    <h2>Service Management</h2>
    <p>Manage all car wash services, pricing, and duration offered to customers.</p>
</div>

<div class="service-layout">

    {{-- LEFT SIDEBAR --}}
    <div class="package-sidebar">
        <div class="package-title">Service Categories</div>

        <ul class="sidebar-list">
            @foreach ($categories as $category)
                <li class="{{ $selectedCategory == $category->id ? 'active' : '' }}">
                    <a href="{{ route('admin.services.byCategory', $category->id) }}">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- RIGHT CONTENT --}}
    <div>

        {{-- SUMMARY --}}
        <div class="summary-box">
            <div class="summary-row">
                <div class="summary-item">
                    Total Services<br>
                    <strong>{{ $services->count() }}</strong>
                </div>
                <div class="summary-item">
                    Category ID<br>
                    <strong>{{ $selectedCategory }}</strong>
                </div>
                <div class="summary-item">
                    Management Scope<br>
                    <strong>Pricing & Duration</strong>
                </div>
            </div>
        </div>

        <div class="page-subheader">
            <h4>Booking Services & Packages</h4>

            <a href="{{ route('admin.services.add', ['category' => $selectedCategory]) }}"
               class="add-service-btn">
                + Add Service
            </a>
        </div>

        <div class="helper-text">
            Configure service details such as pricing, duration, and service description. These services will be available for customer bookings.
        </div>

        {{-- SERVICES --}}
        @forelse($services as $service)

            <div class="service-card">

                <div class="service-image">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}">
                    @else
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="service-name">{{ $service->name }}</div>

                    <div class="service-desc">
                        {{ $service->description }}
                    </div>

                    <div class="service-meta">
                        <span class="price-tag">
                            RM {{ number_format($service->price,2) }}
                        </span>

                        <span class="duration-tag">
                            {{ $service->duration }} mins
                        </span>
                    </div>

                    <div class="service-actions">
                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-edit">
                            Edit
                        </a>

                        <form action="{{ route('admin.services.delete', $service->id) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this service?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-delete">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        @empty

            <div class="empty-state">
                <h4>No services created</h4>
                <p>Start by adding services to this category so customers can book them.</p>
            </div>

        @endforelse

    </div>

</div>

@endsection
