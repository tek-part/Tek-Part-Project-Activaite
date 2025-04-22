@extends('admin.layouts.app')
@section('title') {{ __('admin.products-edit') ?? 'Edit Product' }} @endsection
@section('sub-title') {{ __('admin.products-edit') ?? 'Edit Product' }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                            <h4 class="card-title mb-1"><i class="fas fa-edit me-2"></i>{{ __('admin.products-edit') ?? 'Edit Product' }}</h4>
                            <p class="opacity-75 small mb-0">{{ __('admin.update-product-details') ?? 'Update product details' }}</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label fw-bold">
                                                <i class="fas fa-box custom-primary-text me-2"></i>{{ __('admin.products-name') ?? 'Product Name' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price" class="form-label fw-bold">
                                                <i class="fas fa-tag custom-primary-text me-2"></i>{{ __('admin.products-price') ?? 'Price' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="description" class="form-label fw-bold">
                                                <i class="fas fa-align-left custom-primary-text me-2"></i>{{ __('admin.products-description') ?? 'Description' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label fw-bold">
                                                <i class="fas fa-toggle-on custom-primary-text me-2"></i>{{ __('admin.products-status') ?? 'Status' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="1" {{ old('status', $product->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>{{ __('admin.active') ?? 'Active' }}</option>
                                                <option value="0" {{ old('status', $product->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>{{ __('admin.inactive') ?? 'Inactive' }}</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category_id" class="form-label fw-bold">
                                                <i class="fas fa-sitemap custom-primary-text me-2"></i>{{ __('admin.products-category') ?? 'Category' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                                <option value="">{{ __('admin.select-category') ?? 'Select Category' }}</option>
                                                @foreach($categories ?? [] as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="sku" class="form-label fw-bold">
                                                <i class="fas fa-barcode custom-primary-text me-2"></i>{{ __('admin.products-sku') ?? 'SKU' }}
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                                                <button type="button" class="btn text-white" id="generate-sku" style="background-color: #03436b;">
                                                    <i class="fas fa-random me-1"></i> {{ __('admin.generate') ?? 'Generate' }}
                                                </button>
                                            </div>
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Current Product Image -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-image custom-primary-text me-2"></i>{{ __('admin.current-image') ?? 'Current Image' }}
                                            </label>
                                            <div class="mt-2">
                                                @if($product->image_path)
                                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 200px;">
                                                @else
                                                    <div class="alert alert-info">
                                                        {{ __('admin.no-image') ?? 'No image available' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-image custom-primary-text me-2"></i>{{ __('admin.products-image') ?? 'Product Image' }}
                                            </label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                                            <small class="text-muted">{{ __('admin.leave-empty-to-keep-current-image') ?? 'Leave empty to keep current image' }}</small>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 mb-2">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-secondary me-2 px-4 py-2" style="min-width: 140px; font-size: 1rem; font-weight: bold; border-radius: 8px;">
                                        <i class="fas fa-arrow-left me-2"></i> {{ __('admin.back') ?? 'Back' }}
                                    </a>
                                    <button type="submit" class="btn text-white px-4 py-2" style="background-color: #03436b !important; min-width: 140px; font-size: 1rem; font-weight: bold; box-shadow: 0 4px 10px rgba(3, 67, 107, 0.3); border-radius: 8px;">
                                        <i class="fas fa-save me-2"></i> {{ __('admin.update') ?? 'Update' }}
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to generate random SKU
        function generateRandomSKU() {
            const prefix = 'PRD';
            const randomDigits = Math.floor(10000 + Math.random() * 90000); // 5-digit random number
            const timestamp = Date.now().toString().slice(-4); // Last 4 digits of timestamp
            return `${prefix}-${randomDigits}-${timestamp}`;
        }

        // Add event listener to the generate button
        document.getElementById('generate-sku').addEventListener('click', function() {
            document.getElementById('sku').value = generateRandomSKU();
        });
    });
</script>
@endsection
