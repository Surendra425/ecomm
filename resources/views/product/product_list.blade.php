@extends('layouts.'.$loginUser)
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    @php
            $pageTitle ="productList";
     $contentTitle ='Product List';
    @endphp
    <div class="m-portlet m-portlet--mobile">
        @include($loginUser.'.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Product List
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <a href="{{ url(route('products.create'))}}" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill pull-right">
                                    <span>
                                        <i class="la la-list-alt"></i>
                                        <span>Add Product</span>
                                    </span>
            </a>
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
<input type="hidden" id="vendorId" value="{{!empty($vendorId) ? $vendorId : ''}}">
                <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                         <th>Product Title</th>
                        <th>Vendor Name</th>
                        @if($loginUser == 'admin' && \Request::segment(1) == 'admin')
                        <th>Home Date Time</th>
                        <th>Product For</th>
                        @endif
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!--end: Search Form -->
        </div>

        <div class="modal fade" id="UpdateDateModel" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Product Date">
          <form id="frmDateUpdate" name="frmDateUpdate" action="">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Product Date</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
              </div>
              <div class="modal-body"> 
                  <input type="hidden" name="date_product_id" id="date_product_id" value="">
                  <div class="form-group m-form__group">
                      <label for="start_date">Select Date<span class="text-danger">*</span></label>
                      <div class='input-group' id='m_daterangepicker_2'>
                          <input type='text' class="form-control m-input" id="home_date_time" name="home_date_time" readonly  placeholder="Select Date" value="" />
                          <span onclick="document.getElementById('home_date_time').focus();" class="input-group-addon"><i class="la la-calendar-check-o"></i></span>
                      </div>
                      <label id="home_date_time-error" class="error" for="home_date_time"></label>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
          </form>
        </div>
        
        
        <div class="modal fade" id="UpdateGenderModel" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Product Date">
          <form id="frmUpdateGender" name="frmUpdateGender" action="">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Product For</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
              </div>
              <div class="modal-body"> 
                  <input type="hidden" name="gender_product_id" id="gender_product_id" value="">
                  <div class="form-group m-form__group">
                      <label for="gender_type">Select Product For<span class="text-danger">*</span></label>
                            <select class="custom-select col-md-6" name="gender_type" id="gender_type">
                                <option value="Both">Both</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>                          
                            </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeModel" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
          </form>
        </div>

    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
    <script>
        var table;
        $(function() {
            table = $('#admin-table').DataTable({
            	//"scrollX": true,
            	processing: true,
                serverSide: true,
                "ajax": {
                    "url":'{{ url(route("productSearch"))  }}',
                    "type": "POST",
                    "async": false,
                    "data" : function ( d ) {
                        d.vendorId =  $("#vendorId").val()
                    },
                },
                columns: [
                     { data: 'product_title', name: 'product_title' },
                    { data: 'vendor_name', name: 'vendor_name' },
                    @if(Auth::guard('admin')->user() && \Request::segment(1) == 'admin')
                    { data: 'home_date_time', name: 'home_date_time' },
                    { data: 'gender_type', name: 'gender_type' },
                    @endif
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });


        $("#frmDateUpdate").validate({
       	 ignore: [],
           rules: {
               home_date_time: {
                   required: true,
               }
           },
           messages: {
        	   home_date_time: {
                   required: "Please select date and time",
               }
           },
           submitHandler: function (form) {

        	   var productId = $('#date_product_id').val();

        	   var homeDateTime = $('#home_date_time').val();

        	   $('#UpdateDateModel').modal('toggle');
        	   
        	   $('#home_date_link_'+productId).html(homeDateTime);
        	   $('#home_date_link_'+productId).data('date', homeDateTime);
        	   $.ajax({
        	         type: 'POST',
        	         url: form.action,
        	         data: $("#frmDateUpdate").serialize(),
        	         success: function (data) {
        	         },
                     error: function (error) {
        	         },
        	         complete: function (response) {
        	         },
        	   });
           }
       });

        $(document).on("click",".update_home_date",function() {
              $('#frmDateUpdate').attr('action', $(this).data('url'));
              $('#date_product_id').val($(this).data('product-id'));
              $('#home_date_time').val($(this).data('date'));
        });

        $(document).on("click",".update_gender_type",function() {
            $('#frmUpdateGender').attr('action', $(this).data('url'));
            $('#gender_product_id').val($(this).data('product-id'));
            $('#gender_type').val($(this).data('gender'));
        });

        
        var date = new Date();

        
        date.setYear({{$currentYear}});
        date.setMonth({{$currentMonth}});
        date.setDate({{$currentDay}});
        date.setHours({{$currentHour}});
        date.setMinutes({{$currentMinute}});
         
        $('#home_date_time').datetimepicker({
        	startDate:date,
            format: "dd/mm/yyyy hh:ii",
            pickerPosition: 'bottom-right'
        });

        $("#frmUpdateGender").validate({
          	 ignore: [],
              rules: {
                  gender_type: {
                      required: true,
                  }
              },
              messages: {
           	   home_date_time: {
                      required: "Please select Gender",
                  }
              },
              submitHandler: function (form) {

           	   var productId = $('#gender_product_id').val();

           	   var genderType = $('#gender_type').val();

           	   $('#closeModel').trigger('click');

           	   $('#gender_type_link_'+productId).html(genderType);
           	   $('#gender_type_link_'+productId).data('gender', genderType);

           	   $.ajax({
           	         type: 'POST',
           	         url: form.action,
           	         data: $("#frmUpdateGender").serialize(),
           	         success: function (data) {
           	         },
                        error: function (error) {
           	         },
           	         complete: function (response) {
           	         },
           	   });
              }
          });
        
    </script>
@endsection

