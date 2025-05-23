@extends('layouts.app')

@section('content')

<div class="d-flex">
@include('partials.sidebar_left', ['manager_id' => $manager->id ?? null])

    <div class="container-fluid mt-3 ms-3">

        <div class="d-flex"> 
            <div>
                <h3 > {{ $product->name }}  {{ $product->version }} </h3>
                <p class="mb-0">{{ __('salespage.totalprodforselling') }} <strong>USDT {{ number_format($totalAmount, 2, '.', '') }}</strong> </p>
                <div class="d-flex">
                    
                    <p class="ms-0">{{ __('salespage.registered') }} <strong>{{$totalCount}}</strong> {{ __('salespage.buyers') }}&nbsp;  |  </p>
                    <p class="ms-2">{{ __('salespage.paid') }} <strong>{{$paidCount}}</strong> {{ __('salespage.units') }}</p>
                </div>
            </div>
            <div>
               
                <form method="POST" action="{{ route('sale.updatePrice') }}">
                    @csrf
                    <input type="hidden" name="manager_id" value="{{ $user->manager_id }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="d-flex" style="margin-left: 70px; align-items: center; gap: 10px;">
                        <label for="usdt-price">{{ __('salespage.setprice') }} USDT</label>
                        <input id="usdt-price" name="price" type="text" value="{{ $current_price }}" style="width: 70px; border-radius: 4px; border: 1px #303944b0 solid; text-align: center;" />
                        <button type="submit" style="color: white; color: green; width: 40px; border: none; padding: 5px 5px; background: none;">{{ __('salespage.save') }}</button>
                    </div>
                </form>



                <span style="margin-left: 70px; align-items: center; gap: 10px; font-size: 12px; color: crimson">{{ __('salespage.priceforyou') }} <strong>USDT&nbsp;{{ $product->min_price }}</strong> </span>

                @php
                
                $profit = $current_price - $product->min_price;
                
                @endphp

                <div style="margin-left: 70px; align-items: center; gap: 10px; font-size: 12px; color: crimson">{{ __('salespage.profitasperunit') }} <strong>USDT&nbsp;{{ number_format($profit, 2, '.', '') }}</strong></div>


            </div>
        </div>   

    @if($sales && $sales->count())

<div class="custom-table">
    <div class="custom-row custom-header">
        <div class="custom-cell" style="width: 30px !important"></div>
        <div class="custom-cell">{{ __('salespage.email') }}</div>
        <div class="custom-cell">{{ __('salespage.registered2') }}</div>
        <div class="custom-cell">{{ __('salespage.paid2') }}</div>
        <div class="custom-cell">{{ __('salespage.sellingprice') }}</div>
        <div class="custom-cell"></div>
    </div>


    @foreach ($sales as $sale)

    @php
    $email = $sale->buyers_email;
    $index = $totalCount - $loop->index; 
    @endphp

    <div class="custom-row">
    <div class="custom-cell" style="width: 30px !important">{{ $index }}</div>
    <div class="custom-cell" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
    <a href="mailto:{{ $sale->buyers_email }}"
        target="_blank" 
       style="color: blue; text-decoration: none;"
       title="{{ $sale->buyers_email }}">
        {{ $sale->buyers_email }}
    </a>
    </div>
    <div class="custom-cell">{{ $sale->created_at->format('d.m.Y') }}</div>

    <div class="custom-cell">
        @if ($sale->invoice->is_paid == 1)
            <span style="color:green">{{ __('salespage.yes') }}</span>
        @else
            <span style="color:red">{{ __('salespage.no') }}</span>
        @endif
    </div>

    <div class="custom-cell">
        <strong>
            <span style="color: {{ $sale->invoice->is_paid == 1 ? 'black' : 'red' }}">{{$sale->price}}</span>
        </strong>
    </div>

    <div class="custom-cell" style="text-align: left;">
        <a href="" class="text-primary" style="text-decoration: none;"></a>
    </div>
    </div>

   @endforeach

   

</div>

@else

{{ __('salespage.youdonthavesaling') }}

@endif

@php
    $currentPage = $sales->currentPage();
    $lastPage = $sales->lastPage();
    $startPage = max(1, $currentPage - 2);
    $endPage = min($lastPage, $currentPage + 2);
@endphp

@if ($lastPage > 1)
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-cente ">

        {{-- Previous --}}
        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $sales->previousPageUrl() }}" tabindex="-1">{{ __('salespage.pre') }}</a>
        </li>

        {{-- Pages --}}
        @for ($i = $startPage; $i <= $endPage; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="{{ $sales->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- Next --}}
        <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $sales->nextPageUrl() }}">{{ __('salespage.next') }}</a>
        </li>

    </ul>
</nav>
@endif
    </div>


   


</div>

@endsection
