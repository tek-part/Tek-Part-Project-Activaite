@extends('admin.layouts.app')
@section('title') {{ __('admin.users') }} @endsection
@section('sub-title') {{ __('admin.users') }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">{{ __('admin.users') }}</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">{{ __('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('admin.users') }}</li>
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
                                    <h5 class="text-primary fw-semibold mb-0">{{ __('admin.users-details') }}</h5>
                                </div>
                                <div class="col-auto">
                                    @permission('users-create')
                                        <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
                                            <i class="fas fa-user-plus me-1"></i>
                                            {{ __('admin.users-create') }}
                                        </a>
                                    @endpermission
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filters -->
                        <div class="card-body border-bottom">
                            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ __('admin.search') }}..." name="search" value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="role" class="form-select">
                                        <option value="">{{ __('admin.all_roles') }}</option>
                                        @foreach(\App\Models\Role::all() as $role)
                                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                {{ $role->display_name ?? $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-filter me-1"></i> {{ __('admin.filter') }}
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-redo me-1"></i> {{ __('admin.reset') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="datatable_1">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0">{{ __('admin.users-name') }}</th>
                                            <th class="border-0">{{ __('admin.users-email') }}</th>
                                            <th class="border-0">{{ __('admin.users-role') }}</th>
                                            <th class="border-0">{{ __('admin.users-phone') }}</th>
                                            <th class="border-0">{{ __('admin.users-address') }}</th>
                                            <th class="text-end border-0">{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr class="border-top">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2 bg-soft-primary rounded-circle">
                                                            <span class="avatar-title text-primary">{{ substr($user->name, 0, 1) }}</span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><i class="fas fa-envelope text-muted me-1"></i> <span class="text-muted">{{ $user->email }}</span></td>
                                                <td>
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-soft-success text-success rounded-pill">{{ $role->display_name ?? $role->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                                    @endforeach
                                                </td>
                                                <td><i class="fas fa-phone-alt text-muted me-1"></i> <span class="text-muted">{{ $user->phone ?? '+1 234 567 890' }}</span></td>
                                                <td><i class="fas fa-map-marker-alt text-muted me-1"></i> <span class="text-muted">{{ $user->address }}</span></td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        @permission('users-edit')
                                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-soft-primary btn-sm rounded-pill" data-bs-toggle="tooltip" title="{{ __('admin.edit') }}">
                                                                <i class="fas fa-edit me-1"></i> {{ __('admin.edit') }}
                                                            </a>
                                                        @endpermission

                                                        @permission('users-delete')
                                                            <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-soft-danger btn-sm rounded-pill"
                                                                    onclick="return confirm('{{ __('admin.confirm-delete') }}')"
                                                                    data-bs-toggle="tooltip" title="{{ __('admin.delete') }}">
                                                                    <i class="fas fa-trash-alt me-1"></i> {{ __('admin.delete') }}
                                                                </button>
                                                            </form>
                                                        @endpermission
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bootstrap Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
