@php $version = '?version=1.0'; @endphp

<script src="{{ url('assets/frontend/js/all-min-js.js') }}{{$version}}"></script>

{{-- <script src="{{ url('assets/frontend/js/bootstrap.min.js') }}{{$version}}"></script>

<script src="{{ url('assets/frontend/js/jquery.touchSwipe.min.js') }}{{$version}}"></script>

<script src="{{ url('assets/frontend/js/owl.carousel.js') }}{{$version}}"></script>

<script src="{{ url('assets/vendors/base/jquery.validate.min.js') }}{{$version}}"></script>

<script src="{{ url('assets/frontend/js/swiper.min.js') }}{{$version}}"></script>

<script src="{{ url('front/webpack.js.download') }}{{$version}}"></script>

<script src="{{ url('front/vendor.js.download') }}{{$version}}"></script>

<script src="{{ url('front/app.js.download') }}{{$version}}"></script>

<script src="{{ url('assets/frontend/toastr-master/build/toastr.min.js') }}{{$version}}"></script>

<script src="{{ url('assets/frontend/blockui-master/jquery.blockUI.js') }}{{$version}}"></script> --}}


<script src="{{ url('assets/frontend/js/common.js') }}{{$version}}"></script> 

<script type='text/javascript'>

    var isShowLoader = true;

    $(document).ready(function(){
    
    @include('front.common.toaster_message')

     $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    });
    
    $(document).bind("ajaxStart.mine", function () {
        $('body').addClass('overflow-hidden');

        if(isShowLoader) {
            $.blockUI(
                    {
                        message: "<img src='{{ url('assets/loader_new.gif') }}' class='loaderGif'  style='height: 100px;width: 100px;'/>",
                        centerX: true,
                        centerY: true,

                    });
        }
    });

    $(document).bind("ajaxStop.mine", function () {
        $('body').removeClass('overflow-hidden');
        if(isShowLoader){
            setTimeout( function () {

                $.unblockUI();
            },1000);

        }
        isShowLoader = true;
    });

    var previousUrl = '{{url()->previous()}}';

    $("#search #srch-term").on("keypress", function(e) {
        if (e.which === 32 && !this.value.length)
            e.preventDefault();
    });

    $("#searchKey #srch-term2").on("keypress", function(e) {
        if (e.which === 32 && !this.value.length)
            e.preventDefault();
    });
    $('.search-box #srch-term3').keyup(function(e){
    
        if (e.which === 229){
            console.log('dd');
            e.preventDefault();
        }


    });
    $(document).on('keypress','.search-box #srch-term3',function (e) {
        
        if (e.which === 32 && !this.value.length)
            e.preventDefault();
    });
</script>

