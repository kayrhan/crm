@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/custom.datatable.row.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet"/>

    <style>


        input[type="text"] {
            border: 1px solid #D5D5D5;
        }

        input[type="select"] {
            border: 1px solid #D5D5D5;
        }

        input[type="number"] {
            border: 1px solid #D5D5D5;
        }

        input[type="password"] {
            border: 1px solid #D5D5D5;
        }

    </style>
@endsection
@section('page-header')



@endsection
@section('content')
    <!-- Row -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 col-md-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">Freelancers </h3>
                </div>
                <div class="card-body">

                    <div class="row  w-100">
                        <div class="row border-bottom w-100">
                            <div class="col-lg-12 col-md-12">

                                <div class="row">
                                    <div class="col-lg-7 col-md-7">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <div class="table-responsive">
                                                    <table class="table table-bordered nowrap datatable-custom-row" id="freelancers"
                                                           style="width: 100%;">
                                                        <thead>
                                                        <tr align="center">
                                                            <th class="w-5">User ID</th>
                                                            <th class="w-15">First Name</th>
                                                            <th class="w-15">Surname</th>
                                                            <th class="w-30">Organization</th>
                                                            <th class="w-30">Email</th>
                                                            <th class="w-10">Actions</th>

                                                        </tr>

                                                        </thead>
                                                        <tbody id="dataTableTbody" class="dataTable">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                    </div>


                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>
    </div><!-- end app-content-->
    </div>
@endsection
@section('js')


    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/datatables.js')}}"></script>

    <script>

        $(document).ready(function () {


            $('#organization').select2({
                    ajax: {
                        url: '/getOrganizationsRawData',
                        processResults: function (data, page) {
                            return {
                                results: data
                            };
                        }
                    },
                    allowClear: true,
                    placeholder: 'Select an organization'

                }
            );


            $('#freelancers').DataTable({

                "processing": false,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    url: "/freelancers/list",
                    type: "get",

                },
                "paging": true,
                select: true,
                fixedColumns: true,

                "columns": [{
                    "data": "id",
                    "visible": true,
                    "orderable": true,
                    render: function (data, type, row) {
                        if (data)
                            return data;
                        else
                            return '-';
                    }
                },
                    {
                        "data": "first_name",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data)
                                return data;
                            else
                                return '-';
                        }
                    },
                    {
                        "data": "surname",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data)
                                return data;
                            else
                                return '-';
                        }
                    },
                    {
                        "data": "org_id",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data)
                                return data;
                            else
                                return '-';
                        }
                    },
                     {
                        "data": "email",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data)
                                return data;
                            else
                                return '-';
                        }
                    },
                     {
                        "data": "actions",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data)
                                return data;
                            else
                                return '-';
                        }
                    }
                ],
                columnDefs:[{className:"text-center",targets:[5]}]
            });


        });

    </script>


@endsection
