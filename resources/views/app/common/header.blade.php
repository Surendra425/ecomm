<header>
    <div class="container-fluid" id="topBar">
        <div class="container">
            @if(empty($customer))
                <div class="col-sm-4">
                    <span><a href="{{ url('login') }}">Login</a></span>
                    <span>|</span>
                    <span><a href="{{ url('register') }}">SignUp</a></span>
                </div>
            @else
                <div class="col-sm-4">
                    <span><a href="{{ url('logout') }}">Logout</a></span>
                    <span>|</span>
                <span class="dropdown Login">
                    <button class="btn btn-default dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">{{ $customer->first_name." ".$customer->last_name }}
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <a href="{{url(route('profile.index'))}}"><li>My Account</li></a>
                        <a href="{{ url(route('myOrders')) }}"><li>My Order</li></a>
                        <a href="{{ url(route('myShopzz')) }}"><li>My I Can Save the world</li></a>
                        <a href="{{ url(route('myLikes')) }}"><li>My Likes</li></a>
                        <a href="{{ url(route('cart.index')) }}"><li>My Cart</li></a>
                        <li class="dropdown-submenu"><a href="#">My Address</a>
                            <ul class="dropdown-menu">
                              @foreach($userAddress as $address)
                                    <a id="addressSelect{{$address->id}}" href="javascript:void(0);" class="userAddress user-address {{$address->is_selected == 'Yes' ? 'active' : ''}} " data-address_id="{{$address->id}}"><li>{{$address->full_name}}</li></a>
                                @endforeach
                                <a href="{{route('address.create')}}"><li>Add Address</li></a>
                                </ul>
                         </li>

                    </ul>
                </span>
                </div>
            @endif
        </div>
    </div>
    <div class="container-fluid" id="navBar">
        <div class="container">
            <div class="container-fluid">
                <div class="container">
                    <nav class="navbar topbar" role="navigation" id="mobile">
                        <div class="navbar-header">
                            <div class="col-xs-2">
                                <button type="button" class="topbar-menu-link sidebar-toggler navbar-toggle">
                                    <span  class="icon-bar"></span>
                                    <span  class="icon-bar"></span>
                                    <span  class="icon-bar"></span>
                                </button>
                                <div class="sidebar" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                    <div class="overlay"></div>
                                    <div class="sidebar-wrapper">
                                        <div class="sidebar-profile">
                                            @if(empty($customer))
                                                <div class="profile-pic">
                                                    <img src="{{url('assets/app/media/img/no_user_image_100.png')}}" width="40px;" height="40px;">
                                                </div>
                                                <!-- <a href="index.html"><img src="images/logo.svg"></a> -->
                                                <div class="profile-user">
                                                    <h4 class="profile-user-name"> <span>  Hi, Guest </span></h4>
                                                    <a href="{{ url('login') }}" class="profile-user-logout"> Login</a>
                                                </div>
                                            @else
                                                <div  class="profile-pic">
                                                    <img src="{{ !empty($customer->profile_image) ? url('/doc/profile_image').'/'.$customer->profile_image : url('assets/app/media/img/no_user_image_100.png')}}" width="40px;" height="40px;">
                                                </div>
                                                <!-- <a href="index.html"><img src="images/logo.svg"></a> -->
                                                <div class="profile-user">
                                                    <h4 class="profile-user-name"> <span>  Hi, {{ $customer->first_name." ".$customer->last_name }} </span></h4>
                                                    <a href="{{ url('logout') }}" class="profile-user-logout"> Logout</a>
                                                </div>
                                            @endif
                                            <a href="#" class="profile-sidebarClose"></a>
                                        </div>

                                        <ul class="sidebar-list m--right">
                                            <li  class="sidebar-list-item">
                                                <form method="get" action="{{url(route('searchProduct'))}}">
                                                    <input class="form-control" placeholder="I'm shopping for..." name="keyword"
                                                           id="srch-term1" type="text" value="{{isset($keyword) ? $keyword : ''}}">
                                                    <div class="base" id="search-div1">
                                                        <ul id="search-parm1" class="search-parm"></ul>
                                                    </div>
                                                </form>
                                            </li>
                                            @if(!empty($customer))
                                                <li class="sidebar-list-item">
                                                    <a class="toggle_link sidebar-list-item-link list-account"><i class="fa fa-user list-icon"></i>
                                                        &nbsp;&nbsp;Account/Tracking</a>
                                                    <div class="toggle-content">
                                                        <a href="#" class="hide-content-link"> <i class="fa fa-caret-left"></i><span class="hide-content-text">Back</span></a>
                                                        <ul class="nested-list">
                                                            <a class="nested-list-item-link" href="{{url(route('profile.index'))}}"><li class="nested-list-item">My Account Page</li></a>
                                                            <a class="nested-list-item-link" href="{{ url(route('myOrders')) }}"><li class="nested-list-item">My Order</li></a>
                                                        </ul>
                                                    </div>
                                                </li> 
                                                <a class="nested-list-item-link list-account" href="{{ url(route('myShopzz')) }}">
                                                    <li class="nested-list-item">
                                                        <img class="list-icon" src="{{ url('assets/frontend/images/unfollow_icon_small.png') }}">
                                                       {{-- <img  src="{{ url('assets/frontend/images/unfollow_icon.png') }}" height="16px" width="16px">--}}
                                                        My Shop
                                                    </li>
                                                </a>
                                                <a class="nested-list-item-link list-account" href="{{ url(route('myLikes')) }}">
                                                    <li class="nested-list-item">
                                                        <i class="fa fa-heart list-icon"></i>
                                                        My Likes
                                                    </li></a>
                                                <a class="nested-list-item-link list-account" href="{{ url(route('cart.index')) }}">
                                                    <li class="nested-list-item">
                                                        <img class="list-icon"  src="{{ url('assets/frontend/images/my_cart_small.png') }}">
                                                        My Cart
                                                    </li>
                                                </a>
                                                <li class="sidebar-list-item">
                                                    <a class="toggle_link sidebar-list-item-link list-select-country kw"> <i class="fa fa-flag list-icon"></i>
                                                        My Address </a>
                                                    <div class="toggle-content">
                                                        <a href="#" class="hide-content-link"> <i class="fa fa-caret-left"></i><span class="hide-content-text">Back</span></a>
                                                        <ul class="nested-list countries-list">
                                                            @foreach($userAddress as $address)
                                                                <a id="addressSelected{{$address->id}}" class="nested-list-item-link user-address userAddress1 country-link {{$address->is_selected == 'Yes' ? 'active' : ''}} ae {{(isset($pageTitle) && ($pageTitle == 'editAddress')) ? 'is-selected' :''}}" data-address_id="{{$address->id}}" href="javascript:void(0);">
                                                                    <li class="nested-list-item">

                                                                        {{$address->full_name}}
                                                                        <i class="icon-done"></i>

                                                                    </li>
                                                                </a>
                                                            @endforeach
                                                            <a class="nested-list-item-link country-link kw {{(isset($pageTitle) && ($pageTitle == 'addAddress')) ? 'is-selected' :''}} " href="{{route('address.create')}}">
                                                                <li class="nested-list-item">
                                                                    Add Address
                                                                    <i class="fa fa-plus-circle fa-lg"></i>

                                                                </li>
                                                            </a>
                                                        </ul>
                                                    </div>
                                                </li>

                                            @endif
                                            <a href="{{ url(route("aboutUs")) }}" class="nested-list-item-link">
                                                <li class="nested-list-item">
                                                    <i class="fa fa-exclamation-circle list-icon"></i>
                                                    About Us

                                                </li>
                                            </a>
                                            <a href="{{ url("call-us") }}" class="nested-list-item-link">
                                                <li class="nested-list-item">
                                                    <i class="fa fa-phone list-icon"></i>
                                                    Contact Us

                                                </li>
                                            </a>
                                            <li class="sidebar-list-item">
                                                <a class="toggle_link sidebar-list-item-link list-customerservice">
                                                    <i class="fa fa-question-circle list-icon"></i>
                                                    Customer Services
                                                </a>
                                                <div class="toggle-content">
                                                    <a href="#" class="hide-content-link"> <i class="fa fa-caret-left"></i><span class="hide-content-text">Back</span></a>
                                                    <ul class="nested-list">
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">How can I track my order?</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">How can I return an item?</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">How does my refund get processed?</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Information on available products</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">FAQs</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Delivery</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Size Guide</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Payment Methods</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Returns</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Consumer Rights</li></a>
                                                        <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}"><li class="nested-list-item">Legal</li></a>
                                                    </ul>
                                                </div>
                                            </li>

                                            <li class="sidebar-list-item">
                                                <a href="#" class="toggle_link sidebar-list-item-link list-men">
                                                    Categories </a>

                                            </li>
                                            @foreach($mainCategory as $item)

                                                <li class="sidebar-list-item">
                                                    <a href="#" class="toggle_link sidebar-list-item-link list-men">
                                                     <span><img src="{{ url('/doc/category_image/'.$item->category_image) }}" width="20px"></span>
                                                        {{$item->category_name}}</a>
                                                    <div class="toggle-content">
                                                        <a href="#" class="hide-content-link"> <i class="fa fa-caret-left"></i><span class="hide-content-text">Back</span></a>
                                                        <ul class="nested-list">
                                                            <a class="nested-list-item-link" href="{{ url(route('showCategory',['categorySlug'=>$item->category_slug])) }}">
                                                                <li class="nested-list-item">

                                                                    All {{$item->category_slug}}

                                                                </li>
                                                            </a>
                                                            @if(count($item->subCategories) > 0)
                                                                @foreach($item->subCategories as $list)
                                                                    <a class="nested-list-item-link" href="{{url(route('showCategory',['categorySlug'=>$list->category_slug]))}}">
                                                                        <li class="nested-list-item">
                                                                            {{$list->category_name}}

                                                                        </li>
                                                                    </a>
                                                                @endforeach
                                                            @else
                                                            @endif

                                                        </ul>
                                                    </div>
                                                </li>

                                            @endforeach
                                           
                                            <li class="sidebar-list-item" style="align-content: center;">
                                                <div class="sidebar-list-item-link" style="padding-bottom:20px;">

                                                <!-- <a href="#" class="toggle_link sidebar-list-item-link list-men"> -->
                                                    <img width="100px"  onclick="window.location.href = 'https://play.google.com/store/apps/details?id=com.app';" src="{{ url('assets/frontend/images/google.png')}}">
                                                <!-- </a> -->
                                                <!-- <a href="#" class="toggle_link sidebar-list-item-link list-men">
                                                 -->    <img width="100px"  src="{{ url('assets/frontend/images/button-app-store.png')}}">
                                                <!-- </a> -->
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-8">
                                <div class="LOGO">
                                    <a class=".navbar-brand" href="{{ url('') }}">
                                        <img src="{{ url('assets/logo.svg') }}" id="logo">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-2 cartWidth">

                                <div class="cartPosition">
                                    <a href="{{ url('cart') }}">
                                        <img  id="cart" src="{{ url('assets/frontend/images/cart.svg') }}">
                                    <span class="badge badge-notify my-cart-badge">
                                        {{ isset($totalCartItem)?$totalCartItem:0 }}
                                    </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="container-fluid">
                <div class="container">
                    <div id="large">
                        <div class="col-sm-1 col-md-1 col-lg-1" id="menu">
                            {{--<span>--}}
                            {{--<i onclick="openNav()" class="fa fa-bars" aria-hidden="true"></i>--}}
                            {{--</span>--}}
                            <button type="button" class="topbar-menu-link sidebar-toggler navbar-toggle">
                                <span  class="icon-bar"></span>
                                <span  class="icon-bar"></span>
                                <span  class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-2">
                            <a href="{{ url('') }}"><img src="{{ url('assets/logo.svg') }}" id="logo"></a>
                        </div>
                        <form id="searchKey" method="get" action="{{url(route('searchProduct'))}}">
                            <div class="col-sm-6 col-md-7 col-lg-8" id="search">
                                <input class="form-control" autocomplete="off" value="{{isset($keyword) ? $keyword : ''}}" placeholder="I'm shopping for..." name="keyword" id="srch-term" type="text">
                                <div class="hidden base base-width" id="search-div">
                                    <ul id="search-parm" class="search-parm"></ul>
                                </div>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                                <div class="input-group-btn">
                                    <button id="srch-button" class="btn btn-default" type="submit">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="col-sm-2 col-md-1 col-lg-1">
                            <div class="cartPosition">
                                <a href="{{ url('cart') }}">
                                    <img id="cart" src="{{ url('assets/frontend/images/cart.svg') }}">
                                </a>
                                <span class="badge badge-notify my-cart-badge">
                                    <a href="{{ url('cart') }}">{{ isset($totalCartItem)?$totalCartItem:0 }}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <nav class="navbar" id="largeNavBar">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#"></a>
                    </div>
                    <ul class="nav navbar-nav">
                        @foreach($mainCategory as $item)
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{$item->category_name}}
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{url(route('showCategory',['categorySlug'=>$item->category_slug]))}}">
                                            All {{ $item->category_name }}
                                        </a>
                                    </li>
                                    @if(count($item->subCategories) > 0)
                                        @foreach($item->subCategories as $list)
                                            <li>
                                                <a href="{{url(route('showCategory',['categorySlug'=>$list->category_slug]))}}">
                                                    {{$list->category_name}}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif

                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div id="mySidenav" class="sidenav">
        {{--<ul class="nav1">
            <form id="searchKey" method="get" action="{{url(route('searchProduct'))}}">
            <li><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a></li>
            <li><input class="form-control" autocomplete="off" placeholder="Search products,restaurant and food" name="keyword"
                       id="srch-term1" type="text">
                <div class="base" id="search-div1">
                    <ul id="search-parm1"></ul>
                </div>
            </li>
            </form>
            @if(empty($customer))
            <li><a href="{{ url('login') }}" class="LoginMenu">Login</a></li>
            <li><a href="{{ url('register') }}" class="LoginMenu">Sign Up</a></li>
            @else
            <li><a href="{{ url('logout') }}" class="LoginMenu">Logout</a></li>
            @endif
        </ul>--}}
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @include('app.common.flash')
        </div>
    </div>
</div>
