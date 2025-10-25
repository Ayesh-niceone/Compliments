@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Compliments</h5>
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="compliments-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Department</th>
                        <th>Department Code</th>
                        <th>Phone</th>
                        <th>Plate Number</th>
                        <th>Created At</th>
                        <th>Completion Type</th>
                        <th>Care User</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Assign Care User Modal -->
<div class="modal fade" id="assignCareUserModal" tabindex="-1" aria-labelledby="assignCareUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="assignCareUserForm" method="POST" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="assignCareUserModalLabel">Assign Care User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="compliment_id" name="compliment_id">
        <div class="mb-3">
          <label for="care_user_id" class="form-label">Select Care User</label>
          <select name="care_user_id" id="care_user_id" class="form-select" required>
              <option value="">-- Choose User --</option>
              @foreach($careUsers as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let table = $('#compliments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('compliments.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'department', name: 'department' },
            { data: 'code', name: 'department_code' },
            { data: 'phone', name: 'phone' },
            { data: 'plate_number', name: 'plate_number' },
            { data: 'created_at', name: 'created_at' },
            { data: 'completion_type', name: 'completion_type' },
            { data: 'care_user', name: 'care_user' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Handle Assign Care User button click
    $(document).on('click', '.assign-care-user-btn', function() {
        const complimentId = $(this).data('id');
        $('#compliment_id').val(complimentId);
        $('#assignCareUserForm').attr('action', `/compliments/${complimentId}/assign-care-user`);
        $('#assignCareUserModal').modal('show');
    });

    // Handle modal form submit via AJAX
    $('#assignCareUserForm').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#assignCareUserModal').modal('hide');
                table.ajax.reload(null, false);
                toastr.success('Care user assigned successfully');
            },
            error: function(xhr) {
                toastr.error('Failed to assign care user');
            }
        });
    });
});
</script>
@endpush
