@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Activity Logs</h1>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>User</th>
                <th>Log Name</th>
                <th>Date</th>
                <th width="120">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>

                    <td>{{ $log->description }}</td>

                    <td>{{ $log->causer?->name ?? 'System' }}</td>

                    <td>{{ $log->log_name }}</td>

                    <td>{{ $log->created_at->format('Y-m-d H:i A') }}</td>

                    <td>
                        <a href="{{ route('logs.show', $log->id) }}" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {{ $logs->links() }}
</div>
@endsection
