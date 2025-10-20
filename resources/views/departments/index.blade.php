@extends('layouts.app')

@section('content')
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Departments</h5>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                Add Department
            </button>
            <div class="table-responsive" >
                <table class="table text-nowrap mb-0 align-middle" id="departments-table">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">#</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Name</h6>
                            </th>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Action</h6>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal for create department -->
    <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="createDepartmentForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDepartmentModalLabel">Add Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="name" class="form-label">Department Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="storeDepartment()"  class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#departments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('departments.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
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
        function storeDepartment() {
            $.ajax({
                url: "{{ route('departments.store') }}",
                method: "POST",
                data: $('#createDepartmentForm').serialize(),
                success: function(response) {
                    $('#createDepartmentModal').modal('hide');
                    $('#departments-table').DataTable().ajax.reload();
                    $('#createDepartmentForm')[0].reset();
                },
                error: function(xhr) {
                    alert('An error occurred while creating the department.');
                }
            });
        }
    </script>
@endpush
