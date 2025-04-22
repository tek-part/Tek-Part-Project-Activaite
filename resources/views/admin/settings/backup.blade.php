@extends('admin.layouts.app')
@section('title') {{ __('admin.backup-settings') }} @endsection
@section('sub-title') {{ __('admin.backup-settings') }} @endsection
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
                    <i class="fas fa-database fa-2x text-primary me-3"></i>
                    <div>
                        <h4 class="mb-1">{{ __('admin.backup-management') }}</h4>
                        <p class="mb-0 text-muted">{{ __('admin.backup-management-desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('diagnostics'))
        <!-- Diagnostics Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-tachometer-alt me-2"></i>{{ __('admin.backup-diagnostics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <h5 class="text-primary mb-1">{{ session('diagnostics')['time'] }} {{ __('admin.seconds') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('admin.total-execution-time') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <h5 class="text-primary mb-1">{{ session('diagnostics')['size'] }}</h5>
                                    <p class="text-muted small mb-0">{{ __('admin.backup-size') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 text-center">
                                    <h5 class="text-primary mb-1">{{ config('database.connections.mysql.database') }}</h5>
                                    <p class="text-muted small mb-0">{{ __('admin.database') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Backup Information Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('admin.backup-information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.application-name') }}:</span>
                                <span class="fw-bold">{{ config('backup.backup.name') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.backup-disk') }}:</span>
                                <span class="fw-bold">{{ implode(', ', config('backup.backup.destination.disks')) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('admin.backup-location') }}:</span>
                                <span class="fw-bold">{{ storage_path('app/Laravel') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="text-muted">{{ __('admin.backup-schedule') }}:</span>
                                <span class="fw-bold">{{ __('admin.daily-at') }} 01:00 AM</span>
                            </div>
                        </div>
                        <hr>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ __('admin.backup-automatic-note') }}
                        </div>
                    </div>
                </div>

                <!-- Backup Action Card -->
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-cogs me-2"></i>{{ __('admin.backup-actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('admin.create-backup-desc') }}</p>
                        <form action="{{ route('admin.settings.backup.create') }}" method="POST" class="mb-4">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i>{{ __('admin.create-new-backup') }}
                            </button>
                        </form>

                        <p class="text-muted mb-3">{{ __('admin.backup-retention-policy') }}</p>
                        <ul class="small text-muted mb-0">
                            <li>{{ __('admin.keep-all-backups-for', ['days' => config('backup.cleanup.default_strategy.keep_all_backups_for_days', 7)]) }}</li>
                            <li>{{ __('admin.keep-daily-backups-for', ['days' => config('backup.cleanup.default_strategy.keep_daily_backups_for_days', 16)]) }}</li>
                            <li>{{ __('admin.keep-weekly-backups-for', ['weeks' => config('backup.cleanup.default_strategy.keep_weekly_backups_for_weeks', 8)]) }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Backup List Card -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>{{ __('admin.available-backups') }}</h5>
                    </div>
                    <div class="card-body">
                        @if(count($backups) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin.date') }}</th>
                                            <th>{{ __('admin.filename') }}</th>
                                            <th>{{ __('admin.size') }}</th>
                                            <th>{{ __('admin.age') }}</th>
                                            <th>{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($backups as $backup)
                                            <tr>
                                                <td>{{ $backup['date'] }}</td>
                                                <td class="text-truncate" style="max-width: 200px;" title="{{ $backup['filename'] }}">
                                                    {{ $backup['filename'] }}
                                                </td>
                                                <td>{{ $backup['size'] }}</td>
                                                <td>{{ $backup['age'] }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.settings.backup.download', $backup['filename']) }}" class="btn btn-sm btn-success me-1" title="{{ __('admin.download-backup') }}">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <form action="{{ route('admin.settings.backup.delete', $backup['filename']) }}" method="POST" onsubmit="return confirm('{{ __('admin.delete-backup-confirm') }}')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('admin.delete-backup') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('admin.no-backups-found') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Backup Guidelines Card -->
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-header bg-dark text-white" style="background-color: #03436b !important;">
                        <h5 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>{{ __('admin.backup-guidelines') }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li class="mb-2">{{ __('admin.backup-guideline-1') }}</li>
                            <li class="mb-2">{{ __('admin.backup-guideline-2') }}</li>
                            <li class="mb-2">{{ __('admin.backup-guideline-3') }}</li>
                            <li class="mb-0">{{ __('admin.backup-guideline-4') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show loading spinner when creating a backup
    document.addEventListener('DOMContentLoaded', function() {
        const backupForm = document.querySelector('form[action="{{ route('admin.settings.backup.create') }}"]');

        if (backupForm) {
            backupForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('admin.creating-backup') }}';
                submitBtn.disabled = true;
            });
        }
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
</style>
@endsection
