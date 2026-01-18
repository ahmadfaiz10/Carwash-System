@extends('layouts.admin')

@section('title', 'Manage Service Categories')

@section('content')

<style>
:root {
    --carwash-blue: #007bff;
    --carwash-aqua: #00e0ff;
    --carwash-dark: #003b73;
}

/* Banner */
.carwash-banner {
    background: linear-gradient(135deg, #007bff, #00e0ff);
    padding: 35px 20px;
    border-radius: 18px;
    color: white;
    text-align: center;
    margin-bottom: 40px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.carwash-banner h2 {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 6px;
}
.carwash-banner p {
    font-size: 15px;
    opacity: 0.95;
}

/* Category wrapper */
.category-wrapper {
    background: rgba(255,255,255,0.55);
    padding: 35px;
    border-radius: 18px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.35);
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
}

/* Category item */
.category-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(90deg, #f5faff, #e6f5ff);
    border-radius: 14px;
    padding: 18px;
    border: 1px solid #c9dbef;
    margin-bottom: 16px;
    transition: 0.25s ease;
    cursor: pointer;
}
.category-item:hover {
    transform: translateY(-3px);
    background: #ffffff;
    box-shadow: 0 8px 18px rgba(0,0,0,0.10);
}

/* Center text */
.category-center-name {
    flex: 1;
    text-align: center;
    font-size: 18px;
    font-weight: 700;
    color: #003b73;
}

.category-actions {
    display: flex;
    gap: 10px;
}
.category-actions button {
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 20px;
}

.btn-edit { color: #ffb300; }
.btn-delete { color: #e63946; }

/* Add box */
.add-box {
    padding: 14px 25px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 12px;
    border: 2px dashed #89b6d9;
    text-align: center;
    background: rgba(255,255,255,0.6);
    cursor: pointer;
    transition: 0.25s;
    width: 260px;
    margin: 0 auto;
}
.add-box:hover {
    background: rgba(0,123,255,0.10);
    border-color: #007bff;
}
</style>


<div class="container">

    <!-- RESTORED BLUE BANNER -->
    <div class="carwash-banner">
        <h2>🚘 Manage Service Categories</h2>
        <p>Organize and customize your service groups.</p>
    </div>
    <!-- END BANNER -->

    <div class="category-wrapper">

        @foreach($categories as $cat)

            <div class="category-item"
                 onclick="window.location='{{ route('admin.services.byCategory', $cat->id) }}'">

                <div class="category-center-name">
                    {{ $cat->name }}
                </div>

                <div class="category-actions">

                    <button class="btn-edit"
                        onclick="event.stopPropagation(); toggleEdit('{{ $cat->id }}');">
                        ✎
                    </button>

                    <form method="POST"
                          onclick="event.stopPropagation();"
                          action="{{ route('admin.services.category.delete', $cat->id) }}"
                          onsubmit="return confirm('Delete this category?');">
                        @csrf @method('DELETE')
                        <button class="btn-delete">✖</button>
                    </form>

                </div>
            </div>

            <!-- Edit Form -->
            <form id="editForm-{{ $cat->id }}"
                  class="mt-2 p-3 bg-white rounded shadow-sm"
                  style="display:none; width: 400px; margin: 0 auto;"
                  method="POST"
                  action="{{ route('admin.services.category.update', $cat->id) }}">
                @csrf

                <label class="fw-bold mb-1">Edit Category</label>
                <input type="text" name="ServiceCategory"
                       value="{{ $cat->name }}"
                       class="form-control mb-3" required>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary"
                        onclick="toggleEdit('{{ $cat->id }}')">Cancel</button>
                    <button class="btn btn-success">Save</button>
                </div>
            </form>

        @endforeach

        <div class="add-box mt-4" onclick="showAddForm()">
            + Add New Category
        </div>

        <form id="addForm"
              class="mt-3 p-3 bg-white rounded shadow-sm"
              style="display:none; width: 400px; margin: 0 auto;"
              method="POST"
              action="{{ route('admin.services.category.store') }}">
            @csrf

            <label class="fw-bold mb-1">Category Name</label>
            <input type="text" name="ServiceCategory" class="form-control mb-3" required>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" onclick="hideAddForm()" class="btn btn-danger">
                    Cancel
                </button>
                <button class="btn btn-success">Save</button>
            </div>
        </form>

    </div>
</div>


<script>
function toggleEdit(id){
    let form = document.getElementById('editForm-'+id);
    form.style.display = form.style.display === "block" ? "none" : "block";
}

function showAddForm(){
    document.getElementById('addForm').style.display = "block";
}

function hideAddForm(){
    document.getElementById('addForm').style.display = "none";
}
</script>

@endsection
