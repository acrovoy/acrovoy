@extends('layouts.app')

@section('title', __('vision.title'))

@section('content')
    <section class="vision-wrapper">
        <h1>{{ __('vision.title') }}</h1>

        <p>{{ __('vision.intro_1') }}</p>
        <p>{{ __('vision.intro_2') }}</p>

        <h2>{{ __('vision.features_title') }}</h2>
        <ul class="features-list">
            @foreach(__('vision.features') as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

        <p>{{ __('vision.final') }}</p>
    </section>
@endsection
