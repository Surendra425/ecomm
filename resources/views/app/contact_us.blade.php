@extends('layouts.app')
@section('title') Contact Us @endsection
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

    <div class="container-fluid">
        <div class="container" id="Query">
            <div class="col-sm-6">
                <legend class="">SUBMIT A QUERY</legend>
                <form method="post" id="formContact" action="{{route('storeContactUs')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">Your email address <span class="asteric">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" data-validation="email">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject <span class="asteric">*</span></label>
                        <input id="subject" name="subject" class="form-control" type="text" data-validation="required" placeholder="Enter subject">
                    </div>
                    <div class="form-group">
                        <label for="description">Description <span class="asteric">*</span></label>
                        <textarea class="Description" rows="6" name="description" id="description" placeholder="Description"></textarea>
                    </div>
                    <div class="form-group">
                        <button id="submit" type="submit" value="submit" class="btn btn-primary btn-block">SUBMIT</button>
                    </div>
                </form>
                <div class="hr"><hr></div>
            </div>
            <div class="col-sm-1">
                <div class="verticalContactUs"></div>
            </div>
            <div class="col-sm-5" id="Log-In">
                <div class="customerSupport">
                    <h5>CONTACT CUSTOMER CARE</h5>
                    <p>
                        If You have Registered your query, You can know
                        your query status by contact to our Customer Care executive.
                        Our contact number is displayed below.
                    </p>
                    <p>
                        <b>Customer Care Number :</b> +965-99229889
                    </p>
                    <a href="tel:+965-99229889" class="btn btn-primary btn-block" >CALL NOW</a>
                    {{--<button id="callNow" type="submit" value="submit" class="btn btn-primary btn-block">CALL NOW</button>--}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5T3UCNDyKcybT7kfiDueQHe4D45pmKg&callback=initMap"></script>
<script type="text/javascript">
var map;
function initMap()
{
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: new google.maps.LatLng(51.5087908, -0.1267527),
        mapTypeId: 'roadmap'
    });

    var iconBase = 'images/';
    var icons = {
        parking: {
            icon: "{{ url('assets/frontend/images/Location1.png') }}"
        },
        library: {
            icon: "{{ url('assets/frontend/images/Location1.png') }}"
        },
        info: {
            icon: "{{ url('assets/frontend/images/Location1.png') }}"
        }
    };

    var features = [
        {
            position: new google.maps.LatLng(51.5087908, -0.1267527),
            type: 'info'
        },
        {
            position: new google.maps.LatLng(51.5087908, -0.1267527),
            type: 'library'
        }
    ];

    // Create markers.
    features.forEach(function (feature)
    {
        var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
        });
    });
}


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

<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#formContact").validate({
            rules: {
                "email": {
                    required: true,
                    email: true
                },
                "subject": {
                    required: true
                },
                "description": {
                    required: true
                }
            },
            messages: {
                "email": {
                    required: "Email address is required",
                    email: "Please enter valid email address"
                },
                "subject": {
                    required: "Subject is required",
                },
                "description": {
                    required: "Description is required",
                }
            },
        });

    });
</script>
@endsection