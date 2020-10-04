@extends('front.layout.index')
@section('title') My Profile @endsection
@section('content')
    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
        </div>
    </div>
    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title') </span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="collection">
        <div class="container">
            <div class="row" >
                <div class="col-md-6" id="login-page">
                    <div id="legend">
                        <legend class="">My Profile</legend>
                    </div>
                    <form name="myform" method="post" action="{{url(route('profile.store'))}}" id="formUpdateProfile" novalidate="novalidate">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="asteric">*</span></label>
                                    <input id="first_name" name="first_name" class="form-control clsLoginField" value="{{ !empty($customer)?$customer->first_name:"" }}" type="text" data-validation="required" placeholder="First Name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="asteric">*</span></label>
                                    <input id="last_name" name="last_name" class="form-control clsLoginField" value="{{ !empty($customer)?$customer->last_name:"" }}" type="text" data-validation="required" placeholder="Last Name" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mobile_no">Mobile Number <span class="asteric">*</span></label>
                                    <input type="text" id="mobile_no" name="mobile_no" class="form-control phonNumberOnly numeric clsLoginField" value="{{ !empty($customer)?$customer->mobile_no:"" }}" placeholder="Mobile Number"/>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="landline_no">Landline Number</label>
                                    <input type="text" id="landline_no" name="landline_no" class="form-control clsLoginField phonNumberOnly numeric" placeholder="Landline Number" value="{{ !empty($customer)?$customer->landline_no:"" }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="asteric">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control clsLoginField" value="{{ !empty($customer)?$customer->email:"" }}" placeholder="Email Address" data-validation="email" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Gender <span class="asteric">*</span></label>
                                    <div class="m-radio-inline">
                                        <label class="m-radio">
                                            <input type="radio" name="gender" value="Male" {{ !empty($customer) && $customer->gender == 'Male'? 'checked':"" }}> Male
                                            <span></span>
                                        </label>
                                        <label class="m-radio">
                                            <input type="radio" name="gender" value="Female" {{ !empty($customer) && $customer->gender == 'Female'? 'checked':"" }}> Female
                                            <span></span>
                                        </label>
                                    </div>
                                    <label id="gender-error" class="error" for="gender"></label>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="password_confirmation">Age<span class="asteric">*</span></label>
                                     <input id="age" name="age" class="form-control clsLoginField" type="text" value="{{ !empty($customer)?$customer->age:"" }}" placeholder="Age">
                                 </div>
                             </div>--}}
                            <div class="form-group">
                                <button type="submit" value="submit" class="btn btn-primary btn-block">Update Profile</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-sm-1">
                    <div class="vertical"></div>
                </div>
                <div class="col-sm-5 ch_pass_block" id="Log-In">
                    <h5>Change Password</h5>
                    <p>Registering for this site allows you to access your order status
                        and history. Just fill in the fields on left, and we&apos;ll get a new
                        account set up for you in no time. We will only ask you for
                        information necessary to make the purchase process faster
                        and easier.</p>
                    {{-- <span>Already have an account?</span> --}}
                    <a href="{{ url('change-password') }}" class="btn btn-primary btn-block">Change Password</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#formUpdateProfile").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2,
                        maxlength:50,
                    },
                    last_name: {
                        required: true,
                        minlength: 2,
                        maxlength:50,
                    },
                    mobile_no: {
                        required: true,
                        minlength: 7,
                        maxlength: 17,
                        number: true,
                    },
                    landline_no: {
                        minlength: 7,
                        maxlength: 17,
                        number: true,
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },

                },
                messages: {
                    first_name: {
                        required: "First Name is required",
                        minlength: "First Name have atleast 2 character",
                        maxlength: "First Name not greater than {0} character",
                    },
                    last_name: {
                        required: "Last Name is required",
                        minlength: "Last Name have atleast 2 character",
                        maxlength: "Last Name not greater than {0} character",
                    },
                    mobile_no: {
                        required: "Mobile Number is required",
                        minlength: "Mobile Number have atleast {0} character",
                        maxlength: "Mobile Number not greater than {0} character",
                        number: "Invalid phone number",
                    },
                    landline_no: {
                        required: "Landline Number is required",
                        minlength: "Landline Number have atleast {0} character",
                        maxlength: "Landline Number not greater than {0} character",
                        number: "Invalid landline number",
                    },
                    email: {
                        required: "Email address is required"
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
