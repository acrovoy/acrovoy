<header>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">ACROVOY</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Products
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Order Scanner 1.01</a></li>
            
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item disabled" href="#">Bitcoinex</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Development</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Algotrading</a>
        </li>
      </ul>

     
      <div class="nav-item">
          <a class="nav-link disabled me-3" aria-disabled="true" style="color:darkgray">For Partners</a>
      </div>

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
                          <label for="loginEmail" class="form-label">Login</label>
                          <input type="text" class="form-control" id="loginEmail" name="email" placeholder="Enter login" required>
                      </div>
                      <div class="mb-3">
                          <label for="loginPassword" class="form-label">Password</label>
                          <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                      </div>
                      <button type="submit" class="btn btn-primary w-100 mb-2">Sign In</button>
                  </form>
                  
                  <!-- Forgot password link -->
                  <div class="text-center">
                      <a href="{{ route('password.request') }}" class="d-block text-decoration-none" style="font-size: 0.875rem; color: #0d6efd;">
                          Forgot the password?
                      </a>
                  </div>
              </div>
          </div>
      @endguest

        @auth
            <!-- Если пользователь АВТОРИЗОВАН, показываем его email -->
            <div class="nav-item dropdown me-3">
                <a class="nav-link" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:#2ddd63">
                  {{ Auth::user()->email }}
                </a>
                <div class="dropdown-menu dropdown-menu-end p-2">

                <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                <div><hr class="dropdown-divider"></div>
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item">Logout</button>
                  </form>
                </div>
            </div>
        @endauth

      <li class="nav-item dropdown list-unstyled me-3">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            En
          </a>
          <ul class="dropdown-menu" style="width: 60px; min-width: 0;">
            <li><a class="dropdown-item" href="#">Sp</a></li>
            <li><a class="dropdown-item" href="#">De</a></li>
            <li><a class="dropdown-item" href="#">Ru</a></li>
           
          </ul>
      </li>



      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>



</header>