@extends('layouts.app')

@section('content')


<div class="d-flex">


@include('partials.sidebar_left', ['manager_id' => $manager->id ?? null])

<div class="container-fluid mt-3 ms-3 mb=0">

                

<div class="d-flex align-items-center mb-3">
    <h5 class="me-0">{{ __('addproduct.youcanaddfollprod') }}</h5>

    @if (session('success'))
        <span style="color: green; margin-left:20px">
            {{ session('success') }}
        </span>
    @endif
</div>


                <form action="{{ route('sales.updateProducts') }}" method="POST">
    @csrf

    <div class="custom-table">
        <div class="custom-row custom-header">
            <div class="custom-cell">{{ __('addproduct.product') }}</div>
            <div class="custom-cell">{{ __('addproduct.priceforyou') }}</div>
            <div class="custom-cell">{{ __('addproduct.websiteprice') }}</div>
            <div class="custom-cell">{{ __('addproduct.select') }}</div>
            <div class="custom-cell"></div>
        </div>

        @foreach($allproducts as $product)
            <div class="custom-row">
                <div class="custom-cell">{{ $product->name }} {{ $product->version }}</div>
                <div class="custom-cell"><strong>{{ $product->min_price }}</strong></div>
                <div class="custom-cell"><strong>{{ $product->discounted_price }}</strong></div>
                <div class="custom-cell">
                <input class="form-check-input" type="checkbox" name="products[]" value="{{ $product->id }}" {{ $product->checked }}>
                </div>
                <div class="custom-cell" style="text-align: left;">
                    <a href="{{ $product->url }}" target="_blank" class="text-primary" style="text-decoration: none;">{{ __('addproduct.details') }}</a>
                </div>
            </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary mt-3">{{ __('addproduct.savemyprod') }}</button>
</form>

 
</div>
</div>
@endsection
