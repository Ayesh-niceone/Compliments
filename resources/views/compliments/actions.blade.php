<div class="btn-group" role="group">
    <a href="{{ route('compliments.show', $row->id) }}" class="btn btn-sm btn-info">{{__('View')}}</a>
    <button class="btn btn-sm btn-warning assign-care-user-btn" data-id="{{ $row->id }}">{{__('Assign')}}</button>
    <form action="{{ route('compliments.destroy', $row->id) }}" method="POST" style="display:inline-block;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">{{__('Delete')}}</button>
    </form>
</div>
