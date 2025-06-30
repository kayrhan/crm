@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        tbody td:nth-child(10) {
            position: relative;
        }

        .paymentButton {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: #01b710;
            color: #fff;
            border: none;
            font-weight: bold;
        }

        tbody td:nth-child(10):hover > .paymentButton {
            display: block;
        }

        .payment-log {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: #0141b7;
            color: #fff;
            border: none;
            font-weight: bold;
        }

        tbody td:nth-child(10):hover > .payment-log {
            display: block;
        }
        #payment-history-body tr{
            border-bottom: 1px solid #e1e1e1;
        }
        #payment-history-body tr:last-child {
            border-bottom: none;
        }
        .custom-dropdown-menu{
            left: 50px !important;
            min-width: 5rem !important;
            top: -5px !important;
        }
        .custom-dropdown-menu a{
            font-size: 12px !important;
            padding: 0.2rem 1rem !important;
        }
        #dropdownMenuButton:hover,
        #dropdownMenuButton:focus,
        #dropdownMenuButton:active {
            color: #705ec8 !important;
        }
    </style>
@endsection
@section('page-header')
    <div class="page-header mt-0 mb-3">
        <div class="page-leftheader">
            <h4 class="page-title mb-0"> {{$company->full_name}} Accounting </h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$company->full_name}} Accounting</a></li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
@if($type == "invoice")
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <p class="mb-1"><span>Invoices of </span>
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    @foreach($calculated_payments as $paymentYear)
                        <li class="nav-item ml-3" role="presentation">
                            <a class="nav-link @if($paymentYear == "All") active @endif" id="pills-home-tab" data-toggle="pill" href="#pills-{{ $paymentYear }}" role="tab" aria-controls="pills-{{ $paymentYear }}">{{ $paymentYear }}</a>
                        </li>
                    @endforeach
                </ul>
                </p>
            </div>
            <div class="card-body p-3">
                <div class="tab-content" id="pills-tabContent">
                    @foreach($calculated_payments as $paymentYear)
                        <div class="tab-pane fade @if($paymentYear == "All") show active @endif" id="pills-{{$paymentYear}}" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <p class=" mb-1">Total Invoice Amount</p>
                                            <h2 class="mb-1 font-weight-bold" id="totalAmount{{$paymentYear}}"></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <p class=" mb-1 " style="color:#009203;">Paid Invoice Amount</p>
                                            <h2 class="mb-1 font-weight-bold" id="paidAmount{{$paymentYear}}"></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <p class=" mb-1 " style="color:#ff0000">Unpaid Invoice Amount</p>
                                            <h2 class="mb-1 font-weight-bold" id="openAmount{{$paymentYear}}"></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@if(count($contracted_customers) > 0)
<div class="card">
    <div class="card-header">
        <div class="card-title">
            Contracted Customers
        </div>
    </div>
    <div class="card-body p-3">
        <div class="d-flex flex-wrap flex-row w-100">
            <a class="btn-sm btn-primary mr-2 mb-2 text-white pointer-cursor contracted-customer" id="all-customers">All</a>
            @foreach($contracted_customers as $contracted_customer)
            <a class="btn-sm btn-gray mr-2 mb-2 text-white pointer-cursor contracted-customer" id="{{ $contracted_customer->id }}">{{ $contracted_customer->org_name }}</a>
            @endforeach
        </div>
    </div>
