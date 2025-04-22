@extends('admin.layouts.app')
@section('title') {{ __('admin.settings') }} @endsection
@section('sub-title') {{ __('admin.settings') }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Company Information Settings -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                            <h4 class="card-title mb-1"><i class="fas fa-building me-2"></i>{{ __('admin.company-info-settings') }}</h4>
                            <p class="opacity-75 small mb-0">{{ __('admin.manage-company-details') }}</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                @if ($setting->logo)
                                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="img-thumbnail" width="100">
                                @else
                                    <div class="mb-3 text-center">
                                        <i class="fas fa-building fa-3x text-primary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <h5 class="fw-bold mb-3">{{ $setting->name }}</h5>
                                <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i> {{ $setting->email }}</p>
                                <p class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i> {{ $setting->address }}</p>
                                <p class="text-muted mb-1"><i class="fas fa-phone-alt me-2"></i> {{ $setting->phone1 }}</p>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-edit me-2"></i>{{ __('admin.edit-company-settings') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Backup Settings -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                            <h4 class="card-title mb-1"><i class="fas fa-database me-2"></i>{{ __('admin.backup-settings') }}</h4>
                            <p class="opacity-75 small mb-0">{{ __('admin.manage-system-backups') }}</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 text-center">
                                <i class="fas fa-hdd fa-3x text-primary mb-3"></i>
                                <h5 class="fw-bold mb-3">{{ __('admin.data-backup-management') }}</h5>
                                <p class="text-muted mb-4">{{ __('admin.backup-settings-desc') }}</p>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.settings.backup') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-database me-2"></i>{{ __('admin.manage-backups') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cache Settings -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                            <h4 class="card-title mb-1"><i class="fas fa-bolt me-2"></i>{{ __('admin.cache-settings') }}</h4>
                            <p class="opacity-75 small mb-0">{{ __('admin.manage-application-cache') }}</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 text-center">
                                <i class="fas fa-memory fa-3x text-primary mb-3"></i>
                                <h5 class="fw-bold mb-3">{{ __('admin.system-cache') }}</h5>
                                <p class="text-muted mb-4">{{ __('admin.cache-settings-desc') }}</p>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.settings.cache') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-broom me-2"></i>{{ __('admin.manage-cache') }}
                                </a>
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
    :root {
        --custom-primary: #03436b;
    }

    .custom-primary-bg {
        background-color: #03436b !important;
    }

    .custom-primary-text {
        color: #03436b !important;
    }

    .text-primary {
        color: #03436b !important;
    }

    .btn-primary {
        background-color: #03436b !important;
        border-color: #03436b !important;
    }

    .btn-primary:hover {
        background-color: #02375a !important;
        border-color: #02375a !important;
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

    .img-thumbnail {
        border-color: #03436b;
        border-radius: 12px;
    }

    .card {
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .card-header {
        border-radius: 0.75rem 0.75rem 0 0 !important;
        padding: 1rem 1.25rem;
    }
</style>
@endsection
