@extends('layouts.app')
@section('title') {{ $event->title }} @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/slider.css') }}">
@endsection
@section('meta')
    <meta name="description" content="{{ strip_tags($event->description) }}"/>
    <meta property="og:url" content="{{ route('EventDetails', ['eventSlug' => $event->slug])}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{ $event->title }}"/>
    <meta property="og:description" content="{{ strip_tags($event->description) }}"/>
    <meta property="og:image" content="{{ !empty($event->media[0]) ? url('doc/events/images_temp/'.$event->media[0]->file)  : url('assets/app/media/img/no-images.jpeg') }}"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $event->title }}">
    <meta name="twitter:description" content="{{ strip_tags($event->description) }}">
    <meta name="twitter:image" content="{{ !empty($event->media[0]) ? url('doc/events/images_temp/'.$event->media[0]->file) : url('assets/app/media/img/no-images.jpeg') }}">
@endsection

@section('content')

    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ route('EventDetails', ['eventSlug' => $event->slug])}}"
                         class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">{{ $event->title }}</span>
            </div>
        </div>
    </div>
<div class="container">
        <div class="col-sm-6 pd-m-0">
            <!-- Slider for MobileView -->
            <div id="bs-carousel" class="carousel fade-carousel slide desktop-hide" data-ride="carousel"
                 data-wrap="false" data-interval="false">
                <div class="overlay"></div>
                <ol class="carousel-indicators">
                    @foreach($event->media as $k=> $image)
                        <li data-target="#bs-carousel" data-slide-to="{{ $k }}"
                            class="{{ "item".$k }} {{ ($k==0)?"active":"" }}">
                        </li>
                    @endforeach
                </ol>

                <div class="carousel-inner prvw">
                    @foreach($event->media as $k=> $media)
                        @if($media->type == 'image')
                        <div class="item {{ ($k==0)? "active" : "" }}">
                            <img src="{{ url('doc/events/images_temp/'.$media->file) }}">
                        </div>
                        @else
                        <div class="item {{ ($k==0)? "": "" }}">
                            
                            <video width="100%" height="auto" controls="">
                                 <source loop controls='true' src="{{ url('doc/events/videos').'/'.$media->file }}"
                                          type="video/mp4">                                      
                            </video>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- Slider for MobileView End-->
            <div class="product-slider">
                <div id="carousel" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach($event->media as $k => $media)
                            <div class="item {{ ($k==0)?"active":"" }}">
                                @if($media->type == 'image')
                                    <img src="{{ url('doc/events/images_temp/'.$media->file) }}">
                                @else
                                    <video width="100%" height="auto" controls="">
                                         <source loop controls='true' src="{{ url('doc/events/videos').'/'.$media->file }}"
                                                  type="video/mp4">                                      
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="clearfix">
                    <div id="thumbcarousel" class="carousel slide" data-interval="false">
                        <div class="carousel-inner">
                            @foreach($event->media as $k => $media)
                                <div class="item {{ ($k==0) ? "active":"" }}">
                                    <div data-target="#carousel" data-slide-to="{{ $k }}" class="thumb">
                                        @if($media->type == 'image')
                                            <img src="{{ url('doc/events/images_temp/'.$media->file) }}">
                                        @else
                                            <video width="110" height="110">
                                                 <source loop controls='true' src="{{ url('doc/events/videos').'/'.$media->file }}"
                                                          type="video/mp4">                                      
                                            </video>
                                            
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($event->media->count() > 4)
                            <a class="left" href="#thumbcarousel" role="button" data-slide="prev">
                                <i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
                            </a>
                            <a class="right" href="#thumbcarousel" role="button" data-slide="next">
                                <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <a class="left">
                                <i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
                            </a>
                            <a class="right">
                                <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>
                            </a>

                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!-- Content For Mobile View -->
        <div class="col-sm-6 desktop-hide">
            <div class="product-layer">
                <figcaption>
                    {{ $event->title }}
                </figcaption>
                
            </div>
            <div class="wrap figure-info">
                <h2 class="tit">Description</h2>
                <div class="description">
                    <div class="row">
                        <div class="col-md-12" style="overflow-x: auto">
                            {!!$event->description!!}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="col-sm-6 mobile-hide">
            <div class="col-sm-12 col-xs-12 pd-0">
                <h3 class="Adidas-product">{{ $event->title }}</h3>
                
                <div class="description">
                    <div class="row">
                        <div class="col-md-12" style="overflow-x: auto">
                            {!!$event->description!!}
                        </div>
                    </div>
                </div>
                
                <div class="description">
                    <div class="row">
                        <div class="col-md-12" style="overflow-x: auto">
                            @if(!empty($event->address))
                              <strong>Address: </strong> {{ $event->address }}
                              <br>
                            @endif
                            
                            @if(!empty($event->contact_number))
                              <strong>Contact Number: </strong> <a href="tel:{{ $event->contact_number }}">{{ $event->contact_number }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
@endsection
@section('js')
    {{--<script type="text/javascript" src="{{url('assets/frontend/js/owl.carousel.js')}}"></script>--}}
    <script type="text/javascript" src="{{url('assets/frontend/js/carousel-swipe.js')}}"></script>
    <script type="text/javascript">

        var totalItems = $('#thumbcarousel .carousel-inner .item').length;

        $('#thumbcarousel .carousel-inner .item').each(function () {
            var itemToClone = $(this);
            // alert(totalItems);
            if (totalItems > 4) {
                for (var i = 1; i < 4; i++) {
                    itemToClone = itemToClone.next();

                    // wrap around if at end of item collection

                    if (!itemToClone.length) {
                        itemToClone = $(this).siblings(':first');
                    }

                    // grab item, clone, add marker class, add to collection
                    itemToClone.children(':first-child').clone()
                            .addClass("cloneditem-" + (i))
                            .appendTo($(this));
                }
            }
            else {
                for (var i = 1; i < totalItems; i++) {
                    itemToClone = itemToClone.next();

                    // wrap around if at end of item collection

                    if (!itemToClone.length) {
                        itemToClone = $(this).siblings(':first');
                    }

                    // grab item, clone, add marker class, add to collection
                    itemToClone.children(':first-child').clone()
                            .addClass("cloneditem-" + (i))
                            .appendTo($(this));
                }
            }
        });
        var fixed = document.getElementById("thumbcarousel");

        fixed.addEventListener('mousewheel', function (e) {
            e.preventDefault();
        }, false);
        $(".viewmore-content .col-lg-3.col-md-3.col-sm-4.col-xs-6").hide();
        $("#viewmore button").on('click', function () {
            $(".viewmore-content .col-lg-3.col-md-3.col-sm-4.col-xs-6").show();
            $(this).hide();
        });
        $('.readMore').click(function () {
            if ($(".learnMore").hasClass("collapse in")) {
                $(this).text('Read more');
            }
            else {
                $(this).text('Show Less');
            }
        });
        $(document).ready(function () {
            $("#bs-carousel").carousel({
                wrap: false,
            });

            
            $('#thumbcarousel').bind('mousewheel', function (e) {
                if (e.originalEvent.wheelDelta / 120 > 0 || e.originalEvent.detail < 0) {
                    $(this).carousel('next');
                }
                else {
                    $(this).carousel('prev');
                }
            });
            
            var fixed = document.getElementById("thumbcarousel");
            fixed.addEventListener('mousewheel', function (e) {
                e.preventDefault();
            }, false);
        });
        
        var mySwiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 0,
            freeMode: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            breakpoints: {
                1400: {
                    slidesPerView: 3

                },

                992: {
                    slidesPerView: 2.2
                },

                768: {
                    slidesPerView: 2


                },
                736: {
                    slidesPerView: 2.5
                },
                640: {
                    slidesPerView: 2.5
                },
                568: {
                    slidesPerView: 2.5
                },

                480: {
                    slidesPerView: 2.5

                }

            }

        });
    </script>
@endsection