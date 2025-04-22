@extends('admin.layouts.app')

@section('title', __('admin.edit-company-settings'))

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('admin.edit-company-settings') }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">{{ __('admin.settings') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('admin.edit-company-settings') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('admin.back') }}
                </a>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center bg-light rounded-4 p-3">
                    <i class="fas fa-cog fa-2x text-primary me-3"></i>
                    <div>
                        <h4 class="mb-1">{{ __('admin.edit-company-settings') }}</h4>
                        <p class="mb-0 text-muted">{{ __('admin.edit-company-settings-desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-building me-2"></i>{{ __('admin.edit-company-settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0 rounded-4 h-100">
                                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                                            <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('admin.basic-information') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('admin.company-name') }}</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                                    name="name" value="{{ old('name', $setting->name ?? '') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">{{ __('admin.email') }}</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email', $setting->email ?? '') }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="address" class="form-label">{{ __('admin.address') }}</label>
                                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                                    name="address" value="{{ old('address', $setting->address ?? '') }}">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0 rounded-4 h-100">
                                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                                            <h6 class="card-title mb-0"><i class="fas fa-phone-alt me-2"></i>{{ __('admin.contact-information') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="phone1" class="form-label">{{ __('admin.phone1') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1"
                                                        name="phone1" value="{{ old('phone1', $setting->phone1 ?? '') }}">
                                                </div>
                                                @error('phone1')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone2" class="form-label">{{ __('admin.phone2') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2"
                                                        name="phone2" value="{{ old('phone2', $setting->phone2 ?? '') }}">
                                                </div>
                                                @error('phone2')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0 rounded-4">
                                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                                            <h6 class="card-title mb-0"><i class="fas fa-image me-2"></i>{{ __('admin.logo') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="logo" class="form-label">{{ __('admin.upload-logo') }}</label>
                                                <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                                    name="logo">
                                                @error('logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                @if (isset($setting->logo) && !empty($setting->logo))
                                                    <div class="mt-3 text-center p-3 border rounded bg-light">
                                                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Company Logo"
                                                            class="img-fluid" style="max-height: 120px;">
                                                        <p class="text-muted small mt-2 mb-0">{{ __('admin.current-logo') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0 rounded-4">
                                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                                            <h6 class="card-title mb-0"><i class="fas fa-align-left me-2"></i>{{ __('admin.description') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                                    name="description" rows="5" style="min-height: 120px;">{{ old('description', $setting->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> {{ __('admin.save') }}
                                    </button>
                                </div>
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
    .card {
        overflow: hidden;
    }

    .btn-outline-primary {
        color: #03436b;
        border-color: #03436b;
    }

    .btn-outline-primary:hover {
        background-color: #03436b;
        color: white;
    }

    .text-primary {
        color: #03436b !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .rounded-4 {
        border-radius: 0.75rem !important;
    }

    .btn-primary {
        background-color: #03436b;
        border-color: #03436b;
    }

    .btn-primary:hover {
        background-color: #02304d;
        border-color: #02304d;
    }
</style>
@endsection
