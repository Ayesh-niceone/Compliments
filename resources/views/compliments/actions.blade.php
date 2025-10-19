<div class="btn-group" role="group">
    <a href="{{ route('compliments.show', $row->id) }}" class="btn btn-sm btn-info">View</a>
    <a href="{{ route('compliments.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <form action="{{ route('compliments.destroy', $row->id) }}" method="POST" style="display:inline-block;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
</div>
