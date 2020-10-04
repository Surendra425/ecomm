<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>I Can Save the world | {{$contentTitle or ''}}</title>
<meta name="description" content="I Can Save the world - Marketplace">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="base-url" content="{{ url('/admin') }}">
<meta name="url" content="{{ url('/') }}">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
	

<link href="{{ url('assets/vendors/base/vendors.bundle.css') }}"
	rel="stylesheet" type="text/css" />
<link href="{{ url('assets/demo/default/base/style.bundle.css') }}"
	rel="stylesheet" type="text/css" />
<link href="{{ url('css/custom.css') }}" rel="stylesheet"
	type="text/css" />
<link rel="shortcut icon"
	href="{{ url('assets/demo/default/media/img/logo/favicon.png') }}" />
@yield('css')
</head>
<body
	class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<!-- START: Header -->
		@include('admin.common.header')
		<!-- END: Header -->

		<!-- begin::Body -->
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			<!-- BEGIN: Left Aside -->
			<button class="m-aside-left-close  m-aside-left-close--skin-dark "
				id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			@include('admin.common.sidebar')
			<!-- END: Left Aside -->
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
				<!-- BEGIN: Subheader -->
				<div class="m-subheader ">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h3 class="m-subheader__title ">@yield('title')</h3>
							<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
								@yield('breadcrumbs')
							</ul>
						</div>
					</div>
				</div>
				<!-- END: Subheader -->
				<div class="m-content">
					<!--Begin::Main Portlet-->
					@yield('content')
					<!--End::Main Portlet-->
				</div>
			</div>
		</div>
		<!-- end:: Body -->

		<!-- begin::Footer -->
		@include('admin.common.footer')
		<!-- end::Footer -->
	</div>
	<!-- end:: Page -->
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
            $(document).on('click', '.pay-data', function(e){
                var name = $(this).data('name');
                var value = $(this).data('value');
                if(confirm('Are you sure want to pay '+value+' to '+name+'?')) {

                    return true;
                }

                e.preventDefault();

                return false;
            });
			$(document).on('click', '.block-data', function(e){
                var name = $(this).data('name');

                if(confirm('Are you sure want to block this '+name+'?')) {

                    return true;
                }

                e.preventDefault();

                return false;
            });
			$(document).on('keypress', '.phonNumberOnly', function(evt){
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 47 && charCode > 31
						&& (charCode < 48 || charCode > 57))
					return false;

				return true;
			});
			$(document).on('keypress', '.priceValidation', function(evt){
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31
						&& (charCode < 48 || charCode > 57))
					return false;

				return true;
			});
        </script>
	@yield('js')
</body>
</html>