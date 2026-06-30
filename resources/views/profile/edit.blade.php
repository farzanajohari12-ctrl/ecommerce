@extends('layouts.app')

@section('content')
<body id="page-top">
<div id="wrapper">

    @include('layouts.sidebar')

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            @include('layouts.topbar')

            <!-- Begin Page Content -->
            <div class="container-fluid px-4 py-4">
                
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800 fw-bold">Account Settings</h1>
                    <span class="text-muted d-none d-sm-inline-block">Manage your profile and security settings</span>
                </div>

                <!-- Content Row -->
                <div class="row g-4">
                    
                    <!-- Left Column: User Overview Summary Card -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card border-0 shadow-sm text-center py-4">
                            <div class="card-body">
                                <div class="mb-3 position-relative d-inline-block">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4e73df&color=fff&size=128" 
                                         class="rounded-circle img-thumbnail shadow-sm" 
                                         alt="Profile Avatar"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <h4 class="font-weight-bold mb-1 text-gray-800">{{ auth()->user()->name }}</h4>
                                <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>
                                <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">
                                    <i class="fas fa-user-shield me-1"></i> Verified Account
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Forms -->
                    <div class="col-xl-8 col-lg-7">
                        
                        <!-- Card: Update Profile -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center">
                                <i class="fas fa-user-edit text-primary mr-3 fs-5"></i>
                                <h6 class="m-0 font-weight-bold text-primary fs-5">Profile Information</h6>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-gray-700">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                            <input name="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold text-gray-700">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                            <input name="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button class="btn btn-primary px-4 shadow-sm">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Card: Change Password -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center">
                                <i class="fas fa-lock text-warning mr-3 fs-5"></i>
                                <h6 class="m-0 font-weight-bold text-warning fs-5">Update Password</h6>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <form method="POST" action="{{ route('profile.password') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-gray-700">Current Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                                            <input type="password" name="current_password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr class="my-4 text-gray-200">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold text-gray-700">New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock-open text-muted"></i></span>
                                                <input type="password" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold text-gray-700">Confirm New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                                <input type="password" name="password_confirmation" class="form-control border-start-0" required>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-warning text-white px-4 shadow-sm mt-2">
                                        <i class="fas fa-shield-alt me-1"></i> Update Password
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

            @include('layouts.footer')

        </div>
    </div>
</div>

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


</body>
@endsection