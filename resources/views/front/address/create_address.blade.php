@extends('front.layout.index')
@section('title') {{!empty($address) ? 'Edit Address' : 'Add Address'}} @endsection
@section('css')

@endsection

@section('meta')
    <meta charset="utf-8">
<meta name="description" content="" />
<meta property="og:url" content="" />
<meta property="og:type" content="article" />
<meta property="og:title" content="" />
<meta property="og:description" content="" />
<meta property="og:image" content="" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="">
@endsection


@if(!empty($address))
    @php
        $pageTitle ='editAddress';
    @endphp
@else
    @php
        $pageTitle ='addAddress';
    @endphp
@endif
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
                <div class="col-md-12">
                    <div id="legend">
                        <legend class="">@yield('title')</legend>
                    </div>
                    <form id="myform" method="post" action="{{!empty($address) ? route('userAddressUpdate',['address'=> $address->id]) : route('address.store') }}" id="formLogin" novalidate="novalidate">
                        {{ csrf_field() }}
                        <input type="hidden" name="address_id" id="address_id" value="{{!empty($address) ? $address->id : ''}}">
                        <input type="hidden" name="store_slug" id="store_slug" value="{{!empty($store_slug) ? $store_slug : ''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="full_name">Address Title <span class="asteric">*</span></label>
                                    <input id="full_name" name="full_name" class="form-control clsLoginField" value="{{ !empty($address)?$address->full_name :old('full_name') }}" type="text" placeholder="e.g. Home,Office,Dwaniya...etc." >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country_id">Country <span class="asteric">*</span></label>
                                    <select class="form-control clsLoginField" id="country_id" name="country_id">
                                        @foreach($country as $item)
                                            <option value="{{$item->id}}" data-countryname = {{$item->country_name}}  @if(!empty($address) && $item->id == $address->country_id) @php echo 'selected' @endphp @elseif(empty($address) && ($item->country_name == 'Kuwait' || $item->country_name == 'KUWAIT')) @php echo 'selected' @endphp @endif >{{$item->country_name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="country" name="country" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="city_id">Area <span class="asteric">*</span></label>
                                    <select class="form-control clsLoginField" id="city_id" name="city_id">
                                        <option value="{{!empty($address) && !empty($city) &&  $address->city_id == $city->id ? $address->city_id : ''}}" {{!empty($address) && !empty($city) &&  $address->city_id == $city->id ? 'selected' : ''}}>{{ !empty($city) && $address->city_id == $city->id ?  $city->city_name : 'Select Area'}}</option>
                                    </select>
                                    <input type="hidden" id="city" name="city" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group hide" id="additional_directions_div">
                                    <label for="additional_directions">Additional Directions </label>
                                    <input id="additional_directions"  name="additional_directions" class="form-control clsLoginField" type="text"  placeholder="Additional Directions" value="{{ !empty($address)?$address->additional_directions:old('additional_directions') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="block">Block <span class="asteric">*</span></label>
                                    <input id="block" name="block" class="form-control clsLoginField" value="{{ !empty($address)?$address->block :old('block') }}" type="text" placeholder="Block" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="street">Street <span class="asteric">*</span></label>
                                    <input id="street" name="street" class="form-control clsLoginField" value="{{ !empty($address)?$address->street:old('street') }}" type="text" placeholder="Street" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="avenue">Avenue </label>
                                    <input type="text" id="avenue" name="avenue" class="form-control clsLoginField" value="{{ !empty($address)?$address->avenue:old('avenue') }}" placeholder="Avenue">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="building">Building <span class="asteric">*</span></label>
                                    <input type="text" id="building" name="building" class="form-control clsLoginField" placeholder="Building" value="{{ !empty($address)?$address->building:old('building') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="floor">Floor</label>
                                    <input type="text" id="floor" name="floor" class="form-control clsLoginField" value="{{ !empty($address)?$address->floor:old('floor') }}" placeholder="Floor">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apartment">Apartment</label>
                                    <input type="text" id="apartment" name="apartment" class="form-control clsLoginField" value="{{ !empty($address)?$address->apartment:old('apartment') }}" placeholder="Apartment">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile">Mobile <span class="asteric">*</span></label>
                                    <input type="text" id="mobile_no" name="mobile" class="form-control numeric clsLoginField phonNumberOnly" placeholder="Mobile" value="{{ !empty($address)?$address->mobile:old('mobile') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="landline">Landline Number</label>
                                    <input type="text" id="landline" name="landline" class="form-control numeric clsLoginField phonNumberOnly" placeholder="Landline Number" value="{{ !empty($address)?$address->landline:old('landline') }}">
                                </div>
                            </div>
                            <div class="col-md-12">

                                <div class="form-group">
                                    <button type="submit" value="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#country_id").change(function ()
            {
                var countryId = this.value;
                country(countryId);
            });

            $('#country_id').trigger('change');

            $("#city_id").change(function ()
            {
                var countryName = $('#country_id option:selected').data('countryname');
                var cityName = $('#city_id option:selected').text();

                $("input[type='hidden'][name='country']").val(countryName);
                $("input[type='hidden'][name='city']").val(cityName);
            });

            $("#myform").validate({
                rules: {
                    full_name: {
                        required: true,
                    },

                    country_id: {
                        required: true
                    },
                    city_id: {
                        required: true
                    },
                    mobile: {
                        required: true,
                        minlength:7,
                        maxlength:17
                    },
                    landline:{
                        minlength:7,
                        maxlength:17
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
                    full_name: {
                        required: "Address title is required",
                    },

                    country_id: {
                        required: "Country is required."
                    },

                    city_id: {
                        required: "Area is required."
                    },
                    mobile: {
                        required: "Mobile number is required.",
                        minlength: "Mobile number have atleast {0} digit",
                        maxlength: "Please enter no more than {0} digits",
                    },
                    landline :{
                        minlength: "Landline no have atleast {0} digit",
                        maxlength: "Please enter no more than {0} digits",
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
    </script>
@endsection