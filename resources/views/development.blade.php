@extends('layouts.app')

@section('title', __('development.title'))

@section('content')
   

    <section class="development-wrapper">
        <h1>{{ __('development.title') }}</h1>

        <p>{{ __('development.intro_1') }}</p>
        <p>{{ __('development.intro_2') }}</p>

        <h2>{{ __('development.experience_title') }}</h2>
        <ul>
            @foreach(__('development.experience_items') as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

        <p>{{ __('development.transparency') }}</p>
        <p>{{ __('development.final') }}</p>
    </section>
@endsection
