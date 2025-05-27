
@php
/** @var \Anhskohbo\NoCaptcha\NoCaptcha $NoCaptcha */
@endphp
@use('Anhskohbo\NoCaptcha\Facades\NoCaptcha', 'NoCaptcha')

@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
<section class="page-wrapper">
    <h1>{{ __('contact.heading') }}</h1>

    <p>{{ __('contact.intro') }}</p>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Contact Form -->
    <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('contact.email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="subject">{{ __('contact.subject') }}</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="message">{{ __('contact.message') }}</label>
            <textarea name="message" id="message" rows="6" required class="form-control">{{ old('message') }}</textarea>
        </div>

        {!! NoCaptcha::display() !!}
        {!! NoCaptcha::renderJs() !!}


        <button type="submit" class="btn btn-primary">{{ __('contact.send') }}</button>
    </form>
</section>
@endsection