@extends('layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/custom.datatable.row.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{URL::asset('assets/plugins/multipleselect/multiple-select.css')}}">

<style>


    .ms-parent{
        padding-bottom: 8px !important;
    }
    .ms-choice{
        padding: 1rem 0rem 1rem 0rem;
        height: 30px;

    }

    .blink-button {
        background-color: #BA3129;
        -webkit-border-radius: 60px;
        border-radius: 60px;
        border: none;
        color: #eeeeee;
        cursor: pointer;
        display: inline-block;
        font-family: sans-serif;
        font-size: 20px;
        padding: 5px 15px;
        text-align: center;
        text-decoration: none;
    }

    @keyframes glowing {
        0% {
            background-color: #BA3129;
            box-shadow: 0 0 5px #BA3129;
        }

        50% {
            background-color: #d53830;
            box-shadow: 0 0 20px #d53830;
        }

        100% {
            background-color: #d53830;
            box-shadow: 0 0 5px #d53830;
        }
    }

    @keyframes userTagAlert {
        0% {
            background-color: #BA3129;
            box-shadow: 0 0 5px #BA3129;
        }

        50% {
            background-color: #d53830;
            box-shadow: 0 0 12px #d53830;
        }

        100% {
            background-color: #d53830;
            box-shadow: 0 0 5px #d53830;
        }
    }

    @keyframes openedTicketCounterRed {
        0% {
            background-color: #BA3129;
            box-shadow: 0 0 5px #BA3129;
        }

        50% {
            background-color: #d53830;
            box-shadow: 0 0 12px #d53830;
        }

        100% {
            background-color: #d53830;
            box-shadow: 0 0 5px #d53830;
        }
    }

    @keyframes openedTicketCounterOrange {
        0% {
            background-color: #eeb90a;
            box-shadow: 0 0 5px #eeb90a;
        }

        50% {
            background-color: #eeb90a;
            box-shadow: 0 0 12px #eeb90a;
        }

        100% {
            background-color: #eeb90a;
            box-shadow: 0 0 5px #eeb90a;
        }
    }

    @keyframes openedTicketCounterGreen {
        0% {
            background-color: #42a601;
            box-shadow: 0 0 5px #42a601;
        }

        50% {
            background-color: #42a601;
            box-shadow: 0 0 12px #42a601;
        }

        100% {
            background-color: #42a601;
            box-shadow: 0 0 5px #42a601;
        }
    }

    @keyframes glowingYellow {
        0% {
            background-color: #ecb403;
            box-shadow: 0 0 5px #ecb403;
        }

        50% {
            background-color: #ecb403;
            box-shadow: 0 0 20px #ecb403;
        }

        100% {
            background-color: #ecb403;
            box-shadow: 0 0 5px #ecb403;
        }
    }

    .assigned-user-alert {
        animation: userTagAlert 1300ms infinite;
    }

    .opened-ticket-counter-red {
        animation: openedTicketCounterRed 1300ms infinite;
    }

    .opened-ticket-counter-orange {
        animation: openedTicketCounterOrange 1300ms infinite;
    }

    .opened-ticket-counter-green {
        animation: openedTicketCounterGreen 1300ms infinite;
    }

    .blink-button {
        animation: glowing 1300ms infinite;
    }

    .btn-status {
        background-color: #96A9B5;
        color: #FFFFFF !important;
        width: 8rem;

    }

    .org-name {
        text-align: center;
        font-weight: bold;
        font-size: 0.6rem;
    }

    .total-ticket {
        text-align: center;
        font-size: 0.7rem;
        font-weight: bold;
    }

    .main-filter-text {
        font-size: 0.8rem;
    }

    .org-btn {
        width: 8rem;
    }

    .employeeFilter {
        width: 8rem;
        text-align: center;
        font-weight: bold;
        font-size: 0.6rem;
    }

    #ticketsDueDate_filter {
        display: none !important;
    }

    @media screen and (max-width: 1366px) {
        .org-name {
            font-size: 0.7rem;
        }

        .total-ticket {
            font-size: 0.6rem;
        }
    }

    @media screen and (max-width: 500px) {
        .vip-organization {
            display: none;
        }
    }
