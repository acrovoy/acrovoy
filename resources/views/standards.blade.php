@extends('layouts.app')

@section('title', __('standards.title'))

@section('content')

<section class="standards-wrapper">
    <h1>{{ __('standards.title') }}</h1>

    <p>{{ __('standards.intro_1') }}</p>
    <p>{{ __('standards.intro_2') }}</p>

    <h2>{{ __('standards.features_title') }}</h2>
    <ul>
        @foreach(__('standards.features') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>

    <p>{{ __('standards.final') }}</p>
</section>

@endsection

@push('styles')
   
@endpush
