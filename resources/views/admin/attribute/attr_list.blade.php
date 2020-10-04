@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" type="text/css"
          href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    @php
        $pageTitle ="attributeList";
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
                        Attribute List
                    </h3>
                </div>

            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <button type="button" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill pull-right"
                    data-toggle="modal" data-target="#create_model">
                                    <span>
                                        <i class="la la-list-alt"></i>
                                        <span>New Attribute</span>
                                    </span>
            </button>
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Attribute Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>


            </div>
            <!--end: Search Form -->
        </div>
    </div>
    <!--begin::Modal-->
    <div class="modal fade" id="create_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Attribute</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="attr_create" class="m-form m-form--fit m-form--label-align-right form" method="post"
                          action="{{ url(route('attributes.store')) }}" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group" id="attr_div">
                            <label for="attribute_name" class="form-control-label">Attribute Name : <span
                                        class="danger">*</span></label>
                            <input type="text" class="form-control" id="attribute_name" name="attribute_name">
                            <span style="color: #f4516c" id="attr_message"></span>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Status:</label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" checked> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive"> Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitAttr" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Attribute</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="edit_body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="submitEditAttr" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection
@section('js')
    <script type="text/javascript"
            src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
    <script>
        var table;
        $(function () {
            table = $('#admin-table').DataTable({
            	processing: true,
                serverSide: true,
                "ajax": {
                    "url": '{{ url(route("attrSearch"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    {data: 'attribute_name', name: 'attribute_name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        });

    </script>
    <script type="text/javascript">
        $("#submitAttr").click(function (event) {
            var attrName = $("#attribute_name").val();
            if (attrName == '') {
                $("#attr_message").text("Attribute Name is required.");
                $("#attr_div").addClass('has-danger');
            } else {
                $.ajax({
                    url: " {{ url(route("checkAttrName"))  }}",
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'attrName': attrName,
                    },
                    success: function (result) {

                        if (result == 1) {
                            $("#attr_message").text("Attribute Name already taken.");
                            $("#attr_div").addClass('has-danger');
                            return false;
                        } else {
                            $("#attr_div").removeClass('has-danger');
                            $("#attr_message").text("");
                            $("#attr_create").submit();

                        }
                    }
                });

            }
        });
        $("#submitEditAttr").click(function (event) {

            var attrName = $("#edit_attribute_name").val();
            var attr_id = $("#attr_id").val();
            if (attrName == '') {
                $("#edit_attr_message").text("Attribute Name is required.");
                $("#edit_attr_div").addClass('has-danger');
            } else {
                $.ajax({
                    url: " {{ url(route("checkAttrName"))  }}",
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'attrName': attrName,
                        'attr_id': attr_id
                    },
                    success: function (result) {
                        if (result == 1) {
                            $("#edit_attr_message").text("Attribute Name already taken.");
                            $("#edit_attr_div").addClass('has-danger');
                        } else {
                            $("#edit_attr_div").removeClass('has-danger');
                            $("#edit_attr_message").text("");
                            $("#attr_edit").submit();
                        }
                    }
                });
            }
        });
        $(document).ready(function () {
            $("#edit_model").on("show.bs.modal", function (e) {
                var id = $(e.relatedTarget).data('id');
                if (id != undefined) {
                    var htmlText = '';

                    $.get("attributes/" + id + "/edit", function (data) {
                        var obj = jQuery.parseJSON(data);
                        var active = '';
                        var inactive = '';
                        if (obj.status == 'Active') {
                            active += "checked";
                        } else {
                            inactive += "checked";
                        }
                        htmlText += '<form id="attr_edit" class="m-form m-form--fit m-form--label-align-right form" method="post" action="attributes/' + obj.id + '/update" novalidate>';
                        htmlText += '{{ csrf_field() }}';
                        htmlText += '<input type="hidden" class="form-control" id="attr_id" name="attr_id" value="' + obj.id + '">';
                        htmlText += '<div class="form-group" id="edit_attr_div">';
                        htmlText += '<label for="attribute_name" class="form-control-label">Attribute Name : <span class="danger">*</span></label>';
                        htmlText += '<input type="text" class="form-control" id="edit_attribute_name" name="edit_attribute_name" value="' + obj.attribute_name + '">';
                        htmlText += '<span style="color: #f4516c" id="edit_attr_message"></span>';
                        htmlText += '</div>';
                        htmlText += '<div class="form-group">';
                        htmlText += '<label for="message-text" class="form-control-label">Status:</label>';
                        htmlText += '<div class="m-radio-inline">';
                        htmlText += '<label class="m-radio">';
                        htmlText += '<input type="radio" name="status" value="Active" ' + active + '> Active';
                        htmlText += '<span></span>';
                        htmlText += '</label>';
                        htmlText += '<label class="m-radio">';
                        htmlText += '<input type="radio" name="status" value="Inactive" ' + inactive + '> Inactive';
                        htmlText += '<span></span>';
                        htmlText += '</label>';
                        htmlText += '</div>';
                        htmlText += '</div>';
                        htmlText += '</form>';
                        $('#edit_body').append(htmlText);
                    });


                }


            });
        });

    </script>
@endsection

