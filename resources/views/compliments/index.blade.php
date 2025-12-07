@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-md-2">
            <label for="">{{ __('Departments') }}</label>
            <select id="filterDepartment" class="form-select select2" multiple>
                <option value="">{{ __('All Departments') }}</option>
                @foreach ($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name_lang }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
                        <label for="">{{ __('Completion Types') }}</label>

            <select id="filterCompletionType" class="form-select select2" multiple>
                <option value="">{{ __('All Completion Types') }}</option>
                @foreach ($completionTypes as $ct)
                    <option value="{{ $ct->id }}">{{ $ct->name_lang }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="">{{ __('Statuses') }}</label>
            <select id="filterStatus" class="form-select select2" multiple>
                <option value="">{{ __('All Statuses') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->id }}">{{ $s->name_lang }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="">{{ __('Care Users') }}</label>
            <select id="filterCareUser" class="form-select select2" multiple>
                <option value="">{{ __('All Care Users') }}</option>
                @foreach ($careUsers as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="">{{ __('Target Types') }}</label>
            <select id="filterTargetType" class="form-select select2" multiple>
                <option value="">{{ __('All Target Types') }}</option>
                <option value="customer">{{ __('Customer') }}</option>
                <option value="worker">{{ __('Worker') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>{{ __('From Date') }}</label>
            <input type="date" id="filterDateFrom" class="form-control">
        </div>
        <div class="col-md-3">
            <label>{{ __('To Date') }}</label>
            <input type="date" id="filterDateTo" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button id="clearFilters" class="btn btn-outline-secondary">{{ __('Clear Filters') }}</button>
        </div>


    </div>

    <!-- Date range filters -->
    <div class="row mb-4">
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-success" id="exportExcelBtn">
                <i class="ti ti-download"></i> {{ __('Export Excel') }}
            </button>

            <button class="btn btn-danger" id="exportPdfBtn">
                <i class="ti ti-file-text"></i> {{ __('Export PDF') }}
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
    <div class="modal fade" id="assignCareUserModal" tabindex="-1" aria-labelledby="assignCareUserModalLabel"
        aria-hidden="true">
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
                            @foreach ($careUsers as $user)
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            let table = $('#compliments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('compliments.index') }}",
                    data: function(d) {
                        d.department_id = $('#filterDepartment').val();
                        d.completion_type_id = $('#filterCompletionType').val();
                        d.status_id = $('#filterStatus').val();
                        d.care_user_id = $('#filterCareUser').val();
                        d.target_type = $('#filterTargetType').val();
                        d.date_from = $('#filterDateFrom').val();
                        d.date_to = $('#filterDateTo').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'code',
                        name: 'department_code'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'plate_number',
                        name: 'plate_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'completion_type',
                        name: 'completion_type'
                    },
                    {
                        data: 'care_user',
                        name: 'care_user'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // ✅ Filter reload
            $('#filterDepartment, #filterCompletionType, #filterStatus, #filterCareUser, #filterTargetType, #filterDateFrom, #filterDateTo')
                .change(function() {
                    table.ajax.reload();
                });

            $('#clearFilters').click(function() {
                $('#filterDepartment, #filterCompletionType, #filterStatus, #filterCareUser, #filterTargetType')
                    .val('');
                $('#filterDateFrom, #filterDateTo').val('');
                table.ajax.reload();
            });
            // ✅ Export to Excel
            $('#exportExcelBtn').click(function() {
                let params = $.param({
                    department_id : $('#filterDepartment').val(),
                    completion_type_id : $('#filterCompletionType').val(),
                    status_id : $('#filterStatus').val(),
                    care_user_id : $('#filterCareUser').val(),
                    target_type : $('#filterTargetType').val(),
                    date_from : $('#filterDateFrom').val(),
                    date_to : $('#filterDateTo').val()
                });
                window.location.href = "{{ route('compliments.export') }}?" + params;
            });

            $('#exportPdfBtn').click(function() {
                let params = $.param({
                    department_id : $('#filterDepartment').val(),
                    completion_type_id : $('#filterCompletionType').val(),
                    status_id : $('#filterStatus').val(),
                    care_user_id : $('#filterCareUser').val(),
                    target_type : $('#filterTargetType').val(),
                    date_from : $('#filterDateFrom').val(),
                    date_to : $('#filterDateTo').val()
                });
                window.location.href = "{{ route('compliments.export.pdf') }}?" + params;
            });

            $('.form-select').select2();
        });
    </script>
    <script>
        // ✅ Handle clicking the "Assign" button
        $(document).on('click', '.assign-care-user-btn', function() {
            let complimentId = $(this).data('id');
            $('#compliment_id').val(complimentId);
            $('#assignCareUserModal').modal('show');
        });

        // ✅ Handle form submission for assigning care user
        $('#assignCareUserForm').on('submit', function(e) {
            e.preventDefault();

            let complimentId = $('#compliment_id').val();
            let formData = $(this).serialize();

            $.ajax({
                url: '/compliments/' + complimentId + '/assign-care-user',
                method: 'PUT',
                data: formData,
                success: function(response) {
                    $('#assignCareUserModal').modal('hide');
                    $('#compliments-table').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('Care User Assigned Successfully') }}',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('Error') }}',
                        text: xhr.responseJSON?.message || '{{ __('Something went wrong') }}'
                    });
                }
            });
        });
    </script>
@endpush
