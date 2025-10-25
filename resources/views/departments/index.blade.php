@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Departments</h5>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
            Add Department
        </button>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="departments-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createDepartmentForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="name" class="form-control mb-2" required>

                    <label class="form-label">Department Code</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="storeDepartment()" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editDepartmentForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Department Name</label>
                    <input type="text" id="edit_name" name="name" class="form-control mb-2" required>

                    <label class="form-label">Department Code</label>
                    <input type="text" id="edit_code" name="code" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="updateDepartment()" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#departments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('departments.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'code', name: 'code'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function storeDepartment() {
    $.ajax({
        url: "{{ route('departments.store') }}",
        method: "POST",
        data: $('#createDepartmentForm').serialize(),
        success: function() {
            $('#createDepartmentModal').modal('hide');
            $('#departments-table').DataTable().ajax.reload();
            $('#createDepartmentForm')[0].reset();
        },
        error: function() {
            alert('Error creating department');
        }
    });
}

function editDepartment(id, name, code) {
    $('#edit_id').val(id);
    $('#edit_name').val(name);
    $('#edit_code').val(code);
    $('#editDepartmentModal').modal('show');
}

function updateDepartment() {
    let id = $('#edit_id').val();
    $.ajax({
        url: `/departments/${id}`,
        method: 'POST',
        data: $('#editDepartmentForm').serialize(),
        success: function() {
            $('#editDepartmentModal').modal('hide');
            $('#departments-table').DataTable().ajax.reload();
        },
        error: function() {
            alert('Error updating department');
        }
    });
}

function deleteDepartment(id) {
    if (!confirm('Are you sure you want to delete this department?')) return;
    $.ajax({
        url: `/departments/${id}`,
        method: 'DELETE',
        data: {_token: '{{ csrf_token() }}'},
        success: function() {
            $('#departments-table').DataTable().ajax.reload();
        },
        error: function() {
            alert('Error deleting department');
        }
    });
}
</script>
@endpush
