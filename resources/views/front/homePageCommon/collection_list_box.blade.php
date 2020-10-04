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