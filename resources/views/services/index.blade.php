@extends("layouts.master")
@section("css")
<link href="{{ asset("assets/plugins/datatable/css/dataTables.bootstrap4.min.css") }}" rel="stylesheet">
<link href="{{ asset("assets/plugins/datatable/css/buttons.bootstrap4.min.css") }}" rel="stylesheet">
<link href="{{ asset("assets/plugins/datatable/responsive.bootstrap4.min.css") }}" rel="stylesheet">
<link href="{{ asset("assets/plugins/select2/select2.min.css") }}" rel="stylesheet">
<style>
    thead tr:not(:last-child) ,tbody tr td:not(:last-child), tbody tr td:last-child {
        pointer-events: none !important;
    }

    tbody tr td:last-child button {
        pointer-events: auto !important;
    }
</style>
@endsection
@section("page-header")
<div class="page-header">
    <div class="page-leftheader">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="/services">Service Monitoring</a></li>
        </ol>
    </div>
</div>
@endsection
@section("content")
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title">Services List</div>
                <div class="btn btn-list">
                    <a href="{{ url("/services/create") }}" class="btn btn-info">
                        <i class="fa fa-plus-circle mr-1"></i>New Service
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered nowrap fs-14 datatable-custom-row w-100 services-table" id="services-table">
                        <thead>
                            <tr>
                                <th class="w-25 search-column">Organization</th>
                                <th class="w-20 search-column">Title</th>
                                <th class="w-10 search-column">Provider</th>
                                <th class="w-10 service-column">Service Type</th>
                                <th class="w-5 search-column">Service Amount</th>
                                <th class="w-10 date-column">Beginning Date</th>
                                <th class="w-10 date-column">Expiring Date</th>
                                <th class="w-10 actions-column">Actions</th>
                            </tr>
                            <tr>
                                <th class="w-25 search-column">Organization</th>
                                <th class="w-20 search-column">Title</th>
                                <th class="w-10 search-column">Provider</th>
                                <th class="w-10 service-column">Service Type</th>
                                <th class="w-5 search-column">Service Amount</th>
                                <th class="w-10 date-column">Beginning Date</th>
                                <th class="w-10 date-column">Expiring Date</th>
                                <th class="w-10 actions-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableTbody" class="dataTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables.js')}}"></script>
<script src="{{ asset('assets/js/numberFormat.js')}}"></script>
<script src="{{ asset('assets/js/custom-number-format.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script>
    let service_types = [];

    @foreach($service_types as $service_type)
    service_types.push({
        id: "{{ $service_type->id }}",
        name: "{{ $service_type->name }}"
    });
    @endforeach

    $(document).ready(function() {
        function services_datatable() {
            $("thead tr input").on("click", function(e) {
                e.stopPropagation();
            });

            $("#services-table").DataTable({
                initComplete: function() {
                    let input_date = `<input type="date" class="form-control form-control-sm">`;
                    let input = `<input type="text" class="form-control form-control-sm" placeholder="Search">`;
                    let services_type_html = `<option value="">All</option>`;

                    $.each(service_types, function(index, value) {
                        services_type_html += `<option value="${value.name}">${value.name}</option>`;
                    });

                    this.api().columns(".search-column").every(function() {
                        let column = this;
                        $(input).appendTo($(this.header()).empty()).on("keyup", function() {
                            let value = $(this).val();
                            column.search(value, true, false).draw();
                        });
                    });

                    this.api().columns(".date-column").every(function() {
                        let column = this;
                        $(input_date).appendTo($(this.header()).empty()).on("change", function() {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });

                    this.api().columns(".service-column").every(function() {
                        let column = this;
                        $(`<select class="form-control form-control-sm">${services_type_html}</select>`).appendTo($(column.header()).empty()).on("change", function() {
                            let val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? "^" + val + "$" : "", true, false).draw();
                        });
                    });

                    this.api().columns(".actions-column").every(function() {
                        $(this.header()).empty();
                    });
                },
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "stateSave": false,
                "paging": true,
                "order":[],
                "ajax": {
                    url: "/services/list",
                    type: "GET",
                },
                "columns": [
                    {
                        "data": "organization",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "title",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            if(data) {
                                return data.length < 50 ? data : `<span>${data.substr(0, 50)}...</span>`;
                            }
                            else {
                                return "-";
                            }

                        }
                    },
                    {
                        "data": "provider",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "service_type",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "service_amount",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                let currency = row.currency == "TRY" ? "₺" : "€";
                                return data + " " + currency;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "beginning_date",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "expiring_date",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "actions",
                        "visible": true,
                        "orderable": false,
                        render: function (data, type, row) {
                            if(data) {
                                return `<div class="d-flex align-items-center justify-content-around"><button class="btn btn-sm btn-danger delete-service" data-service-id="${row.id}"><i class="fa fa-trash"></i></button><button class="btn btn-sm btn-azure service-actions-eye" data-service-id="${row.id}"><i class="fa fa-eye"></i></button></div>`;
                            }
                            else {
                                return "-";
                            }
                        }
                    }
                ],
                "iDisplayLength": 25,
                "language": {
                    "thousands": ".",
                    "processing": `<i class="fa fa-refresh fa-spin"></i>`
                }
            });
        }

         services_datatable();

        function resetDataTable() {
            $("#services-table").DataTable().clear().destroy();
        }

        $(document).on("click", ".delete-service", function() {
           let service_id = $(this).data("service-id");
            confirmModal("The service will be deleted!", "Are you sure?", "Delete", "Cancel", "#0275d8", "#d9534f").then(function() {
                $.ajax({
                    url:"/services/delete/" + service_id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response !== "" && response !== undefined){
                            if(response.success === 1) {
                                toastr.success("Service deleted successfully!", "Success");
                                resetDataTable();
                                services_datatable();
                            }
                            else {
                                toastr.error("Something went wrong!", "Error");
                            }
                        }
                    }
                })
            });
        });

        $(document).on("click", ".service-actions-eye", function() {
            let service_id = $(this).data("service-id");
            window.open("/services/" + service_id, "_blank");
        });
    });
</script>
@endsection
