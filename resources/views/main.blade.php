@extends('layouts.app')

@section('title', 'Acrovoy Order Scanner - Оптимизируйте свою торговлю')

@section('content')


<div class="container py-4" style="border-radius: 15px;">


    <div class="row justify-content-center text-center mb-5">
        <div class="col-md-8">
            <h1 class="display-4 mb-3">Order Scanner</h1>
            <p class="lead">Мы рады представить Вам новый продукт для эффективного анализа стакана ордеров.</p>
        </div>
    </div>


    <div class="ps-3 product-container">

        <div class="product-info md-5">
            <div class="d-flex align-items-center">
                <h2 class="h3 mb-0 me-2">Order Scanner</h2>
                <img src="{{ asset('img/line.png') }}" alt="Line" class="img-fluid ms-3" style="height: 8px; width: auto;">
            </div>

            <div class="h6">version. 1.01</div>

            <div>
                <img src="{{ asset('img/exchngs.png') }}" alt="Line" class="img-fluid mb-4 mt-3" style="height: 34px;">

            </div>

            <p>Это мощный инструмент для анализа рыночных данных в реальном времени. Он помогает трейдерам видеть скрытые объемы, плотности заявок и оптимизировать свои торговые стратегии.</p>
            <p>Это мощный инструмент для анализа рыночных данных в реальном времени. Он помогает трейдерам видеть скрытые объемы, плотности заявок и оптимизировать свои торговые стратегии.</p>
            <p>Это мощный инструмент для анализа рыночных данных в реальном времени. Он помогает трейдерам видеть скрытые объемы, плотности заявок и оптимизировать свои торговые стратегии.</p>
            <p>Это мощный инструмент для анализа рыночных данных в реальном времени. Он помогает трейдерам видеть скрытые объемы, плотности заявок и оптимизировать свои торговые стратегии.</p>
            <p>Посмотреть все характеристики.</p>





        </div>
        <div class="mb-4 mb-md-0 product-image">
            <img src="{{ asset('img/Orderscannerpack.png') }}" alt="Order Scanner" class="img-fluid mx-auto d-block">
        </div>
        <div class="d-flex justify-content-center product-price">
            <DIV>
                <div class="d-flex  mt-4 gap-3 price-block" style="max-width: 440px;">
                    <div class="">
                        <p class="mb-0" style="line-height: 1.3;">Это мощный инструмент для анализа рыночных данных в реальном времени. Он помогает трейдерам видеть скрытые объемы, плотности заявок и оптимизировать свои торговые стратегии.</p>
                    </div>
                    <div class="text-center">
                        <p class="h1 mb-0">150<sup style="font-size:24px">00</sup></p>
                        <p class="small">USDT</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mt-4 gap-3 download-block">
                    <div class="">
                    <a href="{{ route('download.orderscanner') }}" class="position-relative d-inline-block" style="width: 320px;">
                            <img src="{{ asset('img/download.png') }}" alt="Download" class="img-fluid" style="object-fit: cover;">
                            <div class="position-absolute translate-middle-x text-center text-white" style="top: 3%; left: 58%">
                                <div style="font-size: 20px; font-weight: bold;">Скачать</div>
                                <div style="font-size: 14px;">OrderScannerSetup.exe</div>
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
            <h2 class="h3 mb-4">Почему выбирают Order Scanner?</h2>
            <p>С Order Scanner вы получаете преимущество на рынке, быстрее реагируете на изменения и минимизируете риски. Инструмент разработан для профессионалов и начинающих трейдеров, которые стремятся к результату.</p>
        </div>
    </div>
</div>
@endsection