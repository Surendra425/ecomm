@foreach($stores as $k => $store)

<div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 bestSellers">
    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
        {{--  <img alt="media" class="img-responsive" src="{{ url('doc/store_image/'.(($store->store_image != "")?$store->store_image:"store.png")) }}"  alt="Lamp Decor Collection"> --}}
        <img alt="media" class="img-responsive" src="{{ ($store->store_image != "")? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
    </a>
    <p class="card-3-text">
        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
            {{ $store->store_name }}
        </a>
    </p>
</div>

@endforeach