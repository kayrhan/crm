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
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Assets {{ $company->name }}</a></li>
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
                    <div class="card-title">Assets {{ $company->name }}</div>

                    <div class="btn btn-list">
                        <a href="{{ url('/assets/create/' . $company->route_name) }}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> Add Asset </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap fs-14 datatable-custom-row" id="assetsTable" width="100%">
                            <thead>
                                <tr align="center">
                                    <th class="w-7 border-bottom-0">Id</th>
                                    <th class="w-20 border-bottom-0 asset">Organization</th>
                                    <th class="w-15 border-bottom-0">Asset</th>
                                    <th class="w-15 border-bottom-0">Model</th>
                                    <th class="w-7 border-bottom-0">Quantity</th>
                                    <th class="w-7 border-bottom-0">Order By Us</th>
                                    <th class="w-10 border-bottom-0">Partner Pdf</th>
                                    <th class="w-10 border-bottom-0">Add Date</th>
                                    <th class="w-5 border-bottom-0">{{ ucfirst(trans('words.action')) }}</th>
                                </tr>
                                <tr align="center">
                                    <th class="w-7 border-bottom-0">Id</th>
                                    <th class="w-20 border-bottom-0 asset">Organization</th>
                                    <th class="w-15 border-bottom-0">Asset</th>
                                    <th class="w-15 border-bottom-0">Model</th>
                                    <th class="w-7 border-bottom-0">Quantity</th>
                                    <th class="w-7 border-bottom-0">Order By Us</th>
                                    <th class="w-10 border-bottom-0">Partner Pdf</th>
                                    <th class="w-10 border-bottom-0">Add Date</th>
                                    <th class="w-5 border-bottom-0">Action</th>
                                </tr>

                            </thead>
                            <tbody id="dataTableBody" class="dataTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
            let company_name = "{{ $company->route_name }}"

            function offerData() {
                let dt = $('#assetsTable').DataTable({
                    initComplete: function() {
                        // Order By Us Select
                        this.api().columns(5).every(function() {
                            let column = this;
                            let select = $(
                                    `<select class="form-control form-control-sm">
                                        <option value="" selected>All</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>`
                                )
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    let val = $(this).val()
                                    column
                                        .search(val, true, false)
                                        .draw();
                                });

                        });
                    },
                    "processing": true,
                    "serverSide": true,
                    "destroy": false,
                    "paging": true,
                    "order": [],
                    "ajax": {
                        url: "/assets/list/" + company_name,
                        type: "GET",
                    },
                    fixedColumns: true,
                    select: true,
                    "columns": [{
                            "data": "id",
                            "visible": true,
                            "orderable": false,
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
                            "data": "organization",
                            "visible": true,
                            "orderable": false,
                            "searchable": true,
                            render: function(data, type, row) {
                                if (data) {
                                    if (data.length > 40) {
                                        return `<span class="tippy-tooltip" data-tippy-content="${data}">
                                            ${data.slice(0,40)} ...</span>`;
                                    } else {
                                        return data;
                                    }
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "asset",
                            "visible": true,
                            "orderable": false,
                            "searchable": true,
                            render: function(data, type, row) {
                                if (data) {
                                    if (data.length > 25) {
                                        data = data.slice(0, 30) + "...";
                                    }
                                    return data;
                                } else {
                                    return '-';
                                }

                            }
                        },
                        {
                            "data": "model",
                            "visible": true,
                            "orderable": false,
                            "searchable": true,
                            render: function(data, type, row) {
                                if (data) {
                                    if (data.length > 20) {
                                        return `<span class="tippy-tooltip" data-tippy-content="${data}">
                                            ${data.slice(0,20)} ...</span>`;
                                    } else {
                                        return data;
                                    }
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "qty",
                            "visible": true,
                            "orderable": false,
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
                            "data": "order_by_us",
                            "visible": true,
                            "orderable": false,
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
                            "data": "partner_pdf",
                            "visible": true,
                            "orderable": false,
                            "searchable": false,
                            render: function(data, type, row) {
                                if (data) {
                                    let name = data.name;
                                    if (name.length > 20) {
                                        name = name.slice(0, 20) + "..."
                                        return `<a target="_blank" class="text-primary tippy-tooltip d-block" data-tippy-content="${data.name}"
                                            href="${data.path}">${name}</a>`;
                                    } else {
                                        return `<a class="text-primary" href="${data.path}">${name}</a>`;
                                    }
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            "data": "created_at",
                            "visible": true,
                            "orderable": false,
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
                                    let update_btn =
                                        `<a target="_blank" href="/assets/${data}/edit/${company_name}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-pencil"></i>
                                        </a>`
                                    return (
                                        `<div class="text-center">
                                            <button data-asset-id="${data}" class="btn btn-sm btn-danger table-delete-btn">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>`);
                                } else {
                                    return '-';
                                }
                            }
                        },
                    ],
                    "language": {
                        "processing": "<i class='fa fa-refresh fa-spin'></i>",
                    },
                });

                dt.on('click', 'tbody tr td:not(:last-child)', function(e) {
                    if (!$(e.target).hasClass("text-primary")) { // pdf links

                        let company_name = "{{ $company->route_name }}"
                        let data = dt.row($(this).parents('tr')).data();
                        let asset_id = data.id;
                        window.open(`/assets/${asset_id}/edit/${company_name}`)
                    }
                });

            }

            // Datatable Search Inputs
            $('#assetsTable thead tr:eq(1) th').each(function(i) {
                let noSearchTitles = ["Action", "Partner Pdf", "Order By Us"];
                let title = $(this).text();

                if (!noSearchTitles.includes(title)) {
                    html =
                        '<input type="text" class="form-control form-control-sm" placeholder="Search" />';
                    $(this).html(html);
                    let column_index = i;
                    $('input', this).on('input', function() {
                        $('#assetsTable').DataTable()
                            .column(i)
                            .search(this.value)
                            .draw();
                    });
                } else {
                    $(this).html("");
                }
            });

            offerData();

            function resetDataTable() {
                $('#assetsTable').DataTable().ajax.reload();
            }

            // TIPPY tooltips
            tippy.delegate("#assetsTable", {
                target: ".tippy-tooltip",
            });

            // Delete Modal Functions
            $(document).on("click", ".table-delete-btn", function() {
                let asset_id = $(this).data("asset-id");
                confirmModal('Asset will be deleted!',"Are you sure?","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/assets/" + asset_id,
                        type: "DELETE",
                        dataType: "json",
                        success: function(response) {
                            if (response !== "" || response !== undefined) {
                                toastr.success("Asset deleted successfully!", "Success");
                                resetDataTable();
                                offerData();
                            }
                        }
                    });
                });
            })
        });
    </script>
@endsection
