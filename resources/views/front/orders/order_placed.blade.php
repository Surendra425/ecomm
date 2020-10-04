@extends('front.layout.index')
@section('title') Order {{$status}} @endsection
@section('content')
    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">Your Order has been {{$status}} Successfully !!</span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title')</span>
            </div>
        </div>
    </div>
    <div class="container-fluid">
    </div>

@endsection
@section('js')
    @if($orderDetail->is_mail_send == 2 && ($orderDetail->payment_status == 'Pending' || $orderDetail->payment_status == 'Completed' || ($orderDetail->payment_status != 'Failed' && $orderDetail->payment_status != 'Cancelled')))
        <script>

            $(document).ready(function(){
                $.ajax({
                    url:"{{url('sendordersuccessmail/'.base64_encode($orderDetail->id))}}",
                    type:'get',
                    success:function(response){
                        console.log(response);
                    }
                });
            });
        </script>
    @endif
@endsection