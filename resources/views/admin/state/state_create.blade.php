@extends('layouts.admin')

@section('content')
    @php
        $pageTitle ="stateAdd";
    $contentTitle =empty($state) ? 'Create State' : 'Edit State';
    @endphp
    <!--begin::Portlet-->
    <?php //echo "<pre>";echo $state['id'];print_r($state);die; ?>
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        <?php echo (empty($state)) ? 'Create State' : 'Edit State'; ?>
                    </h3>
                </div>
            </div>
        </div>


        <form id="state_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="<?php echo (!empty($state)) ?  (url(route('stateUpdate',['state'=>$state['id']]))) : (url(route('state.store'))); ?>" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$state->id or '' }}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group" >
                            <label  for="state_name">State Name : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="state_name" name="state_name" value="{{$state->state_name or '' }}" placeholder="State Name" >
                        </div>
                        <div class="form-group m-form__group" >
                            <label  for="country_id">Select Country Name : <span class="text-danger">*</span></label>
                            <select class="custom-select col-md-6" name="country_id" id="country_id">
                                <option value="" selected="">Select Country</option>
                                <?php foreach($country as $list) { ?>
                                <option value="{{$list->id}}" {{(!empty($state) && ($state->country_id === $list->id)) ? 'selected' : ''}}>{{$list->country_name}}</option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php  $status='';$no_status='';
                        if(!empty($state)){
                            if($state->status === 'Active'){
                                $status='checked="checked"';
                            }else if($state->status === 'Inactive'){
                                $no_status='checked="checked"';
                            }
                        } else {
                            $status='checked="checked"';
                        } ?>
                        <div class="form-group m-form__group" >
                            <label  for="status">Status : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" <?php echo $no_status;?>> Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
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
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#state_create").validate({
                rules: {
                    state_name: {
                        required: true,
                        remote: {
                            url: baseUrl+'/check/unique/state/state_name',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#state_name" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            },
                        }
                    },country_id: {
                        required: true
                    }
                },
                messages: {
                    state_name: {
                        required: "State Name is required",
                        remote: "State is already exists."
                    },country_id: {
                        required: "Please select country. It is required."
                    }
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });
        });
    </script>
@endsection
