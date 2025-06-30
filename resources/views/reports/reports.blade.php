@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/reports.css') }}" rel="stylesheet" />
    <style>
        .select2-selection .select2-selection--single {
            width: 200px;
        }

        .error-border {
            border: 1px solid red;
        }
    </style>
@endsection
@section('page-header')
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">Reports</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Reports</a></li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Reports</div>
            </div>
            <div class="card-body">
                <form id="organization-form" action="/getReportSummary/all" method="POST">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md-2 col-lg-2 d-flex align-items-center">
                            <div class="form-group ">
                                <div class="form-label">Summary Report</div>
                                <div class="custom-controls-stacked">
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="reportType" value="1" checked="">
                                        <span class="custom-control-label">Organization</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="reportType" value="2">
                                        <span class="custom-control-label">Freelancers</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 col-lg-10">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 text-center">
                                    <span class="form-label selectTitle">Organization</span>
                                </div>
                                <div class="col-md-2 col-lg-2 text-center">
                                    <span class="form-label">Ticket Status</span>
                                </div>
                                <div class="col-md-2 col-lg-2 text-center">
                                    <span class="form-label">Start date</span>
                                </div>
                                <div class="col-md-2 col-lg-2 text-center">
                                    <span class="form-label">End date</span>
                                </div>
                                <div class="col-md-1 col-lg-1 text-center">
                                    <span class="form-label">File Type</span>
                                </div>
                                <div class="col-md-2 col-lg-2 text-center">
                                    <span class="form-label text-center">IT Category</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2">
                                    <input type="hidden" name="returnType" value="pdf" />
                                    <div class="form-group mb-0 selectOrganization">
                                        <select id="organization" name="organization"
                                            class="form-control privateValidateControl">
                                            <option selected value="">Select organization</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0 selectFreelancers">
                                        <select id="freelancer" name="freelancer"
                                                class="form-control privateValidateControl">
                                            <option selected value="">Select Freelancer</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="type" value="pdf">
                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group mb-0">

                                        <select id="status" name="status" class="form-control privateValidateControl">
                                            <option selected value="">Select status</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                @if($status->id == 6)
                                                    <option value="proofed">Done & Proofed</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group  mb-0">
                                        <input type="date" id="start-date" name="start_date" style="min-height: 38px;" class="form-control privateValidateControl">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group  mb-0">
                                        <input type="date" id="end-date" name="end_date" style="min-height: 38px;" class="form-control privateValidateControl">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    <div class="form-group  mb-0">
                                        <select name="file_type" id="file_type" class="form-control">
                                            <option selected value="pdf">PDF</option>
                                            <option value="excel">Excel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col lg-1 col-md-1 d-flex align-items-center justify-content-center">
                                    <div class="form-group d-flex align-items-center justify-content-center my-0">
                                        <input type="radio" id="without-it-category" name="it_category" value="0" checked>
                                        <label class="ml-1 mb-0" for="without-it-category">Without</label>
                                    </div>
                                </div>
                                <div class="col lg-1 col-md-1 d-flex align-items-center justify-content-center">
                                    <div class="form-group d-flex align-items-center justify-content-center my-0">
                                        <input type="radio" id="only-it-category" name="it_category" value="1">
                                        <label class="ml-1 mb-0" for="only-it-category">Only</label>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1 d-flex align-items-center">
                                    <button class="btn btn-sm btn-primary" type="button" id="submit-button-organization" style="min-height: 38px;">Export</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1" id="select-filter" style="display: none;">
                            <div class="col-md-2 col-lg-2 d-flex align-items-center"></div>
                            <div class="col-md-10 col-lg-10">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 text-center"></div>
                                    <div class="col-md-2 col-lg-2 text-center"></div>
                                    <div class="col-md-2 col-lg-2 text-center"></div>
                                    <div class="col-md-2 col-lg-2 text-center"></div>
                                    <div class="col-md-1 col-lg-1 text-center"></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">
                                        <div class="form-group mb-0"></div>
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <div class="form-group  mb-0"></div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 pl-0 pr-0">
                                        <div class="row">
                                            <div class="col-md-3"><span class="form-label ">Create Date</span></div>
                                            <div class="col-md-1">
                                                <div class="form-group  mb-0">
                                                    <input type="checkbox" id="created-at" name="created_at" class="form-check-input" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3"><span class="form-label">Done Date</span></div>
                                            <div class="col-md-1">
                                                <div class="form-group mb-0">
                                                    <input type="checkbox" id="done-date" name="done_date" class="form-check-input" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1">
                                        <div class="form-group  mb-0"></div>
                                    </div>
                                    <div class="col-lg-1 col-md-1">
                                        <div class="form-group  mb-0"></div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 d-flex align-items-center"></div>
                                </div>
                            </div>
                        </div>
                </form>
                <hr>
                <form id="ticket-export-form" action="/getOrganizationSummary/all" method="POST">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md-2 col-lg-2 d-flex align-items-center">
                            <h6>Ticket Summary Report</h6>
                        </div>
                        <div class="col-md-10 col-lg-10">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 text-center">
                                    <span class="form-label">Ticket ID</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group mb-0">
                                        <select id="ticket-id" class="form-control privateValidateControl">
                                            <option selected value="none">Select Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1 d-flex align-items-center">
                                    <button class="btn btn-sm btn-primary" id="ticket-export-btn" type="button" style="min-height: 35px;">Export</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input name="summery" value="1" style="display: none;">
                    <input type="submit" id="ticket-export-submit" style="display: none;">
                </form>
            </div>
        </div>
        @if(count($logs) > 0)
        <div class="card">
            <div class="card-header">
                <div class="card-title">Organization Summary Report Export Logs</div>
            </div>
            <div class="card-body">
                <table class="organization-report-logs-table table-bordered">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Status</th>
                            <th>Exported By</th>
                            <th>Export Time</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Date Type</th>
                            <th>IT Category</th>
                            <th>File Type</th>
                            <th>File Name</th>
                        </tr>
                    </thead>
                    <tbody class="limited-table">
                        @foreach($logs as $log)
                        @php
                        $user = \App\User::query()->find($log->user_id);
                        $user_name = ($user->first_name . " " . $user->surname) ?? "?";
                        $organization = \App\Organization::query()->find($log->organization_id);
                        $organization_name = $organization->org_name;
                        @endphp
                        <tr>
                            <td>{{ $organization_name }}</td>
                            <td>{{ $log->status_name }}</td>
                            <td>{{ $user_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format("d.m.Y [H:i:s]") }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->starting_date)->format("d.m.Y") }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->ending_date)->format("d.m.Y") }}</td>
                            <td>{{ $log->date_type }}</td>
                            <td>{{ $log->it_category_type }}</td>
                            <td>{{ $log->file_type }}</td>
                            <td><a href="/uploads/{{ $log->file_name }}" target="_blank">{{ $log->file_name }}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
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
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script>
        function valitate_organization() {
            let file_type = $("#file_type").val();
            let report_type = $('input[type=radio][name=reportType]:checked').val();
            let org_name = $('#organization');
            if(report_type==2){
                org_name = $('#freelancer');
            }
            let start_date = $('#start-date');
            let end_date = $('#end-date');
            let status = $('#status');
            if (org_name.val() === "" || org_name.val() === null) {
                org_name.addClass("error-border");
                return false;
            }
            if (status.val() === "" || status.val() === null) {
                status.addClass("error-border");
                return false;
            }
            if (start_date.val() === "") {
                start_date.addClass("error-border");
                return false;
            }
            if (end_date.val() === "") {
                end_date.addClass("error-border");
                return false;
            }

            return true;
        }
        $(document).ready(function() {
            $('#users').select2({
                ajax: {
                    url: '/getUsersRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });
            $('#organization').select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $("#ticket-id").select2({
                minimumInputLength:2,//id 1 ile 100 arasındaysa  bulmaz <<bug>>
                ajax: {
                    url: '/tickets/ticketsRaw',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $("#ticket-export-btn").on("click", function() {
                toggleLoader(true);
                let ticket_id = $("#ticket-id").val();
                if (ticket_id !== "") {
                    $.ajax({
                        url: "/getReportSummary/" + ticket_id,
                        type: "POST",
                        data: $("#ticket-export-form").serialize()
                    }).done((response)=>{
                        toggleLoader(false);

                        window.open("/tempfiles/"+response.tempfile+"/"+encodeURI(response.filename))
                    });



                }
            });


            $('#submit-button-organization').on("click", function() {

                if (valitate_organization()) {
                    toggleLoader(true);
                    $.ajax({
                        url: "/getReportSummary/all",
                        type: "POST",
                        data: $("#organization-form").serialize(),
                        dataType:"json"
                    }).done((response)=>{
                        toggleLoader(false);

                            window.open("/tempfiles/"+response.tempfile+"/"+encodeURI(response.filename))


                    }).fail(function (data) {
                        toggleLoader(false);
                    });

                }

            });

            $("#status").on("change",function (){
               if($(this).val() === "proofed" || $(this).val() === "6"){
                    $("#select-filter").fadeIn(200);
                    $("#created-at").prop("disabled",true);
                    $("#done-date").prop("disabled",false);
                    $("#done-date").prop("checked",true);

               }
               else{
                   $("#select-filter").fadeOut(200);
                   $("#created-at").prop("disabled",true);
                   $("#done-date").prop("disabled",true);
                   $("#done-date").prop("checked",false);
               }

            });

            $("#created-at").on("change",function (){
                if($(this).is(":checked")) {
                    $("#done-date").prop("disabled", true);
                    $("#done-date").prop("checked",false);

                }
                else {

                    $("#done-date").prop("disabled", false);
                    $("#done-date").prop("checked",true);
                }
            });

            $("#done-date").on("change",function (){
                if($(this).is(":checked")) {
                    $("#created-at").prop("disabled", true);
                }
                else {

                    $("#done-date").prop("disabled", true);
                    $("#created-at").prop("checked", true);
                    $("#created-at").prop("disabled",false);
                }
            });

            $("#status").trigger("change");

            $('.privateValidateControl').on("keyup change", function() {
                $(this).removeClass("error-border");
            });

            $('#freelancer').select2({
                ajax: {
                    url: '/getFreelancerUsersRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('.selectFreelancers').hide();
            $('input[type=radio][name=reportType]').change(function() {
                if (this.value == 1) {
                    $('.selectFreelancers').hide();
                    $('.selectOrganization').show();
                    $('.selectTitle').text('Organization');
                }
                else if (this.value == 2) {
                    $('.selectFreelancers').show();
                    $('.selectOrganization').hide();
                    $('.selectTitle').text('Freelancer');
                }
            });
        });
    </script>
@endsection
