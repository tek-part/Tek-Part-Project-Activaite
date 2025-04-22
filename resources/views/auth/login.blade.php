@extends('admin.layouts.auth')
@section('title') {{ __('admin.login') }} @endsection
@section('sub-title') {{ __('admin.login') }} @endsection
@section('content')
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="card-body p-0 bg-gradient-primary auth-header-box rounded-top">
                                <div class="text-center p-4">
                                    <a href="{{ URL::to('/') }}" class="logo logo-admin">
                                        @if ($setting->logo)
                                            <img src="{{ asset('storage/' . $setting->logo) }}" style="height: 200px;" alt="{{ __('admin.logo') }}"
                                                class="auth-logo img-fluid">
                                        @else
                                            <img src="{{ asset('assets/images/logo-sm.png') }}" style="height: 200px;" alt="{{ __('admin.logo') }}"
                                                class="auth-logo img-fluid">
                                        @endif
                                    </a>
                                    <h3 class="mt-3 mb-1 fw-bold text-white">{{ __('admin.lets_get_started') }}</h3>
                                    <p class="text-white-50 fs-16 mb-0">{{ __('admin.sign_in_to_continue') }}</p>
                                </div>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <div class="login-form mt-2">
                                    <form class="my-4" action="{{ route('login') }}" method="post">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold" for="username">{{ __('admin.email') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="far fa-envelope"></i></span>
                                                <input type="email" name="email" class="form-control form-control-lg bg-light border-0 ps-2"
                                                    placeholder="{{ __('admin.email') }}" required />
                                            </div>
                                            @error('email')
                                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div><!--end form-group-->

                                        <div class="form-group mb-4">
                                            <label class="form-label fw-semibold"
                                                for="userpassword">{{ __('admin.password') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock"></i></span>
                                                <input type="password" name="password" class="form-control form-control-lg bg-light border-0 ps-2"
                                                    placeholder="{{ __('admin.password') }}" required />
                                            </div>
                                            @error('password')
                                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div><!--end form-group-->

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary btn-lg rounded-pill" type="submit">
                                                        {{ __('admin.login') }} <i class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div>
                                            </div><!--end col-->
                                        </div> <!--end form-group-->
                                    </form><!--end form-->


                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #2b4cff 0%, #03436b 100%);
        }
        .rounded-4 {
            border-radius: 12px !important;
        }
        .social-btn {
            width: 40px;
            height: 40px;
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            transform: translateY(-3px);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #2b4cff;
        }
        .btn-primary {
            background: #2b4cff;
            border-color: #2b4cff;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #1e38b1;
            border-color: #1e38b1;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(43, 76, 255, 0.2);
        }
    </style>
@endsection
