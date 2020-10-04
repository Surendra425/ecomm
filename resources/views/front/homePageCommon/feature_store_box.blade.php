<div class="swiper-slide feature-store-item" style="border-radius: 15px;">

    <div class="item featuredStore">
        <div class="imgContainer-1">
            <a href="{{ url(route('sellerDetail',['storeSlug'=>$store['store_slug']])) }}">
                <img alt="no_image_1920" class="img-responsive img-border-set" src="
                                    {{$store['banner_image'] != '' ?  url('doc/store_banner_images_front/'). '/'.$store['banner_image']: url('assets/frontend/images/no_image_1920.png') }}">
            </a>
            <div class="profile">
                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store['store_slug']])) }}">
                    <img alt="no_store_image_100" class="img-circle" src="{{$store['store_image'] != "" ? url('doc/store_image/').'/'.$store['store_image']:url('assets/app/media/img/no_store_image_100.png')}}">
                </a>
                                <span class="text">
                                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store['store_slug']])) }}">
                                        {{ $store['store_name'] }}
                                    </a>
                                </span>
            </div>
            <div class="rating">
                @for($i=1;$i<=5;$i++)
                    <a href="javascript:void(0)"><span class="fa {{ ($store['rating'] >= $i)?("fa-star checked"):((($store['rating'] > ($i-1)) && ($store['rating'] < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;</a>
                @endfor
                <a class="name1" href="{{ url(route('sellerDetail',['storeSlug'=>$store['store_slug']])) }}"><span>({{ $store['country'] }})</span></a>

            </div>
            <div class="text-followers">
                <span class="Joined"><a>Joined {{ date("Y",strtotime($store['created_at'])) }}</a></span>
            </div>

            <div class="images">
                @foreach($store['products'] as $k => $product)
                    @if($k==3)
                        <div class="col-sm-3 text-center">
                            <a href="{{ url(route('sellerDetail',['storeSlug'=>$store['store_slug']])) }}">
                                <img alt="product_image" class="img-responsive feature-store-product" src="{{ url('doc/product_image/'.((isset($product['image']) && $product['image']!="")?$product['image']:"product.png")) }}">
                            </a>
                            <span class="num">
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store['store_slug']])) }}" style="{{ (count($store['products'])-4 <= 0)? 'background:none':'' }}">{{ (count($store['products'])-4 > 0)? '+':'' }}{{ (count($store['products'])-4 > 0)? count($store['products'])-4:''  }} </a>
                            </span>
                        </div>
                        @php break; @endphp
                    @else
                        <div class="col-sm-3 text-center">
                            <a href="{{ url(route('showProductDetails',['productSlug'=> $product['product_slug']])) }}" >
                                <img alt="product" class="img-responsive feature-store-product" src="{{ url('doc/product_image/'.((isset($product['image']) && $product['image']!="")?$product['image']:"product.png")) }}">
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>