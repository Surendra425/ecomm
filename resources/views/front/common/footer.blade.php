<!-- begin::Footer -->

@php
    $ip_address=$_SERVER['REMOTE_ADDR'];

    try
    {

    $geopluginURL='//www.geoplugin.net/php.gp?ip='.$ip_address;
    $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
    $CountryName = $addrDetailsArr['geoplugin_countryName'];
    if(!$CountryName)
    {
       $CountryName='Kuwait';
    }
    }
    catch(\Exception $e)
    {
       $CountryName = 'Kuwait';
    }

@endphp

<div class="wrapper" style="margin-top: 10px;">
    <div class="container-fluid" id="footerFluid" >
        <div class="container" id="Connect">
            <!-- <div>
                <a><img alt="connect" src="{{ url("assets/frontend/images/connect.png") }}"></a>
                <span><a>{{ $CountryName }}</a></span>
            </div> -->
        </div>
    </div>
    <div class="container-fluid" id="footer">
        <div class="container">
            <h4 class="Subscribe"> Subscribe for special offers & updates</h4>
            <form method="POST" id="fromSubscribe">
                <div class="col-sm-offset-3 col-sm-5">
                    <input class="form-control srch-term required"  placeholder="Enter your email address" id="subscriber_email"  name="subscriber_email" type="email">
                </div>
                <div class="col-sm-4" id="subscribe">
                    <div  class="input-group-btn">
                        <button id="srch-term-banner" class="btn btn-default srch-term-banner" type="submit">SUBSCRIBE</button>
                    </div>
                </div>
            </form>
            <div class="col-sm-12 text-center">
                <ul id="large-footer">
                    <li><h5><a href="{{ url(route("aboutUs")) }}"> About I Can Save the World</a></h5></li>
                    <li><h5><a href="{{ url("sell-with-us") }}">Sell With Us</a></h5></li>
                    <li><h5><a href="{{ url("best-products") }}">Best Products</a></h5></li>
                    <li><h5><a href="{{ url("best-sellers") }}"> Best Sellers</a></h5></li>
                    <li><h5><a href="{{ url("returns") }}">Returns</a></h5></li>
                    <li><h5><a href="{{ url("call-us") }}">Contact Us</a></h5></li>
                    <li><h5><a href="{{ url("help") }}">Help</a></h5></li>
                </ul>
                <div class="col-sm-12 text-center">
                    <span>Copyright &copy; {{ date('Y') }} <a href="{{ url('') }}">I Can Save the World</a>. All Rights Reserved.|</span>
                    <span><a href="{{ url(route("userAgrement")) }}">User Agreement </a>|</span>
                    <span><a href="{{ url(route("privacy")) }}">Privacy Policy </a>|</span>
                    <span><a href="{{ url(route("termCondtions")) }}">Terms & Conditions </a>|</span>
                    <span><a href="{{ url(route("siteMap")) }}">Site Map</a></span>
                </div>
                <!-- <div class="footer-links">
                    <span>Design & Developed by:<a class="Auxano" href="https://www.auxanoglobalservices.com/"> Auxano Global Services</a></span>
                </div> -->
                <!--to set client social link..-->
                <div class="footer-links">
                    <div class="social-menu">
                        <a href="#" class="social-sharing-footer social-sharing-insta"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i></a>
                        <a href="#" class="social-sharing-footer social-sharing-facebook"> <i class="fa fa-facebook-square fa-2x"></i></a>
                        <a href="#" class="social-sharing-footer social-sharing-twitter"><i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end::Footer -->
