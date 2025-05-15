<header>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">ACROVOY</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 800px;">

        <li class="nav-item">
          <a class="nav-link" aria-disabled="true" href="{{ route('development') }}">{{ __('header.development') }}</a>
        </li>


        <li class="nav-item">
          <a class="nav-link me-2" aria-disabled="true" href="{{ route('collaboration') }}">{{ __('header.for_partners') }}</a>
      </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('header.products') }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('orderscanner208') }}">Order Scanner 2.08</a></li>
            <li><a class="dropdown-item" href="{{ route('orderscanner101') }}">Order Scanner 1.01</a></li>
            
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item disabled" href="#">Bitcoinex</a></li>
          </ul>
        </li>
        
        <!-- <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">{{ __('header.algotrading') }}</a>
        </li> -->
      </ul>

     
      

      @guest
          <!-- Если пользователь НЕ авторизован, показываем иконку профиля и форму логина -->
          <div class="nav-item dropdown me-3">
              <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="{{ asset('img/profile.png') }}" alt="Profile" style="height: 24px;">
              </a>
              <div class="dropdown-menu dropdown-menu-end p-4" style="min-width: 250px;">
                  <form method="POST" class="form-horizontal" action="{{ route('login') }}">
                      @csrf
                      <div class="mb-3">
                          <label for="loginEmail" class="form-label">{{ __('header.login') }}</label>
                          <input type="text" class="form-control" id="loginEmail" name="email" placeholder="{{ __('header.enter_login') }}" required>
                      </div>
                      <div class="mb-3">
                          <label for="loginPassword" class="form-label">{{ __('header.password') }}</label>
                          <input type="password" class="form-control" id="loginPassword" name="password" placeholder="{{ __('header.enter_password') }}" required>
                      </div>
                      <button type="submit" class="btn btn-primary w-100 mb-2">{{ __('header.sign_in') }}</button>
                  </form>
                  
                  <!-- Forgot password link -->
                  <div class="text-center">
                      <a href="{{ route('password.request') }}" class="d-block text-decoration-none" style="font-size: 0.875rem; color: #0d6efd;">
                      {{ __('header.forgot_the_password') }}
                      </a>
                  </div>
              </div>
          </div>
      @endguest

        @auth
            <!-- Если пользователь АВТОРИЗОВАН, показываем его email -->
            <div class="nav-item dropdown me-3">
                <a class="nav-link" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:green">
                  {{ Auth::user()->email }}
                </a>
                <div class="dropdown-menu dropdown-menu-end p-2">

                <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('header.dashboard') }}</a>
                <div><hr class="dropdown-divider"></div>
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item">{{ __('header.logout') }}</button>
                  </form>
                </div>
            </div>
        @endauth

        <li class="nav-item lang-selector dropdown list-unstyled me-3">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ strtoupper(app()->getLocale()) }}
          </a>
          <ul class="dropdown-menu" style="width: 60px; min-width: 0;">
            <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'en']) }}">En</a></li>
            <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'es']) }}">Es</a></li> 
            <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'fr']) }}">Fr</a></li> 
            <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'ru']) }}">Ru</a></li> 
            <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'de']) }}">De</a></li>
            <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'cn']) }}">Cn</a></li>
          </ul>
        </li>



      <form class="d-flex" role="search" action="{{ route('search') }}" method="GET">
          <input class="form-control me-2" type="search" name="q" placeholder="{{ __('header.search') }}" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">{{ __('header.go') }}</button>
      </form>
    </div>
  </div>
</nav>



</header>