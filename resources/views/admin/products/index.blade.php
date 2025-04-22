@extends('admin.layouts.app')
@section('title') {{ __('admin.products') ?? 'Products' }} @endsection
@section('sub-title') {{ __('admin.products') ?? 'Products' }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center" style="background-color: #03436b !important;">
                            <div>
                                <h4 class="card-title mb-1"><i class="fas fa-boxes me-2"></i>{{ __('admin.products-list') ?? 'Products List' }}</h4>
                                <p class="opacity-75 small mb-0">{{ __('admin.manage-products') ?? 'Manage your products' }}</p>
                            </div>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle me-1"></i> {{ __('admin.add-product') ?? 'Add Product' }}
                            </a>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-dark" style="background-color: #03436b !important;">
                                        <tr>
                                            <th scope="col" width="80">{{ __('admin.id') ?? 'ID' }}</th>
                                            <th scope="col">{{ __('admin.product') ?? 'Product' }}</th>
                                            <th scope="col">{{ __('admin.category') ?? 'Category' }}</th>
                                            <th scope="col">{{ __('admin.price') ?? 'Price' }}</th>
                                            <th scope="col">{{ __('admin.status') ?? 'Status' }}</th>
                                            <th scope="col">{{ __('admin.created') ?? 'Created' }}</th>
                                            <th scope="col" width="200">{{ __('admin.actions') ?? 'Actions' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                            <tr>
                                                <td>{{ $product->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3" style="width: 40px; height: 40px; overflow: hidden;">
                                                            @if($product->image_path)
                                                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                    <i class="fas fa-box text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                                            <small class="text-muted">{{ $product->sku ?? $product->product_id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($product->price, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $product->is_active ? __('admin.active') ?? 'Active' : __('admin.inactive') ?? 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>{{ $product->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-warning' : 'btn-success' }}">
                                                                <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('admin.confirm-delete') ?? 'Are you sure you want to delete this product?' }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-box fa-3x mb-3"></i>
                                                        <p>{{ __('admin.no-products') ?? 'No products found' }}</p>
                                                        <a href="{{ route('admin.products.create') }}" class="btn btn-sm text-white" style="background-color: #03436b !important;">
                                                            <i class="fas fa-plus-circle me-1"></i> {{ __('admin.create-first-product') ?? 'Create your first product' }}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->links() }}
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

    .pagination {
        justify-content: center;
    }

    .page-item.active .page-link {
        background-color: #03436b;
        border-color: #03436b;
    }

    .page-link {
        color: #03436b;
    }

    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endsection
