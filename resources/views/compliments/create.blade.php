@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Compliment</h1>

    <form action="{{ route('compliments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Created By (Customer)</label>
            <select name="created_by_id" class="form-control" required>
                <option value="">-- Select customer --</option>
                @foreach($customers as $cust)
                    <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->email ?? $cust->phone }})</option>
                @endforeach
            </select>
            <input type="hidden" name="created_by_type" value="{{ \App\Models\Customer::class }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Target (optional)</label>
            <input type="text" name="target_id" class="form-control" placeholder="target id (if any)">
            <small class="form-text text-muted">If you have a Worker model, set target_type to its FQCN via JS or a hidden field.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-control">
                <option value="">-- select --</option>
                @foreach($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Care User (assign)</label>
            <select name="care_user_id" class="form-control">
                <option value="">-- unassigned --</option>
                @foreach($careUsers as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="comment" class="form-control" rows="4"></textarea>
        </div>

        <button class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
