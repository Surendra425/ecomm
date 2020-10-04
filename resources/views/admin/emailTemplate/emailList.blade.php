@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    @php
        $pageTitle ="emailTemplateList";
    $contentTitle ='Email Template List';
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
                        Email Template List
                    </h3>
                </div>
            </div>
        </div>

        <div class="m-portlet__body">
           {{-- <a href="{{route('sendEmailTemplate')}}" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill pull-right">
                <span>Send</span>
            </a>--}}
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Email Title</th>
                        <th>Subject</th>
                        <th>Content</th>
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
        $(function() {
            table = $('#admin-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url":'{{ url(route("emailTemplateSearch"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'subject', name: 'subject' },
                    { data: 'email_content', name: 'email_content' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });

    </script>
@endsection

