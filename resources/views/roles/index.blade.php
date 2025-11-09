@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Roles</h5>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            Add Role
        </button>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="roles-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createRoleForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Role Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="storeRole()" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('roles.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function storeRole() {
    $.ajax({
        url: "{{ route('roles.store') }}",
        method: "POST",
        data: $('#createRoleForm').serialize(),
        success: function() {
            $('#createRoleModal').modal('hide');
            $('#roles-table').DataTable().ajax.reload();
            $('#createRoleForm')[0].reset();
        },
        error: function() {
            alert('Error creating role');
        }
    });
}

function editRole(id) {
    window.location.href = `/roles/${id}/edit`;
}

function deleteRole(id) {
    if (!confirm('Are you sure?')) return;
    $.ajax({
        url: `/roles/${id}`,
        method: 'DELETE',
        data: {_token: '{{ csrf_token() }}'},
        success: function() {
            $('#roles-table').DataTable().ajax.reload();
        },
        error: function() {
            alert('Error deleting role');
        }
    });
}
</script>
@endpush
