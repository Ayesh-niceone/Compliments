@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('compliments.index') }}" class="btn btn-link">&larr; Back</a>

    <h1>Compliment #{{ $compliment->id }}</h1>
    <p><strong>Created by:</strong> {{ optional($compliment->createdBy)->name ?? ($compliment->created_by_type . ' #' . $compliment->created_by_id) }}</p>
    <p><strong>Target:</strong> {{ optional($compliment->target)->name ?? ($compliment->target_type ? $compliment->target_type . ' #' . $compliment->target_id : '-') }}</p>
    <p><strong>Department:</strong> {{ $compliment->department->name ?? '-' }}</p>
    <p><strong>Care user:</strong> {{ $compliment->careUser->name ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $compliment->status }}</p>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Comment</h5>
            <p>{{ $compliment->comment }}</p>
        </div>
    </div>

    <h4>History</h4>
    <ul class="list-group mb-4">
        @foreach($compliment->histories as $h)
            <li class="list-group-item">
                <strong>{{ $h->action }}</strong>
                by {{ $h->user->name ?? 'system' }}
                <br>
                <small>{{ $h->created_at->diffForHumans() }}</small>
                <div>{{ $h->note }}</div>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('compliments.edit', $compliment) }}" class="btn btn-secondary">Edit</a>
</div>
@endsection
