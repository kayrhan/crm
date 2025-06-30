@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Stocks</a></li>
            </ol>

        </div>


    </div>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                @if (Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close">×</button>
                        <i class="fa fa-file mr-2" aria-hidden="true"></i><span
                            class="white">{{ session()->get('success') }}</span>
                    </div>
                @endif
                <div class="card-header" style="display: flex;justify-content: space-between;">
                    <div class="card-title">Stocks</div>

                    <div class="btn btn-list">
                        <a href="{{ url('/add-stock') }}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> Add Stock </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap fs-14 datatable-custom-row" id="officeTable" width="100%">
                            <thead>
                                <tr align="center">
                                    <th class="w-10 border-bottom-0">Id</th>
                                    <th class="w-20 border-bottom-0 office">Office</th>
                                    <th class="w-20 border-bottom-0">Brand</th>
                                    <th class="w-20 border-bottom-0">Modal</th>
                                    <th class="w-10 border-bottom-0">Stock</th>
                                    <th class="w-15 border-bottom-0">Add Date</th>
                                    <th class="w-5 border-bottom-0">{{ ucfirst(trans('words.action')) }}</th>
                                    <th class="w-5 border-bottom-0 d-none"></th>
                                </tr>
                                <tr align="center">
                                    <th class="w-10 border-bottom-0">Id</th>
                                    <th class="w-20 border-bottom-0 office">Office</th>
                                    <th class="w-20 border-bottom-0">Brand</th>
                                    <th class="w-20 border-bottom-0">Modal</th>
                                    <th class="w-10 border-bottom-0">Stock</th>
                                    <th class="w-15 border-bottom-0">Add Date</th>
                                    <th class="w-5 border-bottom-0"></th>
                                    <th class="w-5 border-bottom-0 d-none"></th>
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
    <!-- /Row -->
    </div>
    </div><!-- end app-content-->
    </div>

@endsection
@section('js')
    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/js/numberFormat.js') }}"></script>

    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function offerData() {
                var dt = $('#officeTable').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "destroy": true,
                    "paging": true,
                    "order": [],
                    "ajax": {
                        url: "/stocks/list",
                        type: "GET",
                    },
                    fixedColumns: true,
                    select: true,
                    "columns": [{
                            "data": "id",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    return data;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "office_id",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {
                                if (data) {
                                    return (`
                                        <a class="text-primary office-link" target="_blank"
                                            href="{{ url('/update-office/') }}/${data.id}">
                                            ${data.name}
                                        </a>
                                    `);
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "brand",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    return data;
                                } else {
                                    return '-';
                                }

                            }
                        },
                        {
                            "data": "model",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    return data;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "stock",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    return data;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "created_at",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    return data;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "action",
                            "visible": true,
                            "orderable": false,
                            "searchable": false,
                            render: function(data, type, row) {
                                if (data) {
                                    return (
                                        `<div class="text-center">
                                            <a data-offer-id="${data}" class="btn btn-sm btn-danger offerdeleteButton">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                        <input type="hidden" value="${data}">`);
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "office_search",
                            "visible": false,
                            "searchable": true
                        }
                    ],
                });

            }


            $('#officeTable thead tr:eq(1) th').each(function(i) {
                if (i !== 6) {
                    var title = $(this).text();
                    var html = '';
                    if (title != "Action") {

                        html =
                            '<input type="text" class="form-control form-control-sm" placeholder="Search" />';
                        $(this).html(html);
                        $('input', this).on('input', function() {
                            if (i == 1) {
                                $('#officeTable').DataTable()
                                    .column(7)
                                    .search(this.value)
                                    .draw();
                            } else {
                                $('#officeTable').DataTable()
                                    .column(i)
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    } else {
                        $(this).html(html);
                    }

                } else {

                    $('#todosTable thead tr:eq(1) th:eq(6)').html("");


                }
            });

            offerData();


            function resetDataTable() {
                $('#officeTable').DataTable().clear();
                $('#officeTable').DataTable().destroy();
            }
            $(document).on("click", ".offerdeleteButton", function() {
                let stock_id = $(this).data("offer-id");
                confirmModal('Stock will be deleted!',"Are you sure?","Remove","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/stocks/delete/",
                        type: "POST",
                        data: {
                            stock_id: stock_id
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response !== "" || response !== undefined) {
                                toastr.success("Stock deleted successfully!", "Success");
                                resetDataTable();
                                offerData();
                            }
                        }
                    });
                })
            });

            $('#officeTable tbody').on('click', 'tr td:not(:last-child)', function(e) {
                if ($(e.target).find(".office-link").length != 1 && $(e.target).hasClass("office-link") == false) { // office link td sine tıklanmadıysa
                    var data = $(this).closest('tr').find('input').val();
                    window.location.href = '/update-stock/' + data;
                }
            });

        });
    </script>
@endsection
