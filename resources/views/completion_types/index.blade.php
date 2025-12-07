@extends('layouts.app')

@section('content')
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">{{ __('Completion Types') }}</h5>

            @can('create completion_types')
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCompletionTypeModal">
                    {{ __('Add Completion Type') }}
                </button>
            @endcan

            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle" id="completion_types-table">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ✅ CREATE MODAL -->
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

                        <label class="form-label">{{ __('Name (English)') }}</label>
                        <input type="text" name="name_en" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Name (Arabic)') }}</label>
                        <input type="text" name="name_ar" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Type') }}</label>
                        <select name="type" class="form-select" required>
                            <option value="worker">{{ __('Worker') }}</option>
                            <option value="customer">{{ __('Customer') }}</option>
                        </select>

                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="storeCompletionType()" class="btn btn-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- ✅ EDIT MODAL -->
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

                        <label class="form-label">{{ __('Name (English)') }}</label>
                        <input type="text" id="edit_name_en" name="name_en" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Name (Arabic)') }}</label>
                        <input type="text" id="edit_name_ar" name="name_ar" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Type') }}</label>
                        <select id="edit_type" name="type" class="form-select" required>
                            <option value="worker">{{ __('Worker') }}</option>
                            <option value="customer">{{ __('Customer') }}</option>
                        </select>

                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="updateCompletionType()" class="btn btn-success">
                            {{ __('Update') }}
                        </button>
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
            { data: 'id', name: 'id' },

            // ✅ SHOW NAME BASED ON LOCALE
            {
                data: 'name',
                render: function (data) {
                    if (!data) return '-';

                    return data['{{ app()->getLocale() === 'ar' ? 'name_ar' : 'name_en' }}'] ?? '-';
                }
            },

            { data: 'type', name: 'type' },

            { data: 'action', orderable: false, searchable: false }
        ]
    });
});


// ✅ CREATE
function storeCompletionType() {
    $.ajax({
        url: "{{ route('completion_types.store') }}",
        method: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            name: {
                name_en: $('input[name="name_en"]').val(),
                name_ar: $('input[name="name_ar"]').val()
            },
            type: $('select[name="type"]').val()
        },
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


// ✅ EDIT LOAD
function editCompletionType(id, name_en, name_ar, type) {
    $('#edit_id').val(id);
    $('#edit_name_en').val(name_en);
    $('#edit_name_ar').val(name_ar);
    $('#edit_type').val(type);
    $('#editCompletionTypeModal').modal('show');
}


// ✅ UPDATE
function updateCompletionType() {
    let id = $('#edit_id').val();

    $.ajax({
        url: `/completion_types/${id}`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            name: {
                name_en: $('#edit_name_en').val(),
                name_ar: $('#edit_name_ar').val()
            },
            type: $('#edit_type').val()
        },
        success: function () {
            $('#editCompletionTypeModal').modal('hide');
            $('#completion_types-table').DataTable().ajax.reload();
        },
        error: function () {
            alert('Error updating Completion Type');
        }
    });
}


// ✅ DELETE
function deleteCompletionType(id) {
    if (!confirm('Are you sure?')) return;

    $.ajax({
        url: `/completion_types/${id}`,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
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