</div>
@endif
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
            <div class="card-title w-9">
                @if($type == "offer")
                Offers
                @elseif($type == "invoice")
                Invoices
                @endif
            </div>
            @if($type == "invoice")
            <div class="d-flex w-90">
                <a class="btn btn-sm btn-primary" id="show-all-invoices">Show All Invoices</a>
                <a class="btn btn-sm btn-secondary ml-2" id="show-unpaid-invoices">Show Unpaid Invoices</a>
                <a class="btn btn-sm btn-dark text-white ml-2" id="show-blacklisted-invoices">Show Blacklisted Invoices</a>
            </div>
            @endif
            <div class="page-rightheader">
                <div class="btn btn-list">
                    <a href="{{url('/accounting-tr/add/'.$company->route_name.'/'.$type)}}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-plus-circle mr-1"></i> New @if($type=="offer") Offer @elseif($type=="invoice") Invoice @endif</a>
                </div>
            </div>
        </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap datatable-custom-row" id="contractData">
                        <thead>
                            <tr>
                                @if($type=="offer")
                                <th class="w-7">Offer No</th>
                                <th class="w-7">Invoice No</th>
                                @elseif($type == "invoice")
                                <th class="w-7">Invoice No</th>
                                <th class="w-7">Offer No</th>
                                @endif
                                <th class="w-7">Official Invoice</th>
                                <th class="w-7">Ticket ID(s)</th>
                                <th class="w-11">Customer</th>
                                <th class="w-7">Company</th>
                                @if($type == "invoice")
                                <th class="w-7">Repeat Date</th>
                                @endif
                                <th class="w-10">Added By</th>
                                <th class="w-7 ">Total Amount</th>
                                @if($type == "invoice")
                                <th class="w-7 ">Unpaid Payment</th>
                                @endif
                                <th class="w-11">Subject</th>
                                <th class="w-7 ">Date</th>
                                <th class="w-7 ">Delivery Date</th>
                                <th>Due Date</th>
                                @if($type == "offer")
                                <th class="w-7 ">Mail Status</th>
                                @else
                                <th class="w-7 ">Invoice Robot</th>
                                @endif
                                <th class="w-4 ">Actions</th>
                            </tr>
                            <tr>
                                @if($type=="offer")
                                <th class="w-7">Offer No</th>
                                <th class="w-7">Invoice No</th>
                                @elseif($type == "invoice")
                                <th class="w-7">Invoice No</th>
                                <th class="w-7">Offer No</th>
                                @endif
                                <th class="w-7">Official Invoice</th>
                                <th class="w-7">Ticket ID(s)</th>
                                <th class="w-11">Customer</th>
                                <th class="w-7">Company</th>
                                @if($type == "invoice")
                                <th class="w-7">Repeat Date</th>
                                @endif
                                <th class="w-10">Added By</th>
                                <th class="w-7 ">Total Amount</th>
                                @if($type == "invoice")
                                <th class="w-7">Unpaid Payment</th>
                                @endif
                                <th class="w-11">Subject</th>
                                <th class="w-7 ">Date</th>
                                <th class="w-7 ">Delivery Date</th>
                                <th>Due Date</th>
                                @if($type == "offer")
                                <th class="w-7 ">Mail Status</th>
                                @else
                                <th class="w-7 ">Invoice Robot</th>
                                @endif
                                <th class="w-4 ">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="d-none" id="t-foot">
                        <tr>
                            @if($type == "offer")
                                <th colspan="6"></th>
                                <th class="bg-light font-weight-bold text-danger text-center" style="font-size: 1rem; border-right-color: white;">
                                    Total
                                </th>
                                <th class="bg-light font-weight-bold text-center" style="font-size: 1rem; color: black; border-left-color: white;"></th>
                                <th colspan="6"></th>
                            @else
                                <th colspan="7"></th>
                                <th class="bg-light font-weight-bold text-danger text-center" style="font-size: 1rem; border-right-color: white;">
                                    Total
                                </th>
                                <th class="bg-light font-weight-bold text-center" style="font-size: 1rem; color: black; border-left-color: white; border-right-color: white;"></th>
                                <th class="bg-light font-weight-bold text-center" style="font-size: 1rem; color: black; border-left-color: white;"></th>
                                <th colspan="6"></th>
                            @endif
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($type == "invoice")
<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="payment_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payment-modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payment_form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_number" id="invoice_number" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Invoice Amount</label>
                        <input type="text" class="form-control" name="invoice_amount" id="invoice_amount" disabled>
                    </div>
                    <div class="form-group" id="get-payment-group">
                        <label class="form-label">Receive Payment & Date <span class="text-danger">*</span></label>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <input type="text" class="form-control validate[required]" name="receive_payment" id="receive_payment" placeholder="Enter a numeric value." data-type="currency" required>
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="payment_date" id="payment_date" class="form-control validate[required]" max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{\Carbon\Carbon::now()->format("Y-m-d")}}" data-default="{{\Carbon\Carbon::now()->format("Y-m-d")}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="result" class="form-label">Unpaid Amount</label>
                        <input type="text" id="result" name="result" class="form-control" data-type="currency" disabled>
                        <input type="hidden" id="total_payment" name="total_payment">
                        <input type="hidden" id="payment_status" name="status">
                        <input type="hidden" id="payment_id" name="id">
                    </div>
                    <div class="form-group" id="payments-table">
                        <label for="payment_history_table" class="form-label mb-2">Payment History</label>
                        <table id="payment_history_table" class="w-100">
                            <thead>
                                <tr style="border-bottom: 1px solid black;">
                                    <th class="default-cursor">Payment</th>
                                    <th class="default-cursor">Date</th>
                                    <th class="default-cursor">Added By</th>
                                    <th class="default-cursor">Log Date</th>
                                    <th class="default-cursor text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="payment-history-body">
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="invoice-payment-button" onclick="paymentForm()">Payment</button>
            </div>
        </div>
    </div>
</div>
@endif

<input type="hidden" id="contracted-customer" value="all-customers">

@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js?v=2') }}"></script>
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
<script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script>
    let checkTable="";
    function contractData(status, organization) {
            $('thead tr input').on("click", function(e) {
                e.stopPropagation();
            });
              checkTable = $('#contractData').DataTable({
                initComplete: function() {

                    if(!checkTable.data().count()) {
                        $('#t-foot').addClass('d-none');
                    } else {
                        $('#t-foot').removeClass('d-none');
                    }

                    let input = '<input type="text" class="form-control form-control-sm" placeholder="Search"/>';
                    let input_date = '<input type="date" class="form-control form-control-sm"/>';
                    @if($type == "invoice")
                    this.api().columns([0, 1, 2, 3, 4, 5, 7, 8, 9, 10]).every(function () {
                         let column = this;
                         $(input).appendTo($(this.header()).empty()).on("keyup", function () {
                                    let val = $(this).val();
                                    column.search(val, true, false).draw();
                                });
                    });
                    @else
                    this.api().columns([0, 1, 2, 3, 4, 5, 6, 7, 8]).every(function () {
                        let column = this;
                        $(input).appendTo($(this.header()).empty()).on("keyup", function () {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });
                    @endif

                    @if($type == "invoice")
                    this.api().columns([11, 12, 13]).every(function () {
                        let column = this;

                         $(input_date).appendTo($(this.header()).empty()).on("change", function () {
                                    let val = $(this).val();
                                    column.search(val, true, false).draw();
                                });
                    });
                    @else
                    this.api().columns([9, 10, 11]).every(function () {
                        let column = this;

                        $(input_date).appendTo($(this.header()).empty()).on("change", function () {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });
                    @endif

                    @if($type === "invoice")
                    this.api().columns(6).every(function() {
                        let column = this;
                        let checkbox = `<input type="checkbox" class="mb-2" id="repeat-date-checkbox">`;
                        $(checkbox).appendTo($(this.header()).empty()).on("change", function() {
                            let val = !!$("#repeat-date-checkbox").is(":checked");
                            column.search(val, true, false).draw();
                        });
                    });
                    @endif

                    this.api().columns([{{$type == "invoice" ? 14 : 12}}]).every(function () {
                        let column = this;
                        let input;
                        @if($type == "offer")
                            input="<select class='form-control form-control-sm'><option value=''>All</option><option value='1'>Yes</option><option value='0'>No</option></select>";
                        @else
                            input="<select class='form-control form-control-sm'><option value=''>All</option><option value='1'>Running</option><option value='2'>Not Running</option><option value='3'>Not Set</option></select>";
                        @endif
                        $(input)
                            .appendTo($(this.header()).empty())
                            .on("change", function () {
                                let val = $(this).val();

                                column.search(val, true, false).draw();
                            });
                    });

                    this.api().columns({{$type == "invoice" ? 15 : 13}}).every(function (){
                       $(this.header()).empty();
                    });
                },
                "processing": true,
                "serverSide": true,
                "stateSave": false,
                "destroy": true,
                "paging": true,
                "order":false,
                "ajax": {
                    url: "/accounting-tr/get-data/{{$company->route_name}}/{{$type}}/?status=" + status + "&organization=" + organization,
                    type: "GET",
                },
                select: true,
                "columns": [
                    {
                        "data": "no",
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
                        "data": "{{$type=="offer" ? "proforma_no" : "offer_no"}}",
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
                        "data": "official_invoice",
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
                        "data": "ticket_id",
                        "visible": true,
                        "orderable": false,
                        render: function(data) {
                            if(data) {
                                if(data.length > 1){
                                    let dropdownItems = '';
                                    for (let i = 1; i < data.length; i++) {
                                        dropdownItems += `<a class="dropdown-item" href="/update-ticket/${data[i]}" target="_blank">#${data[i]}</a>`;
                                    }
                                    return `<div class="dropdown">
                                                <a class="text-primary tippy-tooltip" data-tippy-content="Go to Ticket" href="/update-ticket/${data[0]}" target="_blank">#${data[0]}</a>
                                                <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >(${data.length - 1} more)</a>
                                                <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    ${dropdownItems}
                                                </div>
                                            </div>`;
                                }else{
                                    return `<a class="text-primary tippy-tooltip" data-tippy-content="Go to Ticket" href="/update-ticket/${data[0]}" target="_blank"> #${data[0]} </a>`
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "customer",
                        "visible": true,
                        "orderable": false,
                        render: function(data) {
                            if(data) {
                                if(data.length < 45) {
                                    return data;
                                }
                                else {
                                    return "<span class='tippy-tooltip' data-tippy-content='" + data + "'>" + data.substr(0,35) + "..." + "</span>";
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "company",
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
                    @if($type == "invoice")
                    {
                        "data": "repeat_date",
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
                    @endif
                    {
                        "data": "add_by",
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
                        "data": "total_amount",
                        "visible": true,
                        "orderable": false,
                        render: function(data) {
                            if(data) {
                                return numberFormat(data, 2, ",", ".") + " €";
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    @if($type == "invoice")
                    {
                        "data": "unpaid_payment",
                        "visible": true,
                        "orderable": false,
                        "className": "paymentRow",
                        render: function(data, type, row) {
                            if(data) {
                                if(data != "0,00") {
                                    return '<strong data-payment="1" data-invoice-number=' + row['no'] + ' data-invoice-amount="' + numberFormat(row['total_amount'], 2, ',', '.') + '" data-received-payments="' + row['received_payments'] + '" data-customer="' + row['customer'] + '" style="color:red;">' + data + ' €</strong><button class="paymentButton">Payment</button>';
                                }
                                else {
                                    return '<strong data-invoice-number=' + row['no'] + ' data-invoice-amount="' + numberFormat(row['total_amount'], 2, ',', '.') + '" data-received-payments="' + row['received_payments'] + '" data-customer="' + row['customer'] + '" style="color:green;">' + data + ' €</strong><button class="payment-log">Payment Logs</button>'
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    @endif
                    {
                        "data": "title",
                        "visible": true,
                        "orderable": false,
                        render: function(data) {
                            if(data.title) {
                                if(data.title.length < 35) {
                                    return data.title;
                                }
                                else {
                                    return `<span class='tippy-tooltip' data-tippy-content='${data.htmlTitle}' data-html="true">${data.title.substr(0,35)}...</span>`;
                                }
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
                        "data": "delivery_date",
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
                        "data": "deadline",
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
                        "data": "is_mail_send",
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
                        "data": "actions",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if(data) {
                                let html = "-";

                                if(row.filename) {
                                    html = "<a class='btn btn-sm btn-primary' href='/uploads/" + row.filename + "' target='_blank'><i class='fa fa-eye'></i></a>";
                                }

                                return "<div class='d-flex justify-content-center'><div>" + html + "</div></div><input type='hidden' value='" + data + "'>";
                            }
                            else {
                                return "-"
                            }
                        }
                    },
                ],
                "iDisplayLength": 25,
                "language": {
                    "search": "Search (All):",
                    "thousands": ".",
                    "processing": "<i class='fa fa-refresh fa-spin'></i>",
                },
                 "createdRow": function(row, data) {
                    if(data.is_cancel === "Yes") {
                        $(row).css("background", "#ad2903").addClass("text-white");
                        $(row).find(".text-primary").css("cssText", "color:#fff!important");
                    }
                 },

                 // Total Calculations on Footer (Ignore "canceled" ones)
                 footerCallback: function (row, data, start, end, display) {
                     let api = this.api();
                     let intVal = function (i) {
                         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                     };
                     @if($type == "offer")
                     let total = 0;
                     api.data().each( function (index) {
                         if(index['is_cancel'] != "Yes") {
                             total += index['total_amount'];
                         }
                     });

                     api.column(7).footer().innerHTML = numberFormat(total, 2, ',', '.') + ' €';
                     @else
                     let invoiceTotal = 0;
                     api.data().each(function(index) {
                         if(index['storno_no'] == null) {
                             invoiceTotal += index['total_amount'];
                         }
                     });

                     let unpaidTotal = 0;
                     api.data().each( function (index) {
                         if(index['storno_no'] == null) {
                             unpaidTotal += parseFloat(index['unpaid_payment'].replace(/\./g, "").replace(/\,/g, "."));
                         }
                     });

                     api.column(8).footer().innerHTML = numberFormat(invoiceTotal, 2, ',', '.') + ' €';
                     api.column(9).footer().innerHTML = numberFormat(unpaidTotal, 2, ',', '.') + ' €';
                     @endif
                 }
            });
        }
    @if($type == "invoice")

    function deletePayment(paymentID) {
        $('#payment_modal').modal('hide');
        confirmModal('Are you sure that you want to delete this payment?',"Delete Payment","Delete","Close").then(function() {
            toggleLoader(true);
            $.ajax({
                url: '/accounting-tr/delete-payment',
                type: 'POST',
                dataType: 'JSON',
                data: 'payment_id=' + paymentID + '&_token={{csrf_token()}}',
            }).done(function() {
                checkTable.ajax.reload(null, false);
                toastr.success("Payment deleted successfully!", "Success");
                $('#delete_payment_modal').modal('hide');
                toggleLoader(false);
                getPaymentMonitoring()
            }).fail(function() {
                toggleLoader(false);
                toastr.error("Payment deletion has failed!", "Error");
            });
        });
    }

    function paymentSection(id, invoiceNo, totalPayment, invoiceAmount, customer) {
        $('#payment_date').val($('#payment_date').data('default'));
        $('#receive_payment').val('');
        $('#payment_id').val(id);
        $('#invoice_number').val(invoiceNo);
        $('#total_payment').val(totalPayment);
        $('#invoice_amount').val(invoiceAmount + ' €');
        document.getElementById('payment-modal-title').innerHTML = 'Invoice Payment for ' + customer;
        var total_payment = parseFloat($('#total_payment').val());
        var invoice_amount = parseFloat($('#invoice_amount').val().replace(/\./g, "").replace(/\,/g, "."));
        $('#result').val(numberFormat(invoice_amount - total_payment, 2, ',', '.') + ' €');

        $.ajax({
            url: '/accounting-tr/payment-history/' + invoiceNo,
            type: 'GET',
            cache: false,
            success: function(response) {
                $('#payment-history-body').empty();
                if(response) {
                    $.each(response, function (key, value) {
                        $('#payments-table').show();
                        $('#payment-history-body').append("<tr>\
                            <td class='default-cursor' style='height: 2rem'>" + value.payment_amount + "</td>\
                            <td class='default-cursor' style='height: 2rem'>" + value.payment_time + "</td>\
                            <td class='default-cursor' style='height: 2rem'>" + value.created_by + "</td>\
                            <td class='default-cursor' style='height: 2rem'>[" + value.creation_time + "]</td>\
                            <td class='default-cursor d-flex justify-content-center align-items-center' style='height: 2rem'><a onclick='deletePayment(" + value.payment_id + ")'><i class='fa fa-trash text-danger'></i></a></td>\
                            </tr>"
                        );
                    })
                    if(response.length == 0) {
                        $('#payments-table').hide();
                    }
                }
            }
        });
        $('#payment_modal').modal('show');
        $('#invoice-payment-button').show();

        if(total_payment == invoice_amount) {
            $('#get-payment-group').hide();
            $('#invoice-payment-button').hide();
        }
        else {
            $('#get-payment-group').show();
            $('#invoice-payment-button').show();
        }
    }

    function paymentForm() {
        var formid = 'payment_form';
        var validate = $('#'+formid).validationEngine('validate',{
            scroll: false
        });

        if(!validate) {
            return false;
        }
        else {
            var data = {
                'received_payments':$('#receive_payment').val(),
                'payment_date':$('#payment_date').val(),
                'payment_status':$('#payment_status').val(),
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'invoice_number':$('#invoice_number').val(),
            }
            toggleLoader(true);

            $.ajax({
                url: '/accounting-tr/receive-payment',
                type: "POST",
                data: data
            }).done(function() {
                checkTable.ajax.reload(null, false);
                toastr.success("Payment received successfully!", "Success");
                $('#payment_modal').modal('hide');
                toggleLoader(false);
                getPaymentMonitoring()
            }).fail(function() {
                toastr.error("Payment has failed!", "Error");
                toggleLoader(false);
            });
        }
    }

    function precisionPrice(price) {
        return Math.round(price * 100) / 100;
    }
    @endif

    function resetDataTable() {
            $('#contractData').DataTable().clear();
            $('#contractData').DataTable().destroy();
        }
@if($type == "invoice")
    function getPaymentMonitoring(){
        $.ajax({
            url: `/getucon/accounting-tr/{{$ref_company}}/payment-monitoring`,
            type: "POST",
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
        }).done(function(data) {
            if(data.length > 0){
                $.each(data, function (key, value) {
                    $("#totalAmount"+value.year).text(numberFormat(value.total, 2, ",", ".") + " €")
                    $("#paidAmount"+value.year).text(numberFormat(value.payments, 2, ",", ".") + " €")
                    $("#openAmount"+value.year).text(numberFormat(value.unpaid, 2, ",", ".") + " €")
                })
            }
        }).fail(function() {

        });
    }
@endif

    $(document).ready(function() {
        contractData("all", "all-customers");

        $("#show-blacklisted-invoices").on("click", function() {
            resetDataTable();
            contractData("blacklist", $("#contracted-customer").val());
        });

        $("#show-all-invoices").on("click", function() {
            resetDataTable();
            contractData("all", $("#contracted-customer").val());
        });

        $("#show-unpaid-invoices").on("click", function() {
            resetDataTable();
            contractData("unpaid", $("#contracted-customer").val());
        });

        $(".contracted-customer").on("click", function() {
            let id = $(this).attr("id");
            $(".contracted-customer").removeClass("btn-primary");
            $("#all-customers").addClass("btn-gray");
            $(this).addClass("btn-primary");
            $("#contracted-customer").val(id);
            resetDataTable();
            contractData("all", id);
        });

        tippy.delegate("#contractData", {
            target: ".tippy-tooltip",
            allowHTML: true,
        });

        $('#contractData tbody').on('click', 'tr td:not(:last-child,:nth-child(2),:nth-child(3),:nth-child(4),:nth-child(12))', function() {
                var data = $(this).closest('tr').find('input').val();
                var className = $(this).attr('class');
                if(className == ' paymentRow') {
                    var totalPayment = $(this).children("strong").data('received-payments');
                    var invoiceAmount = $(this).children("strong").data('invoice-amount');
                    var invoiceNo = $(this).children("strong").data('invoice-number');
                    var customer = $(this).children('strong').data('customer');
                    paymentSection(data,invoiceNo,totalPayment,invoiceAmount, customer);

                    return false;
                }
                window.open('/accounting-tr/update/{{$ref_company}}/{{$type}}/' + data + '', '_blank');
            });

        $("#receive_payment").on("change keyup", function() {
            var total_payment = parseFloat($('#total_payment').val());
            var invoice_amount = parseFloat($('#invoice_amount').val().replace(/\./g, "").replace(/\,/g, "."));

            let received_payments = parseFloat($("#receive_payment").val().replace(/\./g, "").replace(/\,/g, "."));
            if(received_payments > precisionPrice(invoice_amount - total_payment)) {

                toastr.error('Received payment is greater than the invoice amount!', 'Error');
                $(this).val("");
                $('#result').val(numberFormat(invoice_amount - total_payment, 2, ',', '.') + '€');

                if(received_payments < 0) {
                    $(this).val("");
                    $('#result').val(numberFormat(invoice_amount - total_payment, 2, ',', '.') + '€');
                }
                return false;
            }
            if(received_payments > 0) {
                var calc = precisionPrice(invoice_amount - (received_payments + total_payment));
            }
            else {
                var calc = precisionPrice(invoice_amount - total_payment);
            }

            if(invoice_amount === precisionPrice(received_payments + total_payment)) {
                $("#payment_status").val(2);
            }
            else {
                $("#payment_status").val(1);
            }
            $('#result').val(numberFormat(calc, 2, ',', '.') + '€');
        });
        @if($type == "invoice")
            getPaymentMonitoring()
        @endif
    });
</script>
@endsection
