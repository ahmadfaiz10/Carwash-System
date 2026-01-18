@extends('layouts.staff')

@section('title', 'Staff Products - AutoShineX')
@section('page_title', 'Products Overview')
@section('page_subtitle', 'View product stock and categories (read-only)')

@section('content')

@php
    $totalProducts     = $products->count();
    $categoriesCount   = $products->pluck('Category')->filter()->unique()->count();
    $lowStockCount     = $products->where('StockQuantity', '<=', 5)->count();
    $outOfStockCount   = $products->where('AvailabilityStatus', 'Out of Stock')->count();
@endphp

<style>
    .products-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }
    @media (max-width: 900px) {
        .products-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 550px) {
        .products-stat-grid {
            grid-template-columns: 1fr;
        }
    }

    .prod-stat-card {
        border-radius: 18px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        box-shadow: 0 8px 20px rgba(15,23,42,0.08);
        border: 1px solid #e2e8f0;
    }
    .prod-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #ffffff;
    }
    .icon-blue   { background: linear-gradient(135deg,#2563EB,#38BDF8); }
    .icon-purple { background: linear-gradient(135deg,#7C3AED,#A855F7); }
    .icon-amber  { background: linear-gradient(135deg,#F59E0B,#FACC15); }
    .icon-red    { background: linear-gradient(135deg,#DC2626,#FB7185); }

    .prod-stat-body h4 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
    }
    .prod-stat-body span {
        display: block;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }
    .prod-stat-body small {
        font-size: 11px;
        color: #9ca3af;
    }

    .products-table-wrapper {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15,23,42,0.08);
        overflow: hidden;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .products-table thead {
        background: #0f172a;
        color: #e5e7eb;
    }

    .products-table th,
    .products-table td {
        padding: 9px 10px;
        text-align: left;
    }

    .products-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .stock-pill {
        padding: 3px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .stock-ok {
        background: #dcfce7;
        color: #166534;
    }
    .stock-low {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge {
        padding: 3px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .status-available   { background:#dcfce7; color:#166534; }
    .status-out         { background:#fee2e2; color:#b91c1c; }
    .status-discontinued{ background:#e5e7eb; color:#374151; }

    /* Responsive table to cards on very small screens */
    @media (max-width: 650px) {
        .products-table thead {
            display: none;
        }
        .products-table,
        .products-table tbody,
        .products-table tr,
        .products-table td {
            display: block;
            width: 100%;
        }
        .products-table tr {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 10px 14px;
        }
        .products-table td {
            padding: 4px 0;
        }
        .products-table td::before {
            content: attr(data-label);
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 1px;
        }
    }
</style>

{{-- ====== TOP SUMMARY CARDS ====== --}}
<div class="products-stat-grid">
    <div class="prod-stat-card">
        <div class="prod-stat-icon icon-blue">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="prod-stat-body">
            <h4>Total Products</h4>
            <span>{{ $totalProducts }}</span>
            <small>Active items in system</small>
        </div>
    </div>

    <div class="prod-stat-card">
        <div class="prod-stat-icon icon-purple">
            <i class="bi bi-grid-1x2"></i>
        </div>
        <div class="prod-stat-body">
            <h4>Categories</h4>
            <span>{{ $categoriesCount }}</span>
            <small>Product groups</small>
        </div>
    </div>

    <div class="prod-stat-card">
        <div class="prod-stat-icon icon-amber">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="prod-stat-body">
            <h4>Low Stock (≤ 5)</h4>
            <span>{{ $lowStockCount }}</span>
            <small>Items to monitor</small>
        </div>
    </div>

    <div class="prod-stat-card">
        <div class="prod-stat-icon icon-red">
            <i class="bi bi-slash-circle"></i>
        </div>
        <div class="prod-stat-body">
            <h4>Out of Stock</h4>
            <span>{{ $outOfStockCount }}</span>
            <small>Unavailable items</small>
        </div>
    </div>
</div>

{{-- ====== PRODUCTS TABLE ====== --}}
<div class="products-table-wrapper">
    <table class="products-table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price (RM)</th>
            <th>Stock</th>
            
        </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            @php
                $low = $product->StockQuantity <= 5;
                $status = $product->AvailabilityStatus ?? 'Available';

                $statusClass = 'status-available';
                if (strtolower($status) === 'out of stock') {
                    $statusClass = 'status-out';
                } elseif (strtolower($status) === 'discontinued') {
                    $statusClass = 'status-discontinued';
                }
            @endphp
            <tr>
                <td data-label="Product">
                    <strong>{{ $product->ProductName }}</strong>
                </td>

                <td data-label="Category">
                    {{ $product->Category ?? '-' }}
                </td>

                <td data-label="Price (RM)">
                    RM {{ number_format($product->Price, 2) }}
                </td>

                <td data-label="Stock">
                    <span class="stock-pill {{ $low ? 'stock-low' : 'stock-ok' }}">
                        {{ $product->StockQuantity }}
                        @if($low)
                            • Low
                        @endif
                    </span>
                </td>

               
            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding: 14px; text-align:center; color:#9ca3af;">
                    No products found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
