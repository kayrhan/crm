@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
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
                <li class="breadcrumb-item active" aria-current="page"><a href="">Done Tickets</a></li>
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
    <div class="row" id="DashboardContainer">
        {{-- Organizations Right Part --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Done Tickets
                        <x-infobox info="The dashboard content gets updated every 3 minutes"/>
                    </div>
                </div>

                <div class="card-body pl-3 pr-3 pt-1 pb-1 row">
                @foreach ($organizations as $organization)
                <div class="col-md-6 col-lg-6" id="organization-card-{{ $organization->id }}">

                    <h3 class="card-title mb-2">{{ $organization->org_name }} <i
                            onclick="$.toggleButton('{{ $organization->id }}','organization')" data-toggle="0"
                            id="toggle-organization-{{ $organization->id }}" class="fe fe-chevron-up float-right"
                            style="cursor: pointer"></i></h3>
                    <div class="row mb-1">
                        <div class="col-md-6 col-lg-6">
                            <span class="badge badge-danger block fs-12 text-white"> Done & Proofed
                                <span class="org-proofed-total">
                                    ({{ $organization->done_proofed_ticket_total }})
                                </span>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <span class="badge badge-general text-white block fs-12"> Done & Unproofed
                                <span class="org-unproofed-total">
                                    ({{ $organization->done_unproofed_ticket_total }})
                                </span>
                            </span>
                        </div>
                    </div>
                    <div id="info-organization-{{ $organization->id }}" class="row">
                        {{-- Done & Proofed --}}
                        <div class="col-md-6 col-lg-6 org-status-proofed">
                            @foreach ($organization->done_proofed_personnel_info as $item)
                                <div class="row border-bottom mb-1">
                                    <div class="col-lg-12 col-md-12">
                                        <span class="tippy-tooltip"
                                            data-tippy-content="{{ $item['personnel']['name_surname'] }}">
                                            <a class="link link-primary forwardFromOrganization" href="#"
                                                data-ticket-id="{{ $item['ticket_id'] != null ? $item['ticket_id'] : '0' }}"
                                                data-personnel-id="{{ $item['personnel']['id'] }}"
                                                data-org-id="{{ $organization->id }}" data-status-id="6"
                                                data-proofed="1">
                                                {{ Str::limit($item['personnel']['name_surname'], 30) }}
                                            </a>
                                        </span>
                                        <span class="float-right font-weight-bolder">{{ $item['count'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Done & Unproofed --}}
                        <div class="col-md-6 col-lg-6 org-status-unproofed">
                            @foreach ($organization->done_unproofed_personnel_info as $item)
                                <div class="row border-bottom mb-1">
                                    <div class="col-lg-12 col-md-12">
                                        <span class="tippy-tooltip"
                                            data-tippy-content="{{ $item['personnel']['name_surname'] }}">
                                            <a class="link link-primary forwardFromOrganization" href="#"
                                                data-ticket-id="{{ $item['ticket_id'] != null ? $item['ticket_id'] : '0' }}"
                                                data-personnel-id="{{ $item['personnel']['id'] }}"
                                                data-org-id="{{ $organization->id }}" data-status-id="6"
                                                data-proofed="0">
                                                {{ Str::limit($item['personnel']['name_surname'], 30) }}
                                            </a>
                                        </span>
                                        <span class="float-right font-weight-bolder">{{ $item['count'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @endforeach
                </div>
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

    <script>
        $(document).ready(function() {

            $.toggleButton = function(id, value) {
                let element;
                let infoRow;
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
                let is_proofed = $(this).data("proofed");
                if (ticket_id !== 0) {
                    window.open('/update-ticket/' + ticket_id + '', '_blank');
                } else {
                    window.open("/tickets?status=" + status_id + "&org_id=" + org_id + "&personnel=" +
                        personnel_id + "&proofed=" + is_proofed
                        ); // look at the datatable ajax request on tickets.blade.php
                }
            });

            tippy.delegate(".row", {
                target: ".tippy-tooltip",
            });

        });

        setInterval(() => {

            $.ajax({
                url: "/dashboard/proofed",
                type: "get",
                dataType: "json",
                data: {
                    type: 1
                },
                success: function(response) {
                    // Chaning the Organization Tab
                    let organizations = response["organizations"]
                    organizations = Object.values(organizations);

                    organizations.forEach(org => {
                        let orgDiv = $(`#organization-card-${org.id}`);
                        orgDiv.find(".org-proofed-total").html(
                            `(${org.done_proofed_ticket_total})`
                        );
                        orgDiv.find(".org-unproofed-total").html(
                            `(${org.done_unproofed_ticket_total})`
                        )


                        orgArray = [org.done_proofed_personnel_info, org
                            .done_unproofed_personnel_info
                        ];
                        orgArray.forEach((items,index) => {
                            items = Object.values(items);

                            let proofed = index == 1 ? 0 : 1;
                            let classname = index == 1 ? "unproofed" : "proofed"
                            let open_html = "";
                            items.sort((first, second) => {
                                return second.count - first.count
                            })
                            items.forEach((item) => {
                                open_html += `
                                <div class="row border-bottom mb-1">
                                    <div class="col-lg-12 col-md-12">
                                        <span class="tippy-tooltip"
                                            data-tippy-content="${item['personnel']['name_surname'] }">
                                            <a class="link link-primary forwardFromOrganization" href="#"
                                                data-ticket-id="${item['ticket_id'] != null ? item['ticket_id'] : '0' }"
                                                data-personnel-id="${item['personnel']['id'] }"
                                                data-org-id="${org.id }"
                                                data-status-id="6"
                                                data-proofed="${proofed}">
                                                ${item['personnel']['name_surname']}
                                            </a>
                                        </span>
                                        <span class="float-right font-weight-bolder">
                                            ${item['count']}
                                        </span>
                                    </div>
                                </div>
                                `
                            })

                            orgDiv.find(`.org-status-${classname}`).html(open_html);

                            open_html = null; // freeing up the memory
                        })
                        items = null; // freeing up the memory
                    });
                    organizations = null; // freeing up the memory

                    response = null; // freeing up the memory
                }
            })
        }, 180000);
    </script>
@endsection
