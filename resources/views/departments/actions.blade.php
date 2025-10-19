<div class="btn-group" role="group">
    <button class="btn btn-sm btn-danger" onclick="deleteDepartment({{ $row->id }})">Delete</button>
</div>

<script>
function deleteDepartment(id) {
    if (confirm('Are you sure?')) {
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
