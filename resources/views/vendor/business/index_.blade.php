@extends('layouts.vendor')
@section('title') Add Business Detail @endsection
@section('css')
        <link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/style.css') }}">
@endsection
@section('content')
<!--begin::Portlet-->
<div class="m-portlet">
    @include('vendor.common.flash')
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-md-12">
                <div id="rootwizard">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <div class="container">
                                <ul>
                                    <li><a href="#tab1" class="btn btn-info" data-toggle="tab">Basic Detail</a></li>
                                    <li><a href="#tab2" class="btn btn-info" data-toggle="tab">Business Detail</a></li>
                                    <li><a href="#tab3" class="btn btn-info" data-toggle="tab">Billing / Deposit Detail</a></li>
                                    <li><a href="#tab4" class="btn btn-info" data-toggle="tab">Plan Detail</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="bar" class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="first_name">First Name : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="first_name" name="first_name" value="<?php echo isset($Vendor->first_name) ? $Vendor->first_name : ""; ?>" placeholder="First Name" >
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="last_name">Last Name : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="last_name" name="last_name" value="<?php echo isset($Vendor->last_name) ? $Vendor->last_name : ""; ?>" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="email">Email : <span class="danger">*</span></label>
                                        <input type="email" class="form-control m-input m-input--square" id="email" name="email" value="<?php echo isset($Vendor->email) ? $Vendor->email : ""; ?>" placeholder="Email">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="mobile_no">Mobile No : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="mobile_no" name="mobile_no" value="<?php echo isset($Vendor->mobile_no) ? $Vendor->mobile_no : ""; ?>" placeholder="Mobile No">
                                    </div>                                      
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="store_name">Store Name : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="store_name" name="store_name" value="" placeholder="Store Name">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="address">Address : <span class="danger">*</span></label>
                                        <textarea class="form-control m-input m-input--square" id="address" name="address" placeholder="Address"></textarea>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="state">Category : <span class="danger">*</span></label>
                                        <select class="custom-select col-md-6" name="category_id" id="category_id">
                                            <option value="" selected="">Select Category</option>
                                            <option value="1">General</option>
                                        </select>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="description">Description : </label>
                                        <textarea class="form-control m-input m-input--square" id="description" name="description" placeholder="Description"></textarea>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <input type="hidden" name="store_image_1" id="store_image_1" value="">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="store_image_1">Profile Image</label><br>
                                        <label class="custom-file">
                                            <input type="file" ame="store_image_1" id="store_image_1" class="custom-file-input" onchange="readURL(this);">
                                            <span class="custom-file-control"></span>
                                        </label>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="custom-file">
                                            <img id="blah" src="#" alt="">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="city">City : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="city" name="city" value="" placeholder="Your City" autocomplete="off">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="state">State : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="state" name="state" value="" placeholder="Your State">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="country">Country : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="country" name="country" value="" placeholder="Your Country">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="featured">Is Featured : </label>
                                        <div class="m-radio-inline">
                                            <label class="m-radio">
                                                <input type="radio" name="featured" value="No" checked="checked"> No
                                                <span></span>
                                            </label>
                                            <label class="m-radio">
                                                <input type="radio" name="featured" value="Yes"> Yes
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="status">Status : </label>
                                        <div class="m-radio-inline">
                                            <label class="m-radio">
                                                <input type="radio" name="status" value="Active" checked="checked"> Active
                                                <span></span>
                                            </label>
                                            <label class="m-radio">
                                                <input type="radio" name="status" value="Inactive"> Inactive
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="benificiary_name">Beneficiary name : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="benificiary_name" name="benificiary_name" value="" placeholder="Beneficiary Name">
                                    </div>  
                                    <div class="form-group m-form__group">
                                        <label for="account_number">Account number (Max 12 Digits) : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="account_number" name="account_number" value="" placeholder="Account number">
                                    </div>                                 
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group m-form__group">
                                        <label for="bank_name">Bank name : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="bank_name" name="bank_name" value="" placeholder="Bank name">
                                    </div>  
                                    <div class="form-group m-form__group">
                                        <label for="swift_code">Swift Code : <span class="danger">*</span></label>
                                        <input type="text" class="form-control m-input m-input--square" id="swift_code" name="swift_code" value="" placeholder="Swift Code">
                                    </div>                                   
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab4"> 
                            <div class="m-pricing-table-3 m-pricing-table-3--fixed">
                                <div class="m-pricing-table-3__items">
                                    <div class="row m-row--no-padding">
                                        <?php foreach ($Plans as $key => $val) : ?>
                                            <div class="m-pricing-table-3__item col-lg-1"></div>
                                            <div class="m-pricing-table-3__item col-lg-4" style="background: aliceblue">
                                                <div class="m-pricing-table-3__wrapper">
                                                    <h3 class="m-pricing-table-3__title"><?php echo $val['plan_name']; ?><br>
                                                        <span class="m-pricing-table-3__description" style="margin-top: 0.5rem;"><?php echo $val['sales_percentage']; ?> from the sales</span>
                                                    </h3>
                                                    <br>
                                                    <span class="m-pricing-table-3__description">
                                                        <div class="m-radio-inline">
                                                            <?php if (count($val['Options'])) : ?>
                                                                <?php foreach ($val['Options'] as $v) : ?>
                                                                    <label class="m-radio">
                                                                        <input type="radio" name="plan_option" value="<?php echo $v['id']; ?>"> <?php echo (number_format($v['price'], 2)) . " KD for " . $v['duration']; ?>
                                                                        <span></span>
                                                                    </label><br/>
                                                                <?php endforeach; ?>	
                                                            <?php endif; ?>
                                                        </div>
                                                    </span>
                                                    <div class="m-pricing-table-3__btn">
                                                        <button type="button" class="btn m-btn--pill  btn-brand m-btn--wide m-btn--uppercase m-btn--bolder m-btn--lg">Select Plan</button>
                                                    </div>
                                                </div>
                                            </div>	
                                            <div class="m-pricing-table-3__item col-lg-1"></div>
                                        <?php endforeach; ?>							 		
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="pager wizard">
                            <li class="previous first" style="display:none;"><a href="#">First</a></li>
                            <li class="previous"><a href="#">Previous</a></li>
                            <li class="next last" style="display:none;"><a href="#">Last</a></li>
                            <li class="next"><a href="#">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
<!--end::Portlet-->
@endsection
@section('js')
<script src="{{ url('assets/vendors/custom/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
                                                $(document).ready(function ()
                                                {
                                                    $('#rootwizard').bootstrapWizard({
                                                        onNext: function (tab, navigation, index)
                                                        {

                                                        }, onTabShow: function (tab, navigation, index)
                                                        {
                                                            var $total = navigation.find('li').length;
                                                            var $current = index + 1;
                                                            var $percent = ($current / $total) * 100;
                                                            $('#rootwizard .progress-bar').css({width: $percent + '%'});
                                                        }, onTabClick: function (tab, navigation, index)
                                                        {
//                                                            return false;
                                                        }
                                                    });
                                                });
</script>
@endsection