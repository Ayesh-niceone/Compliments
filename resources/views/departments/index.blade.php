@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">{{ __('Departments') }}</h5>

        @can('create departments')
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                {{ __('Add Department') }}
            </button>
        @endcan

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="departments-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Brand') }}</th>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ CREATE MODAL -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createDepartmentForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Department') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">{{ __('Department Name (English)') }}</label>
                    <input type="text" name="name_en" class="form-control mb-2" required>

                    <label class="form-label">{{ __('Department Name (Arabic)') }}</label>
                    <input type="text" name="name_ar" class="form-control mb-2" required>
                    <label class="form-label">{{ __('Brand') }}</label>
                    <select name="brand_id" class="form-control" required>
                        <option value="">{{ __('Select Brand') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <label class="form-label">{{ __('Department Code') }}</label>
                    <input type="text" name="code" class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button type="button" onclick="storeDepartment()" class="btn btn-success">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- ✅ EDIT MODAL -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editDepartmentForm">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_id" name="id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Department') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">{{ __('Department Name (English)') }}</label>
                    <input type="text" id="edit_name_en" name="name_en" class="form-control mb-2" required>

                    <label class="form-label">{{ __('Department Name (Arabic)') }}</label>
                    <input type="text" id="edit_name_ar" name="name_ar" class="form-control mb-2" required>
                    <label class="form-label">{{ __('Brand') }}</label>
                    <select id="edit_brand_id" name="brand_id" class="form-control" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <label class="form-label">{{ __('Department Code') }}</label>
                    <input type="text" id="edit_code" name="code" class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button type="button" onclick="updateDepartment()" class="btn btn-success">
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
$(function() {
    $('#departments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('departments.index') }}",
        columns: [
            { data: 'id', name: 'id' },

            // ✅ SHOW NAME BASED ON LOCALE
            {
                data: 'name',
                render: function (data) {
                    if (!data) return '-';

                    return data['{{ app()->getLocale() === 'ar' ? 'name_ar' : 'name_en' }}'];
                }
            },
            { data: 'brand', name: 'brand' },
            { data: 'code', name: 'code' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});


// ✅ STORE DEPARTMENT
function storeDepartment() {
    $.ajax({
        url: "{{ route('departments.store') }}",
        method: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            name: {
                name_en: $('input[name="name_en"]').val(),
                name_ar: $('input[name="name_ar"]').val()
            },
            code: $('input[name="code"]').val()
        },
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


// ✅ EDIT LOAD
function editDepartment(id, name_en, name_ar, code) {
    $('#edit_id').val(id);
    $('#edit_name_en').val(name_en);
    $('#edit_name_ar').val(name_ar);
    $('#edit_code').val(code);
    $('#edit_brand_id').val(brand_id);
    $('#editDepartmentModal').modal('show');
}


// ✅ UPDATE
function updateDepartment() {
    let id = $('#edit_id').val();

    $.ajax({
        url: `/departments/${id}`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            name: {
                name_en: $('#edit_name_en').val(),
                name_ar: $('#edit_name_ar').val()
            },
            code: $('#edit_code').val()
        },
        success: function() {
            $('#editDepartmentModal').modal('hide');
            $('#departments-table').DataTable().ajax.reload();
        },
        error: function() {
            alert('Error updating department');
        }
    });
}


// ✅ DELETE
function deleteDepartment(id) {
    if (!confirm('{{ __("Are you sure you want to delete this department?") }}')) return;

    $.ajax({
        url: `/departments/${id}`,
        method: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
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
