@extends('layouts.app')

@section('content')


<div class="d-flex">

@include('partials.sidebar_left')

<div class="container-fluid mt-3 ms-3 mb=0">

        <div class="d-flex">
                    @if(auth()->check()) <div class="mb=0" style="font-size: 24px; font-weight: bold;">{{ __('dashboard.welcome') }}, {{ auth()->user()->name }}!</div> @endif
                    @if($user->is_manager == 1)   
                    <div class="d-flex" style="margin-left: 230px; align-items: center; gap: 10px;">
                        {{ __('dashboard.promocode') }} 
                        <span style="color: green; border: 1px solid green; padding: 2px 6px; border-radius: 4px;">{{$promocode}}</span>
                    </div>
                    @endif
        </div>
            
            @if($user->is_manager == 1) <span class="badge text-bg-success mt=0 mb-2">{{ __('dashboard.salesrepresentative') }}</span>@endif

    @if($user->is_manager == 1)

    <p>
       {{ __('dashboard.yourtotalbalance') }} 
        <strong class="ms-3">USDT {{ number_format($grandTotalAmount, 2, '.', '') }}</strong> 
        <a href="#" class="text-primary ms-3" style="text-decoration: none;">{{ __('dashboard.withdrawal_request') }}</a>
    </p>

                <h5>{{ __('dashboard.productsale') }} </h5>

    @if($products && $products->count())
            <div class="custom-table">

            @php
            $product_id = '2';
           
            @endphp
                


                <div class="custom-row custom-header">
                    <div class="custom-cell">{{ __('dashboard.product') }}</div>
                    <div class="custom-cell">{{ __('dashboard.priceforyou') }}</div>
                    <div class="custom-cell">{{ __('dashboard.units_sold') }}</div>
                    <div class="custom-cell">{{ __('dashboard.total_profit') }}</div>
                    <div class="custom-cell"></div>
                </div>

            @foreach($products as $product)

                <div class="custom-row">
                    <div class="custom-cell">{{$product->productDescription->name}} {{$product->productDescription->version}}</div>
                    <div class="custom-cell"><strong>{{$product->productDescription->min_price}}</strong></div>
                    <div class="custom-cell"><strong>{{$product->paidCount}}</strong></div>
                    <div class="custom-cell"><strong>{{ number_format($product->totalAmount, 2, '.', '') }}</strong></div>
                    <div class="custom-cell"  style="text-align: left;"><a href="{{ route('salespage', ['product_id' => $product->product_id]) }}" class="text-primary" style="text-decoration: none;">{{ __('dashboard.details') }}</a></div>
                </div>

            @endforeach   


            </div>
    @else
    <div class="d-flex mb-4"><span>{{ __('dashboard.youdonthaveprod') }}</span><a href="/add_product" class="ms-1" style="color:cadetblue; text-decoration:none">{{ __('dashboard.here') }}</a>
    </div>
    @endif


@endif

<h5>{{ __('dashboard.yourproducts') }}</h5>



@if($sales && $sales->count())
    <div class="custom-table">
        <div class="custom-row custom-header">
            <div class="custom-cell">{{ __('dashboard.product') }}</div>
            <div class="custom-cell">{{ __('dashboard.owner_price') }}</div>
            <div class="custom-cell">{{ __('dashboard.discount') }}</div>
            <div class="custom-cell">{{ __('dashboard.final_price') }}</div>
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

                @if($sale->manager->user->name == 'ACROVOY')
                    <span style="color:#0d6efd">{{ $sale->manager->user->name }}</span>
                @else
                    <span style="color: green">{{ $sale->manager->user->name }}</span>
                @endif


                    @if(($sale->invoiceDetails->is_paid ?? null) != 1)
                        <span style="color:red">{{ __('dashboard.unpaid') }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <span style="font-size: 14px">{{ __('dashboard.youdonthaveproducts') }}</span>
@endif
    
    





</div>
</div>
@endsection
