@extends('front.layout.index')
@section('title') Contact Us @endsection

@section('meta')

@endsection

@section('content')
    <div class="container-fluid" id="Contact">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                CONTACT US
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url(route('home')) }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span ><a class="home_myaccount">Contact Us&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">Submit a Request</span>
            </div>
        </div>
    </div>

    <div class="container-fluid" >
        <div class="container">
            {{--<div class="col-sm-3 panel1">
                <legend class="query">SELECT YOUR QUERY</legend>
                <div class="panel-group accordion-2a">
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a01">
                            <h4 class="panel-title">Shopping & Ordering<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a01" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Nec tristique! Odio sit turpis ac sit magna, non. Elementum ultrices tristique, rhoncus lectus, turpis ac, purus magna! Et massa pulvinar ridiculus dignissim. Egestas</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a02">
                            <h4 class="panel-title">Payments & Discounts<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a02" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Jarlsberg croque monsieur say cheese. Stilton cheddar cheese and biscuits pecorino cream cheese cheese triangles rubber cheese jarlsberg. Macaroni cheese cheese strings cheese slices parmesan bavarian bergkase chalk and cheese fondue parmesan. Parmesan macaroni cheese rubber cheese who moved my cheese hard cheese who moved my cheese the big cheese.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a03">
                            <h4 class="panel-title">Changing Your Order<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a03" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Roquefort fondue queso. Cheeseburger cheese and biscuits cheesecake cheese strings cheddar say cheese mascarpone halloumi. Macaroni cheese feta fromage frais cheese and biscuits cheesecake cauliflower cheese emmental pecorino. Jarlsberg cut the cheese brie who moved my cheese when the cheese comes out everybody's happy monterey jack squirty cheese.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a04">
                            <h4 class="panel-title">Shipping Issues<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a04" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Roquefort fondue queso. Cheeseburger cheese and biscuits cheesecake cheese strings cheddar say cheese mascarpone halloumi. Macaroni cheese feta fromage frais cheese and biscuits cheesecake cauliflower cheese emmental pecorino. Jarlsberg cut the cheese brie who moved my cheese when the cheese comes out everybody's happy monterey jack squirty cheese.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a05">
                            <h4 class="panel-title">Returns & Exchanges<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a05" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Roquefort fondue queso. Cheeseburger cheese and biscuits cheesecake cheese strings cheddar say cheese mascarpone halloumi. Macaroni cheese feta fromage frais cheese and biscuits cheesecake cauliflower cheese emmental pecorino. Jarlsberg cut the cheese brie who moved my cheese when the cheese comes out everybody's happy monterey jack squirty cheese.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a06">
                            <h4 class="panel-title">Troubleshooting<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a06" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Roquefort fondue queso. Cheeseburger cheese and biscuits cheesecake cheese strings cheddar say cheese mascarpone halloumi. Macaroni cheese feta fromage frais cheese and biscuits cheesecake cauliflower cheese emmental pecorino. Jarlsberg cut the cheese brie who moved my cheese when the cheese comes out everybody's happy monterey jack squirty cheese.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading" data-toggle="collapse" data-parent=".accordion-2a" href="#a2-a07">
                            <h4 class="panel-title">Getting Started<i class="fa fa-plus pull-right"></i></h4>
                        </div>
                        <div id="a2-a07" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Roquefort fondue queso. Cheeseburger cheese and biscuits cheesecake cheese strings cheddar say cheese mascarpone halloumi. Macaroni cheese feta fromage frais cheese and biscuits cheesecake cauliflower cheese emmental pecorino. Jarlsberg cut the cheese brie who moved my cheese when the cheese comes out everybody's happy monterey jack squirty cheese.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>--}}
            {{--<div class="col-sm-9 panel1">
                <legend class="address">ADDRESS CHANGES</legend>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s.</p>
                <div>
                    <button type="button" id="submitAddress"  class="btn btn-default">Submit an address change request</button></div>
                <p>Please note: Because your address determines where your order is made, we can't change your delivery country.</p>
            </div>--}}
            <div class="col-sm-12"><hr></div>
            <div class="col-sm-12" id="Customer">
                <div class="centerr-align">
                    {{--<h3>Can't find what you're looking for?</h3>--}}
                    <a href="{{ url('contact-us') }}" class="button-btn">Submit a Request</a>
                    <span>or</span>
                    <a href="tel:+96599229889" class="button-btn">Call our Customer Care</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        $('.i-accordion').on('show.bs.collapse', function (n)
        {
            $(n.target).siblings('.panel-heading').find('.panel-title i').toggleClass('fa-chevron-down fa-chevron-up');
        });
        $('.i-accordion').on('hide.bs.collapse', function (n)
        {
            $(n.target).siblings('.panel-heading').find('.panel-title i').toggleClass('fa-chevron-up fa-chevron-down');
        });

        /* P */
        $('.accordion-2a, .accordion-2b, .accordion-3').on('show.bs.collapse', function (n)
        {
            $(n.target).siblings('.panel-heading').find('.panel-title i').toggleClass('fa-minus fa-plus');
        });
        $('.accordion-2a, .accordion-2b, .accordion-3').on('hide.bs.collapse', function (n)
        {
            $(n.target).siblings('.panel-heading').find('.panel-title i').toggleClass('fa-plus fa-minus');
        });

    </script>
@endsection