<div class="container-fluid" id="MensCollection">
    <div class="container">
        <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
            @yield('title')
        </div>
    </div>
</div>
<div class="container-fluid" id="footerFluid">
    <div class="container" id="home-myAccount">
        <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">
            <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
            <span class="home-myAccount-1">@yield('title') </span>
        </div>
    </div>
</div>