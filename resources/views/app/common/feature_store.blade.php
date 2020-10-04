
@if(count($FeatureStore) > 0)
<div class="container-fluid" id="column-width">
    <div class="container">

        <div  class="swiper-container">
            <div class="swiper-wrapper">

                @foreach($FeatureStore as $k => $store)
                    <div class="swiper-slide feature-store-item" style="border-radius: 15px;">

                        <div class="item featuredStore">
                            <div class="imgContainer-1">
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                    <img class="img-responsive img-border-set" src="
                                    {{$store->banner_image != '' ?  url('doc/store_banner_images_front/'). '/'.$store->banner_image: url('assets/frontend/images/no_image_1920.png') }}">
                                </a>
                                <div class="profile">
                                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                        <img class="img-circle" src="{{$store->store_image != "" ? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png')}}">
                                    </a>
                                <span class="text">
                                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                        {{ $store->store_name }}
                                    </a>
                                </span>
                                </div>
                                <div class="rating">
                                    @for($i=1;$i<=5;$i++)
                                        <a href="javascript:void(0)"><span class="fa {{ ($store->rating >= $i)?("fa-star checked"):((($store->rating > ($i-1)) && ($store->rating < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;</a>
                                    @endfor
                                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}"><span>({{ $store->country }})</span></a>

                                </div>
                                <div class="text-followers">
                                    <span class="Joined"><a>Joined {{ date("Y",strtotime($store->created_at)) }}</a></span>
                                </div>

                                <div class="images">
                                    @foreach($store->products as $k => $product)
                                        @if($k==3)
                                            <div class="col-sm-3 text-center">
                                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                                    <img class="img-responsive feature-store-product" src="{{ url('doc/product_image/'.((isset($product->images[0]) && $product->images[0]->image_url!="")?$product->images[0]->image_url:"product.png")) }}">
                                                </a>
                                                <span class="num"><a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">+{{ count($store->products)-3  }} </a></span>
                                            </div>
                                            @php break; @endphp
                                        @else
                                            <div class="col-sm-3 text-center">
                                                <a href="{{ url(route('sellerProductsDetail',['productSlug'=> $product->product_slug])) }}" >
                                                    <img class="img-responsive feature-store-product" src="{{ url('doc/product_image/'.((isset($product->images[0]) && $product->images[0]->image_url!="")?$product->images[0]->image_url:"product.png")) }}">
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@else
    <div class="container-fluid" id="column-width" style="background-color: #ffffff;">
        <div class="container">
    <div class="noproducts">
        <h3>No Stores Available</h3>
        </div>
            </div>
    </div>
@endif