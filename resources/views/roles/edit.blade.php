@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Edit Role: {{ $role->name }}</h5>

        <form id="updateRoleForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="role_id" value="{{ $role->id }}">

            <div class="mb-3">
                <label class="form-label">Role Name</label>
                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
            </div>

            <h6 class="fw-bold mt-4 mb-2">Permissions</h6>

            <div class="mb-2">
                <input type="checkbox" id="selectAllPermissions">
                <label for="selectAllPermissions" class="fw-semibold">Select All Permissions</label>
            </div>

            @php
                // Group permissions by module prefix
                $groupedPermissions = $permissions->groupBy(function($perm) {
                    return explode('.', $perm->name)[0]; // permissions like 'users.view'
                });
            @endphp

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedPermissions as $module => $perms)
                    <tr>
                        <td>{{ ucfirst($module) }}</td>
                        <td>
                            <input type="checkbox" class="select-module" data-module="{{ $module }}"
                                {{ collect($perms->pluck('id'))->every(fn($id) => in_array($id, $rolePermissions)) ? 'checked' : '' }}>
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
    // Global select all
    $('#selectAllPermissions').change(function() {
        $('.select-module').prop('checked', this.checked);
    });

    // Module select/unselect
    $('.select-module').change(function() {
        const allChecked = $('.select-module:checked').length === $('.select-module').length;
        $('#selectAllPermissions').prop('checked', allChecked);
    });

    // Initialize global select all checkbox
    const allModulesChecked = $('.select-module:checked').length === $('.select-module').length;
    $('#selectAllPermissions').prop('checked', allModulesChecked);
});

function updateRole() {
    const id = $('#role_id').val();
    let selectedPermissions = [];

    $('.select-module').each(function() {
        if($(this).is(':checked')) {
            const module = $(this).data('module');
            // get all permission ids of this module from server-rendered groupedPermissions
            const perms = {!! json_encode($groupedPermissions->map(fn($p) => $p->pluck('id'))) !!};
            selectedPermissions.push(...perms[module]);
        }
    });

    $.ajax({
        url: `/roles/${id}`,
        method: 'POST',
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
