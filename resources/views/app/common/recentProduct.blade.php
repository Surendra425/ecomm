<?php echo "hello";die; ?>
@if(count($recentProducts))
@foreach($recentProducts as $item)
@php
$item->price = isset($item->combination[0])?$item->combination[0]->rate:0;
@endphp
@if(count($item->combination) == 1 && $item->combination[0]['combination_title']=="")
<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product-item" >
    <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}">
        <img  class="img-responsive" src="{{ url('doc/product_image/'.$item->images[0]->image_url) }}">
    </a>
    <div class="tag">
        <div class="imgBorder"> 
            <span class="type" >{{ $item->product_title }}</span>
            <span class="item">
                @if($item->combination[0]->quantity > 0)
                <form action="{{ url(route('addProductsToCart')) }}" method="POST">

                    {{ csrf_field() }}
                    <input type="hidden" name="product_combination" value="{{ $item->combination[0]->id }}">
                    <input type="hidden" name="item_quantity" value="1"/>
                    <button class="btn price  btn-sm clsIndividualProduct {{ ($item->combination[0]->quantity < 1)?'clsStockOut':'' }}">
                        <span>{{ (float)($item->combination[0]->rate) }} KD</span>
                    </button>
                </form>
                @else
                    <button type="button"  class="btn btn-sm price clsIndividualProduct clsStockOut">
                                                <span><span>Out of Stock</span></span>
                                            </button>
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
        <img  class="img-responsive" src="{{ url('doc/product_image/'.$item->images[0]->image_url) }}">
    </a>
    <div class="tag">
        <div class="imgBorder">
            <span class="type" >{{ $item->product_title }}</span>
            <span id="modal">
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModals{{$item->id}}"><b>{{ (float)($item->combination[0]->rate)   }}&nbsp;KD</b></button>
                <div class="modal fade" id="myModals{{$item->id}}" role="dialog">
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
                                                    {{--<select class="form-control item_quantity Quantity1 qty" name="item_quantity">
                                                            <option value="1" selected>1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                        </select>--}}
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
                    <b>{{ (float)($item->combination[0]->rate)  }} KD</b>
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
@endforeach
@else
<div class="col-md-12 noproducts">
    <h2>No Product Available</h2>
</div>
@endif