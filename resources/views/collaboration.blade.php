@extends('layouts.app')

@section('title', __('collaboration.title'))

@section('content')
    <section class="collaboration-wrapper">
        <h1>{{ __('collaboration.title') }}</h1>

        <p>{{ __('collaboration.intro_1') }}</p>
        <p>{{ __('collaboration.intro_2') }}</p>

        <h2>{{ __('collaboration.features_title') }}</h2>
        <ul>
            @foreach(__('collaboration.features') as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

        <p>{{ __('collaboration.final') }}</p>
    </section>
@endsection

@push('styles')

@endpush
