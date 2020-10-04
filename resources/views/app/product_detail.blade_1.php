@extends('layouts.app')
@section('title') {{ $product->product_title }} @endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/slider.css') }}">
@endsection
@section('meta')
<meta name="description" content="{{ $product->long_description }}" />
<meta property="og:url" content="{{ route('sellerProductsDetail', ['productSlug' => $product->product_slug])}}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $product->product_title }}" />
<meta property="og:description" content="{{ strip_tags($product->long_description) }}" />
<meta property="og:image" content="{{ url('doc/product_image/'.$product->images[0]->image_url) }}" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $product->product_title }}">
<meta name="twitter:description" content="{{ strip_tags($product->long_description) }}">
<meta name="twitter:image" content="{{ url('doc/product_image/'.$product->images[0]->image_url) }}">
@endsection

@section('content')
<div class="wrapper">
    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">{{ $product->product_title }}</span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-6">
            <div class="product-slider">
                <div id="carousel" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach($product->images as $k=>$image)
                        <div class="item {{ ($k==0)?"active":"" }}">
                            <img src="{{ url('doc/product_image/'.$image->image_url) }}"> 
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="clearfix">
                    <div id="thumbcarousel" class="carousel slide" data-interval="false">
                        <div class="carousel-inner">
                            @foreach($product->images as $k=>$image)
                            <div class="item {{ ($k==0)?"active":"" }}">
                                <div data-target="#carousel" data-slide-to="{{ $k }}" class="thumb">
                                    <img src="{{ url('doc/product_image/'.$image->image_url) }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a class="left" href="#thumbcarousel" role="button" data-slide="prev"> 
                            <i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i> 
                        </a>
                        <a class="right" href="#thumbcarousel" role="button" data-slide="next">
                            <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i> 
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-12 col-xs-12">
                <h3 class="Adidas-product">{{ $product->product_title }}</h3>
            </div>
            <div class="col-sm-12 col-xs-12">
                <span class="name-1">by 
                    <b>
                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                            {{ $store->store_name }}
                        </a>
                    </b>
                </span>
                <span>
                    &nbsp;&nbsp;
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star unchecked"></span></a>
                    <a href="#" style="color: black"><span>&nbsp;&nbsp;({{ $store->city }}, {{ $store->country }})</span></a>&nbsp;&nbsp;
                    @if($store->is_follow == "No")
                    <a href="{{ url(route('sellerFollow',['store'=>$store->id])) }}">
                        <span class="followers">Follow</span>
                    </a>
                    @else
                    <a href="{{ url(route('sellerUnfollow',['store'=>$store->id])) }}">
                        <span class="followers active" style="background-color: #176c93;color: #FFFFFF;">Unfollow</span>
                    </a>
                    @endif
                </span>
            </div>
            <div class="row" id="button-price">
                <span class="item">
                    <p class="label-product success-product new-label-1 price-label">
                        <span class="price-1" >{{ $product->combination[0]->rate }} KD</span>
                    </p>
                </span>
            </div>
            <div class="options">
                <form action="{{ url(route('addProductsToCart')) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        @if(count($product->combination) > 1)
                        <div class="col-sm-9">
                            <label for="Option" class="option-category">Option</label>
                            <select name="product_combination" class="Option form-control product_combination">
                                @foreach($product->combination as $combination)
                                <option value="{{ $combination->id }}" rate="{{ $combination->rate }}" quantity="{{ $combination->quantity }}">{{ $combination->combination_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="product_combination" value="{{ $product->combination[0]->id }}" quantity="{{ $product->combination[0]->quantity }}" >
                        @endif
                        <div class="col-sm-3">
                            <label for="Quantity" class="option-category">Quantity</label>
                            <input type="number" class="Quantity form-control"  step="1" min="1" max="{{ $product->combination[0]->quantity }}"  name="item_quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                            <i class="fa fa-caret-up productPage" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" aria-hidden="true"></i>
                            <i class="fa fa-caret-down productPage"  onclick="this.parentNode.querySelector('input[type=number]').stepDown()" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <a>
                                @if($product->combination[0]->quantity > 1) 
                                <button class="addToCart text-center" type="submit">
                                    <img src="{{ url('assets/frontend/images/button_image.png') }}">Add to Cart
                                </button>
                                @else
                                <button class="addToCart text-center" type="button">
                                    Out of Stock
                                </button>
                                @endif
                            </a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="deliver-product">
                            <hr>
                            @foreach($shipping as $item)
                            <h5>Delivers to {{ $item->country_name }}</h5>
                            <div>Deliver within <b>{{ $item->delivery_day_1 }} to {{ $item->delivery_day_2 }}</b> Days</div>
                            @endforeach
                            <hr>
                            <h5>Share this Product</h5>
                            <div class="social-menu">
                                <a class="social-sharing" data-type="facebook" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <img src="{{ url('assets/frontend/images/facebook-share-icon.png') }}">
                                </a>
                                <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <img src="{{ url('assets/frontend/images/google-share-icon.png') }}">
                                </a>
                                <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <img src="{{ url('assets/frontend/images/twitter-share-icon.png') }}">
                                </a>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <h4 class="product-info">
                <div>Product Info</div>
            </h4>
            <p style="word-wrap: break-word;word-break: break-all">
                {{ $product->long_description }}
            </p>
        </div>
    </div>
    <div class="container-fluid" id="imgBorder-1">
        <div class="container" id="related-products">
            <h4 class="border"><b>RELATED PRODUCTS</b></h4>
            <p class="border-para-1">More from same store</p>
            @if(count($releted_products))
            @foreach($releted_products as $item)
            @php
            $item->price = isset($item->combination[0])?$item->combination[0]->rate:0;
            @endphp
            @if(count($item->combination) == 1 && $item->combination[0]['combination_title']=="")
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product-item" >
                <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}">
                    <img  class="img-responsive" style="height: 200px" src="{{ url('doc/product_image/'.$item->images[0]->image_url) }}">
                </a>
                <div class="tag">
                    <div class="imgBorder"> 
                        <span class="type" >{{ $item->product_title }}</span>
                        <span class="item">
                            @if($item->combination[0]->quantity > 0)
                            <form action="{{ url(route('addProductsToCart')) }}" method="POST">
                                @endif
                                {{ csrf_field() }}
                                <input type="hidden" name="product_combination" value="{{ $item->combination[0]->id }}">
                                <input type="hidden" name="item_quantity" value="1"/>
                                <button class="btn price clsIndividualProduct {{ ($item->combination[0]->quantity < 1)?'clsStockOut':'' }}">
                                    <span>{{ number_format($item->combination[0]->rate,2) }} KD</span>
                                </button>
                                @if($item->combination[0]->quantity > 0)
                            </form> 
                            @endif
                        </span>
                        <div class="name">by 
                            <b>
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$item->store->store_slug])) }}">
                                    {{ $item->store->store_name }}
                                </a>
                            </b>
                        </div>
                    </div>
                </div>
                <span class="heart">
                    <!--<a href="{{ url(route((($item->is_liked=="No")?"likeProduct":"unlikeProduct"),['productSlug'=>$item->product_slug])) }}">-->
                    <a href="{{ empty($customer)?url(route('login')):'javascript:void(0)' }}" class="{{ (($item->is_liked=="No")?"clsLikeProduct":"clsUnlikeProduct") }}" product_id='{{ $item->id }}'>
                        <i class="fa fa-heart{{ ($item->is_liked=="No")?"-o":"" }}" aria-hidden="true" style=""></i>
                    </a>
                </span>
                <div class="more">
                    <div class="modal-mobile">
                        <a href="#" data-toggle="modal" data-target="#myModal-1">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                        <div id="myModal-1" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            <li>
                                                <a href="#"  title="Share" class="slideUP">
                                                    <i class="fa fa-share" aria-hidden="true"></i> 
                                                    <span>Share</span>
                                                    <p>Share with your friends</p>
                                                </a>
                                                <div class="social-menu">
                                                    <a class="social-sharing" data-type="facebook" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <img src="{{ url('assets/frontend/images/facebook-share-icon.png') }}">
                                                    </a>
                                                    <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <img src="{{ url('assets/frontend/images/google-share-icon.png') }}">
                                                    </a>
                                                    <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <img src="{{ url('assets/frontend/images/twitter-share-icon.png') }}">
                                                    </a>
                                                </div>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" class="copy-text">
                                                    <input type="hidden" name="link" class="link" value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                                    <i class="fa fa-link" aria-hidden="true"></i>
                                                    <span>Copy Link</span>
                                                    <p>copy product link</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropup">
                        <div class="dropdown-toggle" type="button" data-toggle="dropdown">
                            <a href="#">
                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </a>
                        </div>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="javascript:void(0);" title="Share" class="slideUP">
                                    <i class="fa fa-share" aria-hidden="true"></i>
                                    <span>Share</span><p>Share with your friends</p> </a>
                                <div class="social-menu">
                                    <a class="social-sharing" data-type="facebook" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <img src="{{ url('assets/frontend/images/facebook-share-icon.png') }}">
                                    </a>
                                    <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <img src="{{ url('assets/frontend/images/google-share-icon.png') }}">
                                    </a>

                                    <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <img src="{{ url('assets/frontend/images/twitter-share-icon.png') }}">
                                    </a>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0)" class="copy-text">
                                    <input type="hidden" name="link" class="link" value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <span>Copy Link</span>
                                    <p>copy product link</p>
                                </a>
                            </li>  
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            @if(count($item->combination) >= 1  && $item->combination[0]['combination_title']!="")
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6  product-item">
                <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}">
                    <img  class="img-responsive"  style="height: 200px" src="{{ url('doc/product_image/'.$item->images[0]->image_url) }}">
                </a>
                <div class="tag">
                    <div class="imgBorder">
                        <span class="type" >{{ $item->product_title }}</span>
                        <span id="modal">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><b>{{$item->combination[0]['rate']}}&nbsp;KD</b></button>
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">SELECT OPTION</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url(route('addProductsToCart')) }}" method="POST" <?php echo ($item->combination[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                                                {{ csrf_field() }}
                                                <ul>
                                                    <li>
                                                        <div class="row">
                                                            <div class="col-xs-9">
                                                                <select name="product_combination" class="form-control product_combination">
                                                                    @foreach($item->combination as $combination)
                                                                    <option value="{{ $combination->id }}" quantity="{{ $combination->quantity }}" >{{ $combination->combination_title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-3"  style="margin-top: 10px">
                                                                <span >
                                                                    <input type="number" name="item_quantity" class="form-control item_quantity" min="1" value="1">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        @if($item->combination[0]->quantity > 0)
                                                        <button class="addToCart-modal" type="submit">Add to Cart</button>
                                                        @else
                                                        <button class="addToCart-modal" type="submit">Out of Stock</button>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <span class="dropdown">
                            <button id="dropdown" class="btn  dropdown-toggle btnDropdown" type="button">
                                <b>{{ number_format($item->combination[0]->rate,2) }} KD</b>
                            </button>
                            <ul class="dropdown-menu" id="select">
                                <span>SELECT OPTION</span>
                                <form action="{{ url(route('addProductsToCart')) }}" method="POST" <?php echo ($item->combination[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                                    {{ csrf_field() }}
                                    <li>
                                        <div class="row">
                                            <div class="col-xs-8 col-md-8">
                                                <select name="product_combination" class="form-control product_combination">
                                                    @foreach($item->combination as $combination)
                                                    <option value="{{ $combination->id }}" quantity="{{ $combination->quantity }}" >{{ $combination->combination_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-4 col-md-4"  style="margin-top: 10px">
                                                <span >
                                                    <input type="number" name="item_quantity" class="form-control item_quantity" min="1" value="1">
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        @if($item->combination[0]->quantity > 0)
                                        <button id="dropdown-save" type="submit">Add to Cart</button>
                                        @else
                                        <button id="dropdown-save" type="submit">Out of Stock</button>
                                        @endif
                                    </li>   
                                </form>
                            </ul>
                        </span>
                        <div class="name">by 
                            <b>
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$item->store->store_slug])) }}">
                                    {{ $item->store->store_name }}
                                </a>
                            </b>
                        </div>
                    </div>
                </div>
                <span class="heart">
                    <!--<a href="{{ url(route((($item->is_liked=="No")?"likeProduct":"unlikeProduct"),['productSlug'=>$item->product_slug])) }}">-->        
                    <a href="{{ empty($customer)?url(route('login')):'javascript:void(0)' }}" class="{{ (($item->is_liked=="No")?"clsLikeProduct":"clsUnlikeProduct") }}" product_id='{{ $item->id }}'>
                        <i class="fa fa-heart{{ ($item->is_liked=="No")?"-o":"" }}" aria-hidden="true" ></i>
                    </a>
                </span>
                <span class="count">100</span>
                <div class="more">
                    <div class="modal-mobile">
                        <a href="#" data-toggle="modal" data-target="#myModal-1">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                        <div id="myModal-1" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            <li>
                                                <a href="#"  title="Share" class="slideUP">
                                                    <i class="fa fa-share" aria-hidden="true"></i> 
                                                    <span>Share</span>
                                                    <p>Share with your friends</p>
                                                </a>
                                                <div class="social-menu">
                                                    <a class="social-sharing" data-type="facebook" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <img src="{{ url('assets/frontend/images/facebook-share-icon.png') }}">
                                                    </a>
                                                    <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <img src="{{ url('assets/frontend/images/google-share-icon.png') }}">
                                                    </a>
                                                    <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <img src="{{ url('assets/frontend/images/twitter-share-icon.png') }}">
                                                    </a>
                                                </div>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" class="copy-text">
                                                    <input type="hidden" name="link" class="link" value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                                    <i class="fa fa-link" aria-hidden="true"></i>
                                                    <span>Copy Link</span>
                                                    <p>copy product link</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropup">
                        <div class="dropdown-toggle" type="button" data-toggle="dropdown">
                            <a href="#">
                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </a>
                        </div>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="javascript:void(0);" title="Share" class="slideUP">
                                    <i class="fa fa-share" aria-hidden="true"></i>
                                    <span>Share</span><p>Share with your friends</p> 
                                </a>
                                <div class="social-menu">
                                    <a class="social-sharing" data-type="facebook" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <img src="{{ url('assets/frontend/images/facebook-share-icon.png') }}">
                                    </a>
                                    <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <img src="{{ url('assets/frontend/images/google-share-icon.png') }}">
                                    </a>

                                    <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <img src="{{ url('assets/frontend/images/twitter-share-icon.png') }}">
                                    </a>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0)" class="copy-text">
                                    <input type="hidden" name="link" class="link" value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <span>Copy Link</span>
                                    <p>copy product link</p>
                                </a>
                            </li>  
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            @if(count($item->combination) == 0)
            @endif
            @endforeach
            @else            
            <div class="container">
                <div class="noproducts">
                    <h3>No Products Available</h3>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!--    <div class="container">
            <div id="viewmore">
                <button id="btnViewMoreRelatedProducts"><b>VIEW MORE</b></button>
            </div>
        </div>-->
    <div class="container text-center loader">
        <img src="{{ url('assets/loader.gif') }}" style="height: 80px;width: 80px;">
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var Page = 1, PageLimit = 8, HasMoreProducts = "{{ (count($releted_products)>=8)?'Yes':'No' }}";
    $(document).ready(function ()
    {

        $(".loader").hide();
        $(document).on("change", ".product_combination", function ()
        {
            var quantity = $(this).find("option:selected").attr("quantity");
            var rate = $(this).find("option:selected").attr("rate");
            var html = "<span class='price-1'>" + rate + " KD</span>";
            $(document).find("#button-price").find(".price-label").html(html);
            $(this).closest("form").find(".Quantity").attr("max", quantity);
            if (quantity > 0)
            {
                $(this).closest("form").find(".addToCart").attr("type", "submit");
                $(this).closest("form").find(".addToCart").html("<img src=\'{{ url('assets/frontend/images/button_image.png') }}\'>Add to Cart");
            }
            else
            {
                $(this).closest("form").find(".addToCart").attr("type", "button");
                $(this).closest("form").find(".addToCart").html('Out of Stock');
            }
        });
        $('#thumbcarousel .carousel-inner .item').each(function ()
        {
            var itemToClone = $(this);

            for (var i = 1; i < 4; i++)
            {
                itemToClone = itemToClone.next();

                // wrap around if at end of item collection
                if (!itemToClone.length)
                {
                    itemToClone = $(this).siblings(':first');
                }

                // grab item, clone, add marker class, add to collection
                itemToClone.children(':first-child').clone()
                        .addClass("cloneditem-" + (i))
                        .appendTo($(this));
            }
        });

        $('#thumbcarousel').bind('mousewheel', function (e)
        {
            if (e.originalEvent.wheelDelta / 120 > 0 || e.originalEvent.detail < 0)
            {
                $(this).carousel('next');
            }
            else
            {
                $(this).carousel('prev');
            }
        });
        var fixed = document.getElementById("thumbcarousel");
        fixed.addEventListener('mousewheel', function (e)
        {
            e.preventDefault();
        }, false);
        $(document).on("click", "#btnViewMoreRelatedProducts", function (e)
        {
//            if (HasMoreProducts == "Yes")
//            {
//                var btn = this;
//                var data = {
//                    "start": (Page * PageLimit),
//                    "limit": PageLimit
//                };
//                $.ajax({
//                    url: "{{ url(route('getRelatedProducts',['product'=>$product->id])) }}",
//                    dataType: 'text',
//                    type: 'post',
//                    contentType: 'application/x-www-form-urlencoded',
//                    data: data,
//                    success: function (data, textStatus, jQxhr)
//                    {
//                        var NewProducts = 0;
//                        var CurrentProducts = $("#related-products").find(".product-item").length;
//                        if (data != "")
//                        {
//                            $("#related-products").append(data);
//                            var TotalProducts = $("#related-products").find(".product-item").length;
//                            NewProducts = TotalProducts - CurrentProducts;
//                        }
//                        if (NewProducts < PageLimit)
//                        {
//                            HasMoreProducts = "No";
//                            $(btn).hide();
//                        }
//                        else
//                        {
//                            Page = Page + 1;
//                        }
//                        $(".loader").hide();
//                    },
//                    error: function (jqXhr, textStatus, errorThrown)
//                    {
//                        console.log(errorThrown);
//                    }
//                });
//            }
        });
        if (HasMoreProducts == "No")
        {
            $("#btnViewMoreRelatedProducts").hide();
        }
    });
    $(window).scroll(function ()
    {
        if ($(window).scrollTop() == $(document).height() - $(window).height())
        {
            if (HasMoreProducts == "Yes")
            {
                var data = {
                    "start": (Page * PageLimit),
                    "limit": PageLimit
                };
                $(".loader").show();
                $.ajax({
                    url: "{{ url(route('getRelatedProducts',['product'=>$product->id])) }}",
                    dataType: 'text',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: data,
                    success: function (data, textStatus, jQxhr)
                    {
                        var NewProducts = 0;
                        var CurrentProducts = $("#related-products").find(".product-item").length;
                        if (data != "")
                        {
                            $("#related-products").append(data);
                            var TotalProducts = $("#related-products").find(".product-item").length;
                            NewProducts = TotalProducts - CurrentProducts;
                        }
                        if (NewProducts < PageLimit)
                        {
                            HasMoreProducts = "No";
                            $("#btnViewMoreRelatedProducts").hide();
                        }
                        else
                        {
                            Page = Page + 1;
                        }
                        $(".loader").hide();
                    },
                    error: function (jqXhr, textStatus, errorThrown)
                    {
                        console.log(errorThrown);
                    }
                });
            }
        }
    });
</script>
@endsection