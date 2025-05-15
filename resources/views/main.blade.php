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
    <h2>{{ __('main.description') }}</h2>
<p><strong>Acrovoy</strong>{{ __('main.description1') }}</p>

    <div class="hero-box">
        <h4>{{ __('main.description2') }}</h4>
        <ul>
         <li><strong>{{ __('main.description3') }}</strong></li>
        <li>{{ __('main.description4') }}</li>
        <li>{{ __('main.description5') }}</li>
    </ul>
    </div>

    <div class="hero-box">
        <h4>{{ __('main.description6') }}</h4>
        <p>{{ __('main.description7') }}</p>
        <div class="role-selector">
            <button onclick="showRole('trader')">{{ __('main.trader') }}</button>
            <button onclick="showRole('influencer')">{{ __('main.influencer') }}</button>
            <button onclick="showRole('developer')">{{ __('main.developer') }}</button>
            <button onclick="showRole('startup')">{{ __('main.startup') }}</button>
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
        <h2 class="section-title">{{ __('main.description8') }}</h2>
        <p class="section-subtitle">{{ __('main.description9') }}</p>

        {{-- Product 1 --}}
        <div class="product">
            <div class="product-info">
                <h3>{{ __('main.description10') }}</h3>
                <p class="version">{{ __('main.version') }} 2.08 <span class="badge">{{ __('main.presale') }}</span></p>
                <p>{{ __('main.description12') }}</p>
                <ul>
                    <li><strong>{{ __('main.description13') }}</strong></li>
                    <li><strong>{{ __('main.description14') }}</strong></li>
                    <li><strong>{{ __('main.description15') }}</strong></li>
                    <li><strong>{{ __('main.description16') }}</strong></li>
                    <li><strong>{{ __('main.description17') }}</strong></li>
                </ul>
                <a href="{{ route('orderscanner208') }}" class="learn-more">{{ __('main.learn_more') }}</a>
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
                <h3>{{ __('main.description10') }}</h3>
                <p class="version">{{ __('main.version') }} 1.01</p>
                <p>{{ __('main.description19') }}</p>
                <ul>
                    <li><strong>{{ __('main.description20') }}</strong></li>
                    <li><strong>{{ __('main.description14') }}</strong></li>
                    <li><strong>{{ __('main.description15') }}</strong></li>
                    <li><strong>{{ __('main.description16') }}</strong></li>
                </ul>
                <a href="{{ route('orderscanner101') }}" class="learn-more">{{ __('main.learn_more') }}</a>
            </div>
        </div>
    </div>
</section>



{{-- Hero Section --}}
<div class="hero-wrapper form-wrapper">
    <div class="hero-section form-section">
        
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
    <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
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
        





        <div class="hero-text">
          

            <div class="hero-box">
                <h4>{{ __('main.description21') }}</h4>
                <ul>
                    <li>{{ __('main.description22') }}</li>
                    <li>{{ __('main.description23') }}</li>
                </ul>
            </div>
            <div class="hero-box">
                <h4>{{ __('main.description24') }}</h4>
                <ul>
                    <li><strong>{{ __('main.description25') }}</strong></li>
                    <li>{{ __('main.description26') }}</li>
                    <li>{{ __('main.description27') }}</li>
                </ul>
            </div>

            <div class="hero-box">
                <h4>{{ __('main.contact_us') }}</h4>
                <ul>
                    <li><strong>{{ __('main.email') }}</strong> {{ __('main.description31') }}</li>
                    <li><strong>{{ __('main.telegram') }}</strong> {{ __('main.description32') }}</li>
                    <li>{{ __('main.description33') }}</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<script>
    const translations = {
        trader: {
            title: @json(__('main.tradera')),
            text: @json(__('main.tradera2')),
        },
        influencer: {
            title: @json(__('main.influencera')),
            text: @json(__('main.influencera2')),
        },
        developer: {
            title: @json(__('main.developera')),
            text: @json(__('main.developera2')),
        },
        startup: {
            title: @json(__('main.startupa')),
            text: @json(__('main.startupa2')),
        }
    };
</script>

<script>
function showRole(role) {
    const result = document.getElementById('role-result');
    const data = translations[role];

    if (data) {
        result.innerHTML = `<strong>${data.title}</strong><br>${data.text}`;
    } else {
        result.innerHTML = '';
    }
}
</script>


@endsection