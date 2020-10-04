@extends('layouts.app')
{{--@if(!empty($customer))
    @extends('layouts.app')
@elseif(!empty($vendor))
    @extends('layouts.vendor')
@elseif(!empty($admin))
    @extends('layouts.admin')
@else
    @extends('layouts.app')
@endif--}}
@section('title') 404 - Page not found @endsection
@section('content')
<div class="wrapper">
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
            <img src="{{ url('assets/frontend/images/404.png') }}" style="max-width: 100%;">
        </div>
    </div>
</div>
@endsection

