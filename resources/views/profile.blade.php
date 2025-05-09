@extends('layouts.app')

@section('content')


<div class="d-flex">


@include('partials.sidebar_left')


      
    <div class="container-fluid mt-3 ms-3" style="width: 400px;">

    <div class="d-flex align-items-center mb-2">
        <h3 class="me-4 mt-1">{{ __('profile.profile') }}</h3>

        @if (session('status'))
            <span  style="color:green">
                {{ session('status') }}
            </span>
        @endif
        @if (session('status_password'))
                 <span  style="color: green">
                    {{ session('status_password') }}
                 </span>
        @endif


    </div>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-2">
            <label for="name">{{ __('profile.name') }}</label>
            <input type="text" style="border: 1px solid #303944b0;" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
        </div>

        <div class="form-group mb-2">
            <label for="email">Email</label>
            <input type="email" style="border: 1px solid #303944b0;" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
        </div>

       

        <button type="submit" class="btn btn-primary mt-3">{{ __('profile.updateprofile') }}</button>
            </form>


            <div class="mt-5 ms-0" style="width: 400px;">
            
           
            <form method="POST" action="{{ route('password.update.custom') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="current_password">{{ __('profile.current_password') }}</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" style="border: 1px solid #303944b0;" required autocomplete="current-password">
                    @error('current_password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">{{ __('profile.new_password') }}</label>
                    <input type="password" name="password" id="password" class="form-control" style="border: 1px solid #303944b0;" required autocomplete="new-password">
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation">{{ __('profile.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" style="border: 1px solid #303944b0;" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('profile.change_password') }}</button>
            </form>
        </div>
        





</div>













</div>
@endsection
