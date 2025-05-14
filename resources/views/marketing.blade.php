@extends('layouts.app')

@section('title', __('marketing.title'))

@section('content')
    <section class="marketing-wrapper">
        <h1>{{ __('marketing.title') }}</h1>
        
        <p>{{ __('marketing.intro_1') }}</p>
        <p>{{ __('marketing.intro_2') }}</p>

        <h2>{{ __('marketing.features_title') }}</h2>
        <ul>
            @foreach(__('marketing.features') as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>

        <p>{{ __('marketing.final') }}</p>
    </section>
@endsection
