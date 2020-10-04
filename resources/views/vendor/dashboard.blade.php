@extends('layouts.vendor')
@section('title') Dashboard @endsection
@section('content')
@php 
$pageTitle ="dashboard";
@endphp
<div class="m-portlet">
    @include('vendor.common.flash')
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-md-4">
                <!--begin:: Widgets/Stats2-1 -->

                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">Total Products</h3>
                                </div>
                                <div class="col m--align-right">
                                    <a href="{{route('products.index')}}"><span class="m-widget1__number m--font-brand"> {{ $totalProducts }}</span></a>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="col-md-4">
                <!--begin:: Widgets/Stats2-1 -->

                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">Total Orders</h3>
                                </div>
                                <div class="col m--align-right">
                                    <a href="{{route('my_order.index')}}"><span class="m-widget1__number m--font-warning">{{ $totalOrders }}</span> </a>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>

        </div>
    </div>
</div>
<div class="m-portlet">
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-md-4">
                <!--begin:: Widgets/Stats2-1 -->
                <div class="m-widget1">
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">Total Daily Product Visitor</h3>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-brand"> {{ $totalProductVisitor }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!--begin:: Widgets/Stats2-1 -->
                <div class="m-widget1">
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">Total Daily Store Visitor</h3>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-warning">{{ $totalStoreVisitor }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!--begin:: Widgets/Stats2-1 -->
                <a href="{{route('my_order.index')}}">
                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">Total Daily Orders</h3>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-warning">{{ $totalDaliyOrders }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="m-portlet">
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-md-4">
                <!--begin:: Widgets/Stats2-1 -->
                <div class="m-widget1">
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">Total Sales</h3>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">{{ number_format((!empty($totalSales)?$totalSales->grand_total:0),2)." KD" }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!--begin::Portlet-->
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            Sales Stats
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div id="m_morris_1" style="height:500px;"></div>
            </div>
        </div>
        <!--end::Portlet-->
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    var MorrisChartsDemo = function ()
    {
        //== Private functions
        var demo1 = function ()
        {
            // LINE CHART
            var chart = Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'm_morris_1',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [{
                        y: 2,
                        a: 1,
                        b: 2,
                    },
                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'y',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['a', 'b'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Values a', 'Value b']
            });
            $.ajax({
                type: "POST",
                url: "{{route('vendorSaleRecord')}}", // This is the URL to the API
            })
                    .done(function (data)
                    {
                        //alert("hi");
                        console.log(data);

                        JSON.stringify(data);
                       // console.log(JSON.stringify(data));
                        // When the response to the AJAX request comes back render the chart with new data
                        chart.setData(data);
                    })
                    .fail(function ()
                    {
                        // If there is no communication between the server, show an error
                        alert("error occured");
                    });
        }

        return {
            // public functions
            init: function ()
            {
                demo1();
            }
        };
    }();

    jQuery(document).ready(function ()
    {
        MorrisChartsDemo.init();
    });
</script>
@endsection