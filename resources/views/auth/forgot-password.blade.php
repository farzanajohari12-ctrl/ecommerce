@extends('layouts.app')
@section('content')
  <!-- Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Forgot Password</h1>
                  </div>
                  <!-- Validation Errors -->
                  <x-auth-validation-errors class="mb-4" :errors="$errors" />
                  
                  {{-- Flash Messages --}}
                  @if (session('success'))
                      <div class="alert alert-success">{{ session('success') }}</div>
                  @endif
                  @if (session('error'))
                      <div class="alert alert-danger">{{ session('error') }}</div>
                  @endif

                  <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                      <label>Email address</label>
                      <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                        placeholder="Enter Email Address">
                    </div>
                    
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
                    </div>
                    <hr>
                  </form>
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Content -->
@section('content')
