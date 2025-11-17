@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">{{ __('Edit Role') }}: {{ $role->name }}</h5>

        <form id="updateRoleForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="role_id" value="{{ $role->id }}">

            <div class="mb-3">
                <label class="form-label">{{__('Role Name')}}</label>
                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
            </div>

            <h6 class="fw-bold mt-4 mb-2">{{__('Permissions')}}</h6>

            <div class="mb-3">
                <input type="checkbox" id="selectAllPermissions">
                <label for="selectAllPermissions" class="fw-semibold"> {{__('Select All Permissions')}}</label>
            </div>

            @php
                // Group by module (before the underscore or space)
                $groupedPermissions = $permissions->groupBy(function($p) {
                    $parts = explode(' ', $p->name);
                    return $parts[1] ?? 'other';
                });
            @endphp

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="20%">{{ __('Module') }}</th>
                        <th>{{ __('Permissions') }}</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($groupedPermissions as $module => $perms)

                    @php
                        // Convert module name → Completion Types, Order Pickup, etc.
                        $moduleTitle = ucwords(str_replace('_', ' ', $module));
                    @endphp

                    <tr>
                        <td class="align-middle">
                            <strong>{{ __($moduleTitle) }}</strong><br>

                            {{-- MAIN MANAGE CHECKBOX --}}
                            <input type="checkbox"
                                class="module-check"
                                data-module="{{ $module }}">

                            <label><small>{{ __('Manage') }} {{ __($moduleTitle) }}</small></label>
                        </td>

                        <td>
                            <div class="row">

                                @foreach($perms as $perm)

                                    @php
                                        // name example: "view completion_types"
                                        $parts = explode(' ', $perm->name);
                                        $action = ucfirst($parts[0]); // "View"
                                    @endphp

                                    <div class="col-md-3 mb-2">
                                        <input type="checkbox"
                                            class="permission-box permission-{{ $module }}"
                                            value="{{ $perm->id }}"
                                            {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>

                                        <label>{{ __($action) }}</label>
                                    </div>

                                @endforeach

                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

            <button type="button" onclick="updateRole()" class="btn btn-success mt-3">Update Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">Back</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {

    // ---- GLOBAL SELECT ALL ----
    $('#selectAllPermissions').on('change', function() {
        $('.permission-box, .module-check').prop('checked', $(this).is(':checked'));
    });

    // ---- MODULE CHECK ----
    $('.module-check').on('change', function() {
        let module = $(this).data('module');
        $('.permission-' + module).prop('checked', $(this).is(':checked'));
        updateGlobalCheck();
    });

    // ---- INDIVIDUAL PERMISSION ----
    $('.permission-box').on('change', function() {
        const classes = $(this).attr('class').split(' ');
        const moduleClass = classes.find(c => c.startsWith('permission-'));
        const module = moduleClass.replace('permission-', '');

        updateModuleCheck(module);
        updateGlobalCheck();
    });

    // Mark module checkbox if all inside are selected
    function updateModuleCheck(module) {
        const boxes = $('.permission-' + module);
        const allChecked = boxes.length === boxes.filter(':checked').length;
        $('.module-check[data-module="' + module + '"]').prop('checked', allChecked);
    }

    // Mark global checkbox if everything is selected
    function updateGlobalCheck() {
        const allChecked = $('.permission-box').length === $('.permission-box:checked').length;
        $('#selectAllPermissions').prop('checked', allChecked);
    }

    // Initialize state
    $('.module-check').each(function() {
        updateModuleCheck($(this).data('module'));
    });
    updateGlobalCheck();
});


function updateRole() {
    const id = $('#role_id').val();

    let selectedPermissions = $('.permission-box:checked')
        .map(function() { return $(this).val(); })
        .get();

    $.ajax({
        url: `/roles/${id}`,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            name: $('input[name="name"]').val(),
            permissions: selectedPermissions
        },
        success: function() {
            alert('Role updated successfully');
        },
        error: function() {
            alert('Error updating role');
        }
    });
}
</script>
@endpush
