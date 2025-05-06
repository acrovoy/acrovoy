@extends('layouts.app')

@section('content')


<div class="d-flex">

@include('partials.sidebar_left')

<div class="container-fluid mt-3 ms-3 mb=0">

        <div class="d-flex">
                    @if(auth()->check()) <div class="mb=0" style="font-size: 24px; font-weight: bold;">Welcome, {{ auth()->user()->name }}!</div> @endif
                    @if($user->is_manager == 1)   
                    <div class="d-flex" style="margin-left: 230px; align-items: center; gap: 10px;">
                        promocode for salling: 
                        <span style="color: green; border: 1px solid green; padding: 2px 6px; border-radius: 4px;">{{$promocode}}</span>
                    </div>
                    @endif
        </div>
            
            @if($user->is_manager == 1) <span class="badge text-bg-success mt=0 mb-2">Sales Representative</span>@endif

    @if($user->is_manager == 1)

    <p>
        Your Total Balance: 
        <strong class="ms-3">USDT {{ number_format($grandTotalAmount, 2, '.', '') }}</strong> 
        <a href="#" class="text-primary ms-3" style="text-decoration: none;">Withdrawal request</a>
    </p>

                <h5>Your Products for Sale:</h5>
            <div class="custom-table">

            @php
            $product_id = '2';
           
            @endphp
                


                <div class="custom-row custom-header">
                    <div class="custom-cell">Product</div>
                    <div class="custom-cell">Price for you</div>
                    <div class="custom-cell">Units sold</div>
                    <div class="custom-cell">Total Profit</div>
                    <div class="custom-cell"></div>
                </div>

            @foreach($products as $product)

                <div class="custom-row">
                    <div class="custom-cell">{{$product->productDescription->name}} {{$product->productDescription->version}}</div>
                    <div class="custom-cell"><strong>{{$product->productDescription->min_price}}</strong></div>
                    <div class="custom-cell"><strong>{{$product->paidCount}}</strong></div>
                    <div class="custom-cell"><strong>{{ number_format($product->totalAmount, 2, '.', '') }}</strong></div>
                    <div class="custom-cell"  style="text-align: left;"><a href="{{ route('salespage', ['product_id' => $product->product_id]) }}" class="text-primary" style="text-decoration: none;">Details</a></div>
                </div>

            @endforeach   


            </div>

@endif

<h5>Your Products:</h5>

@if($sales && $sales->count())
    <div class="custom-table">
        <div class="custom-row custom-header">
            <div class="custom-cell">Product</div>
            <div class="custom-cell">Owner price</div>
            <div class="custom-cell">Discount</div>
            <div class="custom-cell">Final price</div>
            <div class="custom-cell"></div>
        </div>

        @foreach ($sales as $sale)
            <div class="custom-row">
                <div class="custom-cell">{{ $sale->product->name }} {{ $sale->product->version }}</div>
                <div class="custom-cell">
                    <strong>{{ $sale->own_price ? number_format($sale->site_price, 2) : '—' }}</strong>
                </div>
                <div class="custom-cell text-danger">
                    @php
                        $ownPrice = $sale->site_price;
                        $discountedPrice = $sale->price;
                        $discountPercent = ($ownPrice && $discountedPrice)
                            ? round((($ownPrice - $discountedPrice) / $ownPrice) * 100)
                            : 0;
                    @endphp
                    {{ $discountPercent }}%
                </div>
                <div class="custom-cell">
                    <strong>{{ $sale->price ? number_format($sale->price, 2) : '—' }}</strong>
                </div>
                <div class="custom-cell" style="text-align: left;">
                    <span style="color: #2ddd63">{{ $sale->manager->user->name }}</span>
                    @if(($sale->invoiceDetails->is_paid ?? null) != 1)
                        <span style="color:red">UNPAID</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <span style="font-size: 14px">You don't have products</span>
@endif
    
    





</div>
</div>
@endsection
