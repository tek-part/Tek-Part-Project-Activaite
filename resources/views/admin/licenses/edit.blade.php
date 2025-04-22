@extends('admin.layouts.app')

@section('title', 'Edit License')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit License</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.licenses.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Licenses
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.licenses.update', $license->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="client_name">Client Name</label>
                            <input type="text" name="client_name" id="client_name" class="form-control" value="{{ old('client_name', $license->client_name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="domain">Domain</label>
                            <input type="text" name="domain" id="domain" class="form-control" value="{{ old('domain', $license->domain) }}" placeholder="Enter domain (optional)">
                            <small class="form-text text-muted">The domain where this license will be used.</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" {{ old('is_active', $license->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                                <small class="form-text text-muted">Whether this license is currently active.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_permanent" id="is_permanent" class="custom-control-input" {{ old('is_permanent', $license->is_permanent) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_permanent">Permanent License</label>
                                <small class="form-text text-muted">If checked, the license will never expire.</small>
                            </div>
                        </div>

                        <div class="form-group" id="expiryDateGroup" style="{{ old('is_permanent', $license->is_permanent) ? 'display: none;' : '' }}">
                            <label for="expires_at">Expiry Date</label>
                            <input type="date" name="expires_at" id="expires_at" class="form-control" value="{{ old('expires_at', $license->expires_at ? $license->expires_at->format('Y-m-d') : '') }}">
                            <small class="form-text text-muted">When this license will expire (leave empty for permanent licenses).</small>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update License
                                </button>
                                <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isPermanentCheckbox = document.getElementById('is_permanent');
        const expiryDateGroup = document.getElementById('expiryDateGroup');

        isPermanentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                expiryDateGroup.style.display = 'none';
                document.getElementById('expires_at').value = '';
            } else {
                expiryDateGroup.style.display = 'block';
            }
        });
    });
</script>
@endpush
@endsection
