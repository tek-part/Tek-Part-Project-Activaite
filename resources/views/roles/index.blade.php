@extends('admin.layouts.app')
@section('title') {{ __('admin.roles') }} @endsection
@section('sub-title') {{ __('admin.roles') }} @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title fw-bold text-primary">{{ __('admin.roles') }}</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">{{ __('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('admin.roles') }}</li>
                            </ol>
                        </div>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0 fw-semibold text-primary">{{ __('admin.roles-details') }}</h5>
                                </div>
                                <div class="col-auto">
                                    @permission('roles-create')
                                        <a href="{{ route('roles.create') }}" class="btn btn-primary text-white rounded-pill shadow-sm">
                                            <i class="fas fa-plus-circle me-1"></i> {{ __('admin.roles-create') }}
                                        </a>
                                    @endpermission
                                </div>
                            </div>
                        </div><!--end card-header-->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table custom-table table-hover mb-0" id="datatable_1">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="border-0 rounded-start ps-4 py-3 text-dark">{{ __('admin.roles-name') }}</th>
                                            <th class="border-0 py-3 text-dark">{{ __('admin.roles-permissions') }}</th>
                                            <th class="border-0 rounded-end text-end pe-4 py-3 text-dark">{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $role)
                                            <tr class="border-top">
                                                <td class="fw-medium ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-user-shield"></i>
                                                        </div>
                                                        <span>{{ $role->display_name }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($role->permissions as $permission)
                                                            <span class="badge bg-soft-primary text-primary rounded-pill px-2 py-1 fs-12">{{ $permission->display_name }}</span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        @permission('roles-edit')
                                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-soft-primary btn-sm rounded-pill" data-bs-toggle="tooltip" title="{{ __('admin.edit') }}">
                                                                <i class="fas fa-edit me-1"></i> {{ __('admin.edit') }}
                                                            </a>
                                                        @endpermission
                                                        @permission('roles-delete')
                                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-soft-danger btn-sm rounded-pill" data-bs-toggle="tooltip" title="{{ __('admin.delete') }}"
                                                                    @if ($role->name == 'superadmin') disabled @endif
                                                                    onclick="return confirm('{{ __('admin.confirm-delete') }}')">
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
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div><!-- container -->
    </div>

    <style>
        .custom-table {
            --bs-table-hover-bg: rgba(245, 247, 250, 0.7);
            --bs-table-hover-color: inherit;
        }

        .custom-table thead th {
            font-weight: 600;
            letter-spacing: 0.03em;
            border-top: 0;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
        }

        .custom-table tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }

        .fs-12 {
            font-size: 12px;
        }
    </style>
@endsection
