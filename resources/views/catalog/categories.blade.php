@extends('layouts.app')

@section('content')

<body id="page-top">
<div id="wrapper">

@include('layouts.sidebar')

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

@include('layouts.topbar')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Catalog</li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </nav>

            <h2 class="h4 font-weight-bold">Category Management</h2>
            <p class="text-muted">Manage product categories.</p>
        </div>

        <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Category List</h6>

            <span class="badge badge-light border text-muted px-2 py-1">
                {{ count($categories) }} Categories
            </span>
        </div>

        <div class="table-responsive p-3">

            <table class="table table-hover" id="dataTableHover" style="width: 100%;">
                <thead class="thead-light text-uppercase font-weight-bold small">
                    <tr>
                        <th width="80">#</th>
                        <th>Category Name</th>
                        <th width="200" class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($categories as $key => $category)

                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $category->name }}</td>

                        <td class="text-center">

                            <button
                                class="btn btn-sm btn-warning edit-btn"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}"
                                data-toggle="modal"
                                data-target="#editCategoryModal">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('categories.destroy', $category->id) }}"
                                method="POST"
                                style="display:inline-block;" class="m-0">

                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-sm btn-danger delete-category-btn" title="Delete Category">
                                    <i class="fas fa-trash"></i>
                                </button>
                                

                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="text-center">
                            No categories found.
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>

@include('layouts.footer')

</div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal">
    <div class="modal-dialog">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Save Category
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>


<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal">
    <div class="modal-dialog">

        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text"
                               name="name"
                               id="edit_name"
                               class="form-control"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Update Category
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: "success",
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

<script>
$(document).ready(function () {

    $('.edit-btn').click(function () {

        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#edit_name').val(name);

        $('#editCategoryForm').attr('action', "{{ route('categories.index') }}/" + id);
    });

});

$(document).on('click', '.delete-category-btn', function (e) {
    e.preventDefault();

    let form = $(this).closest('form');

    Swal.fire({
        title: "Are you sure?",
        text: "This category will be deleted permanently!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>

</body>
@endsection

