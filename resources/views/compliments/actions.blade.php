<div class="btn-group" role="group">
        @can('view compliments')

    <a href="{{ route('compliments.show', $row->id) }}" class="btn btn-sm btn-info">{{__('View')}}</a>
    @endcan
        @can('assign compliments')

    <button class="btn btn-sm btn-warning assign-care-user-btn" data-id="{{ $row->id }}">{{__('Assign')}}</button>
    @endcan
        @can('delete compliments')

    <form action="{{ route('compliments.destroy', $row->id) }}" method="POST" style="display:inline-block;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">{{__('Delete')}}</button>
    </form>
    @endcan
</div>
