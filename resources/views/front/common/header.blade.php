
<header>
    <input type="hidden" id="refreshed" value="">

    <div class="container-fluid" id="topBar">
        <div class="container">

            @if(!Auth::guard('customer')->user())
                <div class="col-sm-4">
                    <span><a href="{{ url('login') }}">SignIn</a></span>
                    <span>|</span>
                    <span><a href="{{ url(route('customerRegisterProcess')) }}">SignUp</a></span>
                </div>
            @else

                <div class="col-sm-4">
                    <span><a href="{{ url('logout') }}">Logout</a></span>
                    <span>|</span>
                <span class="dropdown Login">
                    <button class="btn btn-default dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }}
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <a href="{{url(route('profile.index'))}}"><li>My Account</li></a>
                        <a href="{{ url(route('myOrders')) }}"><li>My Order</li></a>
                        <a href="{{ url(route('myShopzz')) }}"><li>My I Can Save the world</li></a>
                        <a href="{{ url(route('myLikes')) }}"><li>My Likes</li></a>
                        <a href="{{ url(route('cart.index')) }}"><li>My Cart</li></a>
                        <li class="dropdown-submenu"><a href="#">My Address</a>
                            <ul class="dropdown-menu">
                              @forelse($userAddress as $address)
                                    <a id="addressSelect{{$address->id}}" href="javascript:void(0);" class="userAddress changeAddress user-address {{$address->is_selected == 'Yes' ? 'active' : ''}} " data-address_id="{{$address->id}}"><li>{{$address->full_name}}</li></a>
                                @empty
                                @endforelse
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
                    <nav class="navbar topbar" id="mobile">
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
                                            @if(!Auth::guard('customer')->check())
                                                <div class="profile-pic">
                                                    <img src="{{url('assets/app/media/img/no_user_image_100.png')}}" alt="profile-pic">
                                                </div>
                                                <!-- <a href="index.html"><img src="images/logo.svg"></a> -->
                                                <div class="profile-user">
                                                    <h4 class="profile-user-name"> <span>  Hi, Guest </span></h4>
                                                    <a href="{{ url('login') }}" class="profile-user-logout"> Login</a>
                                                </div>
                                            @else
                                                <div  class="profile-pic">
                                                    <img src="{{ !empty(Auth::guard('customer')->user()->profile_image) ? url('/doc/profile_image').'/'.Auth::guard('customer')->user()->profile_image : url('assets/app/media/img/no_user_image_100.png')}}" alt="profile-pic">
                                                </div>
                                                <!-- <a href="index.html"><img src="images/logo.svg"></a> -->
                                                <div class="profile-user">
                                                    <h4 class="profile-user-name"> <span>  Hi, {{ Auth::guard('customer')->user()->first_name." ".Auth::guard('customer')->user()->last_name }} </span></h4>
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
                                            @if(Auth::guard('customer')->user())
                                                <li class="sidebar-list-item">
                                                    <a class="toggle_link sidebar-list-item-link list-account"><i class="fa fa-user list-icon"></i>
                                                        &nbsp;&nbsp;Account/Tracking</a>
                                                    <div class="toggle-content">
                                                        <a href="#" class="hide-content-link"> <i class="fa fa-caret-left"></i><span class="hide-content-text">Back</span></a>
                                                        <ul class="nested-list">
                                                            <li class="nested-list-item">
                                                                <a class="nested-list-item-link" href="{{url(route('profile.index'))}}">My Account Page</a>
                                                            </li>
                                                            <li class="nested-list-item">
                                                                <a class="nested-list-item-link" href="{{ url(route('myOrders')) }}">My Order</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nested-list-item">
                                                    <a class="nested-list-item-link list-account" href="{{ url(route('myShopzz')) }}">
                                                        <img class="list-icon" src="{{ url('assets/frontend/images/unfollow_icon_small.png') }}" alt="icon-small">
                                                        {{-- <img alt="icon-unfollow" src="{{ url('assets/frontend/images/unfollow_icon.png') }}">--}}
                                                        My Shopzz
                                                    </a>
                                                </li>
                                                <li class="nested-list-item">
                                                    <a class="nested-list-item-link list-account" href="{{ url(route('myLikes')) }}">
                                                        <i class="fa fa-heart list-icon"></i> My Likes
                                                    </a>
                                                </li>
                                                <li class="nested-list-item">
                                                    <a class="nested-list-item-link list-account" href="{{ url(route('cart.index')) }}">
                                                        <img class="list-icon" alt="my_cart_small"  src="{{ url('assets/frontend/images/my_cart_small.png') }}">
                                                        My Cart
                                                    </a>
                                                </li>
                                                <li class="sidebar-list-item">
                                                    <a class="toggle_link sidebar-list-item-link list-select-country kw">
                                                        <i class="fa fa-flag list-icon"></i> My Address
                                                    </a>
                                                    <div class="toggle-content">
                                                        <a href="#" class="hide-content-link">
                                                            <i class="fa fa-caret-left"></i>
                                                            <span class="hide-content-text">Back</span>
                                                        </a>
                                                        <ul class="nested-list countries-list">
                                                            @foreach($userAddress as $address)
                                                            <li class="nested-list-item">
                                                                <a id="addressSelected{{$address->id}}" class="nested-list-item-link user-address changeAddress country-link {{$address->is_selected == 'Yes' ? 'active' : ''}} ae {{(isset($pageTitle) && ($pageTitle == 'editAddress')) ? 'is-selected' :''}}" data-address_id="{{$address->id}}" href="javascript:void(0);">
                                                                    {{$address->full_name}}
                                                                    <i class="icon-done"></i>
                                                                </a>
                                                            </li>
                                                            @endforeach
                                                            <li class="nested-list-item">
                                                                <a class="nested-list-item-link country-link kw {{(isset($pageTitle) && ($pageTitle == 'addAddress')) ? 'is-selected' :''}} " href="{{route('address.create')}}">
                                                                    Add Address
                                                                    <i class="fa fa-plus-circle fa-lg"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            <li class="nested-list-item">
                                                <a href="{{ url(route('aboutUs') ) }}" class="nested-list-item-link">
                                                    <i class="fa fa-exclamation-circle list-icon"></i>
                                                    About Us
                                                </a>
                                            </li>
                                            <li class="nested-list-item">
                                                <a href="{{ url('call-us') }}" class="nested-list-item-link">
                                                    <i class="fa fa-phone list-icon"></i>
                                                    Contact Us
                                                </a>
                                            </li>
                                            <li class="sidebar-list-item">
                                                <a class="toggle_link sidebar-list-item-link list-customerservice">
                                                    <i class="fa fa-question-circle list-icon"></i>
                                                    Customer Services
                                                </a>
                                                <div class="toggle-content">
                                                    <a href="#" class="hide-content-link">
                                                        <i class="fa fa-caret-left"></i>
                                                        <span class="hide-content-text">Back</span>
                                                    </a>
                                                    <ul class="nested-list">
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">How can I track my order?</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">How can I return an item?</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">How does my refund get processed?</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Information on available products</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">FAQs</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Delivery</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Size Guide</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Payment Methods</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Returns</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Consumer Rights</a>
                                                        </li>
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{url(route('comingSoon'))}}">Legal</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="sidebar-list-item">
                                                <a href="#" class="toggle_link sidebar-list-item-link list-men">Categories</a>
                                            </li>
                                            @foreach($mainCategory as $item)
                                            <li class="sidebar-list-item">
                                                <a href="#" class="toggle_link sidebar-list-item-link list-men">
                                                    <span><img alt="category_image" src="{{ url('/doc/category_image/'.$item->category_image) }}"></span>
                                                    {{$item->category_name}}
                                                </a>
                                                <div class="toggle-content">
                                                    <a href="#" class="hide-content-link">
                                                        <i class="fa fa-caret-left"></i>
                                                        <span class="hide-content-text">Back</span>
                                                    </a>
                                                    <ul class="nested-list">
                                                        <li class="nested-list-item">
                                                            <a class="nested-list-item-link" href="{{ url(route('showCategory',['categorySlug'=>$item->category_slug])) }}">
                                                                All {{$item->category_name}}
                                                            </a>
                                                        </li>
                                                        @if(count($item->subCategories) > 0)
                                                            @foreach($item->subCategories as $list)
                                                            <li class="nested-list-item">
                                                                <a class="nested-list-item-link" href="{{url(route('showCategory',['categorySlug'=>$list->category_slug]))}}">
                                                                    {{$list->category_name}}
                                                                </a>
                                                            </li>
                                                            @endforeach
                                                        @else
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                            @endforeach
                                            <li class="sidebar-list-item" style="align-content: center;">
                                                <div class="sidebar-list-item-link sidebar-list-item-link-last">
                                                    <!-- <a href="#" class="toggle_link sidebar-list-item-link list-men"> -->
                                                    <img onclick="window.location.href = '{{env('GOOGLE_PLAYSTORE_URL')}}';" src="{{ url('assets/frontend/images/google.png')}}" alt="play-store">
                                                    <!-- </a> -->
                                                    <!-- <a href="#" class="toggle_link sidebar-list-item-link list-men">
                                                     -->    <img onclick="window.location.href = '{{env('IPHONE_APPSTORE_URL')}}';" src="{{ url('assets/frontend/images/button-app-store.png')}}" alt="Iphone-app-store">
                                                    <!-- </a> -->
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-8">
                                <div class="logo-box">
                                    <a class=".navbar-brand" href="{{ url('') }}">
                                        <img alt="logo" src="{{ url('assets/logo.png') }}" class="logo" id="responsive-logo">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-2 cartWidth">
                                <div class="cartPosition">
                                    <a href="{{ url('cart') }}">
                                        <img class="cart" id="responsive-cart" alt="cart" src="{{ url('assets/frontend/images/cart.png') }}">
                                    </a>
                                    <span class="badge badge-notify my-cart-badge">
                                        <a href="{{ url('cart') }}">{{ isset($totalCartItem)?$totalCartItem:0 }}</a>
                                    </span>
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
                            <a href="{{ url('') }}"><img alt="logo" src="{{ url('assets/logo.png') }}" style="margin-top:-10px !important;" height="70px" id="logo"></a>
                        </div>
                        <form id="searchKey" method="get" action="{{url(route('searchProduct'))}}">
                            <div class="col-sm-6 col-md-7 col-lg-8" id="search">
                                <input class="form-control" autocomplete="off" value="{{isset($keyword) ? $keyword : ''}}" placeholder="I'm shopping for..." name="keyword" id="srch-term" type="text" required>
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
                                    <img id="cart" class="cart" alt="cart" src="{{ url('assets/frontend/images/cart.png') }}">
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
                            @include('front.homePageCommon.category_list')
                        @endforeach
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div id="mySidenav" class="sidenav">
    </div>
</header>

