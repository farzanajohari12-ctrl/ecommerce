@extends('layouts.app')

@section('content')

<body id="page-top">
<div id="wrapper">

@include('layouts.sidebar')

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

@include('layouts.topbar')
<style>
.low-stock {
    color: #fff;
    background-color: #dc3545;
    padding: 4px 10px;
    border-radius: 6px;
    animation: blink 1s infinite;
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.4; }
    100% { opacity: 1; }
}
</style>
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 font-weight-bold">Stock Management</h2>
            <p class="text-muted">Monitor and manage product inventory levels.</p>
        </div>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Product Stock List</h6>
            <span class="badge badge-light border text-muted px-2 py-1">
                {{ count($products) }} Total items
            </span>
        </div>

        <div class="table-responsive px-3 pb-3">

            <table class="table align-items-center table-hover mb-0" id="dataTableHover" style="width: 100%;">

                <thead class="thead-light text-uppercase font-weight-bold small">
                    <tr>
                        <th class="py-3 border-top-0 text-center">#</th>
                        <th class="py-3 border-top-0" style="min-width: 200px;">Product Name</th>
                        <th class="py-3 border-top-0">Category</th>
                        <th class="py-3 border-top-0">Stock</th>
                    </tr>
                </thead>

                <tbody class="text-dark">

                @forelse($products as $key => $product)

                    <tr>

                        <!-- # -->
                        <td class="font-weight-bold text-muted">
                            {{ $key + 1 }}
                        </td>

                        <!-- Product -->
                        <td>
                            <div class="font-weight-bold text-dark">
                                {{ $product->title ?? $product->name }}
                            </div>
                        </td>

                        <!-- Category -->
                        <td>
                            <span class="badge badge-light border">
                                {{ $product->category->name ?? 'Unassigned' }}
                            </span>
                        </td>

                        <!-- Stock -->
                        <td class="text-center">

                            <span class="stock-text font-weight-bold"
                                data-id="{{ $product->id }}"
                                data-stock="{{ $product->stock_quantity }}">
                                {{ $product->stock_quantity }}
                            </span>

                            <input type="number"
                                class="form-control form-control-sm stock-input d-none mt-1"
                                data-id="{{ $product->id }}"
                                value="{{ $product->stock_quantity }}"
                                style="width: 80px;">

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <h5 class="text-muted">No stock data found</h5>
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

</body>

@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    // Click stock → edit mode
    $('.stock-text').on('click', function () {
        let id = $(this).data('id');

        $(this).hide();
        $('.stock-input[data-id="'+id+'"]').removeClass('d-none').focus();
    });

    // Press Enter → save
    $('.stock-input').on('keypress', function (e) {
        if (e.which === 13) {

            let id = $(this).data('id');
            let stock = $(this).val();
            let input = $(this);

            $.ajax({
                url: "{{ route('product.updateStock') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    stock_quantity: stock
                },
                success: function (res) {

                    if (res.success) {

                        input.addClass('d-none');

                        let text = $('.stock-text[data-id="'+id+'"]');

                        text.text(res.stock).show();

                        updateStockStyle(id, res.stock);
                    }

                }
            });

        }
    });

});
</script>

<script>
function updateStockStyle(id, stock) {
    let el = $('.stock-text[data-id="'+id+'"]');

    el.removeClass('low-stock');

    if (stock <= 5) {
        el.addClass('low-stock');
    }
}
</script>