<div class="btn-group" role="group">
    <a href="{{ route('users.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $row->id }})">Delete</button>
</div>

<script>
function deleteUser(id) {
    if (confirm('Delete this user?')) {
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
