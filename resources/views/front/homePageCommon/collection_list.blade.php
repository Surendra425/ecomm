<div class="container">
    <h4 class="border"><b>COLLECTIONS</b></h4>
    <p class="border-para">Keeps You Updated</p>
    <div class="row">
        @foreach($collections as $k => $collection)
            @include('front.homePageCommon.collection_list_box')
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