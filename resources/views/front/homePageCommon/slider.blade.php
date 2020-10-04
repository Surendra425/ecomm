
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @php //dd($sliders); @endphp
        @foreach($sliders as $k => $collection)
            @include('front.homePageCommon.slider_box_li')
        @endforeach
    </ol>
    <div class="carousel-inner" role="listbox">
        @foreach($sliders as $k => $collection)
            @include('front.homePageCommon.slider_box_div')
        @endforeach
    </div>
</div>