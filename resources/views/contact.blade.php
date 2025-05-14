@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
<section class="page-wrapper">
    <h1>{{ __('contact.heading') }}</h1>

    <p>{{ __('contact.intro') }}</p>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
        @csrf

        <label for="email">{{ __('contact.email') }}</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>

        <label for="subject">{{ __('contact.subject') }}</label>
        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required>

        <label for="message">{{ __('contact.message') }}</label>
        <textarea name="message" id="message" rows="6" required>{{ old('message') }}</textarea>

        <button type="submit">{{ __('contact.send') }}</button>
    </form>
</section>
@endsection

@push('styles')

@endpush
