@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-3">
        <select id="filterDepartment" class="form-select">
            <option value="">{{ __('All Departments') }}</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select id="filterCompletionType" class="form-select">
            <option value="">{{ __('All Completion Types') }}</option>
            @foreach($completionTypes as $ct)
                <option value="{{ $ct->id }}">{{ $ct->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select id="filterStatus" class="form-select">
            <option value="">{{ __('All Statuses') }}</option>
            @foreach($statuses as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 text-end">
        <button class="btn btn-success" id="exportExcelBtn">
            <i class="ti ti-download"></i> {{ __('Export Excel') }}
        </button>
    </div>
</div>

<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">{{ __('Compliments') }}</h5>
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle" id="compliments-table">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Customer Name') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Department Code') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Plate Number') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Completion Type') }}</th>
                        <th>{{ __('Care User') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
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
        <h5 class="modal-title" id="assignCareUserModalLabel">{{ __('Assign Care User') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="compliment_id" name="compliment_id">
        <div class="mb-3">
          <label for="care_user_id" class="form-label">{{ __('Select Care User') }}</label>
          <select name="care_user_id" id="care_user_id" class="form-select" required>
              <option value="">-- {{ __('Choose User') }} --</option>
              @foreach($careUsers as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
          </select>
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
    let table = $('#compliments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('compliments.index') }}",
            data: function (d) {
                d.department_id = $('#filterDepartment').val();
                d.completion_type_id = $('#filterCompletionType').val();
                d.status_id = $('#filterStatus').val();
            }
        },
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

    // ✅ Filter reload
    $('#filterDepartment, #filterCompletionType, #filterStatus').change(function() {
        table.ajax.reload();
    });

    // ✅ Export to Excel
    $('#exportExcelBtn').click(function() {
        let params = $.param({
            department_id: $('#filterDepartment').val(),
            completion_type_id: $('#filterCompletionType').val(),
            status_id: $('#filterStatus').val()
        });
        window.location.href = "{{ route('compliments.export') }}?" + params;
    });

    // Existing Assign User logic...
});

</script>
@endpush
