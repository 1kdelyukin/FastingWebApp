@extends('layouts.app')  <!-- Assuming this template is saved as app.blade.php in layouts -->

@section('title', 'Profile')

@section('content')
<img src="{{ asset('images/THINKFast_app_logo.svg') }}" alt="App Logo"/>

    <div class="py-12" style="
    padding-bottom: 100px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div>
                <div class="max-w-xl px-4 w-full">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div>
                <div class="max-w-xl px-4 w-full">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div>
                <div class="max-w-xl px-4 w-full">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
