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
                    <li class="breadcrumb-item">User Manager</li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>

            <h2 class="h4 font-weight-bold">User Management</h2>
            <p class="text-muted">Manage system users and access levels.</p>
        </div>

        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
            <i class="fas fa-plus"></i> Add User
        </button>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">User List</h6>

            <span class="badge badge-light border text-muted px-2 py-1">
                {{ count($users) }} Users
            </span>
        </div>

        <div class="table-responsive p-3">

            <table class="table table-hover" id="dataTableHover" style="width: 100%;">
                <thead class="thead-light text-uppercase font-weight-bold small">
                    <tr>
                        <th width="80">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="200" class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($users as $key => $user)

                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->hasRole('admin'))
                                <span class="badge badge-info">Admin</span>
                            @elseif($user->hasRole('staff'))
                                <span class="badge badge-warning">Staff</span>
                            @elseif($user->hasRole('user'))
                                <span class="badge badge-success">User</span>
                            @else
                                <span class="badge badge-danger">No Role Assigned</span>
                            @endif
                        </td>

                        <td class="text-center">

                            <button
                                class="btn btn-sm btn-warning edit-btn"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-role="{{ $user->role }}"
                                data-toggle="modal"
                                data-target="#editUserModal">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('users.destroy', $user->id) }}"
                                method="POST"
                                style="display:inline-block;" class="m-0">

                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-sm btn-danger delete-user-btn" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center">
                            No users found.
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Email Address</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Save User
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>


<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal">
    <div class="modal-dialog">

        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text"
                               name="name"
                               id="edit_name"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Email Address</label>
                        <input type="email"
                               name="email"
                               id="edit_email"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                        <input type="password"
                               name="password"
                               class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Update User
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
        let email = $(this).data('email');
        let role = $(this).data('role'); // Added

        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_role').val(role); // Added (sets the selected dropdown option)

        $('#editUserForm').attr('action', "{{ route('users.index') }}/" + id);
    });

});

$(document).on('click', '.delete-user-btn', function (e) {
    e.preventDefault();

    let form = $(this).closest('form');

    Swal.fire({
        title: "Are you sure?",
        text: "This user profile will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete them!",
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