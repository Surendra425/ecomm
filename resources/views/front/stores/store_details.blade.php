@extends('front.layout.index')
@section('title') {{ $store->store_name }} @endsection

@section('meta')

@endsection

@section('css')
@endsection

@section('content')
    <div class="container-fluid" id="dashboardBanner" style="background-image:url('{{isset($store->banner_image) && $store->banner_image != '' ?  url('doc/store_banner_image/').'/'. $store->banner_image: url('assets/frontend/images/no_image_1920.png') }}')">
        <div class="full-overlay">
            <div class="container" >
                <div class="col-sm-8 col-lg-7">
                    <a class="user_pic">
                        <img alt="img-circle" class="img-circle" height="100" width="100" src="{{ ($store->store_image != "")? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
                    </a>
                    <div id="followunfolllow1">
                    <span class="textDashboard">
                        <a class="st_name">
                            <b>{{ $store->store_name }}</b>
                            ({{ $store->country }} )
                        </a>
                        @php
                            $RateUrl = (empty(Auth::guard('customer')->user()))?(url(route('login'))):"javascript:viewRatingModel()";
                        @endphp
                        <span class="rt_star">
                        @for($i=1;$i<=5;$i++)

                                <a href="{{ $RateUrl }}">
                                    <span class="fa {{ ($store->rating >= $i)?("fa-star checked"):((($store->rating > ($i-1)) && ($store->rating < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;</a>
                            @endfor
                         </span>
                        <a class="fl_count"><span>({{ $store->rate_count }})</span></a>
                    </span>
                    </div>
                </div>
                <div class="col-sm-4 col-lg-5 col-xs-12">
                    <div class="search-data">
                        <div class="col-xs-12">
                            <div class="search-box">
                                <input type="hidden" name="storeSlug" id="storeSlug" value="{{$store->store_slug}}">
                                <input type="hidden" name="storeId" id="storeId" value="{{$store->vendor_id}}">
                                <form id="searchKey1" method="get" action="{{url(route('searchProduct',['store' => $store->store_slug]))}}">
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
                                        <img alt="time_icon" src="{{ url('assets/frontend/images/time_icon.png') }}">
                                    </a>
                                    @if(!empty($store->delivery_time))

                                        <span class="event-text" id="from">{{$store->delivery_time}}</span>
                                    @else
                                        @if(empty($userAddress))
                                            <a href="{{ url(route('address.create',['store_slug'=>$store->store_slug])) }}" class="btn btn-xs  btn-success seller-btn" id="btnAddress">Add Address</a>
                                        @else
                                            <p class="event-text" id="addressHasFrom"> - </p>
                                        @endif
                                    @endif
                                </li>
                                <li class="col-xs-2">
                                    <a>
                                        <img alt="charges_icon" src="{{ url('assets/frontend/images/charges_icon.png') }}">
                                    </a>
                                    @if(!empty($store->delivery_charge))
                                        <span class="event-text" id="charge">
                                                @if($store->delivery_charge != 0)
                                                    {{(float)($store->delivery_charge)}}&nbsp;KD
                                                @else
                                                    Free
                                                @endif

                                        </span>
                                    @else
                                        @if(!empty($userAddress))
                                            <p class="event-text" id="addressHasCharge"> - </p>
                                        @endif
                                    @endif
                                </li>

                                <li class="col-xs-2">
                                    <a>
                                        <img alt="status_icon" src="{{ url('assets/frontend/images/status_icon.png') }}">
                                    </a>
                                    <span class="event-text">{{ $store->store_status }}</span>
                                </li>
                                <li class="col-xs-2">
                                    <a href="{{ url(route('sellerAboutUs',['store'=>$store->store_slug])) }}">
                                        <img alt="about_icon" src="{{ url('assets/frontend/images/about_icon.png') }}">
                                    </a>
                                    <span class="event-text">About Us</span>
                                </li>
                                @php
                                    $followUrl = (empty(Auth::guard('customer')->user()))?(url(route('login'))):"javascript:followUnFollow();";
                                @endphp
                                @if($store->is_follow ==1)
                                    <li class="col-xs-2">
                                        <a href="{{$followUrl}}">
                                            <img alt="unfollow_icon" src="{{ url('assets/frontend/images/unfollow_icon.png') }}">
                                        </a>
                                        <span class="event-text">Following</span>
                                    </li>

                                @else
                                    <li class="col-xs-2">
                                        <a href="{{$followUrl}}">
                                            <img alt="follow_icon" src="{{ url('assets/frontend/images/follow_icon.png') }}">
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
            <legend class="mg-0 ">CATEGORIES</legend>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($storeCategories as $item)
                        <div class="swiper-slide">
                            <div class="item">
                                <a class="store-category" data-category-id="{{ $item->id }}" data-product-count="{{$item->product_count}}" href="JavaScript:void(0);">
                                    <img alt="responsive-circle" src="{{ ($item->category_image != "") ? url('doc/category_image/').'/'.$item->category_image:url('assets/app/media/img/no_category_image_100.png') }}" class="img-responsive img-circle" >
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
                <legend class=" mg-0">FEEDS</legend>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="imgBorder-1">
        <div id="products" class="container Dashboard">
            <!-- begin::Recent Product -->
        @include('front.common.products')
        <!-- end:: Recent Product -->
        </div>
    </div>

    <!-- Modal -->
    <div id="storeRatingModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form method="POST" action="{{ url(route('rateStore',['store'=>$store->id])) }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ ((!empty($customer))?($customer->id):"") }}">
                    <input type="hidden" name="store_id" value="{{$store->id}}">
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
                                    <a data-rating="{{ $i }}" class="clsRate"><span class="fa {{ ($store->user_store_rating >= $i)?("fa-star checked"):((($store->user_store_rating > ($i-1)) && ($store->user_store_rating < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;</a>
                                @endfor
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit btn-success">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('js')
    <script>
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

        $(document).ready(function(){

            var url  = "{{url(route('showStoreProducts'))}}";
            var page = 2;
            var lastPage = {{$products->lastPage()}}

            $(window).scroll(function () {
                if($('body').hasClass('overflow-hidden')){
                    return false;
                }
                if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    if (lastPage >= page) {
                        var data = collection = {'page': page, 'store_id':{{$store->id}},'store_category_id':store_category_id}
                        
                        loadProducts(url, data, lastPage, 0);
                        page++;
                    }
                }
            });
            $(document).ajaxStart(function ()
            {
                $(".loader").show();
            });
            var store_category_id = '';
            $(".store-category").on('click',function(){
                var page = 1;
                store_category_id = $(this).data('category-id');
                lastPage = Math.ceil($(this).data('product-count')/{{$perPage}});
                var data = collection = {'page': page, 'store_id':{{$store->id}},'store_category_id':$(this).data('category-id')}
                $('#products').html('');

                loadProducts(url, data, lastPage, 0);
                page++;
            });

            $(document).on('click','#storeRatingModal .btn-submit',function(){

                if($('input[type="hidden"][name="rating"]').val()<=0){
                    $('#storeRatingModal').modal('hide');
                    return ;
                }
                var param = {
                    'store_id':$('input[type="hidden"][name="store_id"]').val(),
                    'user_id':$('input[type="hidden"][name="user_id"]').val(),
                    'rating':$('input[type="hidden"][name="rating"]').val()
                };

                $.ajax({
                    url : '{{ url(route('rateStore')) }}',
                    type: 'post',
                    data: param,
                    success:function(response){
                        if(response.status){
                            toastr.success(response.message,'Success');
                            $('input[type="hidden"][name="rating"]').val(0);
                            $('#followunfolllow1').load(location.href + ' #followunfolllow1>*');
                        }else{
                            toastr.error(response.message,'Error');
                        }
                        $('#storeRatingModal').modal('hide');

                    }
                });
            });
        });

        function viewRatingModel()
        {
            $("#storeRatingModal").modal("show");
        }

        function followUnFollow()
        {
            $.ajax({
                url:'{{ url(route('followUnFollowStore')) }}',
                type:'post',
                data:'store_id='+{{$store->id}},
                success:function(response){

                    $('#deleveryDetails').load(location.href + " #deleveryDetails>*");

                }
            });
        }

    </script>

@endsection