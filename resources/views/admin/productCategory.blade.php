@extends('layouts.admin')

@section('title', 'Product Categories')

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

    box-shadow: 0 12px 24px rgba(0,0,0,0.18);
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
.kpi-value { font-size: 40px; font-weight: 700; }



/* ============================
   MAIN WRAPPER
=============================== */

.card-box {
    background: white;
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 14px 32px rgba(0,0,0,0.07);
    border: 1px solid #dbe3eb;
}



/* ============================
   SEARCH BAR
=============================== */

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.search-input {
    padding: 12px 18px;
    border-radius: 14px;
    border: 1px solid #cbd5e1;
    background: #f1f5f9;
    width: 280px;

    font-size: 15px;
    transition: .25s;
}

.search-input:focus {
    background: white;
    border-color: #1E88E5;
    box-shadow: 0 0 0 4px rgba(30,136,229,0.25);
}



/* ============================
   TABLE STYLING
=============================== */

.table-wrapper {
    margin-top: 20px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 820px;
}

th {
    padding: 14px;
    background: #eef2f7;
    border-bottom: 2px solid #dbe3eb;

    font-size: 15px;
    font-weight: 700;
    color: #334155;
}

td {
    padding: 15px 14px;
    border-bottom: 1px solid #e3e8f0;
    color: #0f172a;
}

tr:hover td {
    background: #f8fafc;
}

.badge-low {
    background: #ffedd5;
    color: #a16207;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
}

.badge-ok {
    background: #d1fae5;
    color: #047857;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
}



/* ============================
   ACTION BUTTONS (PRO STYLE)
=============================== */

.action-wrapper {
    display: flex;
    flex-direction: row;
    gap: 10px;
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
    transition: 0.25s;
}

/* VIEW */
.btn-view {
    border: 2px solid #1E88E5;
    color: #1E88E5;
    background: white;
}
.btn-view:hover {
    background: #1E88E5;
    color: white;
}

/* EDIT */
.btn-edit {
    border: 2px solid #1E88E5;
    background: #1E88E5;
    color: white;
}
.btn-edit:hover {
    background: #1565C0;
    border-color: #1565C0;
}

/* DELETE */
.btn-delete {
    border: 2px solid #dc2626;
    background: #dc2626;
    color: white;
}
.btn-delete:hover {
    background: #b91c1c;
    border-color: #b91c1c;
}



/* ============================
   ADD CATEGORY
=============================== */

.add-title {
    margin-top: 32px;
    font-size: 20px;
    font-weight: 700;
}

.add-box {
    margin-top: 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.add-input {
    padding: 12px 18px;
    border-radius: 14px;
    border: 1px solid #cbd5e1;
    background: #f1f5f9;
    width: 260px;
    font-size: 15px;

    transition: .25s;
}

.add-input:focus {
    border-color: #1E88E5;
    background: white;
    box-shadow: 0 0 0 4px rgba(30,136,229,0.25);
}

.add-btn {
    padding: 12px 26px;
    border-radius: 14px;
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    color: white;
    font-size: 15px;
    font-weight: 600;
    border: none;

    cursor: pointer;
    transition: .25s;
    box-shadow: 0 8px 20px rgba(30,136,229,0.35);
}

.add-btn:hover {
    background: linear-gradient(135deg, #1565C0, #0d47a1);
}



/* ============================
   RESPONSIVE
=============================== */

@media(max-width: 620px){
    .search-input,
    .add-input,
    .add-btn {
        width: 100%;
    }

    .card-box { padding: 22px; }

    .page-title { font-size: 24px; }

    .kpi-value { font-size: 32px; }
}

</style>




<div>

    <!-- TITLE -->
    <div class="page-title">Product Categories</div>
    <div class="page-sub">Easily view, manage and update your carwash inventory categories.</div>



    <!-- KPI CARDS -->
    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kpi-label">Total Categories</div>
            <div class="kpi-value">{{ $categories->count() }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Total Products</div>
            <div class="kpi-value">{{ $categories->sum('product_count') }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Low Stock Categories</div>
            <div class="kpi-value">{{ $categories->where('low_stock_count','>',0)->count() }}</div>
        </div>
    </div>



    <!-- MAIN CONTENT BOX -->
    <div class="card-box">

        <div class="content-header">
            <h3 style="margin:0;">Category Overview</h3>
            <input id="searchCategory" class="search-input" placeholder="Search category...">
        </div>

        <div class="table-wrapper">
            <table id="categoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Low Stock</th>
                        <th style="width: 220px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($categories as $i => $cat)
                    <tr data-name="{{ strtolower($cat->Category) }}">
                        <td>{{ $i+1 }}</td>
                        <td><strong>{{ $cat->Category }}</strong></td>
                        <td>{{ $cat->product_count }}</td>
                        <td>
                            @if($cat->low_stock_count > 0)
                                <span class="badge-low">{{ $cat->low_stock_count }} low</span>
                            @else
                                <span class="badge-ok">OK</span>
                            @endif
                        </td>

                        <td>
                            <div class="action-wrapper">

                                <a href="{{ route('admin.products.byCategory',$cat->Category) }}"
                                   class="action-btn btn-view">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                <a href="{{ route('admin.products.category.edit',$cat->Category) }}"
                                   class="action-btn btn-edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <a href="{{ route('admin.products.category.delete',$cat->Category) }}"
                                   onclick="return confirm('Delete this category and ALL products inside it?')"
                                   class="action-btn btn-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </a>

                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>



        <!-- ADD CATEGORY -->
        <div class="add-title">Add New Category</div>

        <form action="{{ route('admin.products.category.store') }}" method="POST" class="add-box">
            @csrf
            <input name="Category" placeholder="Category name" class="add-input" required>
            <button class="add-btn">Add</button>
        </form>

    </div>

</div>



<script>
document.getElementById('searchCategory').addEventListener('input', function () {
    let q = this.value.toLowerCase();
    document.querySelectorAll('#categoryTable tbody tr').forEach(
        row => row.style.display = row.dataset.name.includes(q) ? '' : 'none'
    );
});
</script>

@endsection
