@if(count($products))
@foreach($products as $item)
@php
$item->price = isset($item->combination[0])?$item->combination[0]->rate:0;
@endphp
@if(count($item->combination) == 1 && $item->combination[0]['combination_title']=="")
<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product-item" >
    <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}">
        <img  class="img-responsive" src="{{ !empty($item->images[0]->image_url) ? url('doc/product_image/'.$item->images[0]->image_url) : url('assets/app/media/img/no-images.jpeg')}}">
    </a>
    <div class="tag">
        <div class="imgBorder"> 
            <span class="type" >{{ $item->product_title }}</span>
            <span class="item">
                @if($item->combination[0]->quantity > 0)
                <form action="{{ route('addProductsToCart') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="product_combination" value="{{ $item->combination[0]->id }}">
                    <input type="hidden" name="item_quantity" value="1"/>

                        <button type="button" onclick="addToCart('{{ $item->combination[0]->id }}','1')" class="btn btn-sm price clsIndividualProduct {{ ($item->combination[0]->quantity < 1)?'clsStockOut':'' }}">
                        <span>{{ (float)($item->combination[0]->rate)  }} KD</span>
                    </button>
                </form>
                @else
                    <p type="button"  class="btn btn-sm price clsIndividualProduct {{ ($item->combination[0]->quantity < 1)?'clsStockOut':'' }}">
                        <span>Out of Stock</span>
                    </p>
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
            <a href="#" data-toggle="modal" data-target="#myModal{{$item->id}}">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            </a>
            <div id="myModal{{$item->id}}" class="modal fade" role="dialog">
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
                                            <i class="fa fa-facebook-square fa-2x"></i>
                                        </a>
                                        <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                           href="javascript:void(0);">
                                            <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                                        </a>
                                        <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                           href="javascript:void(0);">
                                            <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
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
                            <i class="fa fa-facebook-square fa-2x"></i>
                        </a>
                        <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                           href="javascript:void(0);">
                            <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                        </a>

                        <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                           href="javascript:void(0);">
                            <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
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
        <img  class="img-responsive" src="{{ !empty($item->images[0]->image_url) ? url('doc/product_image/'.$item->images[0]->image_url) : url('assets/app/media/img/no-images.jpeg') }}">
    </a>
    <div class="tag">
        <div class="imgBorder">
            <span class="type" >{{ $item->product_title }}</span>
            <span id="modal">
                <button type="button" class="btn btn-info btn-sm ratebutton" rate="{{ $item->combination[0]->rate }}" quantity="{{ $item->combination[0]->quantity }}" data-toggle="modal" data-target="#myModals{{$item->id}}"><b id="optionPrice{{$item->id}}">{{ (float)($item->combination[0]->rate)  }}&nbsp;KD</b></button>
                <div class="modal fade" id="myModals{{$item->id}}" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" rate="{{ $item->combination[0]->rate }}" quantity="{{ $item->combination[0]->quantity }}">&times;</button>
                                <h4 class="modal-title">SELECT OPTION</h4>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('addProductsToCart') }}" method="POST" <?php echo ($item->combination[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                                    {{ csrf_field() }}
                                    <ul>
                                        <li>
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <select name="product_combination" class="form-control product_combination clsproductCombination"  id="optionsChanges{{$item->id}}" onchange="priceUpdate('{{$item->id}}')">
                                                        @foreach($item->combination as $combination)
                                                        <option value="{{ $combination->id }}" product = {{$item->id}} rate="{{ $combination->rate }}" quantity="{{ $combination->quantity }}" >{{ $combination->combination_title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                               <div class="col-xs-3"  style="margin-top: 10px">
                                                    <span>
                                                        <select class="form-control item_quantity Quantity1" id="qty{{$item->id}}" name="item_quantity">
                                                          @php $selectMaxQty = $item->combination[0]->quantity > 12 ? 12 : $item->combination[0]->quantity;
                                                           	@endphp
                                                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                                                         			<option value="{{ $i }}">{{ $i }}</option>
                                                           		 @endfor
                                                        </select>

                                                          {{--  <input type="number" name="item_quantity" class="form-control item_quantity" min="1" value="1">--}}

                                                        {{--<input type="number" class="Quantity1" step="1" min="1" max="{{ $product['combination_qty'] }}" name=quantity[{{ $product['product_combination_id'] }}]" value="{{ $product['quantity'] }}" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                                                        <i class="fa fa-caret-up clsUp" aria-hidden="true"></i>
                                                        <i class="fa fa-caret-down clsDown" aria-hidden="true"></i>--}}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            @if($item->combination[0]->quantity > 0)
                                            <button type="button" class="addToCart-modal saveAddtocart saveAddToCartAvil" onclick="addTocartCombination('{{$item->id}}')">Add to Cart</button>
                                            @else
                                            <button class="addToCart-modal saveAddtocart" type="button" onclick="addTocartCombination('{{$item->id}}')" disabled>Out of Stock</button>
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
                <button id="dropdown{{$item->id}}" class="btn  dropdown-toggle btnDropdown" rate="{{ $item->combination[0]->rate }}" quantity="{{ $item->combination[0]->quantity }}" type="button">
                    <b id="optionPrice1">{{ (float)($item->combination[0]->rate)   }} KD</b>
                </button>
                <ul class="dropdown-menu addtocartSelect" id="select">
                    <span>SELECT OPTION</span>
                    <form action="{{ route('addProductsToCart') }}" method="POST" <?php echo ($item->combination[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                        {{ csrf_field() }}
                        <li>
                            <div class="row">
                                <div class="col-xs-7 col-md-7">
                                    <select name="product_combination" class="form-control product_combination clsproductCombination"  id="selectoption{{$item->id}}">
                                        @foreach($item->combination as $combination)
                                        <option value="{{ $combination->id }}" rate="{{ $combination->rate }}" quantity="{{ $combination->quantity }}" >{{ $combination->combination_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-5 col-md-5" >
										@php $selectMaxQty = $item->combination[0]->quantity > 12 ? 12 : $item->combination[0]->quantity;
                                       	@endphp
                                        <select class="form-control item_quantity product_combination maxQty" id="qtySelected{{$item->id}}" name="item_quantity">
                                        
                                       	
                                            @for ($i = 1; $i <= $selectMaxQty; $i++)
                                     			<option value="{{ $i }}">{{ $i }}</option>
                                       		 @endfor
                                                        </select>
                                        {{--<input type="number" id="qtySelected{{$item->id}}" name="item_quantity" class="form-control item_quantity" min="1" value="1">--}}

                                </div>
                            </div>
                        </li>
                        <li>
                            @if($item->combination[0]->quantity > 0)
                            <button id="dropdown-save" class='saveAddtocart' type="button" onclick="addTocartCombinationWeb('{{$item->id}}')">Add to Cart</button>
                            @else
                            <button id="dropdown-save" class='saveAddtocart' onclick="addTocartCombinationWeb('{{$item->id}}')" type="button" disabled>Out of Stock</button>
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
            <a href="#" data-toggle="modal" data-target="#myModal{{$item->id}}">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            </a>
            <div id="myModal{{$item->id}}" class="modal fade" role="dialog">
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
                                            <i class="fa fa-facebook-square fa-2x"></i>
                                        </a>
                                        <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                           href="javascript:void(0);">
                                            <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                                        </a>
                                        <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                           href="javascript:void(0);">
                                            <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
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
                            <i class="fa fa-facebook-square fa-2x"></i>
                        </a>
                        <a class="social-sharing" data-type="google" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                           href="javascript:void(0);">
                            <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                        </a>

                        <a class="social-sharing" data-type="twitter" data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                           href="javascript:void(0);">
                            <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
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
@elseif(!isset($is_related_view) || $is_related_view == "No")
<div class="noproducts">
    <h3>No Product Available</h3>
</div>
@endif