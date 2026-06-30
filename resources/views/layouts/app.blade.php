<!DOCTYPE html>
<html lang="en">

<head>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="{{ asset('img/logo/logo.png') }}" rel="icon">
  <title>RuangAdmin</title>
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/ruang-admin.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

  <!-- Trix Editor -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">

  <!-- Select2 -->
  <link href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">

</head>

<body>
    <!-- <div class="container"> -->
        {{-- Page Content --}}
        @yield('content')
    <!-- </div> -->

    {{-- Bootstrap JS --}}
    <!-- <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> -->
    <!-- <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
    <!-- <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script> -->
    <!-- <script src="{{ asset('js/ruang-admin.min.js') }}"></script> -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>

    <!-- jQuery (required before Select2) -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Tables JS -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>

    <!-- Your custom Select2 init script -->
    <script>
        $(document).ready(function () {
            $('.select2-single').select2({
            placeholder: "Select a category",
            allowClear: true
            });

            $('.select2-multiple').select2({
            tags: true,
            tokenSeparators: [',']
            });
        });
    </script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
        });
    </script>

    <!-- Base URL for AJAX requests -->
    <script>
        const BASE_URL = "{{ url('/') }}";
    </script>

    <!-- Global Search Script -->
    <script>
        $(document).ready(function () {

            $('#globalSearch').on('keyup', function () {

                let query = $(this).val();

                if (query.length < 2) {
                    $('#globalResults').html('');
                    return;
                }

                $.ajax({
                    url: "{{ route('global.search') }}",
                    type: "GET",
                    data: { query: query },
                    success: function (res) {

                        let html = '';

                        // PRODUCTS
                        if (res.products.length) {
                            html += `<div class="text-primary small font-weight-bold px-2">Products</div>`;
                            res.products.forEach(p => {
                                html += `
                                    <a href="${BASE_URL}/products/${p.id}/view" class="dropdown-item">
                                        ${p.title}
                                    </a>`;
                            });
                        }

                        // CATEGORIES
                        if (res.categories.length) {
                            html += `<div class="text-success small font-weight-bold px-2">Categories</div>`;
                            res.categories.forEach(c => {
                                html += `
                                    <a href="#" class="dropdown-item">
                                        ${c.name}
                                    </a>`;
                            });
                        }

                        // ORDERS
                        if (res.orders.length) {
                            html += `<div class="text-warning small font-weight-bold px-2">Orders</div>`;
                            res.orders.forEach(o => {
                                html += `
                                    <a href="#" class="dropdown-item">
                                        Order #${o.id}
                                    </a>`;
                            });
                        }

                        // USERS
                        if (res.users.length) {
                            html += `<div class="text-info small font-weight-bold px-2">Users</div>`;
                            res.users.forEach(u => {
                                html += `
                                    <a href="#" class="dropdown-item">
                                        ${u.name}
                                    </a>`;
                            });
                        }

                        if (!html) {
                            html = `<div class="text-muted small px-2">No results found</div>`;
                        }

                        $('#globalResults').html(html);
                    }
                });

            });

        });
    </script>

</body>
</html>