</style>
@endsection
@section('page-header')
    <div class="page-header mt-1 mb-1">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ ucfirst(trans('words.tickets')) }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="#">{{ ucfirst(trans('words.tickets')) }}</a>
                </li>
            </ol>
        </div>
    </div>
    @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4 || auth()->user()->role_id == 7)
        <div class="row mt-3">
            <div class="col-md-1 col-lg-1 pr-0">
                <h6>Statuses</h6>
            </div>
            <div class="col-md-11 col-lg-11">

                <div class="d-flex flex-column">
                    <div class="d-flex">
                        <div class="btn-group btn-group-sm w-100 button-height-status" role="group">
                            <a id="resetDataTable" class="btn btn-status statusFilter mr-1 mb-2" data-id="all" style="background-color: #add8e6; color: #FFFFFF;">
                                <span class="main-filter-text  ">Refresh <i class="fa fa-refresh fs-14"></i></span>
                            </a>
                            <a class="btn btn-gray statusFilter mr-1 mb-2" id="opened-button" data-id="1" style="background-color: #BA3129; color: #FFFFFF;width: 8rem;">
                                <span class="main-filter-text">{{ ucfirst(trans('words.opened')) }} (<span id="opened-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-gray  statusFilter mr-1 mb-2" id="transferred-button" data-id="2" style="background-color: #BA3129; color: #FFFFFF;width: 8rem;">
                                <span class="main-filter-text">Question INTERNAL (<span id="transferred-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="3">
                                <span class="main-filter-text">{{ ucfirst(trans('words.in_progress')) }} (<span id="in-progress-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="4">
                                <span class="main-filter-text">{{ ucfirst(trans('words.answered')) }} (<span id="answered-tickets"></span>)</span>

                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="5" style="background-color: #BA3129; color: #FFFFFF;width: 8rem;">
                                <span class="main-filter-text">Question EXTERNAL (<span id="question-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="6">
                                <span class="main-filter-text">{{ ucfirst(trans('words.done')) }} (<span id="done-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="7">
                                <span class="main-filter-text">{{ ucfirst(trans('words.invoiced')) }} (<span id="invoiced-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="8">
                                <span class="main-filter-text">{{ ucfirst(trans('words.on_hold')) }} (<span id="on-hold-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="9">
                                <span class="main-filter-text">{{ ucfirst(trans('words.closed')) }} (<span id="closed-tickets"></span>)</span>
                            </a>
                            <a class="btn btn-status statusFilter mr-1 mb-2" data-id="all">
                                <span class="main-filter-text">{{ ucfirst(trans('words.all_tickets')) }} (<span id="total-tickets"></span>)</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif
    @if (auth()->user()->role_id != 5 && auth()->user()->role_id != 6 && auth()->user()->role_id != 4 && auth()->user()->role_id != 7 && count($top20) > 0)
        <div class="row vip-organization mt-2">
            <div class="col-md-1 col-lg-1">
                <h6>VIP Organizations</h6>
            </div>
            <div class="col-md-11 col-lg-11">
                <div class="d-flex flex-column">
                    <div class="d-flex">
                        <div class="btn-group btn-group-sm" role="group">
                            @foreach ($top20 as $vip)
                                @php
                                    $total_ticket = \App\Ticket::where('org_id', $vip->id)
                                        ->where('status_id', '!=', 9)
                                        ->where('status_id', '!=', 6)
                                        ->where('status_id', '!=', 7)
                                        ->count();

                                @endphp
                                @if ($loop->index <= 10)
                                    <a class="btn btn-gray text-white mb-2 org_filter mr-1 org-btn"
                                        data-id="{{ $vip->id }}">
                                        <div style="display: flex;flex-direction: column;">
                                            <span title="{{ $vip->org_name }}"
                                                class="org-name">{{ \Illuminate\Support\Str::limit($vip->org_name, 14, '...') }}
                                                ({{ $total_ticket }})
                                            </span>
                                        </div>
                                    </a>
                                @endif
                                @if ($loop->index == 10)
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="btn-group btn-group-sm" role="group">
    @endif
    @if ($loop->index > 10)
        <a class="btn btn-gray text-white mb-2 org_filter mr-1 org-btn" data-id="{{ $vip->id }}">
            <div style="display: flex;flex-direction: column;">
                <span title="{{ $vip->org_name }}"
                    class="org-name">{{ \Illuminate\Support\Str::limit($vip->org_name, 14, '...') }}
                    ({{ $total_ticket }})</span>
            </div>
        </a>
    @endif
    @if ($loop->last)
        </div>
        </div>
    @endif
    @endforeach
    </div>
    </div>
    </div>
    @endif
    @if (auth()->user()->role_id != 5 && auth()->user()->role_id != 6 && auth()->user()->role_id != 4 && auth()->user()->role_id != 7)
        <div class="row mt-2">
            <div class="col-md-1 col-lg-1 ">
                <h6>Employees</h6>
            </div>
            <div class="col-md-11 col-lg-11">
                <div style="display: flex;flex-direction: column;" class="w-100">
                    @foreach ($employees as $employee)
                        @if ($loop->first)
                            <div class="d-flex">
                                <div class="btn-group btn-group-sm" role="group">
                        @endif
                        <a class="btn btn-gray text-white mb-2 employeeFilter mr-1" data-id="{{ $employee->id }}">
                            <div style="display: flex;flex-direction: column;">
                                <span title="{{ $employee->first_name . ' ' . $employee->surname }}"
                                    class="org-name">{{ \Illuminate\Support\Str::limit($employee->first_name . ' ' . $employee->surname, 14, '...') }}
                                </span>
                            </div>
                        </a>
                        @if (($loop->index + 1) % 11 == 0)
                </div>
            </div>
            <div class="d-flex">
                <div class="btn-group btn-group-sm" role="group">
    @endif
    @if ($loop->last)
        </div>
    @endif
    @endforeach
    </div>

    </div>
    </div>
    </div>
    @endif


    <!--End Page header-->
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" id="dueDateContext">
            @if (Session::get('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close">×</button>
                    <i class="fa fa-file mr-2" aria-hidden="true"></i><span class="white">{{ session()->get('success') }}</span>
                </div>
            @endif

            <div class="card-header px-2 d-flex justify-content-between">
                <div class="card-title pl-1">
                    Due Date Tickets
                    <x-infobox info="Tickets are shown, excluding closed, invoiced and done tickets whose due date is yesterday or past!" />
                </div>
                @if (auth()->user()->role_id != 7)
                    <div class="btn btn-list">
                        <a class="btn btn-sm" style="background-color: #705ec8 !important;color: white;" onclick="refreshDueDateWithFilters()">
                            <i class="fa fa-refresh"></i> Refresh With Filters
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body pt-1">
                @if(in_array(auth()->user()->role_id, [1, 2, 3]))
                <a class="btn btn-sm pt-2 pb-2" style="background-color: #454763;color: #fff;" data-id="{{auth()->id()}}" id="show-only-me">Show Only My Tickets</a>
                <a class="btn btn-sm pt-2 pb-2" style="background-color: #454763;color: #fff;" id="show-all-tickets">Show All Tickets</a>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered text-wrap datatable-custom-row w-100" id="ticketsDueDate">
                        <thead>
                            <tr>
                                <th class="border-bottom-0 remove-column">Timer</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.no')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.organization')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.ticket_holder')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.assigned_to')) }}</th>
                                <th class="border-bottom-0 status">{{ ucfirst(trans('words.status')) }}</th>
                                <th class="border-bottom-0 category search-input">{{ ucfirst(trans('words.category')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.subject')) }}</th>
                                <th class="border-bottom-0 priority">{{ ucfirst(trans('words.priority')) }}</th>
                                <th class="border-bottom-0 ticket-due-date-table-date-input">{{ ucfirst(trans('words.due_date')) }}</th>
                                <th class="border-bottom-0 ticket-due-date-table-date-input">{{ ucfirst(trans('words.ticket_date')) }}</th>
                            </tr>
                            <tr>
                                <th class="border-bottom-0 remove-column" id="no_sorting_due">Timer</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.no')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.organization')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.ticket_holder')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.assigned_to')) }}</th>
                                <th class="border-bottom-0 status">{{ ucfirst(trans('words.status')) }}</th>
                                <th class="border-bottom-0 category search-input">{{ ucfirst(trans('words.category')) }}</th>
                                <th class="border-bottom-0 search-input">{{ ucfirst(trans('words.subject')) }}</th>
                                <th class="border-bottom-0 priority">{{ ucfirst(trans('words.priority')) }}</th>
                                <th class="border-bottom-0 ticket-due-date-table-date-input">{{ ucfirst(trans('words.due_date')) }}</th>
                                <th class="border-bottom-0 ticket-due-date-table-date-input">{{ ucfirst(trans('words.ticket_date')) }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            @if (Session::get('success'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close">×</button>
                <i class="fa fa-file mr-2" aria-hidden="true"></i><span class="white">{{ session()->get('success') }}</span>
            </div>
            @endif
            <div class="card-header px-2 d-flex justify-content-between">
                <div class="card-title pl-1 w-10">
                    All Tickets
                    <x-infobox info="All tickets order by ticket ID but exclude due date today and past tickets!" />
                </div>

                @if (auth()->user()->role_id != 7)
                    <div class="btn btn-list">
                        <a class="btn btn-sm" style="background-color: #705ec8 !important;color: white;" onclick="refreshWithFilters()">
                            <i class="fa fa-refresh"></i> Refresh With Filters
                        </a>
                        <a href="{{ url('/add-ticket') }}" class="btn btn-sm btn-info">
                            <i class="fa fa-plus-circle"></i> {{ ucfirst(trans('words.add_ticket_button')) }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-wrap datatable-custom-row w-100" id="ticketsData">
                        <thead>
                            <tr>
                                <th class="border-bottom-0 remove-column">Timer</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.no')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.organization')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.ticket_holder')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.assigned_to')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.status')) }}</th>
                                <th class="border-bottom-0 category">{{ ucfirst(trans('words.category')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.subject')) }}</th>
                                <th class="border-bottom-0 priority">{{ ucfirst(trans('words.priority')) }}</th>
                                <th class="border-bottom-0 ticket-table-date-input">{{ ucfirst(trans('words.ticket_date')) }}</th>
                                <th class="border-bottom-0 ticket-table-date-input">{{ ucfirst(trans('words.due_date')) }}</th>
                                <th class="border-bottom-0 ticket-table-date-input">Done Date</th>
                            </tr>
                            <tr>
                                <th class="border-bottom-0 remove-column" id="no_sorting">Timer</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.no')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.organization')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.ticket_holder')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.assigned_to')) }}</th>
                                <th class="border-bottom-0 pb-0 status">{{ ucfirst(trans('words.status')) }}</th>
                                <th class="border-bottom-0 category">{{ ucfirst(trans('words.category')) }}</th>
                                <th class="border-bottom-0">{{ ucfirst(trans('words.subject')) }}</th>
                                <th class="border-bottom-0 priority">{{ ucfirst(trans('words.priority')) }}</th>
                                <th class="border-bottom-0 ticket-table-date-input">{{ ucfirst(trans('words.ticket_date')) }}</th>
                                <th class="border-bottom-0 ticket-table-date-input">{{ ucfirst(trans('words.due_date')) }}</th>
                                <th class="border-bottom-0 ticket-table-date-input">Done Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
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
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.cellEdit.js') }}"></script>
<script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
<script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
<script src="{{URL::asset('assets/plugins/multipleselect/multi-select.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        var personnel = {{ isset($_GET['personnel']) ? $_GET['personnel'] : '0' }};
        var status = "{{ isset($_GET['status']) ? $_GET['status'] : 'all' }}";
        var org_id = {{ isset($_GET['org_id']) ? $_GET['org_id'] : 'null' }};
        var proofed = {{ isset($_GET['proofed']) ? $_GET['proofed'] : 'null' }};
        var due_date_personnel = "{{isset($_GET['due_date_personnel']) ? $_GET['due_date_personnel'] : '0'}}";
        // let intervalIDs = [];
        if(window.location.href.split("?")[1]=="created"){
            toastr.success('Ticket created and e-mail sent.');
        }
        window.history.pushState("object",document.title,location.href.split("?")[0]);
        var dt;
        var dt_with_due_date;

        function ticketsDueDate() {
            dt_with_due_date = $('#ticketsDueDate').DataTable({
                initComplete: function() {
                    hideDueDateTable(dt_with_due_date.rows().count());
                    setTimeout(function() {
                        $.fn.dataTable.tables({
                            visible: true,
                            api: true
                        }).columns.adjust();

                        dt_with_due_date.ajax.reload();
                    }, 1500);

                    this.api().columns('.status').every(function() {
                        let column = this;
                        let select = $('<select class="form-control form-control-sm"><option value="">All</option></select>').appendTo($(column.header()).empty()).on('change', function() {
                            let val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });
                        let options = 5;
                        let text;
                        for(let d = 1; d <= options; d++) {
                            if(d === 1) {
                                text = 'Opened';
                            }
                            if(d === 2) {
                                text = 'Question INTERNAL';
                            }
                            if(d === 3) {
                                text = 'In Progress';
                            }
                            if(d === 4) {
                                text = 'Answered';
                            }
                            if(d === 5) {
                                text = 'Question EXTERNAL';
                            }

                            if(status === d) {
                                select.append('<option value="' + d + '" selected>' + text + '</option>');
                            }
                            else {
                                select.append('<option value="' + d + '">' + text + '</option>');
                            }
                        }

                        select.append(`<option value="8">On Hold</option>`);
                    });
                    this.api().columns('.priority').every(function() {
                        let column = this;
                        let select = $('<select class="form-control  form-control-sm"><option value="">All</option></select>').appendTo($(column.header()).empty()).on('change', function() {
                            let val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });
                        let value;
                        for (let d = 1; d <= 4; d++) {
                            if (d === 1)
                                value = 'Normal';
                            if (d === 2)
                                value = 'High';
                            if (d === 3)
                                value = 'Very High';
                            if (d === 4)
                                value = 'Low';
                            select.append('<option value="' + d + '">' + value + '</option>')
                        }
                    });
                    this.api().columns(".search-input").every(function() {
                        let column = this;
                        let input = $("<input type='text' class='form-control form-control-sm' placeholder='Search'>").appendTo($(column.header()).empty()).on("keyup change", function() {
                        let val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : "", true, false).draw();
                        });

                    });
                    this.api().columns(".remove-column").every(function() {
                        $(this.header()).empty();
                    });
                    this.api().columns('.ticket-due-date-table-date-input').every(function() {
                        let column = this;
                        let input_date = '<input type="date" class="form-control form-control-sm"/>';

                        $(input_date).appendTo($(this.header()).empty()).on('change', function() {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });
                },
                "processing": true,
                "serverSide": true,
                "stateSave": false,
                "destroy": true,
                "paging": false,
                "searching": true,
                "search": {
                    "smart": false,
                },
                "scrollY": "200px",
                "scrollCollapse": true,
                "ajax": {
                    url: "/getWithDueDateTickets",
                    type: "GET",
                    data: {
                        due_date_personnel: due_date_personnel
                    }
                },
                "columns": [
                    {
                        "data": "counter_since_last_opened",
                        "width": "11%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data && row.status_id === 1) {
                                let html = "";

                                setTimer(data, row.id, 1);
                                html += `<div class="d-flex flex-column w-100 align-items-center">
                                            <span class="badge badge-warning" id="time-elapsed-text-1-${row.id}" style="min-width: 130px !important; max-width: 130px !important;"></span>
                                        </div>`;
                                return html;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "id",
                        "width": "5%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                return data + `<input type="hidden" value="${row["id"]}">`;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "org_id",
                        "width": "11%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                let organization_data = row["OrganizationName"];

                                if(organization_data.length < 18) {
                                    return row["OrganizationName"];
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${row["OrganizationName"]}">${row["OrganizationName"].substr(0, 18) + "…"}</span>`;
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "user",
                        "width": "10%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                let user_data = row["UserName"] + " " + row["SurName"];

                                if(user_data.length < 12) {
                                    return user_data;
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${user_data}">${user_data.substr(0, 12) + "…"}</span>`;
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "personnel",
                        "width": "13%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                let html = "";

                                if(row["status_id"] === 1) {
                                    $.each(data, function(index,value) {
                                        html += `<span class="badge badge-warning assigned-user-alert ml-1">${value}</span>`;
                                    });
                                }
                                else {
                                    $.each(data, function(index,value) {
                                        html += `<span class="badge badge-warning ml-1">${value}</span>`;
                                    });
                                }

                                return html;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "status_id",
                        "width": "8%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            let user_data, html;

                            if(data) {
                                if(row.proofed === 1 && row.status_id === 6) {
                                    user_data = "Done & Proofed";
                                }
                                else {
                                    user_data = row["StatusName"];
                                }

                                if(user_data.length < 14) {
                                    html = `<div><i class="fa fa-edit text-info"></i>` + user_data + `</div>`;
                                }
                                else {
                                    html = `<div><i class="fa fa-edit text-info"></i><span class="tippy-tooltip" data-tippy-content="${user_data}">${user_data.substr(0, 14) + "…"}</span></div>`;
                                }

                                return `<div class="d-flex flex-column">${html}</div>`;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "category",
                        "width": "8%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if (data) {
                                let user_data = row["CategoryName"];

                                if(user_data.length < 12) {
                                    return row["CategoryName"];
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${user_data}">${user_data.substr(0, 12) + "…"}</span>`;
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "name",
                        "width": "14%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data) {
                            if(data) {
                                let user_data = data;

                                if(user_data.length < 25) {
                                    return user_data;
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${user_data}">${user_data.substr(0, 25) + "…"}</span>`;
                                }
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "priority",
                        "width": "6%",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            return (data) ? row["PriorityName"] : "-";
                        }
                    },
                    {
                        "data": "due_date",
                        "width": "6%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "created_at",
                        "width": "6%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            if(data) {
                                return moment(new Date(data).toISOString().slice(0, 10)).format("DD.MM.YYYY");
                            }
                            else {
                                return "-";
                            }
                        }
                    }
                ],
                "iDisplayLength": 4,
                "createdRow": function(row, data) {
                    if(data.IsContracted === 1) {
                        $(row).addClass("text-danger");
                    }
                },
                "language": {
                    "thousands": ".",
                    "processing": `<i class="fa fa-refresh fa-spin"></i>`,
                }
            });

            dt_with_due_date.MakeCellsEditable("destroy"); //destroy and regenerate editable table

            dt_with_due_date.MakeCellsEditable({
                "onUpdate": editStatus,
                "inputCss": "form-control form-control-sm pt-0 pb-0 status-selectbox",
                "columns": [5],
                "inputTypes": [{
                    "column": 5,
                    "type": "list",
                    "options": [{
                            "value": "1",
                            "display": "Opened"
                        },
                        {
                            "value": "2",
                            "display": "Question INTERNAL"
                        },
                        {
                            "value": "3",
                            "display": "In Progress"
                        },
                        {
                            "value": "4",
                            "display": "Answered"
                        },
                        {
                            "value": "5",
                            "display": "Question EXTERNAL"
                        },
                        {
                            "value": "6",
                            "display": "Done"
                        },
                        {
                            "value": "7",
                            "display": "Invoiced"
                        },
                        {
                            "value": "8",
                            "display": "On Hold"
                        },
                        {
                            "value": "9",
                            "display": "Closed"
                        },
                        {
                            "value": "11",
                            "display": "Terminated"
                        }
                    ]
                }]
            });
        }

        function ticketsData() {
            let role_id = {{Auth::user()->role_id}};
            dt = $('#ticketsData').DataTable({



                initComplete: function() {
                    this.api().columns('.status').every(function() {
                        let column = this;


                            let select = $('<select multiple="multiple" class="custom-multiselect w-100 " >' +
                                //   '<option value="">All</option>' +
                                '<option value="1">Opened</option>' +
                                '<option value="2">Question INTERNAL</option>' +
                                '<option value="3">In Progress</option>' +
                                '<option value="4">Answered</option>' +
                                '<option value="5">Question EXTERNAL</option>' +
                                '<option value="6">Done</option>' +
                                '<option value="proofed">Done & Proofed</option>' +
                                '<option value="10">Correction After Invoice</option>' +
                                '<option value="7">Invoiced</option>' +
                                '<option value="8">On Hold</option>' +
                                '<option value="9">Closed</option>' +
                                '<option value="11">Terminated</option>' +
                                '</select>').appendTo($(column.header()).empty());


                                multipleSelect();
                                multiSelect();


                        select.on('change', function() {
                            let vals = $(this).val();

                            column
                                .search(vals.length > 0 ? vals : '', true, false)
                                .draw();
                        });
                    });
                    this.api().columns('.priority').every(function() {
                        let column = this;
                        let select = $('<select class="form-control form-control-sm"><option value="">All</option></select>').appendTo($(column.header()).empty()).on('change', function() {
                            let val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        });
                        let value;

                        for(let d = 1; d <= 4; d++) {
                            if (d === 1)
                                value = 'Normal';
                            if (d === 2)
                                value = 'High';
                            if (d === 3)
                                value = 'Very High';
                            if (d === 4)
                                value = 'Low';
                            select.append('<option value="' + d + '">' + value + '</option>')
                        }
                    });
                    this.api().columns(".remove-column").every(function() {
                        $(this.header()).empty();
                    });

                    this.api().columns('.ticket-table-date-input').every(function() {
                        let column = this;
                        let input_date = '<input type="date" class="form-control form-control-sm"/>';

                        $(input_date).appendTo($(this.header()).empty()).on('change', function() {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });
                },
                "processing": true,
                "serverSide": true,
                "stateSave": false,
                "destroy": true,
                "paging": true,
                "search": {
                    "smart": false,
                },
                "ajax": {
                    url: '/getTickets?status=' + status + '&org_id=' + org_id + "&personnel=" + personnel + "&proofed=" + proofed,
                    type: "GET",
                },
                select: true,
                "columns": [
                    {
                        "data": "counter_since_last_opened",
                        "width": "9%",
                        "visible": true,
                        "orderable": false,
                        "searchable": false,
                        render: function(data, type, row) {
                            if(data && row.status_id === 1) {
                                let html = "";

                                setTimer(data, row.id, 2);
                                html += `<div class="d-flex flex-column w-100 align-items-center">
                                            <span class="badge badge-warning" id="time-elapsed-text-2-${row.id}" style="min-width: 130px !important; max-width: 130px !important;"></span>
                                        </div>`;
                                return html;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "id",
                        "width": "5%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                return data + '<input type="hidden" value="' + row["id"] + '">';
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "org_id",
                        "width": "11%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if(data) {
                                let organization_data = row['OrganizationName'];

                                if (organization_data.length < 18) {
                                    return row['OrganizationName'];
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${row['OrganizationName']}">
                                                ${row['OrganizationName'].substr(0, 18) + '…'}
                                        </span>`;
                                }
                            }
                            else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "user",
                        "width": "11%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {

                            if (data) {
                                var user_data = row['UserName'] + " " + row['SurName'];
                                if (user_data.length < 12) {
                                    return user_data;
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${user_data}">
                                        ${user_data.substr(0, 12) + '…'}</span>`;
                                }
                            }
                            else {
                                return '-';
                            }

                        }
                    },
                    {
                        "data": "personnel",
                        "width": "12%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            if (data) {
                                let html = "";
                                if(row["status_id"] == 1) {
                                    $.each(data,function (index,value){
                                        html+=`<span class="badge badge-warning assigned-user-alert ml-1">${value}</span>`;
                                    });
                                }
                                else {
                                    $.each(data,function (index,value){
                                        html+=`<span class="badge badge-warning ml-1">${value}</span>`;
                                    });
                                }

                                return html;

                            }
                            else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "status_id",
                        "width": "11%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            let user_data, html;

                            if(data) {
                                if(row.proofed === 1 && row.status_id === 6) {
                                    user_data = "Done & Proofed";
                                }
                                else {
                                    user_data = row["StatusName"];
                                }

                                if(user_data.length < 14) {
                                    html = `<div><i class="fa fa-edit text-info"></i>` + user_data + `</div>`;
                                }
                                else {
                                    html = `<div><i class="fa fa-edit text-info"></i><span class="tippy-tooltip" data-tippy-content="${user_data}">${user_data.substr(0, 14) + "…"}</span></div>`;
                                }

                                return `<div class="d-flex flex-column">${html}</div>`;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "category",
                        "width": "8%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {

                            if (data) {
                                let user_data = row['CategoryName'];
                                if (user_data.length < 12) {
                                    return row['CategoryName'];
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${user_data}">
                                        ${user_data.substr(0, 12) + '…'}</span>`;
                                }
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "name",
                        "width": "16%",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data) {

                            if (data) {
                                let user_data = data;
                                if (user_data.length < 25) {
                                    return user_data;
                                }
                                else {
                                    return `<span class="tippy-tooltip" data-tippy-content="${user_data}">
                                        ${user_data.substr(0, 25) + '…'}</span>`;
                                }
                            }
                            else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "priority",
                        "width": "6%",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if (data)
                                return row['PriorityName'];
                            else
                                return '-';
                        }
                    },
                    {
                        "data": "created_at",
                        "width": "5%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
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
                        "data": "due_date",
                        "width": "5%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "close_date",
                        "width": "5%",
                        "visible": true,
                        "orderable": true,
                        "searchable": true,
                        render: function(data) {
                            if(data) {
                                return moment(new Date(data).toISOString().slice(0, 10)).format("DD.MM.YYYY");
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
                    "processing": "<i class='fa fa-refresh fa-spin'></i>",
                },
                "createdRow": function(row, data) {
                    if(data.IsContracted === 1) {
                        $(row).addClass('text-danger');
                    }
                    if(data.status_id === 9 || data.status_id === 11){
                        $($(row).children()[5]).css("pointer-events","none");
                        $($(row).children()[5]).find("i").removeClass("fa-edit").removeClass("text-info");
                        $($(row).children()[5]).find("i").addClass("fa-check").addClass("text-dark");
                    }
                    if(data.status_id === 10){
                        $($(row).children()[5]).css("pointer-events","none");
                        $($(row).children()[5]).find("i").removeClass("fa-edit").removeClass("text-info");
                        $($(row).children()[5]).find("i").addClass("fa-check").addClass("text-warning");
                    }
                    if((role_id == 4 || role_id == 7) && (data.status_id == 6 || data.status_id == 7 || data.status_id == 10 || data.status_id == 9 || data.status_id == 11)){
                        $($(row).children()[5]).css("pointer-events","none");
                        $($(row).children()[5]).find("i").removeClass("fa-edit").removeClass("text-info");
                        $($(row).children()[5]).find("i").addClass("fa-check").addClass("text-dark");
                    }
                }
            });

            dt.MakeCellsEditable("destroy"); //destroy and regenerate editable table


            dt.MakeCellsEditable({
                "onUpdate": editStatus,
                "inputCss": "form-control form-control-sm search-filter-input pt-0 pb-0 status-selectbox ",
                "columns": [5],
                "inputTypes": [{
                    "column": 5,
                    "type": "list",
                    "options": [{
                            "value": "1",
                            "display": "Opened"
                        },
                        {
                            "value": "2",
                            "display": "Question INTERNAL"
                        },
                        {
                            "value": "3",
                            "display": "In Progress"
                        },
                        {
                            "value": "4",
                            "display": "Answered"
                        },
                        {
                            "value": "5",
                            "display": "Question EXTERNAL"
                        },
                        {
                            "value": "6",
                            "display": "Done"
                        },
                        {
                            "value": "7",
                            "display": "Invoiced"
                        },
                        {
                            "value": "10",
                            "display": "Correction After Invoice"
                        },
                        {
                            "value": "8",
                            "display": "On Hold"
                        },
                        {
                            "value": "9",
                            "display": "Closed"
                        },
                        {
                            "value": "11",
                            "display": "Terminated"
                        }
                    ]
                }]
            });

            // Save status on click outside and done status change
            $(document).mouseup(function(e) {
                let container = $(".status-selectbox");

                if (!container.is(e.target) && container.has(e.target).length === 0 && container.length != 0) {
                    container.val(container.val()).trigger("change");
                }
                else {
                    // Removing options for tickets with done, invoiced, closed tickets
                    let role_id = {{Auth::user()->role_id}};
                    let container = $(e.target);
                    let optCount =  container.children("option").length;
                    let status = container.val();
                    // Ticket done statusundeyse done,invoiced veya closed seçilebilir
                    if (["6"].includes(status) && optCount > 3 && container.hasClass("status-selectbox")){
                        container.find("option[value='1']").remove();
                        container.find("option[value='2']").remove();
                        container.find("option[value='3']").remove();
                        container.find("option[value='4']").remove();
                        container.find("option[value='5']").remove();
                        container.find("option[value='8']").remove();
                        container.find("option[value='10']").remove();
                    }
                    // Ticket invoiced statusundeyse invoiced,correction after invoice veya closed seçilebilir
                    if (["7"].includes(status) && optCount > 3 && container.hasClass("status-selectbox")){
                        container.find("option[value='1']").remove();
                        container.find("option[value='2']").remove();
                        container.find("option[value='3']").remove();
                        container.find("option[value='4']").remove();
                        container.find("option[value='5']").remove();
                        container.find("option[value='6']").remove();
                        container.find("option[value='8']").remove();
                    }
                    // Ticket done veya invoiced değilse correction after invoice seçilemez!
                    if (!["6","7"].includes(status) && optCount > 3 && container.hasClass("status-selectbox")){
                        container.find("option[value='10']").remove();
                    }
                    if ((role_id == 4 || role_id == 7) && optCount > 3 && container.hasClass("status-selectbox")){
                        container.find("option[value='7']").remove();
                        container.find("option[value='9']").remove();
                        container.find("option[value='10']").remove();
                    }
                }
            });

        }

        function hideDueDateTable(total_records) {
            if(total_records === 0) {
                $("#dueDateContext").hide();
            }
            else {
                $("#dueDateContext").show();
            }
        }

        function editStatus(updatedCell, updatedRow, oldValue) {
            let new_status = updatedCell.data();
            if (oldValue != new_status) {
                let ticket_id = $(updatedRow.selector.rows[0]).find("input").val();
                $.ajax({
                    url: "/ticket/updateStatus",
                    data: "ticket_id=" + ticket_id + "&new_status=" + new_status,
                    dataType: "json",
                    type: "get",
                    success: function(response) {
                        if (response !== undefined && response !== "") {
                            if (response.success === 1) {
                                updateStatusCounters();

                                dt_with_due_date.ajax.reload(function(data) {
                                    hideDueDateTable(data.recordsTotal);
                                    toastr.success("Ticket status updated!", "Success", {timeout: 1000})
                                });

                                dt.ajax.reload();
                            }
                            else {
                                toastr.error("Status can not be changed!", "Error");
                            }
                        }
                        else {
                            toastr.error("An error thrown!", "Error");
                        }
                    }
                });
            }
        }

        function setTimer(diff, row_id, table_type) {
            setInterval(function() {
                diff += 1;
                updateTimer(diff, row_id, table_type);
            }, 1000, diff, row_id, table_type);
        }

        function updateTimer(diff, row_id, table_type) {
            let d = Math.floor(diff / (24 * 60 * 60));
            diff = diff - (d * 24 * 60 * 60);
            let h = Math.floor(diff / (60 * 60));
            diff = diff - (h * 60 * 60);
            let m = Math.floor(diff / (60));

            if(d > 0) {
                $("#time-elapsed-text-" + table_type + "-" + row_id).html(d +" days, " + h + " hours").removeClass("opened-ticket-counter-orange opened-ticket-counter-green").addClass("opened-ticket-counter-red");
            }
            else if(h > 11) {
                $("#time-elapsed-text-" + table_type + "-" + row_id).html(h +" hours, " + m + " minutes").removeClass("opened-ticket-counter-red opened-ticket-counter-green").addClass("opened-ticket-counter-orange");
            }
            else {
                $("#time-elapsed-text-" + table_type + "-" + row_id).html(h +" hours, " + m + " minutes").removeClass("opened-ticket-counter-orange opened-ticket-counter-red").addClass("opened-ticket-counter-green");
            }
        }

        // TIPPY
        tippy.delegate("#ticketsData", {
            target: ".tippy-tooltip",
        });
        tippy.delegate("#ticketsDueDate", {
            target: ".tippy-tooltip",
        })

        function resetDataTable() {
            dt.clear();
            dt.destroy();
        }


        $('.statusFilter').on('click', function() {

            $(".search-filter-input").val("");

            status = $(this).attr('data-id');
            resetDataTable();
            if (status === 'all') {
                $('#ticketsData').DataTable().state.clear();
                updateStatusCounters();
                personnel = 0;
                org_id = "null";
            }
            ticketsData();
        });
        $('#ticketsData tbody').on('click', ' tr td:not(:nth-child(6))', function() {
            let data = $(this).closest('tr').find('input').val();
            window.open('/update-ticket/' + data + '', '_blank');
        });
        $('#ticketsDueDate tbody').on('click', ' tr td:not(:nth-child(6))', function() {
            let data = $(this).closest('tr').find('input').val();
            window.open('/update-ticket/' + data + '', '_blank');
        });

        $('.org_filter').on('click', function() {

            org_id = $(this).attr('data-id');
            resetDataTable();

            ticketsData();

        });
        $('.employeeFilter').on('click', function() {
            personnel = $(this).data('id');
            resetDataTable();
            ticketsData();
        });

        function updateStatusCounters() {
            $.ajax({
                url: "/ticket/update-status-counter",
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    if(response !== undefined && response !== "") {
                        if(response.opened_tickets > 0) {
                            $('#opened-button').addClass("blink-button");
                        }
                        else {
                            $('#opened-button').removeClass("blink-button");
                        }

                        if(response.transferred_tickets > 0) {
                            $("#transferred-button").addClass("blink-button");
                        }
                        else {
                            $("#transferred-button").removeClass("blink-button");
                        }

                        $('#answered-tickets').html(response.answered_tickets);
                        $('#closed-tickets').html(response.closed_tickets);
                        $('#done-tickets').html(response.done_tickets);
                        $('#in-progress-tickets').html(response.in_progress_tickets);
                        $('#invoiced-tickets').html(response.invoiced_tickets);
                        $('#on-hold-tickets').html(response.on_hold_tickets);
                        $('#opened-tickets').html(response.opened_tickets);
                        $('#question-tickets').html(response.question_tickets);
                        $('#total-tickets').html(response.total_tickets);
                        $('#transferred-tickets').html(response.transferred_tickets);
                    }
                }
            });
        }

        function resize() {
            //resize status buttons according to vip organization button height
            $.each($(".button-height-status").children(), function(index, value) {

                $(value).css("height", $(".org-btn").outerHeight());
                $(value).find(".main-filter-text").addClass("align-top");
            })

        }

        $(document).ready(function() {
            ticketsData();
            ticketsDueDate();
            resize();
            $(window).on("resize", function() {
                resize();
            });

            $('#resetDataTable').on('click', function() {
                $(':input:not([type=hidden])').val('');
                due_date_personnel = 0;
                dt_with_due_date.context[0].ajax.data.due_date_personnel = 0;
                dt_with_due_date.ajax.reload();
            });

            $('#ticketsData thead tr:eq(1) th').each(function(i) {
                let title = $(this).text();

                if(title !== 'Ticket Date' && title !== 'Due Date' && title !== 'Action') {
                    $(this).html('<input type="text" placeholder="Search"  class="form-control form-control-sm search-filter-input "/>');
                    $('input', this).on('keyup change', function() {
                        if ($('#ticketsData').DataTable().column(i).search() !== this.value) {
                            $('#ticketsData').DataTable().column(i).search(this.value).draw();
                        }
                    });
                }
                else {
                    $(this).html('');
                }
            });



            updateStatusCounters();
            setInterval(function() {
                updateStatusCounters();
            }, 30000);


            let observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    $("#no_sorting").removeClass("sorting_asc").addClass("sorting_disabled");
                    $("#no_sorting_due").removeClass("sorting_asc").addClass("sorting_disabled");
                });
            });

            let observeTargets = document.querySelectorAll('tbody');
            observeTargets.forEach(function (item){
                observer.observe(item, { childList: true });
            });

            $("#show-only-me").on("click",function (){
                dt_with_due_date.context[0].ajax.data.due_date_personnel = $(this).data("id");
                dt_with_due_date.ajax.reload();
            });

            $("#show-all-tickets").on("click", function () {
                due_date_personnel = 0;
                dt_with_due_date.context[0].ajax.data.due_date_personnel = 0;
                dt_with_due_date.ajax.reload();
            });
        });


        function refreshWithFilters() {
            dt.ajax.reload(null, false);
        }
        function refreshDueDateWithFilters() {
            dt_with_due_date.ajax.reload(null, false);
        }
        // function clearTimerInterval() {
        //     if(intervalIDs.length>0){
        //         for (let i = 0; i < intervalIDs.length; i++) {
        //             clearInterval(intervalIDs[i]);
        //         }
        //     }
        //     intervalIDs = [];
        // }

    </script>
@endsection
