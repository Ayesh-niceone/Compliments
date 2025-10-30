<div class="btn-group" role="group">
     <button class="btn btn-sm btn-warning" onclick="editCompletionType('{{ $row->id }}', '{{ $row->name }}')">{{ __('Edit') }}</button>
    <button class="btn btn-sm btn-danger" onclick="deleteCompletionType({{ $row->id }})">{{ __('Delete') }}</button>
</div>

<script>
function deleteCompletionType(id) {
    if (confirm('{{ __('Are you sure?') }}')) {
        $.ajax({
            url: '/completion_types/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                $('#completion_types-table').DataTable().ajax.reload();
            }
        });
    }
}
</script>
