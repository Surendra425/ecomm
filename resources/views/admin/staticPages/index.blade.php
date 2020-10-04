@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    @php
        $pageTitle ="staticPageList";
        $title = 'Static Page List';
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
                        Static Page List
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {{--<a href="{{route('pages.create')}}" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill pull-right">
                                    <span>
                                        <i class="fa fa-file-text"></i>
                                        <span>New Static Page</span>
                                    </span>
            </a>--}}
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Page Name</th>
                        <th>Headline</th>
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
                    "url":'{{ url(route("pageSearch"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    { data: 'page_name', name: 'page_name' },
                    { data: 'headline', name: 'headline' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });

    </script>
@endsection

