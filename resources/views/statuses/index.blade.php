@extends('layouts.app')

@section('content')
    <div class="card w-100">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Statuses</h5>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createStatusModal">
                Add Status
            </button>
            <div class="table-responsive" >
                <table class="table text-nowrap mb-0 align-middle" id="statuses-table">
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
    <!-- Modal for create Status -->
    <div class="modal fade" id="createStatusModal" tabindex="-1" aria-labelledby="createStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="createStatusForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createStatusModalLabel">Add Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="name" class="form-label">Status Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="storeStatus()"  class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#statuses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('statuses.index') }}",
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
        function storeStatus() {
            $.ajax({
                url: "{{ route('statuses.store') }}",
                method: "POST",
                data: $('#createStatusForm').serialize(),
                success: function(response) {
                    $('#createStatusModal').modal('hide');
                    $('#statuses-table').DataTable().ajax.reload();
                    $('#createStatusForm')[0].reset();
                },
                error: function(xhr) {
                    alert('An error occurred while creating the Status.');
                }
            });
        }
    </script>
@endpush
