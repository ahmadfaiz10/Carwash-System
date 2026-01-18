@extends('layouts.admin')

@section('title', 'Edit Category')

@section('sidebar')
    <div class="sidebar-title"><i class="bi bi-pencil-square"></i> Edit Category</div>

    <div class="sidebar-section-title">Navigation</div>
    <a href="{{ route('admin.products.categories') }}" class="sidebar-link active">
        <i class="bi bi-folder2-open"></i> Category Overview
    </a>
@endsection

@section('content')

<style>
    .page-title { font-size:26px; font-weight:700; margin-bottom:6px; }
    .page-sub { font-size:13px; color:#666; margin-bottom:20px; }

    .prod-card {
        background:white;
        padding:22px;
        border-radius:16px;
        width:550px;
        box-shadow:0 4px 14px rgba(0,0,0,0.08);
    }

    label { font-size:14px; font-weight:600; display:block; margin-bottom:4px; }

    input[type="text"] {
        width:100%;
        padding:10px 14px;
        border-radius:10px;
        border:1px solid #ccc;
        margin-bottom:14px;
        font-size:14px;
    }

    .primary-btn {
        background:#2f80ed;
        color:#fff;
        padding:10px 18px;
        border-radius:10px;
        font-weight:600;
        border:none;
        cursor:pointer;
    }

    .secondary-btn {
        background:#e4e6eb;
        padding:10px 18px;
        border-radius:10px;
        font-weight:600;
        margin-right:10px;
    }
</style>

<div class="page-title">Edit Category</div>
<div class="page-sub">Rename this category. All assigned products will update automatically.</div>

<div class="prod-card">

    <form action="{{ route('admin.products.category.update', $category) }}" method="POST">
        @csrf

        <label>New Category Name</label>
        <input type="text" name="newCategory" value="{{ $category }}" required>

        <div style="margin-top:12px;">
            <a href="{{ route('admin.products.categories') }}" class="secondary-btn">Cancel</a>
            <button class="primary-btn">Save Changes</button>
        </div>
    </form>

</div>

@endsection
