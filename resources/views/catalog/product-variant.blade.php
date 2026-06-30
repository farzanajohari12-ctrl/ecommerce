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
                    <li class="breadcrumb-item active"><a href="{{ url('/variant-attributes') }}">Variant Attributes</a></li>
                    <li class="breadcrumb-item active">{{ $attribute->name }}</li>
                </ol>
            </nav>

            <h2 class="h4 font-weight-bold">
                Variant Attribute Details
            </h2>
            <p class="text-muted">
                Manage product variant attributes (Size, Color, Storage, etc).
            </p>
        </div>

        <div>
            <a href="{{ url('/variant-attributes') }}" class="btn btn-light border">Back to Variants</a>

            <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
                <i class="fas fa-plus"></i> Add Product & Variants
            </button>

            <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ url('/variant/store-new') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProductModalLabel">Add Variants to Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span>&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">

                                <div class="form-group mb-4">
                                    <label for="product_select">Attribute</label>
                                    <input type="text" value="{{ $attribute->name }}" class="form-control" readonly>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="product_select">Select Product</label>
                                    <select name="product_id" id="product_select" class="form-control" required>
                                        <option value="" disabled selected>-- Choose a Product --</option>
                                        @foreach($allProducts as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label>Variant Values (e.g., Yellow, XL, 128GB)</label>
                                <div id="newProductVariantContainer">
                                    <div class="input-group mb-2 variant-row">
                                        <input type="text" class="form-control" name="new_variants[0]" placeholder="Enter variant value" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success add-new-btn">+</button>
                                            <button type="button" class="btn btn-danger remove-new-btn">−</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Product Variants</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CARD -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <table class="table align-items-center table-hover mb-0" id="dataTableHover" style="width: 100%;">
                <thead class="thead-light text-uppercase font-weight-bold small">
                    <tr>
                        <th>#</th>
                        <th>Variant Attribute</th>
                        <th>Product Name</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>
                                <span class="badge badge-info">
                                    {{ $attribute->name }}
                                </span>
                            </td>

                            <td>
                                {{ $product->title }}
                            </td>

                            <td>
                                @foreach($product->variants as $variant)
                                    @if($variant->variant_attribute_id == $attribute->id)
                                        <span class="badge badge-secondary">
                                            {{ $variant->value->value }}
                                        </span>
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                <button class="btn btn-sm btn-warning"
                                    data-toggle="modal"
                                    data-target="#variantModal{{ $attribute->id }}_{{ $product->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Variant Modal -->
                                <div class="modal fade" id="variantModal{{ $attribute->id }}_{{ $product->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ url('/variant/update') }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Edit Variants to Product
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="form-group mb-4">
                                                        <label for="product_select">Attribute</label>
                                                        <input type="text" value="{{ $attribute->name }}" class="form-control" readonly>
                                                    </div>

                                                    <div class="form-group mb-4">
                                                        <label for="product_select">Product</label>
                                                        <input type="text" value="{{ $product->title }}" class="form-control" readonly>
                                                    </div>

                                                    @php
                                                        $filteredVariants = $product->variants->where('variant_attribute_id', $attribute->id);
                                                    @endphp

                                                    <label for="product_select">Variant Value</label>
                                                    <div id="variantContainer{{ $attribute->id }}_{{ $product->id }}">
                                                        @foreach($filteredVariants as $variant)
                                                        <div class="input-group mb-2 variant-row">

                                                            <input type="hidden"
                                                                name="variants_data[{{ $loop->index }}][value_id]"
                                                                value="{{ $variant->value->id }}">

                                                            <input type="text"
                                                                class="form-control"
                                                                name="variants_data[{{ $loop->index }}][value]"
                                                                value="{{ $variant->value->value }}">

                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-success add-btn">+</button>
                                                                <button type="button" class="btn btn-danger remove-btn">−</button>
                                                            </div>

                                                        </div>
                                                        @endforeach
                                                    </div>

                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard Changes</button>
                                                    <button type="submit" class="btn btn-primary">Update Variants</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No products found for this variant attribute.
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



<!-- JS -->
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

<!-- Variant Management Scripts -->
<script>
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-btn")) {
        let container = e.target.closest(".modal-body").querySelector('[id^="variantContainer"]');
        let uniqueIndex = Date.now();

        let newRow = document.createElement("div");
        newRow.classList.add("input-group", "mb-2", "variant-row");

        newRow.innerHTML = `
            <input type="hidden" name="variants_data[${uniqueIndex}][value_id]" value="">
            <input type="text" class="form-control" name="variants_data[${uniqueIndex}][value]" value="">
            <div class="input-group-append">
                <button type="button" class="btn btn-success add-btn">+</button>
                <button type="button" class="btn btn-danger remove-btn">−</button>
            </div>
        `;
        container.appendChild(newRow);
    }

    if (e.target.classList.contains("remove-btn")) {
        let row = e.target.closest(".variant-row");
        let container = row.parentElement;

        if (container.querySelectorAll(".variant-row").length > 1) {
            row.remove();
        }
    }
});
</script>

<script>
    let newRowIdx = 0;

    document.addEventListener("click", function (e) {
        // Handling '+' click inside the Add New Product modal
        if (e.target.classList.contains("add-new-btn")) {
            let container = document.getElementById("newProductVariantContainer");
            newRowIdx++;

            let newRow = document.createElement("div");
            newRow.classList.add("input-group", "mb-2", "variant-row");

            newRow.innerHTML = `
                <input type="text" class="form-control" name="new_variants[${newRowIdx}]" placeholder="Enter variant value" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-success add-new-btn">+</button>
                    <button type="button" class="btn btn-danger remove-new-btn">−</button>
                </div>
            `;
            container.appendChild(newRow);
        }

        // Handling '−' click inside the Add New Product modal
        if (e.target.classList.contains("remove-new-btn")) {
            let row = e.target.closest(".variant-row");
            let container = document.getElementById("newProductVariantContainer");

            if (container.querySelectorAll(".variant-row").length > 1) {
                row.remove();
            }
        }
    });

</script>

</body>
@endsection