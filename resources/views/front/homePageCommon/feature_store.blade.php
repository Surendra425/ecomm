@if(count($featuredStores) > 0)
    <div class="container-fluid" id="column-width">
        <div class="container">

            <div  class="swiper-container">
                <div class="swiper-wrapper">

                    @foreach($featuredStores as $k => $store)
                        @include('front.homePageCommon.feature_store_box')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
