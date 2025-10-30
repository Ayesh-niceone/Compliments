<table>
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('Customer Name') }}</th>
            <th>{{ __('Department') }}</th>
            <th>{{ __('Department Code') }}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{ __('Plate Number') }}</th>
            <th>{{ __('Created At') }}</th>
            <th>{{ __('Completion Type') }}</th>
            <th>{{ __('Care User') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Comment') }}</th>
            <th>{{ __('Care Comment') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($compliments as $index => $compliment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $compliment->customer_name ?? '-' }}</td>
                <td>{{ $compliment->department->name ?? '-' }}</td>
                <td>{{ $compliment->department->code ?? '-' }}</td>
                <td>{{ $compliment->phone ?? '-' }}</td>
                <td>{{ $compliment->plate_number ?? '-' }}</td>
                <td>{{ optional($compliment->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                <td>{{ $compliment->completion_type->name ?? '-' }}</td>
                <td>{{ $compliment->careUser->name ?? '-' }}</td>
                <td>{{ $compliment->status->name ?? '-' }}</td>
                <td>{{ $compliment->comment ?? '-' }}</td>
                <td>{{ $compliment->care_comment ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="text-align:center;"> {{ __('No data available') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
