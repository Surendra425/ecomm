@extends('layouts.vendor')
@section('title') Add Shipping Detail @endsection
@php
    $pageTitle ="Manage Shipping";
@endphp
@section('css')
@endsection
@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        @include('vendor.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                    <h3 class="m-portlet__head-text">
                        @yield('title')
                    </h3>
                </div>
            </div>
        </div>
        <form id="shipping_create" class="m-form m-form--fit m-form--label-align-right form"
              enctype="multipart/form-data" method="post" action="{{url(route('shipping.store'))}}" novalidate>
            {{ csrf_field() }}
            <div class="m-portlet__body">
                <div class="row">
                @php $allcity = []; @endphp
                @foreach($area as $cities)
                    @php $allcity[] = $cities->id;@endphp
                @endforeach
                @php $county = []; @endphp
                @foreach($countrySelected as $counties)
                    @php $county[] = $counties->country_id; @endphp
                    @if(in_array($counties->country_id,$countryIds))
                        <!--start heading-->
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="country_name"><b>Country</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="charge"><b>Charge</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="from"><b>From</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="to"><b>To</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="toTime"><b>Time</b></label>
                                </div>
                            </div>
                            <!--end heading-->
                            <input type="hidden" name="country_name[{{$counties->country_id}}][]"
                                   value="{{$counties->country_name}}">
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="country_name"><b>{{$counties->country_name}}</b></label>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <input type="checkbox" class="shipping-group country-group"
                                           onchange="myCountry(this,'{{$counties->country_id}}')" name="checkCountry[]"
                                           value="{{$counties->country_id}}" checked>
                                </div>
                                <span id="checkCountryMsg" class="danger"></span>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ss">
                                    <input type="text"
                                           class="form-control priceValidation form-control-danger charge_{{$counties->country_id}} re-charge"
                                           onkeyup="addCharge('{{$counties->country_id}}')"
                                           value="{{ $counties->city_id != '' ? '' : $counties->charge }}"
                                           name="charge[{{$counties->country_id}}][]" data-charge="{{ $counties->city_id != '' ? '' : $counties->charge }}"
                                           placeholder="Charge" {{ $counties->city_id != '' ? 'readonly="readonly"' : '' }}>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <input type="text"
                                           class="form-control priceValidation form-control-danger from_{{$counties->country_id}} re-from"
                                           onkeyup="addFrom('{{$counties->country_id}}')"
                                           name="from[{{$counties->country_id}}][]" data-from="{{ $counties->city_id != '' ? '' : $counties->from }}"
                                           value="{{$counties->city_id != '' ? '' : $counties->from }}"
                                           placeholder="Form" {{ $counties->city_id != '' ? 'readonly="readonly"' : '' }}>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <input type="text"
                                           class="form-control priceValidation form-control-danger to_{{$counties->country_id}} re-to"
                                           onkeyup="addTo('{{$counties->country_id}}')"
                                           name="to[{{$counties->country_id}}][]" data-to="{{ $counties->city_id != '' ? '' : $counties->to }}"
                                           value="{{$counties->city_id != '' ? '' : $counties->to }}"
                                           placeholder="To" {{ $counties->city_id != '' ? 'readonly="readonly"' : '' }}>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group">
                                    <select name="to_time[{{$counties->country_id}}][]"
                                            onchange="dayTime(this,'{{$counties->country_id}}')"
                                            class="custom-select col-md-6 to_time_{{$counties->country_id}}" {{ $counties->city_id != '' ? 'readonly="readonly"' : '' }}>
                                        <option value="days" {{$counties->city_id == '' && $counties->time == 'days' ? 'selected' : ''}}>
                                            Day
                                        </option>
                                        <option value="hours" {{$counties->city_id == '' && $counties->time == 'hours' ? 'selected' : ''}}>
                                            Hour
                                        </option>
                                    </select>
                                </div>
                                <br>
                            </div>
                            <div class="m-demo" data-code-preview="true" data-code-html="true"
                                 data-code-js="false" style="width: 100% !important;margin-left: 1%;margin-right: 1%;">
                            <div class="m-demo__preview">
                            <div class="row" style="margin-left: 5px !important;margin-right: 5px !important;"
                                 id="countryDiv_{{$counties->country_id}}">
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="country_name">Area</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="charge">Charge</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="from">From</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="to">To</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="toTime">Time</label>
                                    </div>
                                </div>
                                @php $selectedarea = []; @endphp
                                @foreach($countryCitySelected as $cities)
                                    @if($cities->country_id == $counties->country_id)
                                        @php $selectedarea[] = $cities->city_id; @endphp
                                        @if(in_array($cities->city_id,$allcity))
                                            <input type="hidden" name="city_name[{{$counties->country_id}}][{{$cities->city_id}}][]"
                                                   value="{{$cities->city_name}}">
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <label for="country_name">{{$cities->city_name}}</label>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group s">
                                                    <input type="checkbox"
                                                           class="shipping-group checkCity_{{$counties->country_id}} checkCity_{{$counties->country_id}}_{{$cities->id}}"
                                                           onchange="myCity(this,'{{$counties->country_id}}','{{$cities->city_id}}')"
                                                           name="checkCity[{{$counties->country_id}}][]"
                                                           value="{{$cities->city_id}}" checked>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="text"
                                                           class="form-control priceValidation form-control-danger city_charge_{{$counties->country_id}} city_charge_{{$counties->country_id}}_{{$cities->city_id}}"
                                                           name="chargeCity[{{$counties->country_id}}][{{$cities->city_id}}][]"
                                                           value="{{$cities->charge}}" placeholder="Charge">
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="text"
                                                           class="form-control priceValidation form-control-danger city_from_{{$counties->country_id}} city_from_{{$counties->country_id}}_{{$cities->city_id}}"
                                                           name="fromCity[{{$counties->country_id}}][{{$cities->city_id}}][]"
                                                           value="{{$cities->from}}" placeholder="Form">
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="text"
                                                           class="form-control priceValidation form-control-danger city_to_{{$counties->country_id}} city_to_{{$counties->country_id}}_{{$cities->city_id}}"
                                                           name="toCity[{{$counties->country_id}}][{{$cities->city_id}}][]"
                                                           value="{{$cities->to}}" placeholder="To">
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <select name="city_to_time[{{$counties->country_id}}][{{$cities->city_id}}][]"
                                                            class="custom-select col-md-6 city_to_time_{{$counties->country_id}}  city_to_time_{{$counties->country_id}}_{{$cities->city_id}}">
                                                        <option value="days" {{$cities->time == 'days' ? 'selected' : ''}}>
                                                            Day
                                                        </option>
                                                        <option value="hours" {{$cities->time == 'hours' ? 'selected' : ''}}>
                                                            Hour
                                                        </option>
                                                    </select>
                                                </div>
                                                <br>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                @foreach($area as $cities)
                                    @if($cities->country_id == $counties->country_id)
                                        @if(!in_array($cities->id,$selectedarea))
                                            <input type="hidden" name="city_name[{{$counties->country_id}}][{{$cities->id}}][]"
                                                   value="{{$cities->city_name}}">
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <label for="country_name">{{$cities->city_name}}</label>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="checkbox"
                                                           class="shipping-group checkCity_{{$counties->country_id}} checkCity_{{$counties->country_id}}_{{$cities->id}}"
                                                           onchange="myCity(this,'{{$counties->country_id}}','{{$cities->id}}')"
                                                           name="checkCity[{{$counties->country_id}}][]"
                                                           value="{{$cities->id}}" checked>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="text"
                                                           class="form-control form-control-danger city_charge_{{$counties->country_id}} city_charge_{{$counties->country_id}}_{{$cities->id}}"
                                                           name="chargeCity[{{$counties->country_id}}][{{$cities->id}}][]"
                                                           placeholder="Charge" value="{{ $counties->charge }}">
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="text"
                                                           class="form-control form-control-danger city_from_{{$counties->country_id}}
                                                           city_from_{{$counties->country_id}}_{{$cities->id}}"
                                                           name="fromCity[{{$counties->country_id}}][{{$cities->id}}][]"
                                                           placeholder="Form" value="{{ $counties->from }}">
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <input type="text"
                                                           class="form-control form-control-danger city_to_{{$counties->country_id}} city_to_{{$counties->country_id}}_{{$cities->id}}"
                                                           name="toCity[{{$counties->country_id}}][{{$cities->id}}][]"
                                                            placeholder="To" value="{{ $counties->to }}">
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group m-form__group ">
                                                    <select name="city_to_time[{{$counties->country_id}}][{{$cities->id}}][]" readonly
                                                            class="custom-select col-md-6
                                                            city_to_time_{{$counties->country_id}}  city_to_time_{{$counties->country_id}}_{{$cities->id}}">
                                                        <option value="days" {{ $counties->time == 'days' ? 'selected' : '' }}>
                                                            Day
                                                        </option>
                                                        <option value="hours" {{ $counties->time == 'hours' ? 'selected' : '' }}>
                                                            Hour
                                                        </option>
                                                    </select>
                                                </div>
                                                <br>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                            </div>
                                </div>
                            @endif
                @endforeach
                @foreach($country as $counties)
                    @if(!in_array($counties->id,$county))
                        <!--start heading-->
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="country_name"><b>Country</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="charge"><b>Charge</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="from"><b>From</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="to"><b>To</b></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="toTime"><b>Time</b></label>
                                </div>
                            </div>
                            <!--end heading-->
                            <input type="hidden" name="country_name[{{$counties->id}}][]"
                                   value="{{$counties->country_name}}">
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <label for="country_name"><b>{{$counties->country_name}}</b></label>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group">
                                    <input type="checkbox" class="shipping-group country-group"
                                           onchange="myCountry(this,'{{$counties->id}}')" name="checkCountry[]"
                                           value="{{$counties->id}}">
                                </div>
                                <span id="checkCountryMsg" class="danger"></span>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <input type="text" class="form-control form-control-danger priceValidation charge_{{$counties->id}}"
                                           onkeyup="addCharge('{{$counties->id}}')" name="charge[{{$counties->id}}][]"
                                           placeholder="Charge" readonly>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <input type="text" class="form-control form-control-danger priceValidation from_{{$counties->id}}"
                                           onkeyup="addFrom('{{$counties->id}}')" name="from[{{$counties->id}}][]"
                                           placeholder="Form" readonly>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group ">
                                    <input type="text" class="form-control form-control-danger priceValidation to_{{$counties->id}}"
                                           onkeyup="addTo('{{$counties->id}}')" name="to[{{$counties->id}}][]"
                                           placeholder="To" readonly>
                                </div>
                                <br>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group m-form__group">
                                    <select name="to_time[{{$counties->id}}][]"
                                            onchange="dayTime(this,'{{$counties->id}}')" readonly
                                            class="custom-select col-md-6 to_time_{{$counties->id}}">
                                        <option value="days">Day</option>
                                        <option value="hours">Hour</option>
                                    </select>
                                </div>
                                <br>
                            </div>
                            <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false" style="width: 100% !important;margin-left: 1%;margin-right: 1%;">
                            <div class="m-demo__preview">
                            <div class="row m--hide" style="margin-left: 5px !important;margin-right: 5px !important;"
                                 id="countryDiv_{{$counties->id}}">
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="country_name">Area</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="charge">Charge</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="from">From</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="to">To</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group m-form__group ">
                                        <label for="toTime">Time</label>
                                    </div>
                                </div>

                                @foreach($area as $cities)
                                    @if($cities->country_id == $counties->id)
                                        <input type="hidden" name="city_name[{{$counties->id}}][{{$cities->id}}][]"
                                               value="{{$cities->city_name}}">

                                        <div class="col-md-2">
                                            <div class="form-group m-form__group ">
                                                <label for="country_name">{{$cities->city_name}}</label>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group m-form__group ">
                                                <input type="checkbox"
                                                       class="shipping-group checkCity_{{$counties->id}} checkCity_{{$counties->id}}_{{$cities->id}}"
                                                       onchange="myCity(this,'{{$counties->id}}','{{$cities->id}}')"
                                                       name="checkCity[{{$counties->id}}][]" value="{{$cities->id}}">
                                            </div>
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group m-form__group ">
                                                <input type="text"
                                                       class="form-control priceValidation form-control-danger city_charge_{{$counties->id}} city_charge_{{$counties->id}}_{{$cities->id}}"
                                                       name="chargeCity[{{$counties->id}}][{{$cities->id}}][]" placeholder="Charge"
                                                       readonly>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group m-form__group ">
                                                <input type="text"
                                                       class="form-control priceValidation form-control-danger city_from_{{$counties->id}} city_from_{{$counties->id}}_{{$cities->id}}"
                                                       name="fromCity[{{$counties->id}}][{{$cities->id}}][]" placeholder="Form" readonly>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group m-form__group ">
                                                <input type="text"
                                                       class="form-control priceValidation form-control-danger city_to_{{$counties->id}} city_to_{{$counties->id}}_{{$cities->id}}"
                                                       name="toCity[{{$counties->id}}][{{$cities->id}}][]" placeholder="To" readonly>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group m-form__group ">
                                                <select name="city_to_time[{{$counties->id}}][{{$cities->id}}][]" readonly
                                                        class="custom-select col-md-6 city_to_time_{{$counties->id}} city_to_time_{{$counties->id}}_{{$cities->id}}">
                                                    <option value="days">Day</option>
                                                    <option value="hours">Hour</option>
                                                </select>
                                            </div>
                                            <br>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            </div>
                                </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-12 ml-lg-auto text-center">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--end::Portlet-->
@endsection
@section('js')
    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/form-repeater.js')}}"
            type="text/javascript"></script>
    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"
            type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>


        function myCity(checkbox, country_id, id) {
            var charge = $(".charge_" + country_id).val();
            var from = $(".from_" + country_id).val();
            var to = $(".to_" + country_id).val();
            var to_time = $(".to_time_" + country_id).val();
            if (checkbox.checked) {
                $(".city_charge_" + country_id + "_" + id).removeAttr('readonly');
                $(".city_from_" + country_id + "_" + id).removeAttr('readonly');
                $(".city_to_" + country_id + "_" + id).removeAttr('readonly');
                $(".city_to_time_" + country_id + "_" + id).prop("readonly", false);

                $(".city_charge_" + country_id + "_" + id).addClass('re-charge required');
                $(".city_from_" + country_id + "_" + id).addClass('re-from required');
                $(".city_to_" + country_id + "_" + id).addClass('re-to required');
                $(".city_to_time_" + country_id + "_" + id).addClass('re-time required');


                $(".city_charge_" + country_id + "_" + id).attr('required');
                $(".city_from_" + country_id + "_" + id).attr('required');
                $(".city_to_" + country_id + "_" + id).attr('required');

                var numberOfChecked = $('.checkCity_' + country_id + ':checked').length;
                var totalCheckboxes = $('.checkCity_' + country_id).length;
                var numberNotChecked = totalCheckboxes - numberOfChecked;
                if (numberNotChecked == 0) {
                    $(".charge_" + country_id).prop("readonly", false);
                    $(".from_" + country_id).prop("readonly", false);
                    $(".to_" + country_id).prop("readonly", false);
                    $(".to_time_" + country_id).prop("readonly", false);

                    $(".charge_" + country_id).addClass('re-charge required');
                    $(".from_" + country_id).addClass('re-from required');
                    $(".to_" + country_id).addClass('re-to required');
                    $(".to_time_" + country_id).addClass('re-time required');

                    $(".charge_" + country_id).attr('required');
                    $(".from_" + country_id).attr('required');
                    $(".to_" + country_id).attr('required');
                }

            } else {

                $(".checkCity_" + country_id + "_" + id).removeAttr('checked');

                var numberOfChecked = $('.checkCity_' + country_id + ':checked').length;
                var totalCheckboxes = $('.checkCity_' + country_id).length;
                var numberNotChecked = totalCheckboxes - numberOfChecked;
                if (numberNotChecked == totalCheckboxes) {
                    $(".charge_" + country_id).prop("readonly", false);
                    $(".from_" + country_id).prop("readonly", false);
                    $(".to_" + country_id).prop("readonly", false);
                    $(".to_time_" + country_id).prop("readonly", false);


                    $(".charge_" + country_id).attr('required');
                    $(".from_" + country_id).attr('required');
                    $(".to_" + country_id).attr('required');

                    $(".charge_" + country_id).addClass('re-charge required');
                    $(".from_" + country_id).addClass('re-from required');
                    $(".to_" + country_id).addClass('re-to required');
                    $(".to_time_" + country_id).addClass('re-time required');
                } else {
                    $(".charge_" + country_id).attr('readonly','readonly');
                    $(".from_" + country_id).attr('readonly','readonly');
                    $(".to_" + country_id).attr('readonly','readonly');
                    $(".to_time_" + country_id).attr("readonly", "readonly");

                    $(".charge_" + country_id).removeClass('re-charge required');
                    $(".from_" + country_id).removeClass('re-from required');
                    $(".to_" + country_id).removeClass('re-to required');
                    $(".to_time_" + country_id).removeClass('re-time required');
                    $(".charge_" + country_id).val('');
                    $(".from_" + country_id).val('');
                    $(".to_" + country_id).val('');

                    $(".charge_" + country_id).removeAttr('required');
                    $(".from_" + country_id).removeAttr('required');
                    $(".to_" + country_id).removeAttr('required');
                }

                $(".city_charge_" + country_id + "_" + id).removeAttr('required');
                $(".city_from_" + country_id + "_" + id).removeAttr('required');
                $(".city_to_" + country_id + "_" + id).removeAttr('required');

                $(".city_charge_" + country_id + "_" + id).attr('readonly', 'readonly');
                $(".city_from_" + country_id + "_" + id).attr('readonly', 'readonly');
                $(".city_to_" + country_id + "_" + id).attr('readonly', 'readonly');
                $(".city_to_time_" + country_id + "_" + id).prop("readonly", true);

                $(".city_charge_" + country_id + "_" + id).removeClass('re-charge required');
                $(".city_from_" + country_id + "_" + id).removeClass('re-from required');
                $(".city_to_" + country_id + "_" + id).removeClass('re-to required');
                $(".city_to_time_" + country_id + "_" + id).removeClass('re-time required');

                $(".city_charge_" + country_id + "_" + id).val('');
                $(".city_from_" + country_id + "_" + id).val('');
                $(".city_to_" + country_id + "_" + id).val('');

            }
            $(".charge_" + country_id).val(charge);
            $(".from_" + country_id).val(from);
            $(".to_" + country_id).val(to);
            $(".to_time_" + country_id).val(to_time);

        }
        function myCountry(checkbox, id) {
            if (checkbox.checked) {
                $(".charge_" + id).removeAttr('readonly');
                $(".from_" + id).removeAttr('readonly');
                $(".to_" + id).removeAttr('readonly');
                $(".to_time_" + id).prop("readonly", false);

                $("#countryDiv_" + id).removeClass('m--hide');

                $(".charge_" + id).addClass('re-charge required');
                $(".from_" + id).addClass('re-from required');
                $(".to_" + id).addClass('re-to required');
                $(".to_time_" + id).addClass('re-time required');

                $(".charge_" + id).attr('required');
                $(".from_" + id).attr('required');
                $(".to_" + id).attr('required');

                $(".charge_"+ id).val($(".charge_"+ id).attr('data-charge'));
                $(".from_"+ id).val($(".from_"+ id).attr('data-from'));
                $(".to_"+ id).val($(".to_"+ id).attr('data-to'));

            } else {


                $(".charge_" + id).attr('readonly', 'readonly');
                $(".from_" + id).attr('readonly', 'readonly');
                $(".to_" + id).attr('readonly', 'readonly');
                $(".to_time_" + id).prop("readonly", true);

                $(".charge_" + id).removeAttr('required');
                $(".from_" + id).removeAttr('required');
                $(".to_" + id).removeAttr('required');

                $("#countryDiv_" + id).addClass('m--hide');

                $(".charge_" + id).removeClass('re-charge required');
                $(".from_" + id).removeClass('re-from required');
                $(".to_" + id).removeClass('re-to required');
                $(".to_time_" + id).removeClass('re-time required');
                $(".charge_" + id).val('');
                $(".from_" + id).val('');
                $(".to_" + id).val('');
            }

        }

        function dayTime(timeday, id) {
            //alert("hi");
            var val = $( ".to_time_"+id+" option:selected" ).val();
            //alert(val);
             /*$('.city_to_time_' + id + 'option[value="' + val + '"]').html();*/
            //$(".city_to_time_" + id).val(val.value);
            $('.city_to_time_' + id +' option[value='+val+']').attr('selected','selected');
            $(".checkCity_" + id).attr("checked", "checked");
        }
        function addCharge(id) {

            $(".city_charge_" + id).val($(".charge_" + id).val());
            $(".checkCity_" + id).attr("checked", "checked");
        }
        function addFrom(id) {
            $(".city_from_" + id).val($(".from_" + id).val());
            $(".checkCity_" + id).attr("checked", "checked");

        }
        function addTo(id) {
            $(".city_to_" + id).val($(".to_" + id).val());
            $(".checkCity_" + id).attr("checked", "checked");
        }


        /*$("#shipping_create").submit(function (){
         $(".country-group").find("checkbox").each(function(){
         if ($(this).prop('checked')==true){
         alert("hi");
         }else{
         alert("hello");
         }
         });

         });*/

        $( "#shipping_create" ).submit(function( event ) {
            var numberOfChecked = $('.country-group:checked').length;
            var totalCheckboxes = $('.country-group').length;
            var numberNotChecked = totalCheckboxes - numberOfChecked;
            if (numberOfChecked == 0) {
                $("#checkCountryMsg").text("Please select at least one country.");
                return false;
            }
            //return false;

        });
        $("#shipping_create").validate({
            rules: {
                "checkCountry[]": {
                    require_from_group: [1, ".shipping-group"]
                },
            },
            messages: {
                "checkCountry[]": "Please select at least one country.",

            }
        });
        $(document).ready(function () {

            $.validator.addMethod("chargeRequired", $.validator.methods.required,
                    "Charge is required");

            $.validator.addMethod("fromRequired", $.validator.methods.required,
                    "From is required");

            $.validator.addMethod("toRequired", $.validator.methods.required,
                    "To is required");

            $.validator.addMethod("timeRequired", $.validator.methods.required,
                    "Please select time");


            $.validator.addMethod("chargeNumber", $.validator.methods.number,
                    "Charge must be number");

            $.validator.addMethod("fromNumber", $.validator.methods.required,
                    "From must be number");
            $.validator.addMethod("toNumber", $.validator.methods.required,
                    "To must be number");
            $.validator.addMethod("fromNotZero", $.validator.methods.min,
            "From must be grater then 0");
            $.validator.addMethod("toNotZero", $.validator.methods.min,
            "To must be grater then 0");

            jQuery.validator.addClassRules("re-charge", {
                chargeRequired: true,
                chargeNumber: true,
            });
            jQuery.validator.addClassRules("re-time", {
                timeRequired: true,
            });

            jQuery.validator.addClassRules("re-from", {
                fromRequired: true,
                fromNumber: true,
                fromNotZero:true,
            });
            jQuery.validator.addClassRules("re-to", {
                toRequired: true,
                toNumber: true,
                toNotZero:true,
            });
        });
    </script>
@endsection
