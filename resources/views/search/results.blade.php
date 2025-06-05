@extends('layouts.app')

@section('content')

<style>
    .product-item {
        margin-bottom: 0px; /* Меньше отступ между блоками */
        line-height: 0.5;   /* Меньше межстрочный интервал */
    }

    .product-link {
        display: inline-block;
        color: blue;
        text-decoration: none;
    }

    .product-link:hover {
        text-decoration: underline;
    }

    .search-wrapper h1, 
    .search-wrapper h2 {
        margin-bottom: 12px;
    }

    .search-wrapper p {
        margin-top: 4px;
        margin-bottom: 4px;
    }
</style>

<div class="search-wrapper">
    <h1>{{ __('results.search_result') }} "{{ $query }}"</h1>

    <h2>{{ __('results.found') }}</h2>

    @forelse($products as $product)
        <div class="product-item">
            <a href="{{ $product->url }}" class="product-link" target="_blank">
                {{ $product->name }} {{ $product->version }}
            </a>
            @if($product->description)
                <p>{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
            @endif
        </div>
    @empty
        <p>{{ __('results.nothing') }}</p>
    @endforelse
</div>
@endsection
