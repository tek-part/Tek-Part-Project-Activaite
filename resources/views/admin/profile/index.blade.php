@extends('admin.layouts.app')
@section('title') {{ __('translations.profile') }} @endsection
@section('sub-title') {{ __('translations.profile') }} @endsection
@section('content')
<div class="page-content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Profile Overview Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 rounded-4 shadow-sm" style="background-color: #f8f9fa;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <div class="me-md-4 mb-3 mb-md-0 text-center">
                                @if($user->image)
                                    <img src="{{ asset('storage/'.$user->image) }}" alt="User Avatar" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="avatar avatar-xxl rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <span class="fs-1">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 text-center text-md-start">
                                <h3 class="mb-1 fw-bold">{{ $user->name }}</h3>
                                <div class="mb-2 text-muted">
                                    <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                                    @if($user->phone)
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-phone me-2"></i>{{ $user->phone }}
                                    @endif
                                </div>
                                @if($user->address)
                                    <div class="mb-2 text-muted">
                                        <i class="fas fa-map-marker-alt me-2"></i>{{ $user->address }}
                                    </div>
                                @endif
                                <div class="mt-3">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 me-2">
                                        <i class="fas fa-shield-alt me-1"></i>{{ $user->roles[0]->name ?? __('translations.user') }}
                                    </span>
                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>{{ __('translations.member_since') }}: {{ $user->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3 border-0">
                        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>{{ __('translations.personal_information') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">{{ __('translations.full_name') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">{{ __('translations.email_address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold">{{ __('translations.phone_number') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label fw-bold">{{ __('translations.address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="1">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-save me-2"></i>{{ __('translations.update_information') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3 border-0">
                        <h5 class="mb-0"><i class="fas fa-lock me-2"></i>{{ __('translations.change_password') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="old_password" class="form-label fw-bold">{{ __('translations.current_password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" name="old_password" required>
                                    </div>
                                    @error('old_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold">{{ __('translations.new_password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">{{ __('translations.confirm_password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-check-circle"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-key me-2"></i>{{ __('translations.update_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Profile Picture -->
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3 border-0">
                        <h5 class="mb-0"><i class="fas fa-camera me-2"></i>{{ __('translations.profile_picture') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update.picture') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center mb-4">
                                @if($user->image)
                                    <img src="{{ asset('storage/'.$user->image) }}" alt="Profile Picture" class="img-fluid rounded-circle border shadow" style="width: 180px; height: 180px; object-fit: cover;">
                                @else
                                    <div class="avatar mx-auto rounded-circle d-flex align-items-center justify-content-center bg-light text-primary" style="width: 180px; height: 180px;">
                                        <i class="fas fa-user fa-6x"></i>
                                    </div>
                                @endif

                                <div class="small text-muted mt-2">
                                    {{ __('translations.profile_picture_recommendations') }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">{{ __('translations.select_new_image') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-upload"></i></span>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                                </div>
                                <div class="form-text small">
                                    <i class="fas fa-info-circle me-1"></i>{{ __('translations.allowed_image_formats') }}
                                </div>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 w-100">
                                    <i class="fas fa-camera me-2"></i>{{ __('translations.update_picture') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Information -->
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-info text-white py-3 border-0">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>{{ __('translations.security_info') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-3 d-flex justify-content-between">
                                <span class="fw-bold"><i class="fas fa-clock me-2 text-primary"></i>{{ __('translations.last_login') }}</span>
                                <span>{{ now()->format('d/m/Y H:i') }}</span>
                            </li>
                            <li class="list-group-item px-0 py-3 d-flex justify-content-between">
                                <span class="fw-bold"><i class="fas fa-shield-alt me-2 text-primary"></i>{{ __('translations.account_status') }}</span>
                                <span class="badge bg-info">{{ __('translations.active') }}</span>
                            </li>
                            <li class="list-group-item px-0 py-3 d-flex justify-content-between">
                                <span class="fw-bold"><i class="fas fa-user-shield me-2 text-primary"></i>{{ __('translations.role') }}</span>
                                <span>{{ $user->roles[0]->name ?? __('translations.user') }}</span>
                            </li>
                        </ul>

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('translations.security_recommendation') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #03436b;
        box-shadow: 0 0 0 0.25rem rgba(3, 67, 107, 0.1);
    }

    .bg-primary,
    .btn-primary {
        background-color: #03436b !important;
        border-color: #03436b !important;
    }

    .btn-primary:hover {
        background-color: #02375a !important;
        border-color: #02375a !important;
    }

    .text-primary {
        color: #03436b !important;
    }

    .bg-info {
        background-color: #546e7a !important;
        border-color: #546e7a !important;
    }

    .card-header {
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }

    .rounded-4 {
        border-radius: 0.75rem !important;
    }

    .input-group-text {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    /* RTL Support */
    [dir="rtl"] .me-2,
    [dir="rtl"] .me-3,
    [dir="rtl"] .me-4 {
        margin-right: 0 !important;
    }

    [dir="rtl"] .me-2 {
        margin-left: 0.5rem !important;
    }

    [dir="rtl"] .me-3 {
        margin-left: 1rem !important;
    }

    [dir="rtl"] .me-4 {
        margin-left: 1.5rem !important;
    }

    [dir="rtl"] .ms-3 {
        margin-left: 0 !important;
        margin-right: 1rem !important;
    }

    [dir="rtl"] .text-end {
        text-align: left !important;
    }

    [dir="rtl"] .input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-right: -1px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }

    [dir="rtl"] .input-group:not(.has-validation) > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not(.form-floating) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
</style>
@endsection