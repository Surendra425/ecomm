<div class="container">
    <h4 class="border"><b>COLLECTIONS</b></h4>
    <p class="border-para">Keeps You Updated</p>
    <div class="row">
        @foreach($LatestCollection as $k => $collection)
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 clsItemCollection">
            <div class="CollectionText">
                <a href="{{ url(route('collectionDetail',['collection'=>$collection->collection_slug])) }}">
                    <img class="clsCollectionItem"  src="{{ url('doc/collection_image_temp/'.$collection->background_image) }}" alt="{{ $collection->collection_name }}">
                </a>
                <p class="card-1-text">
                    <a href="{{ url(route('collectionDetail',['collection'=>$collection->collection_slug])) }}">{{ $collection->collection_name }}</a>
                </p>
            </div>
        </div>
        @endforeach
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 clsItemCollection">
            <div class="CollectionText">
                <a href="{{ url('collection') }}">
                    <img class="clsCollectionImage" src="{{ url('assets/frontend/images/collection.png') }}" alt="All Collection">
                </a>
                <p class="card-2-text"><a href="{{ url('collection') }}">VIEW ALL COLLECTIONS</a></p>
            </div>
        </div>
    </div>
</div>