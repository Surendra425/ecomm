@extends('layouts.app')
@section('title') Sell with Us @endsection
@section('content')

    <div class="container-fluid" id="marketPlace">
        <div class="container">
            <h1>A MarketPlace Where you Discover your Products</h1>
            <a href="#" class="launch-modal" data-modal-id="modal-video"><img src="{{ url('assets/frontend/images/play_icon.png') }}"></a>
            {{--<div class="joinTodaybutton">
                <button class="btn btn-default" type="button" onclick="window.location='{{ url(route('vendorRegister')) }}'">Join Today</button>
            </div>--}}
            <div class="modal fade" id="modal-video" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-video">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <video class="embed-responsive-item" src="{{ url('assets/frontend/images/Shopping.mp4') }}" controls webkitallowfullscreen mozallowfullscreen allowfullscreen ></video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container" id="Query">

                <legend class="">REQUEST FOR VENDOR</legend>
            <form class="m-login__form m-form" action="{{ route('vendorRegisterProcess') }}" method="POST" id="vendor_register">
                    {{ csrf_field() }}
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="asteric">*</span></label>
                        <input class="form-control m-input" type="text" placeholder="First Name" name="first_name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="asteric">*</span></label>
                        <input class="form-control m-input" type="text" placeholder="Last Name" name="last_name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="asteric">*</span></label>
                        <input class="form-control m-input" type="email" placeholder="Email" id="email" name="email" autocomplete="off">
                    </div>
                        <div class="form-group">
                            <label for="mobile_no">Mobile <span class="asteric">*</span></label>
                            <input class="form-control m-input phonNumberOnly" type="text" placeholder="Mobile No" id="mobile_no" name="mobile_no" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="store">Store Name <span class="asteric">*</span></label>
                            <input type="text" id="store" name="store" class="form-control" placeholder="Store Name">
                        </div>
                        <div class="form-group">
                            <label for="subject">Password <span class="asteric">*</span></label>
                            <input class="form-control m-input" type="password" placeholder="Password" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="description">Confirm Password <span class="asteric">*</span></label>
                            <input class="form-control m-input" type="password" placeholder="Confirm Password" name="password_confirmation">
                        </div>
                    </div>
                    <div class="col-sm-12">
                    <div class="form-group">
                        <button id="submit" type="submit" value="submit" class="btn btn-primary btn-block">SUBMIT</button>
                    </div>
                        <div class="form-group center-block align-content-center">
                            Already Have Vendor Account ? <a href="{{ route('vendorLogin') }}">Sign In</a>
                    </div>
                    </div>
                </form>
                <div class="hr"><hr></div>

        </div>
    </div>
    <div class="container-fluid hide">
        <div class="container">
            <legend class="salesPlan">OUR SALES PLAN</legend>
            <form id="formPlan" action="{{ url(route('sell-with-us.store')) }}" class="form" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    @foreach($Plans as $k=>$plan)
                    <div class="col-sm-5 sales sales{{ $k+1 }}">
                        <h3>{{ $plan['plan_name'] }}</h3>
                        <p>{{ $plan['sales_percentage'] }}% from the sales</p>
                        <ul class="radioButtons">
                            @foreach($plan['Options'] as $j=>$planoption)
                            <li class="radioGrp">
                               {{-- <input type="radio" class="plan_option" id="selected_plan_options{{ $k.$j }}" name="selected_plan_options" value="{{ $planoption['id'] }}">--}}
                                <label for="selected_plan_options{{ $k.$j }}">{{ number_format($planoption['price'],2) }} KD/{{ $planoption['duration'] }}</label>
                            </li>
                            @endforeach
                        </ul>
                        {{--<div class="planButton1">
                            <button type="submit" class="planButton" >Select Plan</button>
                        </div>--}}
                    </div>
                    @endforeach
                </div>
            </form>
            <div class="col-sm-12 sellustext">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since
                the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </div>
        </div>
    </div>

@endsection
@section('js')
<script type="text/javascript">
    $('.launch-modal').on('click', function (e)
    {
        e.preventDefault();
        $('#' + $(this).data('modal-id')).modal();
    });

    $("#modal-video").click(function (event)
    {
        if (!$(event.target).closest('.modal-sm').length)
        {
            $(this).modal('hide');
        }

    });
    $(document).ready(function ()
    {
        var IsSubmit = false;
        $(document).on("click", ".planButton", function ()
        {
            var val = $(this).closest(".sales").find(".plan_option:checked").val();
            if (!val)
            {
                alert("Please Select atleast one plan option");
                IsSubmit = false;
            }
            else
            {
                IsSubmit = true;
            }
        });
        $("#formPlan").submit(function ()
        {
            return IsSubmit;
        });
    });
    $(document).ready(function ()
    {
        $("#vendor_register").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                store: {
                    required: true
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                mobile_no: {
                    required: true,
                    minlength: 8,
                    number:true,
                    remote: {
                        url: baseUrl+'/check/uniqueVendor/users/mobile_no',
                        type: "post",
                        data: {
                            value: function() {
                                return $( "#mobile_no" ).val();
                            },
                        },
                    }
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: baseUrl+'/check/uniqueVendor/users/email',
                        type: "post",
                        data: {
                            value: function() {
                                return $( "#email" ).val();
                            },
                        },
                    }
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                agree: {
                    required: true
                },
            },
            messages: {
                first_name: {
                    required: "First Name is required",
                    minlength: "First Name have atleast 2 character"
                },
                store: {
                    required:  "Store Name is required",
                },
                last_name: {
                    required: "Last Name is required",
                    minlength: "Last Name have atleast 2 character"
                },
                mobile_no: {
                    required: "Phone Number is required",
                    minlength: "Phone Number have atleast 8 character",
                    number: "Invalid phone number",
                    remote: "Phone Number is already taken."
                },
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address.",
                    remote: "Email is already taken."
                },
                password: {
                    required: "Password is required",
                    minlength: "Password have atleast 6 character"
                },
                password_confirmation: {
                    required: "Confirm password is required",
                    equalTo: "Password and confirm password not match"
                },
            },
            submitHandler: function (form)
            {
                form.submit();
            }
        });
    });
</script>
@endsection

