<div class="btn-group" role="group">
    <button class="btn btn-sm btn-warning"
        onclick="editDepartment({{ $row->id }}, '{{ $row->name }}', '{{ $row->code }}')">{{ __('Edit') }}</button>
    <button class="btn btn-sm btn-danger" onclick="deleteDepartment({{ $row->id }})">{{ __('Delete') }}</button>
    <!-- Worker Form -->
    <a href="{{ route('compliments.createWorker', ['department_id' => $row->id]) }}" target="_blank" class="btn btn-sm btn-primary">
        <i class="fa fa-user-cog"></i> {{ __('Worker Form') }}
    </a>

    <!-- Customer Form -->
    <a href="{{ route('compliments.createCustomer', ['department_id' => $row->id]) }}" target="_blank" class="btn btn-sm btn-success">
        <i class="fa fa-user"></i> {{ __('Customer Form') }}
    </a>
</div>

<script>
    function deleteDepartment(id) {
        if (confirm('{{ __('Are you sure?') }}')) {
            $.ajax({
                url: '/departments/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $('#departments-table').DataTable().ajax.reload();
                }
            });
        }
    }
</script>
