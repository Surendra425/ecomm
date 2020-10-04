{{--@if(!empty($customer))
    @extends('layouts.app')
@elseif(!empty($vendor))
    @extends('layouts.vendor')
@elseif(!empty($admin))
    @extends('layouts.admin')
@else
    @extends('layouts.app')
@endif--}}
@extends('layouts.app')
@section('title') 500 - Oops Something went wrong @endsection
@section('content')
<div class="">
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
                <span><a href="{{ url(route('home')) }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title')</span>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container text-center" style="margin-top: 50px;margin-bottom: 50px;">
            <img src="{{ url('assets/frontend/images/oops.png') }}" style="max-width: 100%;">
            <br>
            <a href="{{url('')}}" class="btn btn-primary btn-lg" >Home</a>
        </div>

    </div>
    <div class="container-fluid">

    </div>
</div>
@endsection

