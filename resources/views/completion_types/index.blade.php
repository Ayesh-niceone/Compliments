@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">{{ __('Completion Types') }}</h5>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCompletionTypeModal">
            {{ __('Add Completion Type') }}
        </button>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="completion_types-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCompletionTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createCompletionTypeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Completion Type') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="storeCompletionType()" class="btn btn-success">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCompletionTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editCompletionTypeForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Completion Type') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="updateCompletionType()" class="btn btn-success">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#completion_types-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('completion_types.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function storeCompletionType() {
    $.ajax({
        url: "{{ route('completion_types.store') }}",
        method: "POST",
        data: $('#createCompletionTypeForm').serialize(),
        success: function () {
            $('#createCompletionTypeModal').modal('hide');
            $('#completion_types-table').DataTable().ajax.reload();
            $('#createCompletionTypeForm')[0].reset();
        },
        error: function () {
            alert('Error creating Completion Type');
        }
    });
}

function editCompletionType(id, name) {
    $('#edit_id').val(id);
    $('#edit_name').val(name);
    $('#editCompletionTypeModal').modal('show');
}

function updateCompletionType() {
    let id = $('#edit_id').val();
    $.ajax({
        url: `/completion_types/${id}`,
        method: 'POST',
        data: $('#editCompletionTypeForm').serialize(),
        success: function () {
            $('#editCompletionTypeModal').modal('hide');
            $('#completion_types-table').DataTable().ajax.reload();
        },
        error: function () {
            alert('Error updating Completion Type');
        }
    });
}

function deleteCompletionType(id) {
    if (!confirm('Are you sure?')) return;
    $.ajax({
        url: `/completion_types/${id}`,
        method: 'DELETE',
        data: {_token: '{{ csrf_token() }}'},
        success: function () {
            $('#completion_types-table').DataTable().ajax.reload();
        },
        error: function () {
            alert('Error deleting Completion Type');
        }
    });
}
</script>
@endpush
