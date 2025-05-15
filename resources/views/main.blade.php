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
            <p><strong>Order Scanner</strong> is not overloaded with features and is fully focused on a single task — detecting large orders.</p>

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
                    <a href="#" class="learn-more">Learn more...</a>
                </div>
                <div class="product-image">
                    <img style = "max-width: 220px;" src="{{ asset('img/os208.png') }}" alt="Order Scanner 2.08">
                </div>
            </div>

            {{-- Product 2 --}}
            <div class="product">
                <div class="product-image">
                    <img style = "max-width: 220px;" src="{{ asset('img/os101.png') }}" alt="Order Scanner 1.01">
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
                    <a href="#" class="learn-more">Learn more...</a>
                </div>
            </div>
        </div>
    </section>
@endsection
