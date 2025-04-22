@extends('admin.layouts.app')
@section('title') {{ __('admin.licenses') }} @endsection
@section('sub-title') {{ __('admin.licenses') }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">{{ __('admin.licenses') }}</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">{{ __('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('admin.licenses') }}</li>
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
                                    <h5 class="text-primary fw-semibold mb-0">{{ __('admin.licenses-details') }}</h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.licenses.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        Generate New License
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success m-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Search and Filters -->
                        <div class="card-body border-bottom">
                            <form action="{{ route('admin.licenses.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ __('admin.search') }}..." name="search" value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-filter me-1"></i> {{ __('admin.filter') }}
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-redo me-1"></i> {{ __('admin.reset') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0">ID</th>
                                            <th class="border-0">License Key</th>
                                            <th class="border-0">Product</th>
                                            <th class="border-0">Client</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Expiration Date</th>
                                            <th class="text-end border-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($licenses as $license)
                                            <tr class="border-top">
                                                <td>{{ $license->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <code class="me-2">{{ $license->license_key }}</code>
                                                        <button class="btn btn-sm btn-soft-secondary copy-btn rounded-pill" data-clipboard-text="{{ $license->license_key }}" title="Copy to clipboard">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <i class="fas fa-box text-muted me-1"></i>
                                                    <span class="text-muted">{{ $license->product->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-user text-muted me-1"></i>
                                                    <span class="text-muted">{{ $license->client->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    @if($license->status == 'active')
                                                        <span class="badge bg-soft-success text-success rounded-pill">Active</span>
                                                    @elseif($license->status == 'expired')
                                                        <span class="badge bg-soft-danger text-danger rounded-pill">Expired</span>
                                                    @elseif($license->status == 'revoked')
                                                        <span class="badge bg-soft-dark text-dark rounded-pill">Revoked</span>
                                                    @else
                                                        <span class="badge bg-soft-warning text-warning rounded-pill">{{ ucfirst($license->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                    @if($license->expiration_date)
                                                        <span class="text-muted">{{ $license->expiration_date->format('Y-m-d') }}</span>
                                                        @if($license->expiration_date->isPast())
                                                            <span class="badge bg-soft-danger text-danger rounded-pill">Expired</span>
                                                        @elseif($license->expiration_date->diffInDays(now()) < 30)
                                                            <span class="badge bg-soft-warning text-warning rounded-pill">Expiring Soon</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-soft-success text-success rounded-pill">Lifetime</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-soft-info btn-sm rounded-pill">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                        <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-soft-primary btn-sm rounded-pill">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        @if($license->status != 'revoked')
                                                            <form action="{{ route('admin.licenses.revoke', $license) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-soft-danger btn-sm rounded-pill"
                                                                    onclick="return confirm('Are you sure you want to revoke this license?')">
                                                                    <i class="fas fa-ban me-1"></i> Revoke
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">No licenses found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bootstrap Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $licenses->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize clipboard.js
        new ClipboardJS('.copy-btn').on('success', function(e) {
            // Show tooltip or notification
            alert('License key copied to clipboard');
            e.clearSelection();
        });
    });
</script>
@endpush
@endsection
