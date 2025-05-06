
@extends('layouts.app')

@section('content')

<div class="d-flex">
    @include('partials.sidebar_left')

    <div class="container-fluid mt-3 ms-3" style="width: 400px;">
        <h3>Forgot Password</h3>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="form-group mb-2">
                <label for="email">Email</label>
                <input type="email" style="border: 1px solid #303944b0;" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ __('Email Password Reset Link') }}
            </button>
        </form>
    </div>
</div>

@endsection


