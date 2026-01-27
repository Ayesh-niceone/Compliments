@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold mb-0">{{ __('Users') }}</h5>
            @can('create users')
            <button class="btn btn-primary" id="createUserBtn">
                <i class="ti ti-plus"></i> {{ __('Add User') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="users-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create / Edit Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="userForm" method="POST" class="modal-content">
            @csrf
            <input type="hidden" id="user_id" name="user_id">

            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">{{ __('Add User') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Validation Errors -->
                <div class="alert alert-danger d-none" id="validationErrors"></div>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">{{ __('Name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold">{{ __('Role') }}</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">{{ __('Select role') }}</option>
                        @foreach($roles as $id => $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 password-field">
                    <label for="password" class="form-label fw-semibold">{{ __('User Password') }}</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Enter password for new user') }}">
                    <small class="text-muted">{{ __('Required only when creating a new user.') }}</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary" id="saveUserBtn">{{ __('Save') }}</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {

    let table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    /** ---------------------------
     *  OPEN CREATE MODAL
     * --------------------------*/
    $('#createUserBtn').click(function() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#userModalLabel').text('Add User');
        $('.password-field').show();

        // reset validation
        $('#validationErrors').addClass('d-none').html("");

        $('#userModal').modal('show');
    });

    /** ---------------------------
     *  OPEN EDIT MODAL
     * --------------------------*/
    $('body').on('click', '.editUserBtn', function() {
        let id = $(this).data('id');

        $.get("{{ url('users') }}/" + id + "/edit", function(data) {
            $('#userModalLabel').text('Edit User');
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#role').val(data.role);

            $('.password-field').hide();

            // reset validation
            $('#validationErrors').addClass('d-none').html("");

            $('#userModal').modal('show');
        });
    });

    /** ---------------------------
     *  CREATE / UPDATE SUBMIT
     * --------------------------*/
    $('#userForm').submit(function(e) {
        e.preventDefault();

        let id = $('#user_id').val();
        let url = id ? "{{ url('users') }}/" + id : "{{ route('users.store') }}";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(res) {
                $('#userModal').modal('hide');
                table.ajax.reload(null, false);
                toastr.success(res.message);
            },
            error: function(xhr) {

                let errors = xhr.responseJSON.errors;

                if (errors) {
                    let html = "<ul>";
                    $.each(errors, function(key, value) {
                        html += "<li>" + value[0] + "</li>";
                    });
                    html += "</ul>";

                    $('#validationErrors')
                        .removeClass('d-none')
                        .html(html);

                } else {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong');
                }
            }
        });
    });

    /** ---------------------------
     *  DELETE USER
     * --------------------------*/
    $('body').on('click', '.deleteUserBtn', function() {
        if (!confirm('Are you sure?')) return;

        let id = $(this).data('id');

        $.ajax({
            url: "{{ url('users') }}/" + id,
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
