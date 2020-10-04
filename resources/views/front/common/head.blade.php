
@php $version = '?version=1.0'; @endphp

<!--Start meta data  -->
    <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
     <meta name="viewport" content="width=device-width, initial-scale=1">
 	 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />

 	<title>@yield('title') | {{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="PRAGMA" content="NO-CACHE">

    <meta name="base-url" content="{{ url('/') }}">

    <link rel="shortcut icon" href="{{ url('assets/demo/default/media/img/logo/favicon.png') }}">

@yield('meta')

<script>

</script>


    <link rel="stylesheet" type="text/css"
          href="{{ url('assets/frontend/font-awesome-4.7.0/css/font-awesome.min.css') }}{{$version}}"/>

    
    <link rel="stylesheet" href="{{ url('assets/frontend/css/all-min-css.css') }}{{$version}}">
    
    @yield('css')
