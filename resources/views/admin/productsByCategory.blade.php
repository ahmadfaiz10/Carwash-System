@extends('layouts.admin')

@section('title', $category)

@section('content')

<style>

/* ============================
   PAGE TITLE
=============================== */

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #0f172a;
}

.page-sub {
    font-size: 15px;
    color: #64748b;
    margin-bottom: 25px;
}



/* ============================
   TOP ACTION BAR
=============================== */

.top-actions {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 28px;
}

.btn-back,
.btn-add {
    padding: 12px 22px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Back Button */
.btn-back {
    background: #e2e8f0;
    color: #334155;
}
.btn-back:hover {
    background: #cbd5e1;
}

/* Add Button */
.btn-add {
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    color: white;
    box-shadow: 0 6px 18px rgba(30,136,229,0.35);
}
.btn-add:hover {
    background: linear-gradient(135deg, #1565C0, #0d47a1);
}



/* ============================
   KPI CARDS
=============================== */

.kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 22px;
    margin-bottom: 30px;
}

.kpi-card {
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    padding: 24px;
    border-radius: 20px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(0,0,0,0.18);
}

.kpi-card::after {
    content: "";
    position: absolute;
    right: -35px;
    top: -35px;
    width: 130px;
    height: 130px;
    background: rgba(255, 255, 255, 0.22);
    border-radius: 50%;
}

.kpi-label { font-size: 15px; opacity: 0.9; }
.kpi-value { font-size: 36px; font-weight: 700; }



/* ============================
   PRODUCT TABLE CONTAINER
=============================== */

.product-card {
    background: white;
    padding: 30px;
    border-radius: 25px;
    border: 1px solid #dbe3eb;
    box-shadow: 0 14px 28px rgba(0,0,0,0.06);
}



/* ============================
   FILTER BAR
=============================== */

.filter-bar {
    display: flex;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 15px;
}

.filter-bar input, 
.filter-bar select {
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
    background: #f1f5f9;
    transition: .25s;
}

.filter-bar input:focus, 
.filter-bar select:focus {
    background: white;
    border-color: #1E88E5;
    box-shadow: 0 0 0 4px rgba(30,136,229,0.25);
}



/* ============================
   TABLE DESIGN
=============================== */

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    min-width: 900px;
}

th {
    padding: 14px;
    background: #eef2f7;
    border-bottom: 2px solid #dce3ec;
    color: #334155;
    font-size: 15px;
    font-weight: 700;
}

td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
    color: #0f172a;
    font-size: 15px;
}

tr:hover td {
    background: #f9fafb;
}

/* Status badges */
.badge-active {
    background: #d1fae5;
    color: #065f46;
    padding: 6px 14px;
    border-radius: 999px;
}

.badge-low {
    background: #ffedd5;
    color: #a16207;
    padding: 6px 14px;
    border-radius: 999px;
}



/* ============================
   PRODUCT IMAGE
=============================== */

.prod-img {
    width: 58px;
    height: 58px;
    border-radius: 12px;
    overflow: hidden;
    background: #f1f1f1;
}

.prod-img img {
    width: 100%; height: 100%; object-fit: cover;
}



/* ============================
   ACTION BUTTONS (PRO)
=============================== */

.action-wrapper {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: .25s;
}

/* EDIT */
.btn-edit {
    border: 2px solid #1E88E5;
    background: #1E88E5;
    color: white;
}
.btn-edit:hover {
    background: #1565C0;
}

/* DELETE */
.btn-delete {
    border: 2px solid #dc2626;
    background: #dc2626;
    color: white;
}
.btn-delete:hover {
    background: #b91c1c;
}



/* ============================
   RESPONSIVE
=============================== */

@media(max-width: 620px){
    
    .btn-add, .btn-back {
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    .filter-bar input, 
    .filter-bar select {
        width: 100%;
    }

    .product-card { padding: 22px; }
}

</style>




<div>

    <!-- Page Title -->
    <div class="page-title">{{ $category }}</div>
    <div class="page-sub">Manage all products under this category.</div>


    <!-- Top Buttons -->
    <div class="top-actions">
        <a href="{{ route('admin.products.categories') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to Categories
        </a>

<a href="{{ route('admin.products.add') }}" class="btn-add">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>


    <!-- KPI Cards -->
    <div class="kpi-row">

        <div class="kpi-card">
            <div class="kpi-label">Total Items</div>
            <div class="kpi-value">{{ $products->count() }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Low Stock</div>
            <div class="kpi-value">{{ $products->where('StockQuantity','<=',5)->count() }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Average Price</div>
            <div class="kpi-value">RM {{ number_format($products->avg('Price'),2) }}</div>
        </div>

    </div>



    <!-- Product Table -->
    <div class="product-card">

        <div class="filter-bar">
            <input id="search" type="text" placeholder="Search product…">
            <select id="filterStatus">
                <option value="">All Status</option>
                <option value="active">Active</option>
            </select>
            <select id="filterStock">
                <option value="">All Stock</option>
                <option value="low">Low Stock (≤ 5)</option>
            </select>
        </div>

        <div class="table-wrapper">
            <table id="prodTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th style="width:300px;">Product</th>
                    <th>Price (RM)</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th style="width:180px;">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($products as $i => $p)
                <tr 
                    data-name="{{ strtolower($p->ProductName) }}"
                    data-status="{{ strtolower($p->AvailabilityStatus) }}"
                    data-stock="{{ $p->StockQuantity }}">

                    <td>{{ $i+1 }}</td>

                    <td>
                        <strong>{{ $p->ProductName }}</strong>
                        <div style="font-size:13px;color:#666;">
                            {{ $p->Description }}
                        </div>
                    </td>

                    <td>{{ number_format($p->Price, 2) }}</td>

                    <td>
                        @if($p->StockQuantity <= 5)
                            <span class="badge-low">{{ $p->StockQuantity }} Low</span>
                        @else
                            <span class="badge-active">{{ $p->StockQuantity }}</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge-active">{{ $p->AvailabilityStatus }}</span>
                    </td>

                    <td>
                        <div class="prod-img">
                            @if($p->Image)
                                <img src="{{ asset('storage/'.$p->Image) }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}">
                            @endif
                        </div>
                    </td>

                    <td>
                        <div class="action-wrapper">

                            <a href="{{ route('admin.products.edit',$p->ProductID) }}"
                               class="action-btn btn-edit">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('admin.products.delete',$p->ProductID) }}"
                                  method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="action-btn btn-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>



<script>
document.querySelectorAll('#search,#filterStatus,#filterStock').forEach(el=>{
    el.addEventListener('input', filter);
});

function filter(){
    let q = search.value.toLowerCase();
    let s = filterStatus.value;
    let st = filterStock.value;

    document.querySelectorAll('#prodTable tbody tr').forEach(r=>{
        let name = r.dataset.name;
        let status = r.dataset.status;
        let stock = parseInt(r.dataset.stock);

        let show = true;

        if(q && !name.includes(q)) show = false;
        if(s == 'active' && status !== 'active') show = false;
        if(st == 'low' && stock > 5) show = false;

        r.style.display = show ? '' : 'none';
    });
}
</script>

@endsection
