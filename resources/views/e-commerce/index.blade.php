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
                    <li class="breadcrumb-item">E Commerce</li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>

            <h2 class="h4 font-weight-bold">Products Management</h2>
            <p class="text-muted">Manage and oversee all your products inventory.</p>
        </div>

        <a href="{{ route('add-product.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    <!-- Table Card -->
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 rounded-lg mb-4">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-bottom-0">
                <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
                <span class="badge badge-light border text-muted px-2 py-1">{{ count($products) }} Total items</span>
            </div>

            <div class="table-responsive w-100 px-3 pb-3">
                <table class="table align-items-center table-hover mb-0" id="dataTableHover" style="width: 100%;">
                    <thead class="thead-light text-uppercase font-weight-bold small">
                        <tr>
                            <th class="py-3 border-top-0 text-center">#</th>
                            <th class="py-3 border-top-0" style="min-width: 200px;">Product Name</th>
                            <th class="py-3 border-top-0">Category</th>
                            <th class="py-3 border-top-0">Price</th>
                            <th class="py-3 border-top-0">Stock</th>
                            <th class="py-3 border-top-0">Status</th>
                            <th class="py-3 border-top-0 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="text-dark">
                        @forelse($products as $key => $product)
                        <tr>
                            <td class="py-3 text-center font-weight-bold text-muted align-middle">{{ $key + 1 }}</td>

                            <td class="py-3 align-middle">
                                <span class="font-weight-bold text-gray-800 d-block text-wrap" style="max-width: 280px; word-break: break-word;">
                                    {{ $product->title ?? $product->name }}
                                </span>
                            </td>

                            <td class="py-3 align-middle">
                                <span class="badge badge-light border text-secondary px-2 py-1">
                                    {{ $product->category->name ?? 'Unassigned' }}
                                </span>
                            </td>

                            <td class="py-3 align-middle font-weight-bold text-dark">
                                RM {{ number_format($product->price, 2) }}
                            </td>

                            <td class="py-3 align-middle">
                                @if(($product->stock_quantity ?? 0) > 5)
                                    <span class="badge badge-success text-white px-2 py-1">
                                        {{ $product->stock_quantity }} Available
                                    </span>
                                @elseif(($product->stock_quantity ?? 0) > 0)
                                    <span class="badge badge-warning text-white px-2 py-1">
                                        {{ $product->stock_quantity }} Low Stock
                                    </span>
                                @else
                                    <span class="badge badge-danger text-white px-2 py-1">
                                        Out of Stock
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 align-middle">
                                <a href="{{ route('product.toggleStatus', $product->id) }}" 
                                class="text-decoration-none">

                                    @if($product->status == 1)
                                        <span class="badge badge-success text-white px-2 py-1">
                                            Active (Click to Disable)
                                        </span>
                                    @else
                                        <span class="badge badge-secondary text-white px-2 py-1">
                                            Inactive (Click to Enable)
                                        </span>
                                    @endif

                                </a>
                            </td>
                            <td class="py-3 align-middle text-center">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                    <a href="{{ route('products.view', $product->id) }}"
                                        class="btn btn-sm btn-warning"
                                        title="View Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" class="m-0">
                                        @csrf
                                        @method('DELETE')

                                        <!-- <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            title="Delete Product">
                                            <i class="fas fa-trash"></i>
                                        </button> -->
                                        <button type="button"
                                            class="btn btn-sm btn-danger delete-product-btn"
                                            title="Delete Product">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="fas fa-box-open fa-3x text-gray-300"></i>
                                </div>
                                <h5 class="text-secondary font-weight-normal mb-1">No products found</h5>
                                <p class="text-muted small mb-0">Your product inventory is currently empty.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script> -->
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
    $(document).on('click', '.delete-product-btn', function (e) {
    e.preventDefault();

    let form = $(this).closest('form');

    Swal.fire({
        title: "Are you sure?",
        text: "This product will be deleted permanently!",
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
