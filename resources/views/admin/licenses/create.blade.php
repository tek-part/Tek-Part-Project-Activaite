@extends('admin.layouts.app')
@section('title') {{ __('admin.licenses-create') ?? 'Create License' }} @endsection
@section('sub-title') {{ __('admin.licenses-create') ?? 'Create License' }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                            <h4 class="card-title mb-1"><i class="fas fa-plus-circle me-2"></i>{{ __('admin.licenses-create') ?? 'Generate New License' }}</h4>
                            <p class="opacity-75 small mb-0">{{ __('admin.fill-details-to-create-license') ?? 'Fill in the details to generate a new license key' }}</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.licenses.store') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="client_name" class="form-label fw-bold">
                                                <i class="fas fa-user custom-primary-text me-2"></i>{{ __('admin.licenses-client_name') ?? 'Client Name' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <input type="text" class="form-control @error('client_name') is-invalid @enderror" id="client_name" name="client_name" value="{{ old('client_name') }}" required>
                                            @error('client_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="product_id" class="form-label fw-bold">
                                                <i class="fas fa-box custom-primary-text me-2"></i>{{ __('admin.licenses-product') ?? 'Product' }}
                                                <span class="badge bg-danger ms-1" title="Required"><i class="fas fa-asterisk fa-xs"></i></span>
                                            </label>
                                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                                <option value="">{{ __('admin.select-product') ?? 'Select Product' }}</option>
                                                @foreach($products ?? [] as $product)
                                                    <option value="{{ $product->product_id }}" {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('product_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="domain" class="form-label fw-bold">
                                                <i class="fas fa-globe custom-primary-text me-2"></i>{{ __('admin.licenses-domain') ?? 'Domain' }}
                                            </label>
                                            <input type="text" class="form-control @error('domain') is-invalid @enderror" id="domain" name="domain" value="{{ old('domain') }}" placeholder="example.com">
                                            @error('domain')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch mt-4 pt-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_permanent" name="is_permanent" {{ old('is_permanent') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="is_permanent">
                                                    <i class="fas fa-infinity custom-primary-text me-2"></i>{{ __('admin.licenses-permanent') ?? 'Permanent License' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch mt-4 pt-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="is_active">
                                                    <i class="fas fa-toggle-on custom-primary-text me-2"></i>{{ __('admin.licenses-active') ?? 'Active' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="expiration-date-container">
                                        <div class="form-group mb-3">
                                            <label for="expires_at" class="form-label fw-bold">
                                                <i class="fas fa-calendar-alt custom-primary-text me-2"></i>{{ __('admin.licenses-expiration') ?? 'Expiration Date' }}
                                            </label>
                                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                            @error('expires_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 mb-2">
                                    <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-secondary me-2 px-4 py-2" style="min-width: 140px; font-size: 1rem; font-weight: bold; border-radius: 8px;">
                                        <i class="fas fa-arrow-left me-2"></i> {{ __('admin.back') ?? 'Back' }}
                                    </a>
                                    <button type="submit" class="btn text-white px-4 py-2" style="background-color: #03436b !important; min-width: 140px; font-size: 1rem; font-weight: bold; box-shadow: 0 4px 10px rgba(3, 67, 107, 0.3); border-radius: 8px;">
                                        <i class="fas fa-key me-2"></i> {{ __('admin.generate') ?? 'Generate License' }}
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
    .form-select:focus,
    .form-check-input:focus {
        border-color: #03436b;
        box-shadow: 0 0 0 0.25rem rgba(3, 67, 107, 0.1);
    }

    .form-check-input:checked {
        background-color: #03436b;
        border-color: #03436b;
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
        const isPermanentCheckbox = document.getElementById('is_permanent');
        const expirationDateContainer = document.getElementById('expiration-date-container');

        // Function to toggle expiration date visibility
        function toggleExpirationDate() {
            if (isPermanentCheckbox.checked) {
                expirationDateContainer.style.display = 'none';
            } else {
                expirationDateContainer.style.display = 'block';
            }
        }

        // Set initial state
        toggleExpirationDate();

        // Add event listener
        isPermanentCheckbox.addEventListener('change', toggleExpirationDate);
    });
</script>
@endsection
