@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-selection .select2-selection--single {
            width: 200px;
        }

        .cursor-default {
            cursor: default !important;
        }

        .table tr {
            cursor: default !important;
        }

        .badge-green {
            background: #089226;
        }

        .badge-general {
            background: #96A9B5;
        }

    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header mt-0 mb-1">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ trans('words.hi') }} {{ auth()->user()->first_name }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}"><i class="fe fe-home mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="">Dashboard</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                @if (in_array('VIEW_TICKETS', auth()->user()->Permissions))
                    <a href="{{ url('/tickets') }}" class="btn btn-warning"><i class="fe fe-list mr-1"></i>
                        {{ trans('words.all_tickets') }} </a>
                @endif
            </div>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Filter
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mb-0">
                                <select id="organization" name="organization" class="form-control ">
                                    <option selected value="">Select organization</option>
                                    @isset($selectedOrganization)
                                        <option selected value="{{$selectedOrganization->id}}">{{$selectedOrganization->org_name}}</option>
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group mb-0">
                                <select id="users" name="users" class="form-control ">
                                    <option selected value="">Select Personnel</option>
                                    @isset($selectedUser)
                                        <option selected value="{{$selectedUser->id}}">{{$selectedUser->first_name}} {{$selectedUser->surname}}</option>
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <button onclick="filterDashboard()" class="btn btn-primary">Filter</button>
                            <button onclick="clearDashboard()" class="btn btn-warning">Clear Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="DashboardContainer">
        {{-- Personnel Left Part --}}
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Tickets: Personnels
                        <x-infobox info="The dashboard content gets updated every 3 minutes"/>
                    </div>
                </div>
                @foreach ($personnels as $personnel)
                    @if($personnel->total_ticket>0 || auth()->id()==$personnel->id)
                        <div class="card-body pl-3 pr-3 pt-1 pb-1" id="personnel-card-{{ $personnel->id }}">
                            <h3 class="card-title mb-2">
                                {{ $personnel->first_name . ' ' . $personnel->surname }}
                                <i onclick="$.toggleButton('{{ $personnel->id }}','personnel')" data-toggle="0"
                                   id="toggle-personnel-{{ $personnel->id }}" class="fe fe-chevron-up float-right"
                                   style="cursor: pointer"></i>
                            </h3>
                            <div class="row mb-1">
                                <div class="col-md-2 col-lg-2">
                                <span class="badge badge-danger block fs-12 text-white">Opened
                                    <span class="prs-open-total">({{ $personnel->opened_ticket_total }})</span></span>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general block fs-12 text-white">Question INTERNAL
                                    <span class="prs-trans-total">({{ $personnel->transferred_ticket_total }})</span></span>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general text-white block fs-12">In progress
                                    <span
                                        class="prs-progr-total">({{ $personnel->in_progress_ticket_total }})</span></span>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general block fs-12 text-white"> Answered
                                    <span class="prs-ans-total">({{ $personnel->answered_ticket_total }})</span></span>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                <span class="badge badge-danger block fs-12 text-white"> Question
                                    <span class="prs-ques-total">({{ $personnel->question_ticket_total }})</span></span>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general block fs-12 text-white"> On Hold
                                    <span class="prs-hold-total">({{ $personnel->on_hold_ticket_total }})</span></span>
                                </div>
                            </div>

                            <div id="info-personnel-{{ $personnel->id }}" class="row">
                                @foreach ($statusses as $status)
                                    <div class="col-md-2 col-lg-2 prs-status-{{ $status->id }}">
                                        @php
                                            if ($status->id == 1) {
                                                $iterate = $personnel->opened_org_info;
                                            } elseif ($status->id == 2) {
                                                $iterate = $personnel->transferred_org_info;
                                            } elseif ($status->id == 3) {
                                                $iterate = $personnel->in_progress_org_info;
                                            } elseif ($status->id == 4) {
                                                $iterate = $personnel->answered_org_info;
                                            } elseif ($status->id == 5) {
                                                $iterate = $personnel->question_org_info;
                                            } elseif ($status->id == 8) {
                                                $iterate = $personnel->on_hold_org_info;
                                            }
                                        @endphp

                                        @foreach ($iterate as $item)
                                            <div class="row border-bottom mb-1">
                                                <div class="col-lg-12 col-md-12">
                                                <span class="tippy-tooltip"
                                                      data-tippy-content="{{ $item['organization']['org_name'] }}">
                                                    <a class="link {{ $item['is_contracted'] == 1 ? 'text-danger' : '' }} forwardFromPersonnel"
                                                       href="#" data-personnel-id="{{ $personnel->id }}"
                                                       data-org-id="{{ $item['organization']['org_id'] }}"
                                                       data-ticket-id="{{ $item['ticket_id'] != null ? $item['ticket_id'] : '0' }}"
                                                       data-status-id="{{ $status->id }}">
                                                        {{ Str::limit($item['organization']['org_name'], 10) }}
                                                    </a>
                                                </span>
                                                    <span class="float-right font-weight-bolder">{{ $item['count'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Organizations Right Part --}}
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Tickets: Organizations
                    </div>
                </div>

                @foreach ($organizations as $organization)
                    <div class="card-body pl-3 pr-3 pt-1 pb-1" id="organization-card-{{ $organization->id }}">
                        <h3 class="card-title mb-2">{{ $organization->org_name }} <i
                                onclick="$.toggleButton('{{ $organization->id }}','organization')" data-toggle="0"
                                id="toggle-organization-{{ $organization->id }}" class="fe fe-chevron-up float-right"
                                style="cursor: pointer"></i></h3>
                        <div class="row mb-1">
                            <div class="col-md-2 col-lg-2">
                                <span class="badge badge-danger block fs-12 text-white">Opened
                                    <span class="org-open-total">
                                        ({{ $organization->opened_ticket_total }})
                                    </span>
                                </span>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general text-white block fs-12">Question INTERNAL
                                    <span class="org-trans-total">
                                        ({{ $organization->transferred_ticket_total }})
                                    </span>
                                </span>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general text-white block fs-12">In progress
                                    <span class="org-prog-total">
                                        ({{ $organization->in_progress_ticket_total }})
                                    </span>
                                </span>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general text-white block fs-12"> Answered
                                    <span class="org-answ-total">
                                        ({{ $organization->answered_ticket_total }})
                                    </span>
                                </span>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <span class="badge badge-danger text-white block fs-12"> Question
                                    <span class="org-ques-total">
                                        ({{ $organization->question_ticket_total }})
                                    </span>
                                </span>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <span class="badge badge-general text-white block fs-12"> On Hold
                                    <span class="org-hold-total">
                                        ({{ $organization->on_hold_ticket_total }})
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div id="info-organization-{{ $organization->id }}" class="row">
                            @foreach ($statusses as $status)
                                <div class="col-md-2 col-lg-2 org-status-{{ $status->id }}">
                                    @php
                                        if ($status->id == 1) {
                                            $iterate = $organization->opened_personnel_info;
                                        } elseif ($status->id == 2) {
                                            $iterate = $organization->transferred_personnel_info;
                                        } elseif ($status->id == 3) {
                                            $iterate = $organization->in_progress_personnel_info;
                                        } elseif ($status->id == 4) {
                                            $iterate = $organization->answered_personnel_info;
                                        } elseif ($status->id == 5) {
                                            $iterate = $organization->question_personnel_info;
                                        } elseif ($status->id == 8) {
                                            $iterate = $organization->on_hold_personnel_info;
                                        }
                                    @endphp

                                    @foreach ($iterate as $item)
                                        <div class="row border-bottom mb-1">
                                            <div class="col-lg-12 col-md-12">
                                                <span class="tippy-tooltip"
                                                      data-tippy-content="{{ $item['personnel']['name_surname'] }}">
                                                    <a class="link link-primary forwardFromOrganization" href="#"
                                                       data-ticket-id="{{ $item['ticket_id'] != null ? $item['ticket_id'] : '0' }}"
                                                       data-personnel-id="{{ $item['personnel']['id'] }}"
                                                       data-org-id="{{ $organization->id }}"
                                                       data-status-id="{{ $status->id }}">
                                                        {{ Str::limit($item['personnel']['name_surname'], 10) }}
                                                    </a>
                                                </span><span
                                                    class="float-right font-weight-bolder">{{ $item['count'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    </div>
    </div>
    <!-- End app-content-->
    </div>
@endsection

@section('js')

    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>

    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script>
        $(document).ready(function() {

            $.toggleButton = function(id, value) {
                let element;
                let infoRow;
                if (value === "personnel") {
                    element = $("#toggle-personnel-" + id);
                    infoRow = $('#info-personnel-' + id);
                }
                if (value === "organization") {
                    element = $("#toggle-organization-" + id);
                    infoRow = $('#info-organization-' + id);
                }

                let key = element.data("toggle");
                if (key === 1) {
                    infoRow.show(300);
                    element.data("toggle", 0);
                    element.removeClass("fe-chevron-up");
                    element.addClass("fe-chevron-down");
                }
                if (key === 0) {
                    infoRow.hide(300);
                    element.data("toggle", 1);
                    element.removeClass("fe-chevron-down");
                    element.addClass("fe-chevron-up");
                }

            }

            $(document).on("click", ".forwardFromOrganization", function() {
                let personnel_id = $(this).data("personnel-id");
                let org_id = $(this).data("org-id");
                let ticket_id = $(this).data("ticket-id");
                let status_id = $(this).data("status-id");
                if (ticket_id !== 0) {
                    window.open('/update-ticket/' + ticket_id + '', '_blank');
                } else {
                    window.open("/tickets?status=" + status_id + "&org_id=" + org_id + "&personnel=" +
                        personnel_id); // look at the datatable ajax request on tickets.blade.php
                }
            });

            $(document).on("click", ".forwardFromPersonnel", function() {
                let personnel_id = $(this).data("personnel-id");
                let org_id = $(this).data("org-id");
                let ticket_id = $(this).data("ticket-id");
                let status_id = $(this).data("status-id");
                if (ticket_id !== 0) {
                    window.open('/update-ticket/' + ticket_id + '', '_blank');
                } else {
                    window.open("/tickets?status=" + status_id + "&org_id=" + org_id + "&personnel=" +
                        personnel_id); // look at the datatable ajax request on tickets.blade.php
                }
            });


            tippy.delegate(".row", {
                target: ".tippy-tooltip",
            });

        });

        function getDashboard(){

            $.ajax({
                url: "/dashboard",
                type: "get",
                dataType: "json",
                data: {
                    type: 1,
                    org_id:$('#organization').val(),
                    user_id:$('#users').val()
                },
                success: function(response) {
                    // Chaning the Personnel Tab
                    let personnels = response["personnels"];
                    personnels.forEach(per => {
                        let perDiv = $(`#personnel-card-${per.id}`);
                        perDiv.find(".prs-open-total").html(
                            `(${per.opened_ticket_total})`
                        );
                        perDiv.find(".prs-trans-total").html(
                            `(${per.transferred_ticket_total})`
                        )
                        perDiv.find(".prs-progr-total").html(
                            `(${per.in_progress_ticket_total})`
                        )
                        perDiv.find(".prs-ans-total").html(
                            `(${per.answered_ticket_total})`
                        )
                        perDiv.find(".prs-ques-total").html(
                            `(${per.question_ticket_total})`
                        )
                        perDiv.find(".prs-hold-total").html(
                            `(${per.on_hold_ticket_total})`
                        )

                        let statuses = response["statusses"]
                        statuses = Object.values(statuses)
                        statuses.forEach(status => {
                            let items = [];
                            if (status.id == 1) {
                                items = per.opened_org_info;
                            } else if (status.id == 2) {
                                items = per.transferred_org_info;
                            } else if (status.id == 3) {
                                items = per.in_progress_org_info;
                            } else if (status.id == 4) {
                                items = per.answered_org_info;
                            } else if (status.id == 5) {
                                items = per.question_org_info;
                            } else if (status.id == 8) {
                                items = per.on_hold_org_info;
                            }
                            items = Object.values(items);

                            let open_html = "";
                            items.sort((first, second) => {
                                return second.count - first.count
                            })
                            items.forEach(item => {
                                open_html += `
                                <div class="row border-bottom mb-1">
                                    <div class="col-lg-12 col-md-12">
                                        <span class="tippy-tooltip"
                                            data-tippy-content="${item['organization']['org_name'] }">
                                            <a class="link forwardFromPersonnel
                                                ${item['is_contracted'] == 1 ? 'text-danger' : '' }"
                                                href="#" data-personnel-id="${per.id }"
                                                data-org-id="${item['organization']['org_id'] }"
                                                data-ticket-id="${item['ticket_id'] != null ? item['ticket_id'] : '0' }"
                                                data-status-id="${status.id}">
                                                ${item['organization']['org_name'].slice(0,10)}...
                                            </a>
                                        </span>
                                        <span class="float-right font-weight-bolder">${item['count'] }</span>
                                    </div>
                                </div>
                                `
                            })
                            perDiv.find(`.prs-status-${status.id}`).html(open_html);

                            open_html = null; // freeing up the memory
                            items = null; // freeing up the memory
                        })
                        statuses = null; // freeing up the memory
                    });
                    personnels = null; // freeing up the memory

                    // Chaning the Organization Tab
                    let organizations = response["organizations"]
                    organizations = Object.values(organizations);

                    organizations.forEach(org => {
                        let orgDiv = $(`#organization-card-${org.id}`);
                        orgDiv.find(".org-open-total").html(
                            `(${org.opened_ticket_total})`
                        );
                        orgDiv.find(".org-trans-total").html(
                            `(${org.transferred_ticket_total})`
                        )
                        orgDiv.find(".org-prog-total").html(
                            `(${org.in_progress_ticket_total})`
                        )
                        orgDiv.find(".org-answ-total").html(
                            `(${org.answered_ticket_total})`
                        )
                        orgDiv.find(".org-ques-total").html(
                            `(${org.question_ticket_total})`
                        )
                        orgDiv.find(".org-hold-total").html(
                            `(${org.on_hold_ticket_total})`
                        )

                        let statuses = response["statusses"]
                        statuses = Object.values(statuses)
                        statuses.forEach(status => {
                            let items = [];
                            if (status.id == 1) {
                                items = org.opened_personnel_info;
                            } else if (status.id == 2) {
                                items = org.transferred_personnel_info;
                            } else if (status.id == 3) {
                                items = org.in_progress_personnel_info;
                            } else if (status.id == 4) {
                                items = org.answered_personnel_info;
                            } else if (status.id == 5) {
                                items = org.question_personnel_info;
                            } else if (status.id == 8) {
                                items = org.on_hold_personnel_info;
                            }
                            items = Object.values(items);

                            let open_html = "";
                            items.sort((first, second) => {
                                return second.count - first.count
                            })
                            items.forEach(item => {
                                open_html += `
                                <div class="row border-bottom mb-1">
                                    <div class="col-lg-12 col-md-12">
                                        <span class="tippy-tooltip"
                                            data-tippy-content="${item['personnel']['name_surname'] }">
                                            <a class="link link-primary forwardFromOrganization" href="#"
                                                data-ticket-id="${item['ticket_id'] != null ? item['ticket_id'] : '0' }"
                                                data-personnel-id="${item['personnel']['id'] }"
                                                data-org-id="${org.id }"
                                                data-status-id="${status.id }">
                                                ${item['personnel']['name_surname'].slice(0,10)}...
                                            </a>
                                        </span>
                                        <span class="float-right font-weight-bolder">
                                            ${item['count']}
                                        </span>
                                    </div>
                                </div>
                                `
                            })
                            orgDiv.find(`.org-status-${status.id}`).html(open_html);

                            open_html = null; // freeing up the memory
                            items = null; // freeing up the memory
                        })
                        statuses = null; // freeing up the memory
                    });
                    organizations = null; // freeing up the memory

                    response = null; // freeing up the memory
                }
            })
        }

        setInterval(() => {
            getDashboard();
        }, 180000);

        $(document).ready(function() {
            $('#users').select2({
                ajax: {
                    url: '/getUsersForDashboard',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });
            $('#organization').select2({
                ajax: {
                    url: '/getOrganizationsForDashboard',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                }
            });


        })


        function filterDashboard(){
            window.location.href = "/dashboard?org_id="+$('#organization').val()+"&user_id="+$('#users').val();
        }
        function clearDashboard(){
            window.location.href = "/dashboard";
        }




    </script>
@endsection
