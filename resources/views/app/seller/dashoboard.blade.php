@extends('layouts.app')
@section('title') {{ $store->store_name }} @endsection
@section('css')
@endsection
@section('content')

    <div class="container-fluid" id="dashboardBanner" style="background-image:url('{{isset($store->banner_image) && $store->banner_image != '' ?  url('doc/store_banner_image/').'/'. $store->banner_image: url('assets/frontend/images/no_image_1920.png') }}')">
        <div class="full-overlay">
            <div class="container" >
                <div class="col-sm-8 col-lg-7">
                    <a class="user_pic">
                        <img class="img-circle" height="100px" width="100px" src="{{ ($store->store_image != "")? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
                    </a>
                    <span class="textDashboard">
                        <a class="st_name">
                            <b>{{ $store->store_name }}</b> 
                            ({{ $store->country }} )
                        </a>
                        @php
                        $RateUrl = (empty($customer))?(url(route('login'))):"javascript:viewRatingModel()";
                        @endphp
                        <span class="rt_star">
                        @for($i=1;$i<=5;$i++)
                      
                        <a href="{{ $RateUrl }}"><span class="fa {{ ($store->rating >= $i)?("fa-star checked"):((($store->rating > ($i-1)) && ($store->rating < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;</a>                    
                        @endfor
                    </span>
                        <a class="fl_count"><span>({{ $store->total_user_rate }})</span></a>
                    </span>
                </div>
                <div class="col-sm-4 col-lg-5" class="col-xs-12">
                    <div class="search-data">
                        <div class="col-xs-12">
                            <div class="search-box">
                                <input type="hidden" name="storeSlug" id="storeSlug" value="{{$store->store_slug}}">
                                <input type="hidden" name="storeId" id="storeId" value="{{$store->vendor_id}}">
                                <form id="searchKey1" method="get" action="{{url(route('searchStoreProduct',['store' => $store->store_slug]))}}">
                                    <div class="input-group stylish-input-group">
                                        <input type="text" class="form-control required"  placeholder="Search in Store" name="keyword" id="srch-term3" required="required" autocomplete="off">
                                        <div class="hidden base base-width dashboardSearch" id="search-div3">
                                            <ul id="search-parm3" class="search-parm"></ul>
                                        </div>
                                        <span class="input-group-addon">
                                            <button   type="submit">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </button> 
                                        </span>
                                    </div>                                 
                                </form>                     
                            </div> 
                        </div> 

                        <div class="col-xs-12" id="deleveryDetails">
                            <ul class="list-inline">
                                <li class="col-xs-2">
                                    <a>
                                        <img src="{{ url('assets/frontend/images/time_icon.png') }}">
                                    </a>
                                    @if(!empty($shipping))

                                       <span class="event-text" id="from">{{isset($shipping->from) && !empty($shipping->from) ? $shipping->from : '0'}}-{{isset($shipping->to) && !empty($shipping->to) ? $shipping->to : '0'}} {{isset($shipping->time) && !empty($shipping->time) ? $shipping->time : 'Days'}}</span>
                                    @else
                                        @if(empty($address))
                                           <a href="{{ url(route('address.create',['store_slug'=>$store->store_slug])) }}" class="btn btn-xs  btn-success seller-btn" id="btnAddress">Add Address</a>
                                        @else
                                            <p class="event-text" id="addressHasFrom"> - </p>
                                        @endif
                                    @endif
                                </li>
                                <li class="col-xs-2">
                                    <a>
                                        <img  src="{{ url('assets/frontend/images/charges_icon.png') }}">
                                    </a>
                                    @if(!empty($shipping))
                                        <span class="event-text" id="charge">
                                            @if(!empty($shipping->charge))
                                                @if($shipping->charge != 0)
                                                 {{(float)($shipping->charge)}}&nbsp;KD
                                                @else
                                               Free
                                                @endif
                                                @else
                                                Free
                                                @endif
                                            </span>
                                    @else
                                        @if(!empty($address))
                                            <p class="event-text" id="addressHasCharge"> - </p>
                                        @endif
                                    @endif
                                </li>

                                <li class="col-xs-2"> 
                                    <a>
                                        <img  src="{{ url('assets/frontend/images/status_icon.png') }}">
                                    </a>
                                    <span class="event-text">{{ $store->store_status }}</span>
                                </li>
                                <li class="col-xs-2"> 
                                    <a href="{{ url(route('sellerAboutUs',['store'=>$store->store_slug])) }}">
                                        <img  src="{{ url('assets/frontend/images/about_icon.png') }}">
                                    </a>
                                    <span class="event-text">About Us</span>
                                </li>
                                @if($store->is_follow =="Yes")
                                <li class="col-xs-2"> 
                                    <a href="{{ url(route('sellerUnfollow',['store'=>$store->id])) }}">
                                        <img  src="{{ url('assets/frontend/images/unfollow_icon.png') }}">
                                    </a>
                                    <span class="event-text">Following</span>
                                </li>

                                @else
                                <li class="col-xs-2"> 
                                    <a href="{{ url(route('sellerFollow',['store'=>$store->id])) }}">
                                        <img  src="{{ url('assets/frontend/images/follow_icon.png') }}">
                                    </a>
                                    <span class="event-text">Follow</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <div class="container-fluid"  id="sliderDashboard" >
        <div class="container">
            <legend class="mg-0">CATEGORIES</legend>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($category as $item)
                    <div class="swiper-slide">
                        <div class="item">
                            <a class="store-category" data-category-id="{{ $item->id }}" href="JavaScript:void(0);">
                                <img src="{{ ($item->category_image != "") ? url('doc/category_image/').'/'.$item->category_image:url('assets/app/media/img/no_category_image_100.png') }}" class="img-responsive img-circle" >
                            </a>
                            <p>{{ $item->category_name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div >
                <legend class="legend mg-0">FEEDS</legend>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="imgBorder-1">
        <div id="store-products-data" class="container Dashboard">
            <!-- begin::Recent Product -->
            @include('app.common.products')
            <!-- end:: Recent Product -->
        </div>
    </div>

<!-- Modal -->
<div id="storeRatingModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="POST" action="{{ url(route('rateSeller',['store'=>$store->id])) }}" >
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ ((!empty($customer))?($customer->id):"") }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rate Store</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Rating : </label><br/>
                            <input type="hidden"  value="0" name="rating" class="rating" data-filled="fa fa-star checked" data-empty="fa fa-star"/>
                            <span class="textDashboard">
                                @for($i=1;$i<=5;$i++)
                                <a rating="{{ $i }}" class="clsRate"><span class="fa {{ ($store->user_rating >= $i)?("fa-star checked"):((($store->user_rating > ($i-1)) && ($store->user_rating < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;</a>
                                @endfor
                            </span>
                        </div>
                        {{-- <div class="col-md-12">
                            <label class="control-label">Review : </label><br/>
                            <textarea id="description" class="col-md-12" name="description">{{$store->user_review or ''}}</textarea>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-submit btn-success" >Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 4.5,
        spaceBetween: 20,
        freeMode: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            // when window width is <= 320px
            768: {
                slidesPerView: 4.6,
                spaceBetween: 8
            },
            // when window width is <= 480px
            1024: {
                slidesPerView: 6,
                spaceBetween: 20
            },
            // when window width is <= 640px
            1600: {
                slidesPerView: 12,
                spaceBetween: 10
            }
        }
    });
    /*$("#dropdown").hover(function ()
    {
        var $this = $(this);

        $this.text("Add to Cart").css("font-weight", "bold");
    }, function ()
    {
        var $this = $(this);
        $this.text('19.00 KD').css("font-weight", "bold");
    });*/

    $(document).ready(function ()
    {
        userAddress();

        $(document).on("click", ".store-category", function ()
        {
        	var storeCategoryId = $(this).data('category-id');
        	var storeId = $('#storeId').val();

        	var data = {
                    "storeId": storeId,
                    "storeCategoryId": storeCategoryId,
                };

            $.ajax({
                url: "{{ url(route('ajaxStoreCategoryProducts')) }}",
                dataType: 'text',
                type: 'post',
                contentType: 'application/x-www-form-urlencoded',
                data: data,
                start: function (e) {
                    $.blockUI(
                            {
                                message: "<img src='{{ url('assets/loader.gif') }}' class='loaderGif'  style='height: 100px;width: 100px;'/>",
                                centerX: true,
                                centerY: true,

                            });
                },
                success: function (data, textStatus, jQxhr) {
                	$('#store-products-data').html(data);
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        });
            
        $(document).on("click", ".clsRate", function ()
        {
            var rating = $(this).attr("rating");
            $(document).find(".clsRate").each(function (key, input)
            {
                if ($(input).attr("rating") <= rating)
                {
                    $(input).find(".fa-star").removeClass("unchecked").addClass("checked");
                }
                else
                {
                    $(input).find(".fa-star").removeClass("checked").addClass("unchecked");
                }
            });
            $(document).find("input.rating").val(rating);
        });
    });
    function viewRatingModel()
    {
        $("#storeRatingModal").modal("show");
    }
</script>
@endsection


