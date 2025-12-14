@extends('layouts.app')

@section('content')
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">{{ __('Brands') }}</h5>

            @can('create brands')
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                    {{ __('Add Brand') }}
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
    <div class="modal fade" id="createBrandModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="createBrandForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Brand') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label class="form-label">{{ __('Name (English)') }}</label>
                        <input type="text" name="name_en" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Name (Arabic)') }}</label>
                        <input type="text" name="name_ar" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="storeBrand()" class="btn btn-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- ✅ EDIT MODAL -->
    <div class="modal fade" id="editBrandModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editBrandForm">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id" name="id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Brand') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label class="form-label">{{ __('Name (English)') }}</label>
                        <input type="text" id="edit_name_en" name="name_en" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Name (Arabic)') }}</label>
                        <input type="text" id="edit_name_ar" name="name_ar" class="form-control" required>


                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="updateBrand()" class="btn btn-success">
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
    $('#brands-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('brands.index') }}",
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

            { data: 'action', orderable: false, searchable: false }
        ]
    });
});


// ✅ CREATE
function storeBrand() {
    $.ajax({
        url: "{{ route('brands.store') }}",
        method: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            name: {
                name_en: $('input[name="name_en"]').val(),
                name_ar: $('input[name="name_ar"]').val()
            }
        },
        success: function () {
            $('#createBrandModal').modal('hide');
            $('#brands-table').DataTable().ajax.reload();
            $('#createBrandForm')[0].reset();
        },
        error: function () {
            alert('Error creating Brand');
        }
    });
}


// ✅ EDIT LOAD
function editBrand(id, name_en, name_ar) {
    $('#edit_id').val(id);
    $('#edit_name_en').val(name_en);
    $('#edit_name_ar').val(name_ar);
    $('#editBrandModal').modal('show');
}


// ✅ UPDATE
function updateBrand() {
    let id = $('#edit_id').val();

    $.ajax({
        url: `/brands/${id}`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            name: {
                name_en: $('#edit_name_en').val(),
                name_ar: $('#edit_name_ar').val()
            }
        },
        success: function () {
            $('#editBrandModal').modal('hide');
            $('#brands-table').DataTable().ajax.reload();
        },
        error: function () {
            alert('Error updating Brand');
        }
    });
}


// ✅ DELETE
function deleteBrand(id) {
    if (!confirm('Are you sure?')) return;

    $.ajax({
        url: `/brands/${id}`,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function () {
            $('#brands-table').DataTable().ajax.reload();
        },
        error: function () {
            alert('Error deleting Brand');
        }
    });
}
</script>
@endpush
