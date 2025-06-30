@extends('layouts.master')
@section('css')
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet"/>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 25px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 17px;
        width: 21px;
        left: 6px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .total-row-custom-class {
        padding: 0.4rem !important;
        color: black !important;
        font-size: 13px !important;
        font-weight: 725 !important;
    }
</style>
@endsection
@section('page-header')
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">Bills</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Bills</a></li>
        </ol>
    </div>
</div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header w-100 d-flex justify-content-between">
                    <div class="card-title w-20">Bills</div>
                    <div class="row w-40">

                    <div class="col-md-12">
                            <div class="form-group  row">
                                <div class="col-md-8">
                                    <select id="company" name="organization"
                                            class="form-control privateValidateControl">
                                        <option selected value="">Select an organization.</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control select2" id="status">
                                        <option value="6">Done & Proofed</option>
                                        <option value="7">Invoiced</option>
                                        <option value="9">Closed</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                        <table class="table table-bordered no-wrap w-100 datatable-custom-row" id="billsData">
                            <thead>
                            <tr>
                                <th class="w-5 border-bottom-0 search-column">ID</th>
                                <th class="w-15 border-bottom-0">Organization</th>
                                <th class="w-15 border-bottom-0 search-column">Personnel</th>
                                <th class="w-20 border-bottom-0 search-column">Subject</th>
                                <th class="w-10 search-column">Category</th>
                                <th class="w-5 border-bottom-0">Transport</th>
                                <th class="w-6 border-bottom-0 pl-1 pr-1 pb-2">Spent Time</th>
                                <th class="w-7 date-column border-bottom-0">Create Date</th>
                                <th class="w-7 date-column border-bottom-0">Done Date</th>
                                <th class="w-5 export-column border-bottom-0 text-center">Choose All</th>
                            </tr>
                            <tr class="second-row" style="display: none;">
                                <th class="w-5 border-bottom-0 search-column">ID</th>
                                <th class="w-15 border-bottom-0"></th>
                                <th class="w-15 border-bottom-0 search-column">Personnel</th>
                                <th class="w-20 border-bottom-0 search-column">Subject</th>
                                <th class="w-10 search-column">Category</th>
                                <th class="w-5 border-bottom-0"></th>
                                <th class="w-6 border-bottom-0 pl-1 pr-1 pb-2"></th>
                                <th class="w-7 date-column border-bottom-0">Create Date</th>
                                <th class="w-7 date-column border-bottom-0">Done Date</th>
                                <th class="w-5 export-column border-bottom-0 text-center">Choose All</th>
                            </tr>
                            </thead>
                            <tbody id="dataTableTbody" class="dataTable"></tbody>
                            <tfoot>
                            <tr>
                                <th class="w-5 ">Total</th>
                                <th class="w-15">&nbsp;</th>
                                <th class="w-15">&nbsp;</th>
                                <th class="w-20">&nbsp;</th>
                                <th class="w-10">&nbsp;</th>
                                <th class="w-5">&nbsp;</th>
                                <th class="w-6" align="center"><span id="spent-total"></span></th>
                                <th class="w-7">&nbsp;</th>
                                <th class="w-7">&nbsp;</th>
                                <th class="w-5 p-2 text-center">
                                    <button class="btn btn-sm btn-primary" id="set-invoced" style="display: none;">Set as Invoiced</button>
                                    <button class="btn btn-sm btn-primary" id="set-closed" style="display: none;">Set as Closed</button>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="/exportAll" id="exportForm" method="post">
        @csrf
        <div id="form-datas"></div>
    </form>


