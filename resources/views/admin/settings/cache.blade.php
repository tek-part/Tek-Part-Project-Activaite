@extends('admin.layouts.app')
@section('title') {{ __('admin.cache-settings') }} @endsection
@section('sub-title') {{ __('admin.cache-settings') }} @endsection
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Back Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('admin.back-to-settings') }}
                </a>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center bg-light rounded-4 p-3">
                    <i class="fas fa-bolt fa-2x text-primary me-3"></i>
                    <div>
                        <h4 class="mb-1">{{ __('admin.cache-management') }}</h4>
                        <p class="mb-0 text-muted">{{ __('admin.cache-management-desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Cache Information -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('admin.cache-information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.cache-driver') }}:</span>
                                <span class="fw-bold text-capitalize">{{ $cacheStats['driver'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.cache-ttl') }}:</span>
                                <span class="fw-bold">{{ $cacheStats['ttl'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.cache-prefix') }}:</span>
                                <span class="fw-bold">{{ $cacheStats['prefix'] }}</span>
                            </div>
                            @if($cacheStats['driver'] === 'file')
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.cache-file-count') }}:</span>
                                <span class="fw-bold">{{ $cacheStats['file_count'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="text-muted">{{ __('admin.cache-size') }}:</span>
                                <span class="fw-bold">{{ $cacheStats['total_size'] }}</span>
                            </div>
                            @endif
                        </div>
                        <hr>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ __('admin.cache-automatic-note') }}
                        </div>
                    </div>
                </div>

                <!-- Cache Actions -->
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-cogs me-2"></i>{{ __('admin.cache-actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <form action="{{ route('admin.settings.cache.clear') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-broom me-2"></i>{{ __('admin.clear-all-cache') }}
                                    </button>
                                </form>
                            </div>
                            <div class="col-12">
                                <form action="{{ route('admin.settings.cache.optimize') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-rocket me-2"></i>{{ __('admin.optimize-cache') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache Details -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-layer-group me-2"></i>{{ __('admin.cache-details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ __('admin.what-is-cached') }}:</h6>
                            <ul class="mb-0">
                                <li class="mb-2">{{ __('admin.cached-item-1') }}</li>
                                <li class="mb-2">{{ __('admin.cached-item-2') }}</li>
                                <li class="mb-2">{{ __('admin.cached-item-3') }}</li>
                                <li class="mb-0">{{ __('admin.cached-item-4') }}</li>
                            </ul>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ __('admin.cache-types') }}:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('admin.cache-type') }}</th>
                                            <th>{{ __('admin.description') }}</th>
                                            <th>{{ __('admin.cleared-by') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium">{{ __('admin.application-cache') }}</td>
                                            <td>{{ __('admin.application-cache-desc') }}</td>
                                            <td><code>cache:clear</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('admin.configuration-cache') }}</td>
                                            <td>{{ __('admin.configuration-cache-desc') }}</td>
                                            <td><code>config:clear</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('admin.route-cache') }}</td>
                                            <td>{{ __('admin.route-cache-desc') }}</td>
                                            <td><code>route:clear</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">{{ __('admin.view-cache') }}</td>
                                            <td>{{ __('admin.view-cache-desc') }}</td>
                                            <td><code>view:clear</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optimization Information -->
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-tachometer-alt me-2"></i>{{ __('admin.optimization-info') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-lightbulb fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">{{ __('admin.when-to-clear-cache') }}</h6>
                                    <p class="mb-0">{{ __('admin.when-to-clear-cache-desc') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-rocket fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">{{ __('admin.when-to-optimize') }}</h6>
                                    <p class="mb-0">{{ __('admin.when-to-optimize-desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show loading spinner when submitting forms
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalContent = submitBtn.innerHTML;
                const isOptimize = form.action.includes('optimize');

                submitBtn.disabled = true;

                if (isOptimize) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('admin.optimizing-cache') }}';
                } else {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('admin.clearing-cache') }}';
                }
            });
        });
    });
</script>
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

    code {
        background-color: #f7f7f9;
        padding: 2px 6px;
        border-radius: 3px;
        color: #e83e8c;
    }
</style>
@endsection
