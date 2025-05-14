@extends('layouts.app')

@section('title', __('service.title'))

@section('content')
    <section class="service-wrapper">
        <h1>{{ __('service.title') }}</h1>

        <p>{{ __('service.intro_1') }}</p>
        <p>{{ __('service.intro_2') }}</p>

        <h2>{{ __('service.services_title') }}</h2>
        <ul>
            @foreach(__('service.services') as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

        <p>{{ __('service.outro') }}</p>
    </section>
@endsection

@push('styles')

@endpush