@extends('layouts.'.$loginUser)
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">

@endsection
@section('content')
    @php
        $pageTitle ="saleReport";
    $contentTitle ='Sales Report';
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
                        Sales Report
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <input type='text' class="form-control" name="sale_month" id="m_datepicker_1" value="{{$date}}" readonly placeholder="Select Month"/>
                </div>
                {{--<div class="col-md-6">
                    <a href="{{route('salesStore')}}" class="btn btn-primary">save</a>
                </div>--}}
            </div>
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Vendor&nbsp;Name</th>
                        <th>KNet&nbsp;Payment&nbsp;KD</th>
                        <th>COD&nbsp;Payment&nbsp;KD</th>
                        <!-- <th>KNet&nbsp;Commission&nbsp;Amount&nbsp;KD</th>
                        <th>COD&nbsp;Commission&nbsp;Amount&nbsp;KD</th> -->
                        <th>Total&nbsp;Commission&nbsp;Amount&nbsp;KD</th>
                        <!-- <th>Paid&nbsp;Amount</th> -->
                        <th>Remaining&nbsp;Amount&nbsp;KD</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
    <script>

        var table;

        function report() {

            table = $('#admin-table').DataTable({

                //"scrollX": true,
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": '{{ url(route("saleReportSearch"))  }}',
                    "type": "POST",
                    "async": false,
                    "data" : function ( d ) {
                        d.date =  $("#m_datepicker_1").val()
                     },
                },
                columns: [
                    {data: 'vendorName', name: 'vendorName'},
                    {data: 'Knet', name: 'Knet', orderable: false, searchable: false},
                    {data: 'COD', name: 'COD', orderable: false, searchable: false},
                    /* {data: 'KnetAmount', name: 'KnetAmount', orderable: false, searchable: false},
                    {data: 'CODAmount', name: 'CODAmount', orderable: false, searchable: false}, */
                    {data: 'totalComissions', name: 'totalComissions', orderable: false, searchable: false},
                    /* {data: 'paid', name: 'paid', orderable: false, searchable: false}, */
                    {data: 'remaning', name: 'remaning', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        }
        $(function(){
            report();
        });
        var currentTime = new Date();
        var startDateFrom = new Date(currentTime.getMonth());
        //alert(startDateFrom);
        $('#m_datepicker_1').datepicker({
            format: "yyyy-M",
            viewMode: "months",
            setDate: startDateFrom, 
            minViewMode: "months",
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
        $("#m_datepicker_1").datepicker().on("changeDate", function(e) {
           table.ajax.reload();
        });
       
    </script>
@endsection

