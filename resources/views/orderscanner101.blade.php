@extends('layouts.app')

@section('title', "{{ __('main.title') }}")

@section('content')

<style>
  .order-scanner-section {
    max-width: 800px;
    margin: 40px auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    font-family: 'Segoe UI', sans-serif;
    color: #333;
  }

  .order-scanner-section h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #1a202c;
  }

  .order-scanner-section h3 {
    font-size: 22px;
    margin-top: 30px;
    margin-bottom: 15px;
    color: #2d3748;
  }

  .order-scanner-section p {
    line-height: 1.7;
    margin-bottom: 16px;
  }

  .order-scanner-section ul {
    list-style-type: disc;
    padding-left: 20px;
  }

  .order-scanner-section li {
    margin-bottom: 12px;
    line-height: 1.6;
  }

  .order-scanner-section strong {
    color: #2b6cb0;
  }

  .order-scanner-section em {
    font-style: italic;
    color: #4a5568;
  }
</style>

<div class="container py-4" style="border-radius: 15px;">

   
    <div class="ps-3 product-container">

        <div class="product-info md-5">
            <div class="d-flex align-items-center">
                <h2 class="h3 mb-0 me-2">Order Scanner</h2>
                <img src="{{ asset('img/line.png') }}" alt="Line" class="img-fluid ms-3" style="height: 8px; width: auto;">
            </div>

            <div class="h6">{{ __('main.version') }}. 1.01</div>

            <div>
                <img src="{{ asset('img/exchngs.png') }}" alt="Line" class="img-fluid mb-4 mt-3" style="height: 34px;">

            </div>

            <p>{{ __('main.product_welcome1') }}</p>
            <p><strong>Order Scanner</strong> {{ __('main.product_welcome2') }}</p>
            <h5>{{ __('main.product_welcome3') }}</h5>
            <p><strong>{{ __('main.product_welcome4') }}</strong> {{ __('main.product_welcome5') }}</p>
            <p><strong>{{ __('main.product_welcome6') }}</strong> {{ __('main.product_welcome7') }}</p>
            <p><strong>{{ __('main.product_welcome8') }}</strong> {{ __('main.product_welcome9') }}</p>
            <p><strong>{{ __('main.product_welcome10') }}</strong> {{ __('main.product_welcome11') }}</p>



        </div>
        <div class="mb-4 mb-md-0 product-image">
            <img src="{{ asset('img/Orderscannerpack.png') }}" alt="Order Scanner" class="img-fluid mx-auto d-block">
        </div>
        <div class="d-flex justify-content-center product-price">
            <DIV>
                <div class="d-flex  mt-4 gap-3 price-block" style="max-width: 440px;">
                    <div class="">
                        <p class="mb-0" style="line-height: 1.3; font-size: 14px;">{{ __('main.product_welcome12') }}</p>
                        <p>{{ __('main.downloaded') }} <span style="font-weight:bold">{{$downloaded}} </span>{{ __('main.times') }}</p>
                    </div>
                    
                    
                    @php
                        $priceParts = explode('.', number_format($product->discounted_price, 2, '.', ''));
                    @endphp

                    <div class="text-center">
                        <p class="mb-0" style="font-size: 48px; line-height: 1; white-space: nowrap;">
                            {{ $priceParts[0] }}
                            <sup style="font-size: 0.5em; position: relative; top: -0.9em;">
                                {{ $priceParts[1] }}
                            </sup>
                        </p>
                        <p class="small">USDT</p>
                    </div>



                </div>

                <div class="d-flex align-items-start mt-4 gap-3 download-block">
                    <div class="">
                    <a href="{{ route('download.orderscanner') }}" class="position-relative d-inline-block" style="width: 320px;">
                            <img src="{{ asset('img/download.png') }}" alt="Download" class="img-fluid" style="object-fit: cover;">
                            <div class="position-absolute translate-middle-x text-center text-white" style="top: 3%; left: 58%">
                                <div style="font-size: 20px; font-weight: bold;">{{ __('main.download') }}</div>
                                <div style="font-size: 14px;">OrderScanner101Setup.exe</div>
                            </div>
                        </a>
                    </div>
                    <div class="text-center d-flex">
                        <img src="{{ asset('img/win.png') }}" class="img-fluid ms-0" style="height: 18px; width: auto;">
                        <p class="ms-2" style="font-size: 10px;">Windows</p>
                        
                        
                        
                    </div>
                </div>
            </div>
        </div>



    </div>



    <div class="row justify-content-center text-center">
        <div class="col-md-10 mt-5">
            <h2 class="h3 mb-4">{{ __('main.product_welcome13') }}</h2>
            <p>{{ __('main.product_welcome14') }}</p>
        </div>
    </div>
</div>
@endsection