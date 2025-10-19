@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Departments</h2>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
        Add Department
    </button>

    <table id="departments-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal for create department -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createDepartmentForm">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDepartmentModalLabel">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="name" class="form-label">Department Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let table = $('#departments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('departments.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    $('#createDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('departments.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#createDepartmentModal').modal('hide');
                table.ajax.reload();
                alert('Department added successfully');
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});
</script>
@endpush
