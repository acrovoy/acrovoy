@extends('layouts.app')

@section('content')


<div class="search-wrapper">
    <h1>{{ __('results.search_result') }} "{{ $query }}"</h1>

    <h2>{{ __('results.found') }}</h2>

    @forelse($products as $product)
        <div class="product-item">
            <a href="" class="product-link">
                {{ $product->name }} {{ $product->version }}
            </a>
            @if($product->description)
                <p style="margin-top: 8px;">{{ Str::limit($product->description, 100) }}</p>
            @endif
        </div>
    @empty
        <p>{{ __('results.nothing') }}</p>
    @endforelse
</div>
@endsection
