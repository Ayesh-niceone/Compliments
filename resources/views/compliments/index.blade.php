@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Compliments</h2>
    <table id="compliments-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Department</th>
                <th>Care User</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#compliments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('compliments.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'department', name: 'department' },
            { data: 'care_user', name: 'care_user' },
            { data: 'comment', name: 'comment' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
