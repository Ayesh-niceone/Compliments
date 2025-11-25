@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ route('logs.index') }}" class="btn btn-link mb-3">&larr; Back</a>

    <h2>Log Entry #{{ $log->id }}</h2>

    <div class="card mt-3">
        <div class="card-body">

            <h5 class="mb-3">General Information</h5>

            <p><strong>Description: </strong> {{ $log->description }}</p>
            <p><strong>User: </strong> {{ $log->causer?->name ?? 'System' }}</p>
            <p><strong>Log Name: </strong> {{ $log->log_name }}</p>
            <p><strong>Created At: </strong> {{ $log->created_at->format('Y-m-d H:i A') }}</p>

            <hr>

            <h5 class="mb-3">Properties</h5>

            <pre class="bg-dark text-white p-3 rounded" style="white-space: pre-wrap;">
{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
            </pre>

            @if($log->subject)
                <hr>
                <h5>Subject (Model Affected)</h5>
                <p><strong>Type:</strong> {{ get_class($log->subject) }}</p>
                <p><strong>ID:</strong> {{ $log->subject->id }}</p>
            @endif

        </div>
    </div>
</div>
@endsection
