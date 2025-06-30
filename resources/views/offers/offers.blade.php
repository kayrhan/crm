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
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="#">{{ ucfirst(trans('words.offer_list')) }}</a></li>
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
                    <div class="card-title">{{ ucfirst(trans('words.offer_list')) }}</div>

                    <div class="btn btn-list">
                        <a href="{{ url('/add-offer') }}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> {{ ucfirst(trans('words.new_offer')) }} </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap fs-14 datatable-custom-row" id="offerTable" width="100%">
                            <thead>
                                <tr align="center">
                                    <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.offer_no')) }}</th>
                                    <th class="w-30 border-bottom-0">{{ ucfirst(trans('words.customer')) }}</th>
                                    <th class="w-15 border-bottom-0">{{ ucfirst(trans('words.company')) }}</th>
                                    <th class="w-15 border-bottom-0">{{ ucfirst(trans('words.offer_amount')) }}</th>
                                    <th class="w-15 border-bottom-0">{{ ucfirst(trans('words.offer_date')) }}</th>
                                    <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.status')) }}</th>
                                    <th class="w-5 border-bottom-0">{{ ucfirst(trans('words.action')) }}</th>
                                </tr>
                                <tr align="center">
                                    <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.offer_no')) }}</th>
                                    <th class="w-30 border-bottom-0">{{ ucfirst(trans('words.customer')) }}</th>
                                    <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.company')) }}</th>
                                    <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.offer_amount')) }}</th>
                                    <th class="w-20 border-bottom-0">{{ ucfirst(trans('words.offer_date')) }}</th>
                                    <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.status')) }}</th>
                                    <th class="w-5 border-bottom-0"></th>
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
                var dt = $('#offerTable').DataTable({
                    initComplete: function() {
                        this.api().columns(5).every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-control form-control-sm"><option value="">All</option><option value="1">Open</option><option value="2">In Progress</option><option value="3">Done</option></select>'
                                    )
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });
                        });

                    },
                    "processing": false,
                    "serverSide": true,
                    "destroy": true,
                    "paging": true,
                    "order": [],
                    "ajax": {
                        url: "/offer/list",
                        type: "GET",
                    },
                    fixedColumns: true,
                    select: true,
                    "columns": [{
                            "data": "offer_no",
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
                            "data": "customer",
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
                            "data": "company",
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
                            "data": "offer_amount",
                            "visible": true,
                            "orderable": true,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    return numberFormat(data, 2, ',', '.') + ' €';

                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "offer_date",
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
                            "data": "status",
                            "visible": true,
                            "orderable": false,
                            "searchable": true,
                            render: function(data, type, row) {

                                if (data) {
                                    if (data === 1)
                                        return "<span class='badge badge-danger fs-14'>Open</span>";
                                    if (data === 2)
                                        return "<span class='badge badge-warning fs-14'>In Proggress</span>";
                                    if (data === 3)
                                        return "<span class='badge badge-success fs-14'>Done</span>";

                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "action",
                            "visible": true,
                            "orderable": false,
                            render: function(data, type, row) {

                                if (data) {
                                    return "<div class='text-center'><a data-offer-id='" + data +
                                        "' class='btn btn-sm btn-danger offerdeleteButton'><i class='fa fa-trash'></i></a></div><input type='hidden' value='" +
                                        data + "'>";
                                } else {
                                    return '-';
                                }
                            }
                        }
                    ],
                    "columnDefs": [{
                            className: 'text-right',
                            targets: [3]
                        },
                        {
                            className: 'text-center',
                            targets: [0, 1, 2, 4, 5]
                        },
                    ],
                    "iDisplayLength": 25,
                    "language": {
                        "thousands": ".",
                        "processing": "<i class='fa fa-refresh fa-spin'></i>",
                    }
                });

            }


            $('#offerTable thead tr:eq(1) th').each(function(i) {
                if (i !== 6) {
                    var title = $(this).text();
                    var html = '';
                    html =
                        '<input type="text" class="form-control form-control-sm" placeholder="Search"  />';
                    $(this).html(html);
                    $('input', this).on('keyup change', function() {
                        $('#offerTable').DataTable()
                            .column(i)
                            .search(this.value)
                            .draw();
                    });

                } else {

                    $('#todosTable thead tr:eq(1) th:eq(6)').html("");


                }
            });

            offerData();

            $('#offerTable tbody').on('click', 'tr td:not(:last-child)', function() {

                var data = $(this).closest('tr').find('input').val();
                window.location.href = '/update-offer/' + data;
            });

            function resetDataTable() {
                $('#offerTable').DataTable().clear();
                $('#offerTable').DataTable().destroy();
            }
            $(document).on("click", ".offerdeleteButton", function() {
                let offer_id = $(this).data("offer-id");
                confirmModal('Offer will be deleted!',"Are you sure?","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url: "/offer/delete/" + offer_id,
                        type: "get",
                        success: function(response) {
                            if (response !== "" || response !== undefined) {
                                toastr.success("Offer deleted successfully!", "Success");
                                resetDataTable();
                                offerData();
                            }
                        }
                    });
                });

            });
        });
    </script>
@endsection
