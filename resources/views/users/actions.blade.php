<div class="btn-group" role="group">
    <button class="btn btn-sm btn-warning editUserBtn" data-id="{{ $row->id }}">{{ __('Edit User') }}</button>
    <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $row->id }})">{{ __('Delete User') }}</button>
</div>

<script>
function deleteUser(id) {
    if (confirm('{{ __('Are you sure') }}')) {
        $.ajax({
            url: '/users/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                $('#users-table').DataTable().ajax.reload();
            }
        });
    }
}
</script>
