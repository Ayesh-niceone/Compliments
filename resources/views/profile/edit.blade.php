@extends('layouts.app')

@section('content')
<div class="card w-100">
    <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">{{ __('Profile') }}</h5>

        <div class="row">
            <div class="col-12">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <div class="col-12">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <div class="col-12">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
