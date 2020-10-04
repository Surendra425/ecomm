@if(!empty($products))
    @foreach($products as $key => $item)
        @php //dd($item[5]); @endphp
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product-item">
            <div class="image_block">
                <a href="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}">
                   <img alt="no-images" class="img-responsive" src="{{ !empty($item->images[0]->file_name) ? url('doc/product_image/'.$item->images[0]->file_name) : url('assets/app/media/img/no-images.jpeg')}}">
                </a>
                <div class="dropup">
                    <div class="dropdown-toggle" data-toggle="dropdown">
                        <a href="#">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                    </div>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="javascript:void(0);" title="Share" class="slideUP">
                                <i class="fa fa-share" aria-hidden="true"></i>
                                <span>Share</span>
                                <p>Share with your friends</p></a>
                            <div class="social-menu">
                                <a class="social-sharing" data-type="facebook"
                                   data-url="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-facebook-square fa-2x"></i>
                                </a>
                                <a class="social-sharing" data-type="google"
                                   data-url="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                                </a>

                                <a class="social-sharing" data-type="twitter"
                                   data-url="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
                                </a>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#productCopyModal"
                               class="copy-text">
                                <input type="hidden" name="link" class="link"
                                       value="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"/>
                                <i class="fa fa-link" aria-hidden="true"></i>
                                <span>Copy Link</span>
                                <p>copy product link</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="more">
                    <div class="modal-mobile mobile-view">
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
                                                <a href="#" title="Share" class="slideUP">
                                                    <i class="fa fa-share" aria-hidden="true"></i>
                                                    <span>Share</span>
                                                    <p>Share with your friends</p>
                                                </a>
                                                <div class="social-menu">
                                                    <a class="social-sharing" data-type="facebook"
                                                       data-url="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <i class="fa fa-facebook-square fa-2x"></i>
                                                    </a>
                                                    <a class="social-sharing" data-type="google"
                                                       data-url="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <i class="fa fa-google-plus-square fa-2x"
                                                           aria-hidden="true"></i>
                                                    </a>
                                                    <a class="social-sharing" data-type="twitter"
                                                       data-url="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" class="copy-text">
                                                    <input type="hidden" name="link" class="link"
                                                           value="{{ url(route('showProductDetails',['productSlug'=>$item->product_slug])) }}"/>
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
                </div>
            </div>
                <div class="tag">
                    <div class="imgBorder">
                        <span class="type">{{ $item->product_title }}</span>

                        {{--@if($item->options[0]->quantity > 0)--}}

                            @if(!empty($item->options) && count($item->options) >= 1 && $item->options[0]->combination_title !="")

                                {{--in responsive show modal--}}
                                <div class="mobile-view">
                                    <button type="button" class="btn btn-info btn-sm ratebutton"
                                            data-rate="{{ $item->options[0]->rate }}"
                                            data-quantity="{{ $item->options[0]->quantity }}" data-toggle="modal"
                                            data-target="#myModals{{$item->id}}">
                                        <b id="optionPrice{{$item->id}}">{{ (float)($item->options[0]->rate)  }}
                                            &nbsp;KD</b>
                                    </button>

                                    <div class="modal fade" id="myModals{{$item->id}}" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            data-rate="{{ $item->options[0]->rate }}"
                                                            data-quantity="{{ $item->options[0]->quantity }}">&times;</button>
                                                    <h4 class="modal-title">SELECT OPTION</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('addProductsToCart') }}"
                                                          method="POST" <?php echo ($item->options[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                                                        {{ csrf_field() }}
                                                        <ul>
                                                            <li>
                                                                <div class="row">
                                                                    <div class="col-xs-8">
                                                                        <select name="product_combination"
                                                                                class="form-control product_combination clsproductCombination"
                                                                                id="optionsChanges{{$item->id}}">
                                                                            @foreach($item->options as $combination)
                                                                                <option value="{{ $combination->id }}"
                                                                                        data-product={{$item->id}} data-rate="{{ $combination->rate }}"
                                                                                        data-quantity="{{ $combination->quantity }}">{{ $combination->combination_title }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                   <div class="col-xs-4">
                                                                        <span>
                                                                            <select class="form-control item_quantity Quantity2"
                                                                                    id="qty{{$item->id}}"
                                                                                    name="item_quantity" style="margin: 11px 0px 11px 0px;">
                                                                              @php $selectMaxQty = $item->options[0]->quantity > 12 ? 12 : $item->options[0]->quantity;
                                                                              @endphp
                                                                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                @if($item->options[0]->quantity > 0)
                                                                    <button type="button"
                                                                            class=" addToCart-modal saveAddtocart saveAddToCartAvil"
                                                                            onclick="addTocartCombinationWebModel('{{$item->id}}')">Add to Cart</button>
                                                                @else
                                                                    <button class="addToCart-modal saveAddtocart"
                                                                            type="button"
                                                                            onclick="addTocartCombinationWebModel('{{$item->id}}')"
                                                                            disabled>Out of Stock</button>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown">
                                        <button id="dropdown{{$item->id}}" class="btn btn-info  dropdown-toggle btnDropdown "
                                                data-rate="{{ $item->options[0]->rate }}"
                                                data-quantity="{{ $item->options[0]->quantity }}" type="button">
                                            <b class="optionPrice1">{{ (float)($item->options[0]->rate)   }} KD</b><!-- id replaced by class in optionPrice1 -->
                                        </button>
                                        {{-- <span>SELECT OPTION</span> --}}
                                        <ul class="dropdown-menu addtocartSelect select-id before-select-item">
                                            <li>Select Option</li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-xs-7 col-md-7">
                                                        <select name="product_combination"
                                                                class="form-control product_combination clsproductCombination"
                                                                id="selectoption{{$item->id}}">
                                                            @foreach($item->options as $combination)
                                                                <option value="{{ $combination->id }}"
                                                                        data-rate="{{ $combination->rate }}"
                                                                        data-quantity="{{ $combination->quantity }}">{{ $combination->combination_title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-5 col-md-5">
                                                            @php $selectMaxQty = $item->options[0]->quantity > 12 ? 12 : $item->options[0]->quantity;
                                                            @endphp
                                                        <select class="form-control item_quantity product_combination maxQty"
                                                                id="qtySelected{{$item->id}}" name="item_quantity">


                                                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                        {{--<input type="number" id="qtySelected{{$item->id}}" name="item_quantity" class="form-control item_quantity" min="1" value="1">--}}

                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                @if($item->options[0]->quantity > 0)
                                                    <button class='saveAddtocart ' type="button" onclick="addTocartCombinationWeb('{{$item->id}}')">Add to Cart</button>
                                                @else
                                                    <button class='saveAddtocart' type="button" onclick="addTocartCombinationWeb('{{$item->id}}')" disabled>Out of Stock</button>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>

                            @elseif(!empty($item->options) && count($item->options) > 1 && $item->options[0]->combination_title == "")
                                <button type="button" class="btn btn-sm btn-info add-tocart-btn clsIndividualProduct price {{ ($item->options[0]->quantity < 1)?'clsStockOut':'' }}" {{ ($item->options[0]->quantity < 1)? 'disabled':'' }}>
                                    <span>{{ ($item->options[0]->quantity < 1)?'Out of Stock':(float)($item->options[0]->rate).' KD' }}</span>
                                </button>
                                <input type="hidden" name="product_combination"
                                       value="{{ $item->options[0]->id }}">
                                <input type="hidden" name="item_quantity" value="1"/>
                            @elseif(!empty($item->options[0])) 

                            <button type="button" class="btn btn-sm btn-info add-tocart-btn clsIndividualProduct price {{ ($item->options[0]->quantity < 1)?'clsStockOut':'' }}" {{ ($item->options[0]->quantity < 1)? 'disabled':'' }}>
                                <span>{{ ($item->options[0]->quantity < 1)?'Out of Stock':(float)($item->options[0]->rate).' KD' }}</span>
                            </button>
                            <input type="hidden" name="product_combination"
                                   value="{{ $item->options[0]->id }}">
                            <input type="hidden" name="item_quantity" value="1"/>

                        @endif
                        <div class="name">by
                            <b>
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$item->store_slug])) }}">
                                    {{ $item->store_name }}
                                </a>
                            </b>
                        </div>
                    </div>
                </div>


                <div class="heart heart-like-dislike">
                    <a href="{{ empty($customer)?url(route('login')):'javascript:void(0)' }}"
                       class="{{ (($item->is_liked=="No")?"clsLikeProduct":"clsUnlikeProduct") }}"
                       data-product_id='{{ $item->id }}'>
                        <i class="fa fa-heart{{ ($item->is_liked=="No")?"-o":"" }}" aria-hidden="true" style=""></i>
                    </a>
                </div>
        </div>

    @endforeach
@else
    <div class="noproducts">
        <h3>No Product Available</h3>
    </div>
@endif
{{--@elseif(!isset($is_related_view) || $is_related_view == "No")
    <div class="noproducts">
        <h3>No Product Available</h3>
    </div>
@endif--}}

<!-- Modal -->
<div id="productCopyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Product Link</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Copied Product Link : </b><span id="copy_link"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>