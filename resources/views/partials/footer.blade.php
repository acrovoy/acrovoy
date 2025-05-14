<footer  style="margin-top: auto">

<div class="footer">
    <footer class="mt-auto bg-light">
        <div class="container mt-auto bg-light " >
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-4  grey_text"
                 style="justify-content: space-between; padding-left: 25px">


                <div class="col mb-1">
                    <h5 class="mb-4 fw-bold">{{ __('footer.company') }}</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="{{ route('development') }}" class="text-muted text-decoration-none">{{ __('footer.development') }}</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('service') }}" class="text-muted text-decoration-none">{{ __('footer.service') }}</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('collaboration') }}" class="text-muted text-decoration-none">{{ __('footer.collaboration') }}</a></li>

                    </ul>
                </div>

                <div class="col mb-1">
                    <h5 class="mb-4 fw-bold">{{ __('footer.products') }}</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="{{ route('vision') }}" class="text-muted text-decoration-none">{{ __('footer.vision') }}</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('standards') }}" class="text-muted text-decoration-none">{{ __('footer.standarts') }}</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('marketing') }}" class="text-muted text-decoration-none">{{ __('footer.marketing') }}</a></li>

                    </ul>
                </div>

                <div class="col mb-1">
                    <h5 class="mb-4 fw-bold">{{ __('footer.contacts') }}</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="{{ route('contact') }}" class="text-muted text-decoration-none">{{ __('footer.email') }}</a></li>
                        <li class="nav-item mb-2">{{ __('footer.telegram') }}</li>
                        <li class="nav-item mb-2">{{ __('footer.network') }}</li>

                    </ul>
                </div>

                <div class="col mb-1">
                <img src="{{ asset('img/acro.png') }}" alt="Profile">
                </div>


            </div>

            <div class="line_btw_zone_footer mb-3 mt-1"></div>

            <div style="font-size: 12px; color: grey; padding-bottom: 15px">ACROVOY @ 2021-2025</div>
        </div>
    </footer>
</div>

</footer>