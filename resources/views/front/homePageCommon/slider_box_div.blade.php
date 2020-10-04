<div class="item {{ ($k==0)?"active":"" }}">
    <img src="{{ url('doc/collection_image/'.$collection->background_image) }}" alt="{{ $collection->collection_name }}">
    <div class="carousel-caption collection-text">
        <h1 >{{ $collection->collection_name }}</h1>
        <p> {!!$collection->collection_tagline!!}</p>
        <form method="get" action="{{url(route('searchProduct'))}}">
            <div class="col-sm-offset-2 col-sm-6">
                <input type="text" autocomplete="off" class="form-control search-term2 srch-term2" placeholder="I'm shopping for..." name="keyword" required>
                {{--<div class="hidden base base-width search-div2" id="search-div2">
                    <ul id="search-parm2" class="search-parm search-parm2"></ul>
                </div>--}}
            </div>
            <div class="col-sm-1" style="">
                <div  class="input-group-btn">
                    <button class="btn btn-default srch-term-banner-home" type="submit">SEARCH</button>
                </div>
            </div>
        </form>
    </div>
</div>