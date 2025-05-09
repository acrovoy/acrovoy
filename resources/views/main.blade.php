@extends('layouts.app')

@section('title', 'Acrovoy Order Scanner - Оптимизируйте свою торговлю')

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


    <div class="row justify-content-center text-center mb-5">
        <div class="col-md-8">
            <h1 class="display-4 mb-3">Order Scanner</h1>
            <p class="lead">{{ __('main.product_welcome') }}</p>
           
        </div>
    </div>


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

            <p>Это простой, но очень эффективный инструмент для анализа рыночных данных в реальном времени. Он помогает трейдерам отслеживать крупные объёмы, анализировать движение плотностей и оптимизировать торговые стратегии в зависимости от текущей рыночной ситуации.</p>
            <p><strong>Order Scanner</strong> не перегружен функционалом и максимально сфокусирован на одной задаче — поиске крупных ордеров.</p>
            <h5>Основные возможности инструмента:</h5>
            <p><strong>Поддержка бирж Binance и Bybit</strong>. Сканирование работает как в режиме старт-стоп, так и в автоматическом режиме. Нет необходимости вручную открывать список ордеров каждой монеты — Order Scanner сам подсветит все ордера, соответствующие вашим параметрам.</p>
            <p><strong>Широкий охват монет.</strong> Сканирует большое количество монет, включая самые активные и трендовые. Вы можете самостоятельно добавлять в сканируемый список монеты, которые считаете недооценёнными.</p>
            <p><strong>Гибкие настройки.</strong> Оптимальное количество настроек позволяет легко и быстро менять параметры поиска и улучшать сортировку результатов.</p>
            <p><strong>Анализ двух рынков.</strong> Профессиональные трейдеры знают, как важно отслеживать как рынок фьючерсов, так и спот-рынок. Order Scanner позволяет быстро переключаться между ними, предоставляя больше информации для анализа.</p>
            






        </div>
        <div class="mb-4 mb-md-0 product-image">
            <img src="{{ asset('img/Orderscannerpack.png') }}" alt="Order Scanner" class="img-fluid mx-auto d-block">
        </div>
        <div class="d-flex justify-content-center product-price">
            <DIV>
                <div class="d-flex  mt-4 gap-3 price-block" style="max-width: 440px;">
                    <div class="">
                        <p class="mb-0" style="line-height: 1.3; font-size: 14px;">Без подписки и скрытых платежей. Одноразовая оплата. Без ограничений по времени</p>
                        <p>Downloaded: <span style="font-weight:bold">{{$downloaded}} </span>times</p>
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