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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Permission</th>
                        <th>Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $perm)
                        <tr>
                            <td>{{ $perm->name }}</td>
                            <td>
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                    {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
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
function updateRole() {
    const id = $('#role_id').val();
    $.ajax({
        url: `/roles/${id}`,
        method: 'POST',
        data: $('#updateRoleForm').serialize(),
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
