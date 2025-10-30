<div class="btn-group" role="group">
    <button class="btn btn-sm btn-warning" onclick="editDepartment({{ $row->id }}, '{{ $row->name }}', '{{ $row->code }}')">{{ __('Edit') }}</button>
    <button class="btn btn-sm btn-danger" onclick="deleteDepartment({{ $row->id }})">{{ __('Delete') }}</button>
</div>

<script>
function deleteDepartment(id) {
    if (confirm('{{ __('Are you sure?') }}')) {
        $.ajax({
            url: '/departments/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                $('#departments-table').DataTable().ajax.reload();
            }
        });
    }
}
</script>
