@extends('admin.layouts.app')

@section('title') {{ __('admin.license-details') ?? 'License Details' }} @endsection
@section('sub-title') {{ __('admin.license-details') ?? 'License Details' }} @endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center" style="background-color: #03436b !important;">
                            <div>
                                <h4 class="card-title mb-1"><i class="fas fa-key me-2"></i>{{ $license->license_key }}</h4>
                                <p class="opacity-75 small mb-0">{{ __('admin.view-license-details') ?? 'View license details' }}</p>
                            </div>
                            <div class="d-flex">
                                <a href="{{ route('admin.licenses.edit', $license->id) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i> {{ __('admin.edit') ?? 'Edit' }}
                                </a>
                                <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('admin.back') ?? 'Back' }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.license-key') ?? 'License Key' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->license_key }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.client-name') ?? 'Client Name' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->client_name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.product') ?? 'Product' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->product->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.domain') ?? 'Domain' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->domain ?? 'Not specified' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.status') ?? 'Status' }}</span>
                                                </div>
                                                <h5>
                                                    <span class="badge bg-{{ $license->is_active ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $license->is_active ? __('admin.active') ?? 'Active' : __('admin.inactive') ?? 'Inactive' }}
                                                    </span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.license-type') ?? 'License Type' }}</span>
                                                </div>
                                                <h5>
                                                    <span class="badge bg-{{ $license->is_permanent ? 'primary' : 'info' }} rounded-pill">
                                                        {{ $license->is_permanent ? __('admin.permanent') ?? 'Permanent' : __('admin.time-limited') ?? 'Time-limited' }}
                                                    </span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.activation-code') ?? 'Activation Code' }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <h5 class="custom-primary-text mb-0">{{ $license->activation_code }}</h5>
                                                    <form action="{{ route('admin.licenses.regenerate-code', $license->id) }}" method="POST" class="ms-3">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-sync me-1"></i> {{ __('admin.regenerate') ?? 'Regenerate' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @if(!$license->is_permanent)
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.expires-at') ?? 'Expires At' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->expires_at ? $license->expires_at->format('Y-m-d') : 'Not specified' }}</h5>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.created-at') ?? 'Created At' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->created_at->format('Y-m-d H:i') }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.updated-at') ?? 'Last Updated' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $license->updated_at->format('Y-m-d H:i') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">{{ __('admin.actions') ?? 'Actions' }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-3">
                                                @if($license->is_active)
                                                    <form action="{{ route('admin.licenses.deactivate', $license->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger w-100">
                                                            <i class="fas fa-ban me-2"></i> {{ __('admin.deactivate-license') ?? 'Deactivate License' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.licenses.activate', $license->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success w-100">
                                                            <i class="fas fa-check me-2"></i> {{ __('admin.activate-license') ?? 'Activate License' }}
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('admin.licenses.destroy', $license->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.confirm-delete') ?? 'Are you sure you want to delete this license?' }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger w-100">
                                                        <i class="fas fa-trash-alt me-2"></i> {{ __('admin.delete-license') ?? 'Delete License' }}
                                                    </button>
                                                </form>
                                            </div>

                                            @if(count($license->logs) > 0)
                                                <div class="mt-4">
                                                    <h5 class="custom-primary-text mb-3">{{ __('admin.license-logs') ?? 'License Logs' }}</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead class="table-dark" style="background-color: #03436b !important;">
                                                                <tr>
                                                                    <th>{{ __('admin.date') ?? 'Date' }}</th>
                                                                    <th>{{ __('admin.action') ?? 'Action' }}</th>
                                                                    <th>{{ __('admin.ip-address') ?? 'IP Address' }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($license->logs as $log)
                                                                    <tr>
                                                                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                                                        <td>{{ $log->action }}</td>
                                                                        <td>{{ $log->ip_address }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> {{ __('admin.back-to-list') ?? 'Back to List' }}
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

    .info-box {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .info-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection
