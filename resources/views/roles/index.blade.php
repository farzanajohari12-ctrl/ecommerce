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
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">User Manager</li>
                    <li class="breadcrumb-item active">Roles & Permissions</li>
                </ol>
            </nav>
            <h2 class="h4 font-weight-bold">Roles & Permissions Matrix</h2>
            <p class="text-muted">Configure what access levels each user role possesses based on sidebar features.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Access Control Matrix</h6>
        </div>

        <div class="table-responsive p-3">
            <table class="table table-hover table-bordered" id="dataTableHover" style="width: 100%;">
                <thead class="thead-light text-uppercase font-weight-bold small">
                    <tr>
                        <th>Role Name</th>
                        @foreach($permissions as $permission)
                            <th class="text-center">{{ ucwords(str_replace(['manage_', 'view_', '_'], ['', '', ' '], $permission->name)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td class="font-weight-bold text-dark">
                                {{ ucfirst($role->name) }}
                            </td>

                            @foreach($permissions as $permission)
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->name }}"
                                               class="custom-control-input permission-trigger" 
                                               data-role-id="{{ $role->id }}"
                                               id="check-{{ $role->id }}-{{ $permission->id }}"
                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="check-{{ $role->id }}-{{ $permission->id }}"></label>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@include('layouts.footer')

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // Fire AJAX request instantly when any checkbox changes state
    $('.permission-trigger').change(function() {
        let roleId = $(this).data('role-id');
        let checkedBoxes = [];
        
        // Find all currently checked boxes in this role's row
        $(`input[data-role-id="${roleId}"]:checked`).each(function() {
            checkedBoxes.push($(this).val());
        });

        $.ajax({
            url: "{{ url('roles-permissions') }}/" + roleId,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                _method: 'PUT',
                permissions: checkedBoxes
            },
            success: function(response) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: "success",
                    title: "Permissions auto-saved successfully!",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Could not sync permissions. Please refresh."
                });
            }
        });
    });
});
</script>

</body>
@endsection