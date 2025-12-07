@extends('layouts.app')

@section('content')
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">{{ __('Statuses') }}</h5>

            @can('create statuses')
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createStatusModal">
                    {{ __('Add Status') }}
                </button>
            @endcan

            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle" id="statuses-table">
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

    <!-- ✅ CREATE STATUS MODAL -->
    <div class="modal fade" id="createStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="createStatusForm">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Status') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label class="form-label">{{ __('Status Name (English)') }}</label>
                        <input type="text" name="name_en" class="form-control" required>

                        <label class="form-label mt-2">{{ __('Status Name (Arabic)') }}</label>
                        <input type="text" name="name_ar" class="form-control" required>

                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="storeStatus()" class="btn btn-success">
                            {{ __('Save') }}
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
    $('#statuses-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('statuses.index') }}",
        columns: [
            { data: 'id', name: 'id' },

            // ✅ SHOW NAME BASED ON CURRENT LOCALE
            {
                data: 'name',
                render: function (data) {
                    if (!data) return '-';

                    return data['{{ app()->getLocale() === 'ar' ? 'name_ar' : 'name_en' }}'];
                }
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
});


// ✅ STORE STATUS WITH JSON NAME
function storeStatus() {
    $.ajax({
        url: "{{ route('statuses.store') }}",
        method: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            name: {
                name_en: $('input[name="name_en"]').val(),
                name_ar: $('input[name="name_ar"]').val()
            }
        },
        success: function () {
            $('#createStatusModal').modal('hide');
            $('#statuses-table').DataTable().ajax.reload();
            $('#createStatusForm')[0].reset();
        },
        error: function () {
            alert('An error occurred while creating the Status.');
        }
    });
}
</script>
@endpush