@endsection
@section('js')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
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
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>

        let selectedIds = [];

        function getBillsDatatable(org_id, status = 6) {


            $('#billsData').DataTable({
                initComplete: function () {
                    let input_date = `<input type="date" class="form-control form-control-sm" placeholder="Search">`;
                    let input = `<input type="text" class="form-control form-control-sm" placeholder="Search"/>`;

                    this.api().columns(".search-column").every(function() {
                        let column = this;
                        $(input).appendTo($(this.header()).empty()).on('keyup', function() {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });

                    this.api().columns(".date-column").every(function() {
                        let column = this;
                        $(input_date).appendTo($(this.header()).empty()).on('change', function() {
                            let val = $(this).val();
                            column.search(val, true, false).draw();
                        });
                    });
                },
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "paging": true,
                "order": [],
                "ajax": {
                    url: "/getBills?org_id=" + org_id + "&status=" + status,
                    type: "GET",
                },
                fixedColumns: false,
                select: true,
                "columns": [
                    {
                        "data": "id",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            return data ? `<div class="d-flex justify-content-center"><a class="btn btn-sm btn-info text-center" href="/update-ticket/${data}" target="_blank">${data}</a></div>` : "-";
                        }
                    },
                    {
                        "data": "org_name",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            if (data) {
                                if(data.length<14)
                                {
                                    return data;
                                }
                                else{
                                    return  `<span class="tippy-tooltip" data-tippy-content="${data}">
                                                ${data.substr(0, 25)}
                                        </span>`;
                                }
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "personnel",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "subject",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            if(data) {
                                return data.length < 45 ? data : `<span class="tippy-tooltip" data-tippy-content="${data}">${data.substr(0, 45) + '…'}</span>`;
                            }
                            else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "category",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function(data, type, row) {
                            return data ? row['CategoryName'] : "-";
                        }
                    },
                    {
                        "data": "transport",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        "data": "spent_time",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            return data ? `<span class="spent-time-section">${data}</span>` : "-";
                        }
                    },
                    {
                        "data": "created_at",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            return data ? moment(new Date(data).toISOString().slice(0, 10)).format("DD.MM.YYYY") : "-";
                        }
                    },
                    {
                        "data": "done",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data) {
                            return data ? moment(new Date(data).toISOString().slice(0, 10)).format("DD.MM.YYYY") : "-";
                        }
                    },
                    {
                        "data": "tick_all",
                        "visible": true,
                        "orderable": false,
                        "searchable": true,
                        render: function (data, type, row) {

                            return "<div class='text-center'><label class=\"switch\">\n" +
                                "  <input class=\"check-ticket\" data-ticket-id=\"" + row.id + "\" type=\"checkbox\"  >\n" +
                                "  <span class=\"slider round\"></span>\n" +
                                "</label></div>\n";

                        }
                    }
                ],
                 "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "pageLength": -1,
                "columnDefs": [
                    { "width": "5%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "20%", "targets": 3 },
                    { "width": "10%", "targets": 4 },
                    { "width": "5%", "targets": 5 },
                    { "width": "6%", "targets": 6 },
                    { "width": "7%", "targets": 7 },
                    { "width": "7%", "targets": 8 },
                    { "width": "5%", "targets": 9 },
                ]
            });

            $("#billsData thead tr:eq(1) th").each(function(i) {
                if(i === 9) {
                    let html = `<label class="switch">
                                        <input class="check-ticket-all" data-ticket-id="all" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>`;
                    $(this).html(html);
                }
            });
            $(".dataTables_filter").append("<button class='btn btn-sm btn-primary ml-2' id='export-rows' style='display: none;'>Export Rows</button>");
        }

        function resetDatatable() {
            $('#billsData').DataTable().clear();
            $('#billsData').DataTable().destroy();
        }

        function convert_time(hour, minute) {
            if (minute > 59) {
                let minutes = minute % 60;
                let tmp_hour = parseInt(minute / 60);
                hour = tmp_hour + hour;
                return (hour.toString()).padStart(2,"0") + ":" + (minutes.toString()).padStart(2,"0"); // for good showing
            }
            else {
                return (hour.toString()).padStart(2,"0") + ":" + (minute.toString()).padStart(2,"0");
            }

        }

        //if a checkbox switch off , remove ticket id from ticket_ids variable
        function array_remove(array, value) {

            return array.filter(function (ele) {
                return ele !== value;
            });
        }

        function control_check_info() {
            /* if only one row selected show buttons*/
            let control = [];
            $.each($('.check-ticket'), function (index, value) {
                if ($(value).is(":checked")) {
                    control.push(1);
                }
                else {
                    control.push(0);
                }

            });
            if(control.indexOf(1) === -1) {
                $('#export-rows').hide();
                $('#set-invoced').hide();
                $('#set-closed').hide();
            }
            else {
                $('#export-rows').show();
                if($('#status').val() === "7")
                    $('#set-closed').show();
                if($('#status').val() === "6") {
                    $('#set-invoced').show();
                }
            }
        }

        function disable_checkbox(status) {
            // if status closed disable all checkboxes
            $.each($('.check-ticket'), function (index, value) {

                if (status === "9") {
                    $(value).prop("disabled", 1);
                    $('.check-ticket-all').prop("disabled", 1);
                }
                else {
                    $(value).prop("disabled", 0);
                    $('.check-ticket-all').prop("disabled", 0);
                }
            });
        }

        var ticket_ids = []; // more dynamic variable (all selected tickets saving this variable)

        $(document).ready(function () {
             // TIPPY
            tippy.delegate("#billsData", {
                target: ".tippy-tooltip",
            });

            $('#company').val("");
            let coding_hours = 0;
            let coding_minutes = 0;
            let consulting_hours = 0;
            let consulting_minutes = 0;
            let testing_hours = 0;
            let testing_minutes = 0;
            let it_support_hours = 0;
            let it_support_minutes = 0;
            let total_spent_hour = 0;
            let total_spent_minutes = 0;

            //it is necessary some section because datatable very dynamic
            function reset_times() {
                coding_hours = 0;
                coding_minutes = 0;
                consulting_hours = 0;
                consulting_minutes = 0;
                testing_hours = 0;
                testing_minutes = 0;
                total_spent_hour = 0;
                total_spent_minutes = 0;
                it_support_hours = 0;
                it_support_minutes = 0;
            }

            $('#company').on("change", function () {
                $('.second-row').show();
                let org_id = $(this).val();
                let status = $("#status").val();
                selectedIds = []
                resetDatatable();
                reset_times();
                $('#spent-total').text("");
                getBillsDatatable(org_id, status); // according to selected organization and ticket status

            });
            $('#status').on("change", function () {
                let org_id = $('#company').val();
                let status = $(this).val();
                selectedIds = []
                resetDatatable();
                reset_times();
                $('#spent-total').text("");
                getBillsDatatable(org_id, status);  // according to selected organization and ticket status

            });

            $(document).on("change", ".check-ticket", function () {
                //For effort calculatin in frontend
                let coding = $(this).parent().parent().parent().parent().find(".coding-section").text().split(":");
                let consulting = $(this).parent().parent().parent().parent().find(".consulting-section").text().split(":");
                let testing = $(this).parent().parent().parent().parent().find(".testing-section").text().split(":");
                let it_support = $(this).parent().parent().parent().parent().find(".it-support-section").text().split(":");

                let total_spent = $(this).parent().parent().parent().parent().find(".spent-time-section").text().split(":");

                let coding_h = parseInt(coding[0]);
                let coding_m = parseInt(coding[1]);

                let consulting_h = parseInt(consulting[0]);
                let consulting_m = parseInt(consulting[1]);

                let testing_h = parseInt(testing[0]);
                let testing_m = parseInt(testing[1]);

                let it_support_h = parseInt(it_support[0]);
                let it_support_m = parseInt(it_support[1]);

                let total_h = parseInt(total_spent[0]);
                let total_m = parseInt(total_spent[1]);

                if ($(this).is(":checked")) {
                    //if checkbox is checked increase time
                    coding_hours += coding_h;
                    coding_minutes += coding_m;

                    consulting_hours += consulting_h;
                    consulting_minutes += consulting_m;

                    testing_hours += testing_h;
                    testing_minutes += testing_m;

                     it_support_hours +=it_support_h;
                     it_support_minutes += it_support_m;

                    total_spent_minutes += total_m;
                    total_spent_hour += total_h;
                    ticket_ids.push($(this).data("ticket-id")); // push checked ticket
                    control_check_info();
                }
                else {
                    //if checkbox unchecked decrease time
                    coding_hours -= coding_h;
                    coding_minutes -= coding_m;

                    consulting_hours -= consulting_h;
                    consulting_minutes -= consulting_m;

                    testing_hours -= testing_h;
                    testing_minutes -= testing_m;

                    it_support_hours    -=it_support_h;
                    it_support_minutes  -= it_support_m;

                    total_spent_minutes -= total_m;
                    total_spent_hour -= total_h;
                    ticket_ids = array_remove(ticket_ids, $(this).data("ticket-id")); // remove unchecked ticket
                    control_check_info();
                }
                /*
                * convert time is beautiful showing and if minute more than 60,move 1 hour to hour section!!*/
                $("#coding-total").text(convert_time(coding_hours, coding_minutes));
                $("#consulting-total").text(convert_time(consulting_hours, consulting_minutes));
                $("#testing-total").text(convert_time(testing_hours, testing_minutes));
                $("#it-support-total").text(convert_time(it_support_hours, it_support_minutes));
                $("#spent-total").text(convert_time(total_spent_hour, total_spent_minutes));
                $("#spent-total").parent().addClass("total-row-custom-class");
            });

            $(document).on("change", ".check-ticket-all", function () {
                if ($(this).is(":checked")) {
                    ticket_ids = [];
                    //if checked tick all ,all tickets selected
                    $.each($('.check-ticket'), function (index, value) { // get all checkbox
                        if (!$(value).is(":checked")) {
                            $(value).prop("checked", 1); // set checked
                            $(value).trigger("change"); // and trigger for calculation
                        }

                    });

                    if ($('#status').val() === "7") { // if invoiced
                        $('#set-invoced').hide();
                        $('#set-closed').show();
                    }

                    if ($('#status').val() === "6") { // if done
                        $('#set-closed').hide();
                        $('#set-invoced').show();
                    }

                    $('#export-rows').show();
                }
                else {
                    //if unchecked tick all , iterate all check box and set unckecked and trigger for calculation
                    $.each($('.check-ticket'), function (index, value) {
                        if ($(value).is(":checked")) {
                            $(value).prop("checked", 0);
                            $(value).trigger("change");
                        }

                    });
                    $('#set-invoced').hide();
                    $('#export-rows').hide();
                    $('#set-closed').hide();
                }
            });

            //datatable processing event// set total calculation empty
            $('#billsData').DataTable().on("processing.dt", function (e, settings, processing) {
                $('#coding-total').text("");
                $('#consulting-total').text("");
                $('#testing-total').text("");
                $('#it-support-total').text("");
                // $('#spent-total').text("");
                // $('.check-ticket-all').prop("checked", false);
                // $('#set-invoced').hide();
                // $('#set-closed').hide();
                showChecked()
                //reset_times(); // reset calculated fields
                ticket_ids = [];
            });

            //data table drawing event//
            //when data table creating if status is closed , disable all checkboxes
            $('#billsData').DataTable().on("draw", function () {
                let status = $('#status').val();
                showChecked()
                disable_checkbox(status);
            });

            function showChecked(){
                const $checkTicket = $('.check-ticket');
                const $checkTicketsAll = $('.check-ticket-all');
                $checkTicketsAll.prop('checked', false);
                $checkTicket.prop('checked', false);
                $checkTicket.each(function() {
                    let billId = $(this).data('ticket-id');
                    if (selectedIds.includes(billId)) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });
                $checkTicket.on('change', function () {
                    let billId = $(this).data("ticket-id");
                    if ($(this).is(':checked')) {
                        if (selectedIds.indexOf(billId) === -1) {
                            selectedIds.push(billId);
                        }
                    } else {
                        let index = selectedIds.indexOf(billId);
                        if (index !== -1) {
                            selectedIds.splice(index, 1);
                        }
                    }

                    if ($checkTicket.filter(':checked').length === $checkTicket.length) {
                        $checkTicketsAll.prop('checked', true);
                    } else {
                        $checkTicketsAll.prop('checked', false);
                    }
                });
                if($checkTicket.filter(':checked').length > 1){
                    if($('#status').val() === "7")
                        $('#set-invoced').hide();
                        $('#set-closed').show();
                    if($('#status').val() === "6") {
                        $('#set-closed').hide();
                        $('#set-invoced').show();
                    }
                }else{
                    if($('#status').val() === "7")
                        $('#set-invoced').hide();
                    if($('#status').val() === "6") {
                        $('#set-closed').hide();
                    }
                }


                if ($checkTicket.filter(':checked').length === $checkTicket.length && $checkTicket.length > 0) {
                    $checkTicketsAll.prop('checked', true);
                } else {
                    $checkTicketsAll.prop('checked', false);
                }
            }

            //set selected ticket to invoiced
            $(document).on("click", "#set-invoced", function () {
                confirmModal('Selected tickets will be updated as invoiced! <br> Tickets will be non-updatable! ',"Are you sure?","Yes","No","#0275d8","#d9534f").then(function() {
                    toggleLoader(true);
                    $.ajax({
                        url: "/updateInvoiced",
                        type: "post",
                        data: {data: selectedIds, _token: '{{csrf_token()}}'},
                        success: function (response) {
                            if (response !== "" && response !== undefined) {
                                if (response.success === 1) {
                                    let org_id = $('#company').val();
                                    let status = $('#status').val();
                                    toastr.success("Success", "Success");
                                    resetDatatable();
                                    getBillsDatatable(org_id, status); // if all is well,regenerate datatable
                                    ticket_ids = [];
                                    selectedIds = [];
                                    reset_times();
                                    toggleLoader(false);
                                }
                                else {
                                    toastr.error("An error thrown", "Error!");
                                    toggleLoader(false);
                                }
                            }
                        }

                    });
                });
            });


            $(document).on("click", "#set-closed", function () {
                confirmModal('Selected tickets will be updated as closed!',"Are you sure?","Yes","No","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url: "/updateClosed",
                        type: "post",
                        data: {data: selectedIds, _token: '{{csrf_token()}}'},
                        success: function (response) {
                            if (response !== "" && response !== undefined) {
                                if (response.success === 1) {
                                    let org_id = $('#company').val();
                                    let status = $('#status').val();
                                    toastr.success("Success", "Success");
                                    resetDatatable();
                                    getBillsDatatable(org_id, status);
                                    ticket_ids = [];
                                    selectedIds = [];
                                    reset_times();
                                }
                                else {
                                    toastr.error("An error thrown", "Error!");
                                }
                            }
                        }
                    });
                });
            });


            $(document).on("click", "#export-rows", function () {

                //ticket_ids

            let htmlDataForm = '';
            //all selected ticket ids append form element because response is an Excel file, we want to download it
            $.each(selectedIds, function (index, value) {
                htmlDataForm+='<input type="hidden" name="data[]" value="'+value+'">';
            });

                $('#form-datas').append(htmlDataForm); // this is inside form element
                $('#exportForm').submit(); // form submit

                $('#form-datas').html(""); // after form submit clear all selected ticket id from form element, IMPORTANT: csrf token must be outside the element
            });

            $('#company').select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });

        });

    </script>
@endsection
