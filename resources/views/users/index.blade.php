@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold mb-0">Users</h5>
            <button class="btn btn-primary" id="createUserBtn">
                <i class="ti ti-plus"></i> Add User
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="users-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
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
        <form id="userForm" method="POST" class="modal-content" action="">
            @csrf
            <input type="hidden" id="user_id" name="user_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                            <option value="customer_care">Customer Care</option>
                        </select>
                    </div>

                    <div class="mb-3 password-field">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted">Required only when creating a new user.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Save</button>
                </div>
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

    // Open create modal
    $('#createUserBtn').click(function() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#userModalLabel').text('Add User');
        $('.password-field').show();
        $('#userModal').modal('show');
    });

    // Edit button click
    $('body').on('click', '.editUserBtn', function() {
        let id = $(this).data('id');
        $.get("{{ url('users') }}/" + id + "/edit", function(data) {
            $('#userModalLabel').text('Edit User');
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#role').val(data.role);
            $('.password-field').hide(); // hide password on edit
            $('#userModal').modal('show');
        });
    });

    // Save or update user
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
                toastr.success(res.message || 'User saved successfully');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong');
            }
        });
    });

    // Delete user
    $('body').on('click', '.deleteUserBtn', function() {
        if (!confirm('Are you sure?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('users') }}/" + id,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                table.ajax.reload(null, false);
                toastr.success(res.message || 'User deleted successfully');
            }
        });
    });
});
</script>
@endpush
