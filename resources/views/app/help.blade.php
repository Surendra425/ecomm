@extends('layouts.app')
@section('title') Help @endsection
@section('content')

    <div class="container-fluid" id="Contact">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
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
        <div class="container help">
            <div>
                <h4>{{$help->headline or 'Help'}}</h4><br>
                <?php echo !empty($help->description) ? $help->description : 'No content' ?>
            </div>
        </div>
    </div>
@endsection

