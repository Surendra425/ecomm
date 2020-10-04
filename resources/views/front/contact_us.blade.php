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
    <div class="container-fluid">
        <div class="container" id="Query">
            <div class="col-sm-6">
                <legend class="">SUBMIT A QUERY</legend>
                <form method="post" id="formContact">
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
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#formContact").validate({
                rules: {
                    "email": {
                        required: true,
                        email: true,
                        regex:emailpattern,
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
                        email: "Please enter valid email address",
                        regex:"Please enter valid email address"
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