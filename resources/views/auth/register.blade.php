@extends('layouts.app')
@section('title') Sign Up @endsection
@section('content')
<div class="container-fluid" id="Banner-MyAccount">
    <div class="container">
        <div class="col-sm-3 col-lg-3 col-xs-6 col-md-3">
            @yield('title')
        </div>
    </div>
</div>
<div class="container-fluid" id="HomeBreadCumb">
    <div class="container" id="home-myAccount">
        <div class="col-sm-3 col-lg-3 col-xs-6 col-md-3">
            <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
            <span><a href="" class="home-myAccount-1">Register</a></span>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="container">
        <!-- REGISTER -->
        <div class="col-sm-6 sign" id="login-page" >
            <div id="legend">
                <legend class="">REGISTER</legend>
            </div>
            <div class="sns">
                <div class="default">
                    <button class="btn-fb btn-right facebook" type="button" next="/">Facebook</button>
                    <button class="btn-gg btn-left google" type="button" >Google</button>
                    {{-- <button class="btn-tw btn-right twitter" type="button" >Twitter</button> --}}
                </div>
            </div>
            <form name="myform" method="post" action="{{ url('/register')}}" id="formLogin">
                {{ csrf_field() }}
                <input type="hidden" name="has_address" id="has_address" value="no"/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="first_name">First Name <span class="asteric">*</span></label>
                                <input id="first_name" name="first_name" class="form-control clsLoginField" type="text" data-validation="required" placeholder="First Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">Last Name <span class="asteric">*</span></label>
                                <input id="last_name" name="last_name" class="form-control clsLoginField" type="text" data-validation="required" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mobile_no">Mobile Number <span class="asteric">*</span></label>
                            <input type="text" id="mobile_no" name="mobile_no" class="form-control phonNumberOnly clsLoginField" placeholder="Mobile Number">
                        </div>
                        {{-- <div class="form-group">
                            <label for="landline_no">Landline Number</label>
                            <input type="text" id="landline_no" name="landline_no" class="form-control phonNumberOnly clsLoginField" placeholder="Landline Number">
                        </div> --}}
                        <div class="form-group">
                            <label for="email">Email Address <span class="asteric">*</span></label>
                            <input type="email" id="email" name="email" class="form-control clsLoginField" placeholder="Email Address" data-validation="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Gender <span class="asteric">*</span></label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="gender" value="Male"> Male
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="gender" value="Female" > Female
                                    <span></span>
                                </label>
                            </div>
                            <label id="gender-error" class="error" for="gender"></label>
                        </div>
                        {{-- <div class="row">

                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="password_confirmation">Birth Date<span class="asteric">*</span></label>
                                     <input id="datepicker1" name="bday" class="form-control clsLoginField" type="text"  placeholder="Birth Date" autocomplete="off">
                                 </div>
                             </div>
                        </div>--}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password <span class="asteric">*</span></label>
                                    <input id="password" name="password" class="form-control clsLoginField" type="password"  placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password <span class="asteric">*</span></label>
                                    <input id="password_confirmation" name="password_confirmation" class="form-control clsLoginField" type="password"  placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-xs  btn-success" id="btnAddress">Add Address</button>
                    </div>
                </div>
                <div class="row clsAddress">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="full_name">Address Title <span class="asteric">*</span></label>
                            <input id="full_name" name="full_name" class="form-control clsLoginField"  type="text" placeholder="e.g. Home,Office,Dwaniya...etc." >
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="country_id">Country <span class="asteric">*</span></label>
                            <select class="form-control clsLoginField" id="country" name="country">
                                @foreach($country as $item)
                                    <option value="{{$item->id}}" data-countryname = {{$item->country_name}} {{$item->country_name == 'Kuwait' ? 'selected' : ''}} >{{$item->country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city_id">Area <span class="asteric">*</span></label>
                            <select class="form-control clsLoginField" id="city" name="city">
                                <option selected>Select Area</option>
                            </select>
                        </div>
                        <div class="form-group hide" id="additional_directions_div">
                            <label for="additional_directions">Additional Directions </label>
                            <input id="additional_directions"  name="additional_directions" class="form-control clsLoginField" type="text"  placeholder="Additional Directions">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="block">Block <span class="asteric">*</span></label>
                            <input id="block" name="block" class="form-control clsLoginField" type="text" placeholder="Block" >
                        </div>
                        <div class="form-group">
                            <label for="street">Street <span class="asteric">*</span></label>
                            <input id="street" name="street" class="form-control clsLoginField" type="text" placeholder="Street" >
                        </div>
                        <div class="form-group">
                            <label for="avenue">Avenue </label>
                            <input type="text" id="avenue" name="avenue" class="form-control clsLoginField" placeholder="Avenue">
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="building">Building <span class="asteric">*</span></label>
                            <input type="text" id="building" name="building" class="form-control clsLoginField" placeholder="Building" >
                        </div>
                        <div class="form-group">
                            <label for="floor">Floor</label>
                            <input type="text" id="floor" name="floor" class="form-control clsLoginField" placeholder="Floor">
                        </div>

                        <div class="form-group">
                            <label for="apartment">Apartment</label>
                            <input type="text" id="apartment" name="apartment" class="form-control clsLoginField" placeholder="Apartment">
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" value="submit" class="btn btn-primary btn-block">REGISTER</button>
                </div>
            </form>
            <div class="hr"><hr></div>
        </div>
        <div class="col-sm-1">
            <div class="vertical"></div>
        </div>
        <div class="col-sm-5" id="Log-In">
            <h5>LOGIN</h5>
            <p>Registering for this site allows you to access your order status
                and history. Just fill in the fields on left, and we&apos;ll get a new
                account set up for you in no time. We will only ask you for
                information necessary to make the purchase process faster
                and easier.</p>
            <span>Already have an account?</span>
            <a id="Login-button" href="{{ url('login') }}" class="btn btn-primary btn-block">LOGIN</a>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ url('assets/frontend/js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("button.facebook").click(function()
        {
            window.location = "{{url(route('socialLogin',['provider'=>'facebook']))}}";
        });
        $("button.google").click(function()
        {
            window.location = "{{url(route('socialLogin',['provider'=>'google']))}}";
        });
        $("button.twitter").click(function()
        {
            window.location = "{{url(route('socialLogin',['provider'=>'twitter']))}}";
        });
        var IsShow = false;
        $(".clsAddress").hide();
        $("#btnAddress").click(function ()
        {
            if (IsShow)
            {
                $(".clsAddress").hide();
                IsShow = false;
                $(this).html("Add Address");
                $(this).removeClass("btn-info");
                $(this).addClass("btn-success");
                $("#has_address").val("no");
            }
            else
            {
                $(".clsAddress").show();
                IsShow = true;
                $(this).html("Remove Address");
                $(this).removeClass("btn-success");
                $(this).addClass("btn-info");
                $("#has_address").val("yes");
            }
        });

        $("#country").change(function ()
        {
            var countryId = this.value;
            country(countryId);
        });
        function country(countryId) {
            var city_id = $("#city_ids").val();
            var country = $('#country option:selected').data('countryname');
            if(country == 'Kuwait'){
                $("#additional_directions_div").addClass('hide');
            }else{
                $("#additional_directions_div").removeClass('hide');
            }
            $.ajax({
                url: " {{url('admin/get/unique/city/country_id') }}",
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'value': countryId
                },
                success: function (result)
                {
                    var obj = jQuery.parseJSON(result);
                    var html = '';
                    if (obj.length > 0)
                    {
                        html += "<option value=''>Select Area</option>";
                        $.each(obj, function (i, item)
                        {
                            if(city_id == item.id){
                                html += "<option value='" + item.id + "' selected>" + item.city_name + "</option>";
                            }else{
                                html += "<option value='" + item.id + "'>" + item.city_name + "</option>";
                            }

                        });
                        $('#city').html(html);
                    }
                    else
                    {
                        html += "<option value=''>Select Area</option>";
                        $('#city').html(html);
                    }
                }
            });
        }
        $(document).ready(function ()
        {
            var countryId = $("#country").val();
            //alert(countryId);
            country(countryId);

        });

        $("#formLogin").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                mobile_no: {
                    required: true,
                    minlength: 8,
                    number: true,
                },
                landline_no: {
                    minlength: 8,
                    number: true,
                },
                email: {
                    required: true
                },
                gender: {
                    required: true
                },
                /*bday: {
                    required: true
                },*/
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                full_name: {
                    required: true,
                },

                country: {
                    required: true
                },

                city: {
                    required: true
                },
                mobile: {
                    required: true
                },
                block: {
                    required: true
                },
                building: {
                    required: true
                },
                street: {
                    required: true
                },
            },
            messages: {
                first_name: {
                    required: "First Name is required",
                    minlength: "First Name have atleast 2 character"
                },
                last_name: {
                    required: "Last Name is required",
                    minlength: "Last Name have atleast 2 character"
                },
                mobile_no: {
                    required: "Mobile Number is required",
                    minlength: "Mobile Number have atleast 8 character",
                    number: "Invalid phone number",
                },
                email: {
                    required: "Email address is required"
                },
                gender: {
                    required: "Gender is required"
                },
                /*bday: {
                    required: "Birth Date is required"
                },*/
                password: {
                    required: "Password is required",
                    minlength: "Password have atleast 6 character"
                },
                password_confirmation: {
                    required: "Confirm password is required",
                    equalTo: "Password and confirm password not match"
                },
                full_name: {
                    required: "Address Title is required",
                },

                country: {
                    required: "Country is required."
                },

                city: {
                    required: "Area is required."
                },
                mobile: {
                    required: "Mobile Number is required."
                },
                block: {
                    required: "Block is required."
                },
                building: {
                    required: "Building is required."
                },
                street: {
                    required: "Street is required."
                },
            },
            submitHandler: function (form)
            {
                form.submit();
            }
        });
    });
    $('#datepicker1').datepicker({ format: 'yyyy-mm-dd' });
   /* $(function() {
        $( "#datepicker" ).datepicker({
            dateFormat : 'mm/dd/yy',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d'
        });
    });*/

</script>


@endsection