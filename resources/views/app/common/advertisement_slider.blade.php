@if($advertisement->count())
<div class="container-fluid" id="shopzz" >
    <!-- Indicators -->
    <div id="myCarousel-1" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @for($i=0;$i<$advertisement->count();$i++)
            <li data-target="#myCarousel-1" data-slide-to="{{ $i }}" class="{{ ($i==0)?"active":"" }}"></li>
            @endfor
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            @foreach($advertisement as $k=>$item)
            <div class="item {{ ($k==0)?"active":"" }}">
                <img src="{{ url('doc/advertisement_image/'.((isset($item->background_image) && $item->background_image != "")?$item->background_image:"advertisement.png")) }}" alt="Chania" style="width: 100%;height: 380px;">
                <div class="carousel-caption" >
                    <h1>{{ $item->advertisement_name }}</h1>
                    <h3>{{ $item->advertisement_tagline }}</h3>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif