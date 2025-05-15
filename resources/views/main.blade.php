@extends('layouts.app')

@section('title', 'Main')

@section('content')

{{-- Hero Section --}}
<div class="hero-wrapper">
    <div class="hero-section">
        <div class="hero-image">
            <img src="{{ asset('img/mountain.png') }}" alt="Mountain">
        </div>
        <div class="hero-text">
    <h2>Reaching tomorrow today</h2>
<p><strong>Acrovoy</strong> is a visionary collective driven by precision. We build intelligent tools that decode market behavior and illuminate hidden liquidity — empowering professionals with actionable insight in real time.</p>

    <div class="hero-box">
        <h4>What drives us:</h4>
        <ul>
         <li><strong>Acrovoy unites experts from diverse fields — technology, finance, and design.</strong></li>
        <li>We collaborate to create innovative tools that solve complex real-world challenges.</li>
        <li>Our strength lies in a shared vision and a multidisciplinary approach to development.</li>
    </ul>
    </div>

    <div class="hero-box">
        <h4>Who we work with:</h4>
        <p>Choose your role and see how Acrovoy can empower your goals:</p>
        <div class="role-selector">
            <button onclick="showRole('trader')">Trader</button>
            <button onclick="showRole('influencer')">Influencer</button>
            <button onclick="showRole('developer')">Developer</button>
            <button onclick="showRole('startup')">Startup</button>
        </div>
        <div id="role-result" class="role-result">
            <!-- динамически подставляется -->
        </div>
    </div>
</div>

    </div>
</div>
<section class="page-wrapper">

    {{-- Products Section --}}
    <div class="products-section">
        <h2 class="section-title">Our Products</h2>
        <p class="section-subtitle">We are pleased to introduce our new product for efficient order book analysis.</p>

        {{-- Product 1 --}}
        <div class="product">
            <div class="product-info">
                <h3>Order Scanner</h3>
                <p class="version">version 2.08 <span class="badge">presale</span></p>
                <p>This is a simple yet highly effective tool for analyzing market data — track large volumes, analyze order book density, movement strategies based on the current market situation.</p>
                <ul>
                    <li><strong>Supports Binance, Bybit and Bitget exchanges.</strong></li>
                    <li><strong>Wide coin coverage.</strong></li>
                    <li><strong>Flexible settings.</strong></li>
                    <li><strong>Dual market analysis.</strong></li>
                    <li><strong>Proxy support.</strong></li>
                </ul>
                <a href="{{ route('orderscanner208') }}" class="learn-more">Learn more...</a>
            </div>
            <div class="product-image">
                <img style="max-width: 220px;" src="{{ asset('img/os208.png') }}" alt="Order Scanner 2.08">
            </div>
        </div>

        {{-- Product 2 --}}
        <div class="product" id="product-2">
            <div class="product-image">
                <img style="max-width: 220px;" src="{{ asset('img/os101.png') }}" alt="Order Scanner 1.01">
            </div>
            <div class="product-info">
                <h3>Order Scanner</h3>
                <p class="version">version 1.01</p>
                <p>This is a simple yet highly effective tool for analyzing market data — track large volumes, analyze order book density, movement strategies based on the current market situation.</p>
                <ul>
                    <li><strong>Supports Binance and Bybit exchanges.</strong></li>
                    <li><strong>Wide coin coverage.</strong></li>
                    <li><strong>Flexible settings.</strong></li>
                    <li><strong>Dual market analysis.</strong></li>
                </ul>
                <a href="{{ route('orderscanner101') }}" class="learn-more">Learn more...</a>
            </div>
        </div>
    </div>
</section>



{{-- Hero Section --}}
<div class="hero-wrapper">
    <div class="hero-section">
        <div class="hero-image">
        
          <!-- Error messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Contact Form -->
    <form method="POST" action="{{ route('contact.send') }}" class="contact-form" style="width: 500px; margin-bottom: 100px">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('contact.email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="subject">{{ __('contact.subject') }}</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="message">{{ __('contact.message') }}</label>
            <textarea name="message" id="message" rows="6" required class="form-control">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('contact.send') }}</button>
    </form>
        





        </div>
        <div class="hero-text">
          

            <div class="hero-box">
                <h4>Main features of the tool:</h4>
                <ul>
                    <li><strong>Supports Binance and Bybit exchanges.</strong></li>
                    <li>Scanning works both in start-stop mode and in automatic mode.</li>
                    <li>No need to open the order book manually — Order Scanner highlights relevant orders based on your parameters.</li>
                </ul>
            </div>
            <div class="hero-box">
                <h4>Main features of the tool:</h4>
                <ul>
                    <li><strong>Supports Binance and Bybit exchanges.</strong></li>
                    <li>Scanning works both in start-stop mode and in automatic mode.</li>
                    <li>No need to open the order book manually — Order Scanner highlights relevant orders based on your parameters.</li>
                </ul>
            </div>

        </div>
    </div>
</div>



<script>
function showRole(role) {
    const result = document.getElementById('role-result');
    let content = '';

    switch(role) {
        case 'trader':
            content = "<strong>You're a Trader</strong><br>Use our detection system to track large market moves in real time and make faster decisions.";
            break;
        case 'influencer':
            content = "<strong>You're an Influencer</strong><br>Partner with Acrovoy and offer your audience powerful trading insights, with monetization tools built-in.";
            break;
        case 'developer':
            content = "<strong>You're a Developer</strong><br>Integrate Acrovoy’s detection engine into your platform using our upcoming API.";
            break;
        case 'startup':
            content = "<strong>You're a Startup</strong><br>Leverage Acrovoy’s tech to enhance your product, attract investors, and stand out from the competition.";
            break;
    }

    result.innerHTML = content;
}
</script>


@endsection