@extends('admin.layouts.app')
@section('title') {{ __('admin.products-details') ?? 'Product Details' }} @endsection
@section('sub-title') {{ __('admin.products-details') ?? 'Product Details' }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center" style="background-color: #03436b !important;">
                            <div>
                                <h4 class="card-title mb-1"><i class="fas fa-box me-2"></i>{{ $product->name }}</h4>
                                <p class="opacity-75 small mb-0">{{ __('admin.view-product-details') ?? 'View product details' }}</p>
                            </div>
                            <div class="d-flex">
                                <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn {{ $product->is_active ? 'btn-warning' : 'btn-success' }} btn-sm">
                                        <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check-circle' }} me-1"></i>
                                        {{ $product->is_active ? __('admin.deactivate') ?? 'Deactivate' : __('admin.activate') ?? 'Activate' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i> {{ __('admin.edit') ?? 'Edit' }}
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.confirm-delete') ?? 'Are you sure you want to delete this product?' }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt me-1"></i> {{ __('admin.delete') ?? 'Delete' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-0 d-flex align-items-center justify-content-center" style="min-height: 250px; background-color: #f8f9fa;">
                                            @if($product->image_path)
                                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                            @else
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-image fa-5x mb-3"></i>
                                                    <p>{{ __('admin.no-image') ?? 'No image available' }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.products-name') ?? 'Product Name' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $product->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.products-price') ?? 'Price' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ number_format($product->price, 2) }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.products-id') ?? 'Product ID' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $product->product_id }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.products-sku') ?? 'SKU' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $product->sku ?? 'N/A' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.products-status') ?? 'Status' }}</span>
                                                </div>
                                                <h5>
                                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $product->is_active ? __('admin.active') ?? 'Active' : __('admin.inactive') ?? 'Inactive' }}
                                                    </span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.products-category') ?? 'Category' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $product->category->name ?? 'N/A' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.created-at') ?? 'Created At' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $product->created_at->format('M d, Y H:i') }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-light rounded p-3">
                                                <div class="mb-2">
                                                    <span class="text-muted small">{{ __('admin.updated-at') ?? 'Updated At' }}</span>
                                                </div>
                                                <h5 class="custom-primary-text">{{ $product->updated_at->format('M d, Y H:i') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="info-box bg-light rounded p-3">
                                        <div class="mb-2">
                                            <span class="text-muted small">{{ __('admin.products-description') ?? 'Description' }}</span>
                                        </div>
                                        <div class="custom-primary-text">
                                            {{ $product->description ?? 'No description available' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="custom-primary-text mb-3">{{ __('admin.licenses') ?? 'Licenses' }}</h5>
                                @if($product->licenses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead class="table-dark" style="background-color: #03436b !important;">
                                                <tr>
                                                    <th>{{ __('admin.license-code') ?? 'License Code' }}</th>
                                                    <th>{{ __('admin.license-email') ?? 'Email' }}</th>
                                                    <th>{{ __('admin.license-status') ?? 'Status' }}</th>
                                                    <th>{{ __('admin.license-expires') ?? 'Expires' }}</th>
                                                    <th>{{ __('admin.license-actions') ?? 'Actions' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($product->licenses as $license)
                                                    <tr>
                                                        <td>{{ $license->code }}</td>
                                                        <td>{{ $license->email }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $license->is_active ? 'success' : 'danger' }} rounded-pill">
                                                                {{ $license->is_active ? __('admin.active') ?? 'Active' : __('admin.inactive') ?? 'Inactive' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $license->expires_at ? $license->expires_at->format('M d, Y') : __('admin.never') ?? 'Never' }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        {{ __('admin.no-licenses') ?? 'No licenses found for this product' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> {{ __('admin.back-to-list') ?? 'Back to List' }}
                                </a>
                                <a href="{{ route('admin.licenses.create') }}" class="btn text-white" style="background-color: #03436b !important;">
                                    <i class="fas fa-plus-circle me-2"></i> {{ __('admin.create-license') ?? 'Create License' }}
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
