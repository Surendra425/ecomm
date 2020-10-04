@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="vendorSalesUpdate";
    $contentTitle = 'Vendor Sales % Update';
    @endphp

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">

                        Vendor Sales % Update
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="vendor_update_sale" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post"
              action="{{ url(route('vendorSaleUpdate'))}}"
              novalidate>
            {{ csrf_field() }}
           <input type="hidden" name="plan_info_id" id="plan_info_id">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="city_name">Vendor Name<span class="text-danger">*</span></label>
                            <select class="custom-select col-md-6" name="vendor" id="vendor">
                            <option value="" selected="">Select Vendor</option>
                            	@foreach($vendor as $item)
                            	<option value="{{ $item->vendor_id }}" data-plan__info_id = "{{ $item->id }}">{{$item->first_name}} {{ $item->last_name}} ({{ $item->sales_percentage}}%)</option>
                            	@endforeach
                            </select>
                        </div>
                         <br>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="sales_per">Sales %<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="sales_percentage"
                                   name="sales_percentage"  placeholder="Sales %">
                        </div>
                    </div>
                </div>

            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Portlet-->


@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#vendor").on('change',function(){
            	var vendor_id = $("#vendor").val();
            	$("#plan_info_id").val($(':selected', this).data('plan__info_id'));
            	
            });
             $("#vendor_update_sale").validate({
                rules: {
                	vendor: {
                        required: true,
                        
                    },
                    sales_percentage: {
                        required: true
                    }
                },
                messages: {
                	vendor: {
                        required: "Please selct vendor. It is required.",
                       
                    },
                    sales_percentage: {
                        required: "Sales % is required"
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            }); 
        });
    </script>
@endsection
