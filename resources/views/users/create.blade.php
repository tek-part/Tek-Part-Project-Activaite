@extends('admin.layouts.app')
@section('title') {{ __('translations.users') }} @endsection
@section('sub-title') {{ __('translations.users') }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                            <h4 class="card-title mb-1"><i class="fas fa-user-plus me-2"></i>{{ __('translations.users-create') }}</h4>
                            <p class="opacity-75 small mb-0">{{ __('translations.fill-details-to-create-user') }}</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label fw-bold">
                                                <i class="fas fa-user custom-primary-text me-2"></i>{{ __('translations.users-name') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label fw-bold">
                                                <i class="fas fa-envelope custom-primary-text me-2"></i>{{ __('translations.users-email') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="role" class="form-label fw-bold">
                                                <i class="fas fa-user-tag custom-primary-text me-2"></i>{{ __('translations.users-role') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label fw-bold">
                                                <i class="fas fa-lock custom-primary-text me-2"></i>{{ __('translations.users-password') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="password_confirmation" class="form-label fw-bold">
                                                <i class="fas fa-lock custom-primary-text me-2"></i>{{ __('translations.users-repassword') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="phone" class="form-label fw-bold">
                                                <i class="fas fa-phone-alt custom-primary-text me-2"></i>{{ __('translations.users-phone') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="address" class="form-label fw-bold">
                                                <i class="fas fa-map-marker-alt custom-primary-text me-2"></i>{{ __('translations.users-address') }}
                                            </label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-image custom-primary-text me-2"></i>{{ __('translations.articles-image') }}
                                                <span class="badge bg-danger ms-1" title="مطلوب"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*" required>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 mb-2">
                                    <button type="submit" class="btn text-white px-4 py-2" style="background-color: #03436b !important; min-width: 140px; font-size: 1rem; font-weight: bold; box-shadow: 0 4px 10px rgba(3, 67, 107, 0.3); border-radius: 8px;">
                                        <i class="fas fa-save me-2"></i> {{ __('translations.create') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    :root {
        --custom-primary: #03436b;
    }

    .custom-primary-bg {
        background-color: #03436b !important;
    }

    .custom-primary-text {
        color: #03436b !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #03436b;
        box-shadow: 0 0 0 0.25rem rgba(3, 67, 107, 0.1);
    }

    .form-group {
        position: relative;
        transition: all 0.2s ease-in-out;
    }

    .form-group:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-size: 70%;
        font-weight: 400;
    }

    .form-control,
    .form-select {
        padding: 0.6rem 1rem;
        font-size: 1rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
        transition: all 0.3s;
    }

    .form-label {
        font-weight: 600;
        color: #03436b;
        margin-bottom: 0.5rem;
    }
</style>
@endsection
