@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label>Site Name</label>
            <input type="text" name="system_name" class="form-control" value="{{ old('system_name', $setting->system_name) }}">
        </div>

        <div class="form-group mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $setting->email) }}">
        </div>

        <div class="form-group mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $setting->phone) }}">
        </div>

        <div class="form-group mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address', $setting->address) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
            @if($setting->logo)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$setting->logo) }}" alt="Logo" height="80">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
