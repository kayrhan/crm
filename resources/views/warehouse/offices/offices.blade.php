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
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Offices</a></li>
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
                    <div class="card-title">Offices</div>

                    <div class="btn btn-list">
                        <a href="{{ url('/add-office-page') }}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> Add Office </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap fs-14 datatable-custom-row" id="officeTable" width="100%">
                            <thead>
                                <tr align="center">
                                    <th class="w-10 border-bottom-0">Id</th>
                                    <th class="w-30 border-bottom-0">Office</th>
                                    <th class="w-30 border-bottom-0">Country</th>
                                    <th class="w-30 border-bottom-0">City</th>
                                    {{-- <th class="w-10 border-bottom-0">{{ ucfirst(trans('words.action')) }}</th> --}}
                                </tr>
                                <tr align="center">
                                    <th class="w-10 border-bottom-0">Id</th>
                                    <th class="w-30 border-bottom-0">Office</th>
                                    <th class="w-30 border-bottom-0">Country</th>
                                    <th class="w-30 border-bottom-0">City</th>
                                    {{-- <th class="w-10 border-bottom-0"></th> --}}
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

    {{--<div class="modal fade" id="deleteOfferModal" tabindex="-1" role="dialog" aria-labelledby="deleteOfferModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteOfferModalLabel">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Office will be deleted!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button id="deleteOfferAcceptButton" type="button" class="btn btn-secondary">Delete</button>
                </div>
            </div>
        </div>
    </div>--}}

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
                        url: "/office/list",
                        type: "GET",
                    },
                    fixedColumns: true,
                    select: true,
                    "columns": [{
                            "data": "id",
                            "visible": true,
                            "orderable": true,
                            "searchable": false,
                            render: function(data, type, row) {

                                if (data) {
                                    return `${data}<input type='hidden' value='${data} '>` ;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "name",
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
                            "data": "country",
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
                            "data": "city",
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
                        // {
                        //     "data": "action",
                        //     "visible": true,
                        //     "orderable": false,
                        //     "searchable": false,
                        //     render: function(data, type, row) {
                        //         if (data) {
                        //             return "<div class='text-center'><a data-offer-id='" + data +
                        //                 "' class='btn btn-sm btn-danger offerdeleteButton'><i class='fa fa-trash'></i></a></div><input type='hidden' value='" +
                        //                 data + "'>";
                        //         } else {
                        //             return '-';
                        //         }
                        //     }
                        // }
                    ],
                });

            }


            $('#officeTable thead tr:eq(1) th').each(function(i) {
                if (i !== 6) {
                    var title = $(this).text();
                    var html = '';
                    if(title == "Country" || title == "City" || title == "Office"){

                        html =
                        '<input type="text" class="form-control form-control-sm" placeholder="Search"  />';
                        $(this).html(html);
                        $('input', this).on('keyup change', function() {
                            if ($('#officeTable').DataTable().column(i).search() !== this.value) {
                                $('#officeTable').DataTable()
                                .column(i)
                                .search(this.value)
                                .draw();
                            }
                        });
                    }else{
                        $(this).html(html);
                    }

                } else {

                    $('#todosTable thead tr:eq(1) th:eq(6)').html("");


                }
            });

            offerData();

            $('#officeTable tbody').on('click', 'tr td:not(:last-child)', function() {

                var data = $(this).closest('tr').find('input').val();
                window.location.href = '/update-office/' + data;
            });

            function resetDataTable() {
                $('#officeTable').DataTable().clear();
                $('#officeTable').DataTable().destroy();
            }
/*            $(document).on("click", ".offerdeleteButton", function() {
                let office_id = $(this).data("offer-id");
                $('#deleteOfferModal').modal("show");
                $('#deleteOfferAcceptButton').data("offer-id", office_id);
            });*/

/*            $('#deleteOfferAcceptButton').on("click", function() {
                let office_id = $(this).data("offer-id");
                $('#deleteOfferModal').modal("hide");
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/office/delete/",
                    type: "post",
                    data: {
                        office_id: office_id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response !== "" || response !== undefined) {
                            toastr.success("Office deleted successfully!", "Success");
                            resetDataTable();
                            offerData();
                        }
                    }
                });

            });*/

        });
    </script>
@endsection
