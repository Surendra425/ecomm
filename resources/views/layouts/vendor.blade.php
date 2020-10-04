<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | I Can Save the world Vendor</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="I Can Save the world Marketplace Vendor">
        <meta name="base-url" content="{{ url('/vendor') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script type="text/javascript">
WebFont.load({
    google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
    active: function ()
    {
        sessionStorage.fonts = true;
    }
});
        </script>
        <link href="{{ url('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('assets/demo/demo2/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{ url('assets/demo/demo2/media/img/logo/favicon.png') }}" /> 
        @yield('css')
    </head>
    <!-- end::Head -->
    <!-- end::Body -->
    <body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default"  >
        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <!-- begin::Header -->
            @include('vendor.common.header')
            <!-- end::Header -->		
            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
                <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver m-container m-container--responsive m-container--xxl m-page__container">	
                    <div class="m-grid__item m-grid__item--fluid m-wrapper">	     
                        <div class="m-content">
                            @yield('content')
                        </div>
                    </div>
                </div>

            </div>
            <!-- end::Body -->
            <!-- begin::Footer -->
            @include('vendor.common.footer')
            <!-- end::Footer -->
        </div>
        <!-- end:: Page -->
        <!-- begin::Scroll Top -->
        <div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
            <i class="la la-arrow-up"></i>
        </div>
        <!-- end::Scroll Top -->
	<script src="{{ url('assets/vendors/base/vendors.bundle.js') }}"
		type="text/javascript"></script>
	<script src="{{ url('assets/demo/default/base/scripts.bundle.js') }}"
		type="text/javascript"></script>
	<script src="{{ url('assets/vendors/base/jquery.validate.min.js') }}"
		type="text/javascript"></script>

	<script src="{{ url('assets/app/js/dashboard.js') }}"
		type="text/javascript"></script>
	<script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#blah')
                            .attr('src', e.target.result)
                            .width(70)
                            .height(70);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
            function readURL1(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#blah1')
                                .attr('src', e.target.result)
                                .width(70)
                                .height(70);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('click', '.delete-data', function(e){
                var name = $(this).data('name');

                if(confirm('Are you sure want to delete this '+name+'?')) {

                    return true;
                }

                e.preventDefault();

                return false;
            });
            $(".phonNumberOnly").keypress(function(evt) {


                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode != 47 && charCode > 31
                        && (charCode < 48 || charCode > 57))
                    return false;

                return true;
            });

            $(".priceValidation").keypress(function(evt) {


                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode != 46 && charCode > 31
                        && (charCode < 48 || charCode > 57))
                    return false;

                return true;
            });
        </script>
        @yield('js')
    </body>
    <!-- end::Body -->
</html>