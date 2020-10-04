@extends('layouts.admin')
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="appVersionList";
$contentTitle ='App Version List';
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
                App Version List
            </h3>
        </div>
    </div>
</div>
<div class="m-portlet__body">

        <ul
        class="nav nav-tabs  m-tabs-line m-tabs-line--2x m-tabs-line--danger"
        role="tablist">
            <li class="nav-item m-tabs__item">
                <a
            class="nav-link m-tabs__link active" data-toggle="tab"
            href="#m_tabs_android" role="tab">Android</a>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link"
            data-toggle="tab" href="#m_tabs_iphone" role="tab">IPHONE</a>
            </li>
           
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="m_tabs_android" role="tabpanel">
              <!--begin: Search Form -->
                <div class="m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                    <table id="android-table" class="table table-striped table-bordered zero-configuration" width="100%">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>App Url</th>
                                <th>App Is Update</th>
                                <th>Update Message</th>
                                <th>Maintenance Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            <!--end: Search Form -->
            </div>
            <div class="tab-pane" id="m_tabs_iphone" role="tabpanel">
                <div
                class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                <table id="iphone-table" class="table table-striped table-bordered zero-configuration" width="100%">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>App Url</th>
                            <th>App Is Update</th>
                            <th>Update Message</th>
                            <th>Maintenance Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                 </table>
                </div>
            </div>
        </div>
</div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
<script>
        var table;
        $(function() {
            table = $('#android-table').DataTable({
            	 processing: true,
                serverSide: true,
                
                "ajax": {
                    "url":'{{ url(route("appVersionSearch"))  }}',
                    "type": "POST",
                    "async": false,
                  "data" : function ( d ) {
                       d.app_type = '2'
                    },
                },
                columns: [
                    { data: 'app_version', name: 'app_version' },
                    { data: 'app_url', name: 'app_url' },
                    { data: 'app_is_update', name: 'app_is_update' },
                    { data: 'app_update_msg', name: 'app_update_msg' },
                    { data: 'app_maintenance_msg', name: 'app_maintenance_msg' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });
        </script>
        <script>
       var table1;
        $(function() {
            table1 = $('#iphone-table').DataTable({
                 processing: true,
                serverSide: true,
                
                "ajax": {
                    "url":'{{ url(route("appVersionSearch"))  }}',
                    "type": "POST",
                    "async": false,
                  "data" : function ( d ) {
                        d.app_type = '1'
                    },
                },
                columns: [
                    { data: 'app_version', name: 'app_version' },
                    { data: 'app_url', name: 'app_url' },
                    { data: 'app_is_update', name: 'app_is_update' },
                    { data: 'app_update_msg', name: 'app_update_msg' },
                    { data: 'app_maintenance_msg', name: 'app_maintenance_msg' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });

    </script>
    @endsection

