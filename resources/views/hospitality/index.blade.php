@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
@endsection
@section('page-header')
<div class="page-header mt-0 mb-3">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">Bewirtungsbeleg</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#top">Bewirtungsbeleg</a></li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title w-10">
                    Hospitality Receipts
                </div>
                <div class="page-rightheader">
                    <div class="btn btn-list">
                        <a href="{{url('/hospitality-receipt/create')}}" class="btn btn-sm btn-info"><i class="fa fa-plus-circle mr-1"></i>New Receipt</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap datatable-custom-row" id="hospitality-table">
                        <thead>
                        <tr>
                            <th>Bewirtende Person</th>
                            <th>Bewirtete Personen</th>
                            <th>Tag der Bewirtung</th>
                            <th>Gesamtbetrag</th>
                            <th>Datum</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <th>Bewirtende Person</th>
                            <th>Bewirtete Personen</th>
                            <th>Tag der Bewirtung</th>
                            <th>Gesamtbetrag</th>
                            <th>Datum</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js?v=2') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.js') }}"></script>
<script src="{{ asset('assets/js/numberFormat.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script>

    function receiptData() {
        $('#hospitality-table').DataTable({
            initComplete: function() {
                let search = '<input type="text" class="form-control form-control-sm" placeholder="Search"/>';
                let date = '<input type="date" class="form-control form-control-sm"/>';

                this.api().columns(4).every(function() {
                    let column = this;

                    $(date).appendTo($(this.header()).empty()).on('change', function() {
                        let value = $(this).val();
                        column.search(value, true, false).draw();
                    });
                });

                this.api().columns([0, 1, 2, 3]).every(function () {
                    let column = this;
                    $(search).appendTo($(this.header()).empty()).on('keyup', function() {
                        let value = $(this).val();
                        column.search(value, true, false).draw();
                    });
                });

                this.api().columns(5).every(function() {
                    $(this.header()).empty();
                });
            },
            "processing": true,
            "serverSide": true,
            "stateSave": false,
            "destroy": true,
            "paging": true,
            "order": false,
            "ajax": {
                url: '/hospitality-receipt/get-table-data',
                type: 'GET'
            },
            select: true,
            "columns": [
                {
                    "data": "host",
                    "visible": true,
                    "orderable": false,
                    render: function(data) {
                        if(data) {
                            if(data.length < 24) {
                                return data;
                            }
                            else {
                                return `<span class="tippy-tooltip" data-tippy-content="${data}">${data.substr(0,24) + '…'}</span>`;
                            }
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "visitors",
                    "visible": true,
                    "orderable": false,
                    render: function(data) {
                        if(data) {
                            return data;
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "day",
                    "visible": true,
                    "orderable": false,
                    render: function(data) {
                        if(data) {
                            if(data > 1) {
                                return data + " Days";
                            }
                            else {
                                return data + " Day";
                            }
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "total_amount",
                    "visible": true,
                    "orderable": false,
                    render: function(data, type, row) {
                        if(data) {
                            let currency = row.currency == "TRY" ? "₺" : "€";
                            return numberFormat(data, 2, ",", ".") + " " + currency;
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "date",
                    "visible": true,
                    "orderable": false,
                    render: function(data) {
                        if(data) {
                            return moment(new Date(data).toISOString().slice(0, 10)).format("DD.MM.YYYY");
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "actions",
                    "visible": true,
                    "orderable": false,
                    render: function(data, type, row) {
                        if(data) {
                            let html = "-";

                            if(row.file_name) {
                                html = '<a href="/uploads/' + row.file_name + '" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-eye"></i></a><a onclick="deleteReceipt(' + row['id'] + ')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                            }

                            return '<div class="d-flex justify-content-around">' + html + '</div><input type="hidden" value="' + data + '">';
                        }
                        else {
                            return "-";
                        }
                    }
                }
            ],
            "iDisplayLength": 25,
            "language": {
                "search": "Search (All)",
                "thousands": "."
            }
        });
    }

    tippy.delegate('#hospitality-table', {
        target: ".tippy-tooltip",
    });

    function deleteReceipt(ID) {
        confirmModal('Are you sure that you want to delete the receipt?',"Are you sure?","Delete","Close","#0275d8","#d9534f").then(function() {
            $.ajax({
                url: '/hospitality-receipt/delete',
                type: 'POST',
                data: {
                    id: ID,
                    _token: "{{ csrf_token() }}"
                }
            }).done(function() {
                location.reload();
                toastr.success("Receipt deleted successfully!", "Success");
            }).error(function() {
                toastr.error("Something went wrong while trying to delete the receipt!", "Error");
                toggleLoader(false);
            });
        });

    }

    $(document).ready(function() {
        receiptData();

        $('#hospitality-table tbody').on('click', 'tr td:not(:last-child)', function() {
            var data = $(this).closest('tr').find('input').val();
            window.open('/hospitality-receipt/edit/' + data, '_blank');
        });
    });
</script>
@endsection
