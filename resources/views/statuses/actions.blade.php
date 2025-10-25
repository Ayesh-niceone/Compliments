<div class="btn-group" role="group">
    <button class="btn btn-sm btn-danger" onclick="deleteStatus({{ $row->id }})">Delete</button>
</div>

<script>
function deleteStatus(id) {
    if (confirm('Are you sure?')) {
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
