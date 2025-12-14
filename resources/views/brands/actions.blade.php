<div class="btn-group" role="group">
    @can('edit brands')
     <button class="btn btn-sm btn-warning" onclick="editBrand('{{ $row->id }}', '{{ $row->name['name_en'] }}', '{{ $row->name['name_ar'] }}')">{{ __('Edit') }}</button>
    @endcan
    @can('delete brands')
    <button class="btn btn-sm btn-danger" onclick="deleteBrand({{ $row->id }})">{{ __('Delete') }}</button>
    @endcan
</div>

<script>
function deleteBrand(id) {
    if (confirm('{{ __('Are you sure') }}')) {
        $.ajax({
            url: '/brands/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                $('#brands-table').DataTable().ajax.reload();
            }
        });
    }
}
</script>
