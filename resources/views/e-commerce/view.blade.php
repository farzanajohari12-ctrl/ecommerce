@extends('layouts.app')
@section('content')
<body id="page-top">
  <div id="wrapper">
    @include('layouts.sidebar') 
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        @include('layouts.topbar') 

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb bg-white px-0 mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">E Commerce</li>
                            <li class="breadcrumb-item active" aria-current="page">View Product</li>
                        </ol>
                    </nav>
                    <h2 class="h4 font-weight-bold mb-0">Product Details</h2>
                </div>

                <div>
                    <button type="button" class="btn btn-outline-primary mr-2" data-toggle="modal" data-target="#editProductModal">
                        <i class="fas fa-edit mr-1"></i> Edit Product
                    </button>
                    <a href="{{ url('/products') }}" class="btn btn-light border">Back to Products</a>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        
                        <div class="col-lg-5 mb-4 mb-lg-0">
                            <div class="border rounded text-center p-3 mb-3 bg-light d-flex align-items-center justify-content-center" style="height: 380px;">
                                @php
                                    $mainImage = $product->images->first()->image_path ?? 'default.png';
                                @endphp

                                <img id="mainProductDisplay"
                                    src="{{ asset($mainImage) }}"
                                    class="img-fluid rounded"
                                    style="max-height: 100%; object-fit: contain;">
                            </div>
                            
                            <div class="row mx-n1 mb-3">

                                @foreach($product->images as $image)
                                    <div class="col-3 px-1">

                                        <div class="border rounded p-1 bg-white thumbnail-wrapper"
                                            onclick="changeDisplayImage(this, '{{ asset($image->image_path) }}')">

                                            <img src="{{ asset($image->image_path) }}"
                                                class="img-fluid rounded w-100"
                                                style="height: 70px; object-fit: cover;">
                                        </div>

                                    </div>
                                @endforeach

                            </div>
                            
                            <div class="d-flex flex-wrap mt-2">

                                {{-- BADGES (priority first) --}}
                                @foreach($product->badges as $badge)
                                    <span class="badge px-3 py-2 mr-1 mb-1 badge-primary">
                                        {{ $badge->name }}
                                    </span>
                                @endforeach

                                {{-- TAGS --}}
                                @foreach($product->tags as $tag)
                                    <span class="badge badge-light border text-secondary px-3 py-2 mr-1 mb-1">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach

                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="pl-lg-3">
                               
                                <span class="text-uppercase tracking-wider text-muted small font-weight-bold">
                                    {{ $product->category->name ?? '-' }}
                                </span>

                                <h1 class="h2 font-weight-bold mt-1 mb-3 text-dark">
                                    {{ $product->title ?? $product->name }}
                                </h1>
                                
                                <div class="bg-light p-3 rounded mb-4 d-flex align-items-center">
                                    <!-- Price Display -->
                                    <span class="h3 font-weight-bold text-danger mb-0 mr-3">
                                        RM {{ number_format($product->sale_price ?? $product->price, 2) }}
                                    </span>

                                    @if($product->sale_price && $product->price > $product->sale_price)
                                        <span class="text-muted mr-2">
                                            <del>RM {{ number_format($product->price, 2) }}</del>
                                        </span>
                                    @endif

                                    <!-- Show Discount % -->
                                    @if($product->sale_price && $product->price > $product->sale_price)
                                        @php
                                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                        @endphp

                                        <span class="badge badge-danger">
                                            -{{ $discount }}% OFF
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-4 py-2 border-top border-bottom">
                                    <h6 class="font-weight-bold text-dark mb-3">Available Options / Variants</h6>

                                    <div class="row">

                                        @foreach($product->variants->groupBy(function ($variant) {
                                            return $variant->attribute->name ?? 'No Attribute';
                                        }) as $attribute => $values)

                                            <div class="col-md-6 mb-3">

                                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-2">
                                                    {{ $attribute }}
                                                </label>

                                                <div class="d-flex flex-wrap">

                                                    @foreach($values as $value)
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-dark mr-1 mb-1 px-3">
                                                            {{ $value->value->value ?? $value->value }}
                                                        </button>
                                                    @endforeach

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-4">

                                    <div class="mr-4">
                                        <span class="text-muted d-block small font-weight-bold mb-1">
                                            AVAILABILITY
                                        </span>

                                        @if($product->stock_quantity > 0)
                                            <span class="text-success font-weight-bold">
                                                <i class="fas fa-check-circle mr-1"></i> In Stock
                                            </span>
                                        @else
                                            <span class="text-danger font-weight-bold">
                                                <i class="fas fa-times-circle mr-1"></i> Out of Stock
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <span class="text-muted d-block small font-weight-bold mb-1">
                                            CURRENT STOCK LEVEL
                                        </span>

                                        <span class="badge badge-secondary px-3 py-2 font-weight-bold">
                                            {{ $product->stock_quantity }} units left
                                        </span>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="border-top pt-4">
                                <ul class="nav nav-tabs" id="productTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active font-weight-bold" id="desc-tab" data-toggle="tab" href="#desc" role="tab" aria-controls="desc" aria-selected="true">
                                            Product Description
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content border-left border-right border-bottom p-4 bg-white rounded-bottom"
                                    id="productTabContent">

                                    <div class="tab-pane fade show active text-secondary"
                                        id="desc"
                                        role="tabpanel"
                                        aria-labelledby="desc-tab"
                                        style="line-height: 1.7;">

                                        {!! $product->description !!}
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        
                    <form id="productForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="row">
                            <div class="col-lg-8">

                                {{-- TITLE --}}
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Product Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="{{ $product->title }}" required>
                                </div>

                                {{-- DESCRIPTION --}}
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Product Description <span class="text-danger">*</span></label>

                                    <input id="product_description" type="hidden" name="description"
                                        value="{{ $product->description }}">

                                    <trix-editor input="product_description"
                                        class="trix-content form-control border"
                                        style="min-height: 200px; max-height: 300px; overflow-y: auto;">
                                    </trix-editor>
                                </div>

                                {{-- IMAGES --}}
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Product Images</label>

                                    <input type="file" name="images[]" id="imageUpload" class="d-none" multiple>

                                    <div class="border p-3 text-center rounded bg-light mb-3"
                                        onclick="$('#imageUpload').click()"
                                        style="cursor:pointer;">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                                        <p class="mb-0">Click to upload images</p>
                                    </div>

                                    <div class="row" id="imagePreview">

                                        @foreach($product->images as $img)
                                            <div class="col-md-3 mb-2 image-box">
                                                <div class="position-relative border rounded p-1 bg-white">

                                                    <img src="{{ asset($img->image_path) }}"
                                                        class="img-fluid rounded w-100"
                                                        style="height:80px; object-fit:cover;">

                                                    {{-- keep existing --}}
                                                    <input type="hidden" name="existing_images[]" value="{{ $img->id }}">

                                                    {{-- mark delete --}}
                                                    <input type="hidden" name="delete_images[]" class="delete-image" value="">

                                                    <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute remove-image"
                                                            data-id="{{ $img->id }}"
                                                            style="top:5px; right:5px;">
                                                        &times;
                                                    </button>

                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                {{-- PRICING --}}
                                <div class="border p-3 rounded mb-4 bg-white">
                                    <h6 class="font-weight-bold mb-3">Pricing</h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Price</label>
                                            <input type="number" step="0.01" name="price"
                                                class="form-control"
                                                value="{{ $product->price }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Sale Price</label>
                                            <input type="number" step="0.01" name="sale_price"
                                                class="form-control"
                                                value="{{ $product->sale_price }}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- RIGHT SIDE --}}
                            <div class="col-lg-4">

                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body">

                                        <label class="font-weight-bold small mb-2">Product Details</label>

                                        <div class="row">

                                            {{-- CATEGORY --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="small font-weight-bold">Category</label>

                                                <select name="category_id" class="form-control">
                                                    <option value="">Select</option>
                                                    @foreach($categories ?? [] as $cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- TAGS --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="small font-weight-bold">Tags</label>

                                                <select name="tags[]" id="tagSelect"
                                                    class="form-control select2-multiple" multiple>
                                                    @foreach($allTags as $tag)
                                                        <option value="{{ $tag->name }}"
                                                            {{ in_array($tag->name, $product->tags->pluck('name')->toArray()) ? 'selected' : '' }}>
                                                            {{ $tag->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- BADGES --}}
                                            <div class="col-md-12">
                                                <label class="small font-weight-bold">Badges</label>

                                                <select name="badges[]" id="badgeSelect"
                                                    class="form-control select2-multiple" multiple>
                                                    @foreach($allBadges as $badge)
                                                        <option value="{{ $badge->name }}"
                                                            {{ in_array($badge->name, $product->badges->pluck('name')->toArray()) ? 'selected' : '' }}>
                                                            {{ $badge->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">

                                        <h6 class="font-weight-bold mb-2 text-dark">Variant Management</h6>

                                        <p class="text-muted small">
                                            Variants can be modified via configurations options panel details <a href="{{ url('variant-attributes') }}">view settings</a>.
                                        </p>

                                        @php
                                            $grouped = $product->variants->groupBy('variant_attribute_id');
                                        @endphp

                                        @forelse($grouped as $attributeId => $variants)

                                            @php
                                                $attributeName = $variants->first()->attribute->name ?? 'Unknown';
                                            @endphp

                                            <div class="border p-2 rounded bg-white small mb-2">

                                                <strong>{{ $attributeName }}:</strong>

                                                {{ $variants->map(function($v) {
                                                    return $v->value->value ?? '';
                                                })->filter()->implode(', ') }}

                                            </div>

                                        @empty

                                            <div class="text-muted small">
                                                No variants available
                                            </div>

                                        @endforelse

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard Changes</button>
                        <button type="button" class="btn btn-primary px-4" onclick="handleFormSubmit()">Save & Update Product</button>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footer')
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <style>
        .thumbnail-wrapper {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            opacity: 0.6;
        }
        .thumbnail-wrapper:hover {
            opacity: 1;
            border-color: #4e73df !important;
        }
        .active-thumbnail {
            opacity: 1;
            border-color: #4e73df !important;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function changeDisplayImage(el, src) {
            document.getElementById('mainProductDisplay').src = src;

            document.querySelectorAll('.thumbnail-wrapper')
                .forEach(e => e.classList.remove('active-thumbnail'));

            el.classList.add('active-thumbnail');
        }

        function handleFormSubmit()
        {
            let formData = new FormData($('#productForm')[0]);

            $.ajax({
                url: "{{ route('product.update') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });

                        $('#editProductModal').modal('hide');
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: "error",
                            title: "Error",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }

                },
                error: function(xhr){

                    if (xhr.status === 422) {
                        let response = xhr.responseJSON;

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: "error",
                            title: "Validation Error",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        return;
                    }

                    console.log(xhr.responseText);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: "error",
                        title: "Something went wrong",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            });
        }
    </script>

    <script>
        $(document).on('click', '.remove-image', function() {

            let imageId = $(this).data('id');

            $(this).siblings('.delete-image').val(imageId);

            $(this).closest('.image-box').hide();
        });
    </script>

    <script>
        $('#imageUpload').on('change', function() {

            $.each(this.files, function(index, file) {

                let reader = new FileReader();

                reader.onload = function(e) {

                    $('#imagePreview').append(`
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-1 bg-white">
                                <img src="${e.target.result}"
                                    class="img-fluid rounded w-100"
                                    style="height:80px;object-fit:cover;">
                            </div>
                        </div>
                    `);
                }

                reader.readAsDataURL(file);
            });

        });
    </script>
</body>
@endsection