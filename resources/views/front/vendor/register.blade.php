@extends('front.layout.index')
@section('title') Sell With Us @endsection

@section('meta')

@endsection

@section('content')

    <div class="container-fluid" id="marketPlace">
        <div class="container">
            <h1>A MarketPlace Where you Discover your Products</h1>
            <a href="#" class="launch-modal" data-modal-id="modal-video"><img alt="play_icon" src="{{ url('assets/frontend/images/play_icon.png') }}"></a>
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
                                    <video class="embed-responsive-item" src="{{ url('assets/frontend/images/Shopping.mp4') }}" controls ></video>
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

            <h3 class="legend">REQUEST FOR VENDOR</h3>
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
                        <input class="form-control m-input numeric phonNumberOnly" type="text" placeholder="Mobile No" id="mobile_no" name="mobile_no" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="store_name">Store Name <span class="asteric">*</span></label>
                        <input type="text" id="store_name" name="store_name" class="form-control" placeholder="Store Name">
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

@endsection
@section('js')
    <script>

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
            $("#vendor_register").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    store_name: {
                        required: true
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    mobile_no: {
                        required: true,
                        minlength: 7,
                        maxlength: 17,
                        number:true,
                        remote: {
                            url: baseUrl+'/check/unique/users/mobile_no',
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
                        regex:emailpattern,
                        remote: {
                            url: baseUrl+'/check/uniqueNotGuest/users/email',
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
                        minlength: "First Name have atleast {0} character"
                    },
                    store_name: {
                        required:  "Store Name is required",
                    },
                    last_name: {
                        required: "Last Name is required",
                        minlength: "Last Name have atleast {0} character"
                    },
                    mobile_no: {
                        required: "Mobile Number is required",
                        minlength: "Mobile Number have atleast {0} character",
                        maxlength: "Mobile Number not greater than {0} character",
                        number: "Invalid phone number",
                        remote: "Phone Number is already taken."
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address.",
                        regex: "Please enter a valid email address.",
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