@extends('admin.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">🛡️ Create Sub Admin</h2>
    <a href="{{ route('subadmins.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card table-card border-0">
    <div class="card-body">
        <form action="{{ route('subadmins.store') }}" method="POST">
            @csrf
            
            <h5 class="text-white mb-3">1. Basic Information</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Mobile</label>
                    <input type="text" name="mobile" class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Status</label>
                    <select name="status" class="form-select bg-dark text-white border-secondary">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-white">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-secondary" required>
                </div>
            </div>

            <hr class="border-secondary">

            <h5 class="text-white mb-3 mt-4">2. Permissions</h5>
            <div class="row mb-4">
                @php
                    $groupedPermissions = $permissions->groupBy(function($perm) {
                        return explode('.', $perm->name)[0];
                    });
                @endphp
                
                @foreach($groupedPermissions as $module => $perms)
                <div class="col-md-3 mb-3">
                    <div class="card bg-dark border-secondary">
                        <div class="card-header border-secondary bg-secondary text-white text-capitalize">
                            <strong>{{ $module }}</strong>
                        </div>
                        <div class="card-body">
                            @foreach($perms as $perm)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="perm_{{ $perm->id }}">
                                <label class="form-check-label text-white" for="perm_{{ $perm->id }}">
                                    {{ str_replace('.', ' ', $perm->name) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <hr class="border-secondary">

            <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                <h5 class="text-white m-0">3. Assign Libraries</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkAllLibraries">
                    <label class="form-check-label text-white" for="checkAllLibraries">
                        Select All Libraries
                    </label>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-dark border-secondary p-3" style="max-height: 300px; overflow-y: auto;">
                        <div class="row">
                            @foreach($libraries as $library)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input library-checkbox" type="checkbox" name="libraries[]" value="{{ $library->id }}" id="lib_{{ $library->id }}">
                                    <label class="form-check-label text-white" for="lib_{{ $library->id }}">
                                        {{ $library->name }} ({{ $library->district->name ?? 'N/A' }})
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success px-4">Save Sub Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAllLibraries');
        const checkboxes = document.querySelectorAll('.library-checkbox');
        
        if(checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = checkAll.checked;
                });
            });

            // Update "Select All" checkbox state if user manually checks/unchecks individual boxes
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    const someChecked = Array.from(checkboxes).some(c => c.checked);
                    checkAll.checked = allChecked;
                    checkAll.indeterminate = someChecked && !allChecked;
                });
            });
            
            // Initial state check
            if(checkboxes.length > 0) {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                const someChecked = Array.from(checkboxes).some(c => c.checked);
                checkAll.checked = allChecked;
                checkAll.indeterminate = someChecked && !allChecked;
            }
        }
    });
</script>
@endsection
