@extends('admin.layouts.app')

@section('title') {{ __('translations.roles-edit') }} @endsection
@section('sub-title') {{ __('translations.roles') }} @endsection

@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ __('translations.roles-edit') }}</h4>
            <p class="card-title-desc">{{ __('translations.fill-details-to-edit-role') }}</p>
          </div>
          <div class="card-body">
            <div class="row justify-content-center">
              <div class="col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row align-items-center">
                      <div class="col">
                        <h4 class="card-title">{{ __('translations.roles-details') }}</h4>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    <form action="{{ route('roles.update', $role->id) }}" method="post">
                      @csrf
                      @method('PUT')

                      <!-- Role Name -->
                      <div class="mb-3">
                        <label for="name" class="form-label">{{ __('translations.roles-name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">
                          <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                      </div>

                      <!-- Display Name -->
                      <div class="mb-3">
                        <label for="display_name" class="form-label">{{ __('translations.roles-displayname') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" required>
                        @error('display_name')
                        <div class="invalid-feedback">
                          <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                      </div>

                      <!-- Permissions Section -->
                      <div class="mb-3">
                        <label class="form-label">{{ __('translations.permissions') }}</label>
                        <div class="row">
                          @foreach ($permissions as $key => $permissionGroup)
                          <div class="col-md-3 mb-3">
                            <div class="card border-secondary">
                              <div class="card-header bg-light text-primary fw-bold">
                                @php $key = explode('--', $key); @endphp
                                {{ __('translations.' . (!empty($key[1]) ? $key[1] : $key[0])) }}
                              </div>
                              <div class="card-body">
                                @foreach ($permissionGroup as $permission)
                                <div class="form-check mb-2">
                                  <input type="checkbox" class="form-check-input" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}"
                                  {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                  <label for="permission_{{ $permission->id }}" class="form-check-label">{{ $permission->display_name }}</label>
                                </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </div>
                      </div>

                      <!-- Submit and Cancel Buttons -->
                      <div class="d-flex justify-content-between pt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                          {{ __('translations.back') }} <i class="fa fa-undo"></i>
                        </a>
                        <button type="submit" class="btn btn-primary">
                          {{ __('translations.update') }} <i class="fa fa-check ms-2"></i>
                        </button>
                      </div>
                    </form>
                  </div><!--end card-body-->
                </div><!--end card-->
              </div> <!--end col-->
            </div><!--end row-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
