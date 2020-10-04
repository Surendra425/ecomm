@extends('layouts.admin')
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="orderList";
$contentTitle ='Order List';
@endphp
<div class="m-portlet m-portlet--mobile">
    @include('admin.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                   Order List
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <!--begin: Search Form -->
        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

            <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Order Code</th>
                        <th>Order Datetime</th>
                        <th>Sub Total</th>
                        <th>Shipping Total</th>
                        <th>Grand Total</th>
                        <th>Payment Type</th>
                         <th>Mail Status</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>


        </div>
        <!--end: Search Form -->
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
<script>
var table;
$(function ()
{
    table = $('#admin-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": '{{ url(route("orderSearch"))  }}',
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'customer_name', name: 'customer_name'},
            {data: 'order_no', name: 'order_no'},
            {data: 'created_at', name: 'created_at'},
            {data: 'sub_total', name: 'sub_total'},
            {data: 'shipping_total', name: 'shipping_total'},
            {data: 'grand_total', name: 'grand_total'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'is_mail_send', name: 'is_mail_send'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'action', name: 'action', orderable: false},
        ],
        "aaSorting": [[2,'desc']],
    });

     $(document).on('click', '.reSendMail', function () {
        
        if($(this).data('val') == '2'){

           if(confirm("Are you sure want to sent a mail ?")) {     
            $(this).removeClass('m-badge--danger');
            $(this).addClass('m-badge--success');
            $(this).find('i').remove();
            $(this).html('<i class="la la-fw" aria-hidden="true" title="Sended">&#xf1c6;</i>');

            $.ajax({
                url:"{{url('sendordersuccessmail')}}/"+$(this).data('orderid'),
                type:'get',
                success:function(response){
                }
            });
          }
        }else{
            return ;
        }
        
    });


});

</script>
@endsection

