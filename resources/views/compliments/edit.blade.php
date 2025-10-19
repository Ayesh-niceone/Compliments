@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Compliment #{{ $compliment->id }}</h1>

    <form action="{{ route('compliments.update', $compliment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="new" {{ $compliment->status == 'new' ? 'selected' : '' }}>New</option>
                <option value="in_progress" {{ $compliment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ $compliment->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ $compliment->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                <option value="">-- not set --</option>
                @foreach($departments as $d)
                    <option value="{{ $d->id }}" {{ $compliment->department_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Care user</label>
            <select name="care_user_id" class="form-control">
                <option value="">-- none --</option>
                @foreach($careUsers as $u)
                    <option value="{{ $u->id }}" {{ $compliment->care_user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Comment (update)</label>
            <textarea name="comment" class="form-control" rows="4">{{ old('comment', $compliment->comment) }}</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
