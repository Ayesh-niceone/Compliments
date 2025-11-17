<div class="btn-group" role="group">
    @can('delete statuses')

    <button class="btn btn-sm btn-danger" onclick="deleteStatus({{ $row->id }})">{{ __('Delete') }}</button>
    @endcan
</div>

<script>
function deleteStatus(id) {
    if (confirm('{{ __('Are you sure?') }}')) {
        $.ajax({
            url: '/statuses/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                $('#statuses-table').DataTable().ajax.reload();
            }
        });
    }
}
</script>
