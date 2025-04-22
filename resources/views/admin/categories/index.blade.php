@extends('admin.layouts.app')
@section('title') {{ __('admin.categories') ?? 'Categories' }} @endsection
@section('sub-title') {{ __('admin.categories') ?? 'Categories' }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">{{ __('admin.categories') ?? 'Categories' }}</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">{{ __('admin.dashboard') ?? 'Dashboard' }}</a></li>
                                <li class="breadcrumb-item active">{{ __('admin.categories') ?? 'Categories' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="text-primary fw-semibold mb-0">{{ __('admin.categories-details') ?? 'Categories Details' }}</h5>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        {{ __('admin.categories-create') ?? 'Add Category' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filters -->
                        <div class="card-body border-bottom">
                            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ __('admin.search') ?? 'Search' }}..." name="search" value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-filter me-1"></i> {{ __('admin.filter') ?? 'Filter' }}
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-redo me-1"></i> {{ __('admin.reset') ?? 'Reset' }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body pt-0">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="datatable_1">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0">ID</th>
                                            <th class="border-0">{{ __('admin.categories-name') ?? 'Name' }}</th>
                                            <th class="border-0">{{ __('admin.categories-description') ?? 'Description' }}</th>
                                            <th class="border-0">{{ __('admin.categories-products-count') ?? 'Products Count' }}</th>
                                            <th class="border-0">{{ __('admin.created_at') ?? 'Created At' }}</th>
                                            <th class="text-end border-0">{{ __('admin.actions') ?? 'Actions' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories ?? [] as $category)
                                            <tr class="border-top">
                                                <td>{{ $category->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2 bg-soft-primary rounded-circle">
                                                            <span class="avatar-title text-primary">{{ substr($category->name, 0, 1) }}</span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">{{ $category->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit($category->description ?? '-', 50) }}</td>
                                                <td><span class="badge bg-soft-info rounded-pill">{{ $category->products_count ?? 0 }}</span></td>
                                                <td><i class="far fa-calendar-alt text-muted me-1"></i> <span class="text-muted">{{ $category->created_at ? $category->created_at->format('Y-m-d') : '-' }}</span></td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-soft-primary btn-sm rounded-pill edit-category"
                                                            data-id="{{ $category->id }}"
                                                            data-name="{{ $category->name }}"
                                                            data-description="{{ $category->description }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#categoryEditModal">
                                                            <i class="fas fa-edit me-1"></i> {{ __('admin.edit') ?? 'Edit' }}
                                                        </button>
                                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-soft-danger btn-sm rounded-pill"
                                                                onclick="return confirm('{{ __('admin.confirm-delete') ?? 'Are you sure you want to delete this category?' }}')"
                                                                data-bs-toggle="tooltip" title="{{ __('admin.delete') ?? 'Delete' }}">
                                                                <i class="fas fa-trash-alt me-1"></i> {{ __('admin.delete') ?? 'Delete' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                        <h5 class="text-muted">{{ __('admin.no_categories_found') ?? 'No categories found' }}</h5>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bootstrap Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $categories->links('pagination::bootstrap-5') ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="categoryModalLabel">{{ __('admin.categories-create') ?? 'Add Category' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">{{ __('admin.categories-name') ?? 'Name' }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('admin.categories-description') ?? 'Description' }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('admin.save') ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="categoryEditModal" tabindex="-1" aria-labelledby="categoryEditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="categoryEditModalLabel">{{ __('admin.categories-edit') ?? 'Edit Category' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-bold">{{ __('admin.categories-name') ?? 'Name' }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label fw-bold">{{ __('admin.categories-description') ?? 'Description' }}</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('admin.update') ?? 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up edit category modal data
        const editButtons = document.querySelectorAll('.edit-category');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_description').value = description;
                document.getElementById('editCategoryForm').action = `/admin/categories/${id}`;
            });
        });
    });
</script>
@endsection
