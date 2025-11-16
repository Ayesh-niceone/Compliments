@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold mb-0">{{ __('Permissions') }}</h5>
            <button class="btn btn-primary" id="createPermissionBtn">
                <i class="ti ti-plus"></i> {{ __('Add Permission') }}
            </button>
        </div> --}}

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="permissions-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Permission Name') }}</th>
                        <th>{{ __('Guard') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="permissionForm" method="POST" class="modal-content">
            @csrf
            <input type="hidden" id="permission_id" name="permission_id">

            <div class="modal-header">
                <h5 class="modal-title" id="permissionModalLabel">{{ __('Add Permission') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">{{ __('Permission Name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let table = $('#permissions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('permissions.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'guard_name', name: 'guard_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // Create new permission
    $('#createPermissionBtn').click(function() {
        $('#permissionForm')[0].reset();
        $('#permission_id').val('');
        $('#permissionModalLabel').text('Add Permission');
        $('#permissionModal').modal('show');
    });

    // Edit
    $('body').on('click', '.editPermissionBtn', function() {
        let id = $(this).data('id');
        $.get("{{ url('permissions') }}/" + id + "/edit", function(data) {
            $('#permission_id').val(data.id);
            $('#name').val(data.name);
            $('#permissionModalLabel').text('Edit Permission');
            $('#permissionModal').modal('show');
        });
    });

    // Save / update
    $('#permissionForm').submit(function(e) {
        e.preventDefault();
        let id = $('#permission_id').val();
        let url = id ? "{{ url('permissions') }}/" + id : "{{ route('permissions.store') }}";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(res) {
                $('#permissionModal').modal('hide');
                table.ajax.reload(null, false);
                toastr.success(res.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong');
            }
        });
    });

    // Delete
    $('body').on('click', '.deletePermissionBtn', function() {
        if (!confirm('Are you sure?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('permissions') }}/" + id,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                table.ajax.reload(null, false);
                toastr.success(res.message);
            }
        });
    });
});
</script>
@endpush
