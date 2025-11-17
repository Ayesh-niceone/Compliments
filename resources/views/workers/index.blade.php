@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">{{ __('Workers') }}</h5>
        @can('create workers')
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createWorkerModal">
            {{ __('Add Worker') }}
        </button>
        @endcan

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="workers-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Job Title') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createWorkerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createWorkerForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Worker') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">{{ __('Worker Name') }}</label>
                    <input type="text" name="name" class="form-control mb-2" required>

                    <label class="form-label">{{ __('Job Title') }}</label>
                    <input type="text" name="job_title" class="form-control mb-2">

                    <label class="form-label">{{ __('Phone') }}</label>
                    <input type="text" name="phone" class="form-control mb-2">

                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" class="form-control" required>
                        <option value="">{{ __('Select Department') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="storeWorker()" class="btn btn-success">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editWorkerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editWorkerForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Worker') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">{{ __('Worker Name') }}</label>
                    <input type="text" id="edit_name" name="name" class="form-control mb-2" required>

                    <label class="form-label">{{ __('Job Title') }}</label>
                    <input type="text" id="edit_job_title" name="job_title" class="form-control mb-2">

                    <label class="form-label">{{ __('Phone') }}</label>
                    <input type="text" id="edit_phone" name="phone" class="form-control mb-2">

                    <label class="form-label">{{ __('Department') }}</label>
                    <select id="edit_department_id" name="department_id" class="form-control" required>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="updateWorker()" class="btn btn-success">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#workers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('workers.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'job_title', name: 'job_title'},
            {data: 'phone', name: 'phone'},
            {data: 'department', name: 'department'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function storeWorker() {
    $.ajax({
        url: "{{ route('workers.store') }}",
        method: "POST",
        data: $('#createWorkerForm').serialize(),
        success: function() {
            $('#createWorkerModal').modal('hide');
            $('#workers-table').DataTable().ajax.reload();
            $('#createWorkerForm')[0].reset();
        },
        error: function() {
            alert('Error creating worker');
        }
    });
}

function editWorker(id, name, job_title, phone, department_id) {
    $('#edit_id').val(id);
    $('#edit_name').val(name);
    $('#edit_job_title').val(job_title);
    $('#edit_phone').val(phone);
    $('#edit_department_id').val(department_id);
    $('#editWorkerModal').modal('show');
}

function updateWorker() {
    let id = $('#edit_id').val();
    $.ajax({
        url: `/workers/${id}`,
        method: 'POST',
        data: $('#editWorkerForm').serialize(),
        success: function() {
            $('#editWorkerModal').modal('hide');
            $('#workers-table').DataTable().ajax.reload();
        },
        error: function() {
            alert('Error updating worker');
        }
    });
}

function deleteWorker(id) {
    if (!confirm('Are you sure you want to delete this worker?')) return;
    $.ajax({
        url: `/workers/${id}`,
        method: 'DELETE',
        data: {_token: '{{ csrf_token() }}'},
        success: function() {
            $('#workers-table').DataTable().ajax.reload();
        },
        error: function() {
            alert('Error deleting worker');
        }
    });
}
</script>
@endpush
