@extends('layouts.master')
@section('css')
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('text-editor/trumbowyg.min.css')}}">
    <link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">
    <link href="{{asset('assets/plugins/tagify/tagify.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/css/tickets.css')}}">

    <style>
        .btn-success:hover, .btn-success:focus {
            border-color: green !important;
            background: green !important;
            filter: brightness(120%);
        }
        .effortHourComment{
            text-align: center;
        }
        .effortMinuteComment{
            text-align: center;
        }
        #effortHour{
            text-align: center;
        }
        #effortMint{
            text-align: center;
        }
        .alert-light-private {
            color: #8a6d3b;
            background-color: #FCF8E3;
            border-color: #FCF8E3;
        }

        .btn-private {
            color: #fff !important;
            background-color: #8a6d3b;
            border-color: #8a6d3b;
            box-shadow: 0 0px 10px -5px rgb(239 75 75 / 44%);
        }

        .trumbowyg-box {
            min-height: 80px !important;
            width: 100% !important;
        }

        .trumbowyg-editor {
            min-height: 80px !important;
            max-height: 450px !important;
            resize: vertical !important;
        }


        .attachment-preview img {
            width: 400px;
            height: auto;
        }

        .trumbowyg img {
            width: 400px;
        }


        tr {
            cursor: auto !important;
        }

        .border-bottom {
            border-bottom: 2px solid #ebecf1;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        input[type="text"] {
            border: 1px solid #D5D5D5;
        }

        input[type="select"] {
            border: 1px solid #D5D5D5;
        }

        input[type="number"] {
            border: 1px solid #D5D5D5;
        }

        input[type="password"] {
            border: 1px solid #D5D5D5;
        }

        .error-border {
            border: 1px solid red !important;
        }

        .effort-input-height {
            height: 1.5rem !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        li.circle-color-red::before{
            border:2px solid red!important;
        }
        li.circle-color-green::before{
            border:2px solid #0dd157!important;
        }
        li.circle-color-yellow::before{
            border:2px solid #ffbb3b!important;
        }
        li.circle-color-gray::before{
            border:2px solid #918e8c !important;
        }
        li.circle-color-black::before{
            border:2px solid black !important;
        }
        .blink-text {
            animation: blinker 0.7s linear infinite;
            color: #BA3129!important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 38px !important;
        }

        .select2-search, .select2-search--inline {
            height: 100% !important;
        }

        .select2-search__field:placeholder-shown {
            width: 100% !important;
        }

        @keyframes blinker {
            50% { opacity: 0; }
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .private-ticket::before {
            font-weight: bold;
            content: "Private";
            color: white;
            padding: 3px 12px 3px 15px;
            height: auto !important;
            margin-right: 52px;
        }

        .custom-discussion-section {
            max-height: 226px;
            overflow: hidden;
        }

        .comment-read-more-text {
            text-decoration: underline !important;
            color: #705ec8 !important;
            font-size: 14px !important;
            font-style: italic !important;
            cursor: pointer !important;
            font-weight: bolder;
        }
        .responsible-person .select2-container{
            min-width: 133.8px;
        }
        .tui-full-calendar-time-date-schedule-block .tui-full-calendar-time-schedule-content strong{
            display: none;
        }
    </style>

    <link href="{{ URL::asset('assets/css/cicons.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/calendar/calendar.css') }}">
@endsection
@section('content')
    <!-- Row -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 col-md-12">
            <div class="card first-card">

                @if(session()->has('message'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                        </button>
                        {!! session()->get('message') !!}
                    </div>
                @endif

                <div class="card-header" style="display: flex;justify-content: space-between;!important;">
                    <h3 class="card-title" style="text-transform: none !important;">{{ucfirst(trans('words.ticket'))}} {{ucfirst(trans('words.information'))}}
                        &nbsp;#{{$ticket->id}} | {{$ticket->name}}  @if($ticket->IsContracted ==1) <span
                            class="text-danger"> (Customer with Contract!)</span> @endif @if($ticket->is_auto==1) <x-infobox info="Automatically created after a support email!"/> @endif
                    </h3>{{--if organization is contracted--}}

                    <div style=" text-align: right;" id="top-button-row">
                        <button class="btn btn-success mt-4 mb-0 ml-4 float-right" type="button" id="open-calendar-tasks-button">Calendar Tasks</button>
                        <button onclick="ticketToCalendar()" class="btn btn-azure mt-4 mb-0 ml-4 float-right">Add Calendar</button>
                        <button type="button" class="btn btn-primary mt-4 mb-0 ml-4 float-right" onclick="copyTicket({{ $ticket->id }})">Copy Ticket</button>
                        <button class="btn btn-outline-primary mt-4 ml-4 mb-0 float-right" id="ticket-export">Export Ticket</button>
                        <button type="submit" class="btn btn-outline-success mt-4 ml-4 mb-0 float-right updateTicketButtonSaveClose">{{trans('words.save')}} & Close</button>
                        <button type="submit" class="btn btn-success mt-4 mb-0 float-right updateTicketButton">{{trans('words.save')}}</button>

                        <a href="{{url('/tickets')}}" class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{trans('words.cancel')}}</a>


                    </div>
                </div>

                <div class="card-body">
                    <input type="hidden" value="{{$ticket->status_id}}" id="current-ticket-status" >
                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <form action="{{url('/edit-ticket').'/'.$ticket->id}}" method="POST" id="updateTicket">
                                @csrf
                                {{-- Title Row --}}
                                <div class="row">
                                    <div class="col-lg-10 col-md-10">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.subject'))}}
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="name" rows="1"
                                                          style="resize: none; white-space: nowrap; overflow-x: hidden; color: black"
                                                          {{ auth()->user()->role_id == 4 || auth()->user()->role_id == 7 ? "readonly":""}} placeholder="Subject">{{$ticket->name}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Description Row --}}
                                <div class="row">
                                    <div class="col-lg-10 col-md-10">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.description'))}}
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-10">
                                                <textarea id="description" name="description" class="form-control" {{ auth()->user()->role_id == 4 || auth()->user()->role_id == 7 ? "disabled":""}}>
                                                    {{$ticket->description}}
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Assigned Company Row --}}
                                <div class="row">
                                    <div class="col-lg-10 col-md-10">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.assigned_to'))}} {{ucfirst(trans('words.company'))}}</label>
                                            <div class="col-md-5">
                                                <select id="organization" name="organization" class="form-control" {{auth()->user()->role_id == 4 || auth()->user()->role_id == 7 ? "disabled":""}}>
                                                    <option value="{{$ticket->org_id}}" selected="selected">
                                                        {{ App\Organization::where('id', $ticket->org_id)->value('org_name') }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <select id="users" name="user" class="form-control" {{auth()->user()->role_id == 4 || auth()->user()->role_id == 7 ? "disabled":""}}>
                                                    <option value="{{$ticket->user}}" selected="selected">

                                                        {{$ticket->UserName}} {{$ticket->SurName}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Middle Rows --}}
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{-- Assigned User --}}
                                        <div class="form-group row border-bottom mr-4">
                                            <label class="col-md-3 col-lg-3 mr-5 form-label my-auto" style="width: 34%">
                                                {{ucfirst(trans('words.assigned_user'))}} (Master)
                                            </label>
                                            <div class="col-md-8 col-lg-8" style="width: 66%">
                                                <select  id="personnel" name="personnel" class="form-control personnel " {{ auth()->user()->role_id == 7 || auth()->user()->role_id == 4 ? "disabled" : ""}}>
                                                    <option value="{{$ticket->personnel}}" selected="selected">
                                                        {{$ticket->PersonnelName}} {{$ticket->PersonnelSurName}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Priority --}}
                                        {{-- Secondary Assigned Users --}}
                                        <div class="form-group row border-bottom mr-4">
                                            <label class="col-md-3 col-lg-3 mr-5 form-label my-auto" style="width: 34%">{{ucfirst(trans('words.assigned_user'))}} (Secondary)</label>
                                            <div class="col-md-8 col-lg-8" style="width: 66%">
                                                <select name="assigned_personnel[]" id="assignedPersonels" data-counter="{{count($ticket_personnel)}}" class="form-control personnel assigned_personnel js-example-basic-multiple" {{ auth()->user()->role_id == 7 ? "disabled" : ""}} multiple="multiple">
                                                    @foreach($ticket_personnel as $index=>$assigned_personnel)
                                                        <option  class="personnel{{$index}}"  data-personnel-{{$index}}="{{$assigned_personnel["mail"]}}" value="{{$assigned_personnel["id"]}}" selected="selected">
                                                            {{$assigned_personnel["name"]}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- External Partner --}}
                                        @if(auth()->user()->role_id != 7 && auth()->user()->role_id != 4)
                                            <div class="form-group row border-bottom mr-4">
                                                <label class="col-md-3 col-lg-3 mr-5 form-label">External Partner</label>
                                                <div class="col-xl-8 col-lg-8 col-md-8">
                                                    <div class="row">
                                                        <label class="col-xl-5 col-lg-5 col-md-5 form-label text-center">External Partner</label>
                                                        <label class="col-xl-5 col-lg-5 col-md-5 form-label text-center responsible-person-area" @if($ticket["partner_count"]==0) style="display: none;" @endif>Responsible Person</label>
                                                        <label class="col-xl-2 col-lg-2 col-md-2 form-label text-center plus-button-area" style="display: none;"></label>
                                                    </div>
                                                </div>
                                                <label class="col-md-3 col-lg-3 mr-5 form-labell"></label>
                                                <div class="col-xl-8 col-lg-8 col-md-5" id="external-partner-field">
                                                    @if($ticket["partner_count"]>0)
                                                        @foreach($ticket["partners"] as $partner)
                                                            @php
                                                                $ext_partner = \App\ExternalPartner::where("id",$partner->partner_id)->first();
                                                                $ext_partner_contact = \App\ExternalPartnerUser::where("id",$partner->contact_id)->first();
                                                            @endphp

                                                            @if($loop->first)
                                                                <div class="row">
                                                                    <div class="col-md-5 col-lg-5 mb-1">
                                                                        <select name="external_partners[]" class="form-control" id="external-partner">
                                                                            <option value="{{$ext_partner->id}}">
                                                                                {{$ext_partner->organization_name}}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-5 col-lg-5 mb-1 responsible-person-area responsible-person" >
                                                                        <select name="external_partner_contacts[]" class="form-control " id="external-partner-contact">
                                                                            <option value="{{$ext_partner_contact->id??""}}">
                                                                                {{($ext_partner_contact->name??"")." ".($ext_partner_contact->surname??"")}}
                                                                            </option>

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 col-lg-2 plus-button-area">
                                                                        <div class="row">
                                                                            <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                                                                <button type="button" class="btn btn-sm btn-success mt-1" id="add-external-partner">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @else
                                                                <div class="row appendedRow">
                                                                    <div class="col-md-5 col-lg-5 mb-1">
                                                                        <select name="external_partners[]" class="form-control" id="external-partner{{$loop->index}}">
                                                                            <option value="{{$ext_partner->id}}" selected="selected">
                                                                                {{$ext_partner->organization_name}}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-5 col-lg-5 mb-1" >

                                                                        <select name="external_partner_contacts[]" class="form-control " id="external-partner-contact{{$loop->index}}">
                                                                            <option value="{{$ext_partner_contact->id??""}}">
                                                                                {{($ext_partner_contact->name??"")." ".($ext_partner_contact->surname??"")}}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 col-lg-2">
                                                                        <div class="row">
                                                                            <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                                                                <button type="button" class="btn btn-sm btn-danger mt-1 remove-external-partner">
                                                                                    <i class="fa fa-minus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="row">
                                                            <div class="col-md-5 col-lg-5 mb-1">
                                                                <select name="external_partners[]" class="form-control" id="external-partner">
                                                                    <option selected="selected">
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-5 col-lg-5 mb-1 responsible-person-area responsible-person" style="display: none" >
                                                                <select name="external_partner_contacts[]" class="form-control " id="external-partner-contact">
                                                                    <option value="">
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 col-lg-2 plus-button-area" style="display: none">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                                                        <button type="button" class="btn btn-sm btn-success mt-1" id="add-external-partner"><i class="fa fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Status --}}
                                        <div class="form-group row border-bottom mr-4">
                                            <label
                                                class="col-md-3 mr-5 form-label my-auto">{{ucfirst(trans('words.status'))}}
                                                @if($ticket->status_id == 6)
                                                    <span style="font-size: 12px;padding-top: 3px;">
                                                        ( {{ $ticket->close_date != null ? \Carbon\Carbon::parse($ticket->close_date)->format('d.m.Y H:i:s') : "-"}} )
                                                    </span>
                                                @endif
                                            </label>
                                            <div class="col-md-8">
                                                @if((in_array($ticket->status_id, [6, 7, 9, 11])) && Auth::user()->role_id == 1)
                                                    <div class="form-row">
                                                        <div class="col-10">
                                                            <select name="status" class="form-control" id="ticket-status">
                                                                @foreach($data["status"] as $status)
                                                                <option value="{{ $status->id }}" {{ $ticket->status_id == $status->id ? "selected": "" }}>{{ ($status->id === 6 && $ticket->proofed) ? "Done & Proofed" : $status->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-2 text-right proofBtnCont">
                                                            @if($ticket->proofed)
                                                                <i data-id="{{$ticket->id}}" class="btn btn-primary p-2 px-3 fa fa-lock" aria-hidden="true" style="font-size:1.2rem;" data-toggle="tooltip" data-placement="top" title="This ticket is proofed."></i>
                                                            @else
                                                                <i data-id="{{$ticket->id}}" onclick="proofModalShow(event)" class="btn btn-light p-2 fa fa-unlock" aria-hidden="true" style="font-size:1.2rem; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Proof the ticket."></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <select name="status" id="statusSaveLogic" class="form-control" {{ (in_array($ticket->status_id, [6, 7, 9, 10, 11]) && (auth()->user()->role_id == 7 || auth()->user()->role_id == 4)) ? "disabled" : ""}}>
                                                        @foreach($data['status'] as $status)
                                                            <option value="{{$status->id}}" {{$ticket->status_id == $status->id ? "selected": ""}}>
                                                                {{$status->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Priority --}}
                                        @if(auth()->user()->role_id != 7)
                                            <div class="form-group row border-bottom mr-4">
                                                <label class="col-md-3 mr-5 form-label my-auto">{{ucfirst(trans('words.priority'))}}</label>
                                                <div class="col-md-8">
                                                    <select name="priority" class="form-control" {{auth()->user()->role_id == 4 ? "disabled":""}}>
                                                        <option value="4" @if($ticket->priority == 4) selected @endif>Low</option>
                                                        <option value="1" @if($ticket->priority == 1) selected @endif>Normal</option>
                                                        <option value="2" @if($ticket->priority == 2) selected @endif>High</option>
                                                        <option value="3" @if($ticket->priority == 3) selected @endif>Very High</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Category Row --}}
                                        <div id="hidden-category-row">
                                            <div class="form-group row border-bottom mr-4">
                                                <label for="ticket-category" class="col-md-3 mr-5 form-label my-auto">{{ucfirst(trans('words.category'))}}</label>
                                                <div class="col-md-8">
                                                    <select name="category" class="form-control" id="ticket-category" @if(in_array(auth()->user()->role_id, [4, 7])) disabled="disabled" @endif>
                                                        @foreach($data['category'] as $category)
                                                            <option value="{{$category->id}}" {{$ticket->category == $category->id ? "selected" : ""}}>{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if($ticket->category === 14)
                                            <div id="ticket-sub-category-row">
                                                <div class="form-group row border-bottom mr-4">
                                                    <label for="ticket-sub-category" class="col-md-3 mr-5 form-label my-auto">IT Category</label>
                                                    <div class="col-md-8">
                                                        <select name="sub_category" class="form-control" id="ticket-sub-category" @if(in_array(auth()->user()->role_id, [4, 7])) disabled="disabled" @endif>
                                                            @foreach($data["sub_category"] as $category)
                                                            <option value="{{ $category->id }}" @if($ticket->sub_category_id === $category->id) selected="selected" @endif>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Due Date Row --}}
                                        <div class="form-group row border-bottom mr-4">
                                            <label class="col-md-3 mr-5 form-label my-auto">Ticket Due Date</label>
                                            <div class="col-md-8">
                                                <input class="form-control privateValidate" type="date" name="due_date" required min="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{$ticket->due_date}}" {{ auth()->user()->role_id == 7 ? "disabled" : ""}}>
                                            </div>
                                        </div>
                                        @if($ticket->status_id == 5 && $ticket->comment_due_date)
                                        <div class="form-group row border-bottom mr-4">
                                            <label class="col-md-3 mr-5 form-label my-auto">Comment Due Date</label>
                                            <div class="col-md-8">
                                                <input class="form-control privateValidate" type="date" name="ticket_comment_due_date" required min="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{$ticket->comment_due_date}}" {{ auth()->user()->role_id == 7 ? "disabled" : ""}}>
                                            </div>
                                        </div>
                                        @endif


                                        @if(auth()->user()->role_id != 7 && auth()->user()->role_id != 4)
                                            {{-- Transport Price Row --}}
                                            <div class="form-group row border-bottom mr-4">
                                                <label class="col-md-3 mr-5 form-label my-auto">{{ucfirst(trans('words.transport'))}} {{ucfirst(trans('words.price'))}}</label>
                                                <div class="col-md-8 d-flex flex-row">
                                                    <input class="form-control" type="number"  id="transport_price" name="transport_price" value="{{$ticket->transport_price}}">
                                                    <button id="transportAdd" style="display: none" data-toggle="modal" data-target="#transportModal" type="button" class=" ml-1 btn btn-sm btn-success">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button id="transportReload" onclick="transportPriceReload()" style="display: none" type="button" class="btn btn-sm btn-warning ml-1">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        @unless(auth()->user()->role_id === 7)
                                        <div class="form-group row mr-4">
                                            <label for="ticket-references" class="col-md-3 mr-5 form-label my-auto">References<x-infobox info="You can add already existing tickets as a reference or create new one by using the button. Changes will be submitted on save!"/></label>
                                            <div class="col-md-3 d-flex flex-row">
                                                <button type="button" id="add-reference-ticket-button" onclick="addReferenceTicket({{ $ticket->id }})" class="btn btn-success w-100 text-wrap standard-form-input my-auto" style="font-weight: 600; max-height: 38px">New Ticket</button>
                                            </div>
                                            @unless(auth()->user()->role_id === 4 && auth()->id() != $ticket->personnel)
                                            <div class="col-md-5 d-flex flex-row">
                                                <select id="ticket-references" name="ticket_references[]" class="standard-form-input w-100" multiple="multiple"></select>
                                            </div>
                                            @endunless
                                        </div>
                                        @endunless
                                    </div>

                                    @if(in_array(auth()->user()->role_id, [1, 2]))
                                        @include('tickets.important-decisions')
                                    @endif


                                </div>

                                @unless(auth()->user()->role_id === 7)
                                @include('tickets.ticket-references')
                                @endunless

                                @unless($effort_logs->isEmpty())
                                <div class="row">
                                    <div class="col-md-10 col-lg-10">
                                        <div class="form-group row border-bottom d-flex flex-column" style="border-top: 1px solid #ebecf1; margin-top:5px; padding-top: 5px">
                                            <div class="col-md-2 col-lg-2">
                                                <span class="form-label">Effort
                                                    <x-infobox info="For billing, the working hours of the personnel are recorded. Internal efforts are not included in this."/>
                                                </span>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row effortLog">
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="d-flex flex-column ">
                                                            <div class="effortLogTables switch-history @if(count($effort_logs) > 10) hide-effort-log-history @endif" style="max-height: 295px; overflow-y: hidden;">
                                                                @php
                                                                $vCreatDate =\Carbon\Carbon::now();
                                                                @endphp
                                                                @foreach($effort_logs as $effort_log)
                                                                    @php
                                                                        $text = "<a class='jumpToComment cursor-pointer comment-effort-tooltip' data-id=".$effort_log->discussion_id.">";
                                                                    @endphp
                                                                    <table class="w-100 effortSection efforts-table table-bordered" data-effort-id="{{$effort_log->id}}">
                                                                        <tr>
                                                                            <td class="font-weight-semibold w-4 text-center {{ $effort_log->is_discussion==1?"text-info":"" }}">{!! $effort_log->is_discussion==1?$text:""!!}{{$loop->index+1}}{!!$effort_log->is_discussion==1?"</a>":""!!}</td>
                                                                            <td class="text-center">{{ \Carbon\Carbon::parse($effort_log->created_at)->format("d.m.Y H:i:s") }}</td>
                                                                            <td class="text-center">
                                                                                @foreach ($data["effortTypes"] as $effort_type)
                                                                                    @if($effort_log->effort_type == $effort_type->id)
                                                                                        @if($effort_log->effort_type == 5){{--Eğer effort tipi internalse eğik yaz çünkü faturalandrırmaya dahil değil--}}
                                                                                        <em>{{$effort_type->type}}</em>
                                                                                        @else
                                                                                        {{$effort_type->type}}
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>
                                                                            <td class="text-center">{{$effort_log->hours}} Hours and {{$effort_log->minutes}} Minutes</td>
                                                                            <td class="text-center">
                                                                                <span class="text-right">
                                                                                <i style="cursor: pointer" class="fa fa-pencil text-info effortUpdateButton mr-2" data-effort-id="{{$effort_log->id}}" {{$effort_log->is_discussion==1?"data-discussion-id=".$effort_log->discussion_id:""}}></i>
                                                                                <i style="cursor: pointer;" class="fa fa-trash text-danger pl-1 effortDeleteButton" data-effort-id="{{$effort_log->id}}" {{$effort_log->is_discussion==1?"data-discussion-id=".$effort_log->discussion_id:""}} ></i>
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @if(count($effort_logs) > 10)
                                                        <div>
                                                            <div class="col-md-12">
                                                                <div class="d-flex justify-content-end py-2">
                                                                    <a class="history-switcher font-weight-semibold text-primary pointer-cursor" onclick="collapseEffortLog()" style="text-decoration: underline;">Show More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(auth()->user()->role_id === 1)
                                <div class="row totalEffortRow pt-2">
                                    <div class="col-md-10 col-lg-10">
                                        <div class="row d-flex flex-column">
                                            <div class="col-md-2 col-lg-2 my-auto">
                                                <span class="form-label">Total Efforts</span>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row" id="effortTotalTimeSection">
                                                    <table class="total-efforts-table table-bordered w-100">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center py-1">Effort Type</th>
                                                                <th class="text-center py-1">Total</th>
                                                                <th class="text-center py-1">Discount</th>
                                                                <th class="text-center py-1">Final</th>
                                                                <th class="text-center py-1">Apply Discount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($effort_with_types as $effort_with_type)
                                                            <tr @if($loop->last) style="background-color: #f5f5f5;" @endif>
                                                                <td class="text-center py-1">{{ $effort_with_type["type"] }}</td>
                                                                <td class="text-center py-1">{{ $effort_with_type["total"] }}</td>
                                                                <td class="text-center py-1">{{ $effort_with_type["discount"] }}%</td>
                                                                <td class="text-center py-1">{{ $effort_with_type["final"] }}</td>
                                                                <td class="text-center py-1">
                                                                    @unless($loop->last || $effort_with_type["type"] == "Internal")
                                                                    <span class="text-right">
                                                                        <i style="cursor: pointer" class="fa fa-percent text-info apply-discount-button mr-2" data-effort-type-text="{{ $effort_with_type["type"] }}" data-effort-type-id="{{ $effort_with_type["id"] }}" data-total-efforts="{{ $effort_with_type["total"] }}" data-total-efforts-minutes="{{ $effort_with_type["minutes"] }}" data-discount="{{ $effort_with_type["discount"] }}" data-final-minutes="{{ $effort_with_type["final_minutes"] }}" data-final-hours="{{ $effort_with_type["final_hours"] }}"></i>
                                                                    </span>
                                                                    @endunless
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endunless

                                {{-- attt --}}
                                @if($ticket['attachment'])
                                    @php $attachment_count = count($ticket['attachment']);@endphp

                                    <div class="row pt-2">
                                        <div class="col-lg-10 col-md-10">
                                            <div class="form-group border">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10">
                                                        <div class="form-label">{{ucfirst(trans('words.attached_files'))}}
                                                            ({{$attachment_count}})
                                                        </div>
                                                        <label class="custom-switch">
                                                            <input type="checkbox" id="attachmentToggle" name="attachmentToggle"
                                                                   class="custom-switch-input" {{$attachment_count > 0 ? "checked" : ""}}>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"></span>
                                                        </label>
                                                    </div>
                                                    @if($ticket["attachment"]->count()>0)
                                                            <div class="row mt-4 col-lg-2 col-md-2" id="downloadAllField">
                                                                <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                                                                    <a  style="text-decoration: underline;" class="attachment-download-btn cursor-pointer" data-ticket-id="{{$ticket['id']}}">Download All <i class="fa fa-download" style="text-decoration:underline;"></i></a>
                                                                </div>
                                                            </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="attachments" {{$attachment_count == 0 ? "style='display:none;'" : ""}}>
                                        <div class="col-lg-10 col-md-10">
                                            <div class="table-responsive border">
                                                @php
                                                    $ticket_attachment_count = $ticket["attachment"]->where("discussion_id","=",null)->count();
                                                    $comment_attachment_count = $ticket["attachment"]->where("discussion_id","!=",null)->where("is_mail","=",0)->count();
                                                    $mail_attachment_count = $ticket["attachment"]->where("discussion_id","!=",null)->where("is_mail",1)->count();
                                                    $total_comment_attachment_count = $comment_attachment_count + $mail_attachment_count;
                                                @endphp
                                                @if($ticket_attachment_count >0)
                                                    <table class="table table-bordered text-wrap w-100" id="">
                                                        <thead>
                                                        <tr align="center">
                                                            <th colspan="8">TICKET ATTACHMENTS</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 3%;">{{ucfirst(trans('words.id'))}}</th>
                                                            <th style="width: 15%;">{{ucfirst(trans('words.file_name'))}}</th>
                                                            <th style="width: 3%;">Extension</th>
                                                            <th style="width: 9%;">{{ucfirst(trans('words.file_size'))}}</th>
                                                            <th style="width: 12%;">{{ucfirst(trans('words.sent_by_mail'))}}</th>
                                                            <th style="width: 19%;">{{ucfirst(trans('words.uploaded_from'))}}</th>
                                                            <th style="width: 20%;">{{ucfirst(trans('words.uploaded_date'))}}</th>
                                                            @if(auth()->user()->role_id != 7)
                                                                <th style="width: 19%; text-align: center;">{{ucfirst(trans('words.action'))}}</th>
                                                            @endif
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($ticket['attachment'] as $attachment)

                                                            @if($attachment->discussion_id==null )

                                                                <tr class="{{$attachment->private==1?"text-white":""}}">
                                                                    <td style="text-align: left;" class="{{$attachment->private==1?"bg-primary":""}}">{{$attachment->id}}</td>
                                                                    <td style="text-align: left;" class="{{$attachment->private==1?"bg-primary":""}}">
                                                                        <a href="{{route("uploads",[$attachment->attachment])}}" class="link {{$attachment->private==1?"text-white":"text-primary"}}" target="_blank">
                                                                            <span @if(strlen($attachment->attachment)>22) data-toggle="tooltip" data-placement="top" title="{{$attachment->attachment}}" @endif>
                                                                                @if(strlen($attachment->attachment)>22)
                                                                                    {{substr($attachment->attachment,0,22)."..."}}
                                                                                @else
                                                                                    {{$attachment->attachment}}
                                                                                @endif
                                                                            </span>
                                                                        </a>
                                                                    </td>
                                                                    <td style="text-align: left;" class="{{$attachment->private==1?"bg-primary":""}}">{{substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</td>
                                                                    <td style="text-align: left;" class="{{$attachment->private==1?"bg-primary":""}}">{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                                    <td style="text-align: center;" class="{{$attachment->private==1?"bg-primary":""}}"> {!!$attachment->is_mail==1? '<i class="fa fa-check"></i>':"<span class='font-weight: bold;'>x</span>"!!} </td>
                                                                    <td style="text-align: left;" class="{{$attachment->private==1?"bg-primary":""}}">{{$attachment->UserName}} {{$attachment->SurName}}</td>
                                                                    <td style="text-align: left;" class="{{$attachment->private==1?"bg-primary":""}}">{{$attachment->ParsedCreatedAt}}</td>
                                                                    @if(auth()->user()->role_id != 7)
                                                                        <td>
                                                                            <div class="text-center">
                                                                                <i class="btn btn-sm btn-danger fa fa-trash deleteAttachment"
                                                                                   data-id="{{$attachment->id}}" data-toggle="tooltip" data-placement="top" title="Delete Attachment"></i>
                                                                                <i class="btn btn-sm btn-primary  private-att fa {{$attachment->private==1?"fa-lock":"fa-unlock"}}"
                                                                                   data-is-private="{{$attachment->private==1?"1":"0"}}"
                                                                                   data-id="{{$attachment->id}}" data-toggle="tooltip" data-placement="top" title="{{$attachment->private?"Set Unprivate":"Set Private"}}"></i>
                                                                            </div>
                                                                        </td>
                                                                    @endif

                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                                @if($total_comment_attachment_count > 0)
                                                    <table class="table table-bordered text-wrap w-100" id="">
                                                        <thead>
                                                        <tr align="center">
                                                            <th colspan="8">COMMENT ATTACHMENTS</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 3%;">{{ucfirst(trans('words.id'))}}</th>
                                                            <th style="width: 15%;">{{ucfirst(trans('words.file_name'))}}</th>
                                                            <th style="width: 3%;">Extension</th>
                                                            <th style="width: 9%;">{{ucfirst(trans('words.file_size'))}}</th>
                                                            <th style="width: 12%;">{{ucfirst(trans('words.sent_by_mail'))}}</th>
                                                            <th style="width: 19%;">{{ucfirst(trans('words.uploaded_from'))}}</th>
                                                            <th style="width: 20%;">{{ucfirst(trans('words.uploaded_date'))}}</th>
                                                            @if(auth()->user()->role_id != 7)
                                                            <th style="width: 28%; text-align: center">{{ucfirst(trans('words.action'))}}</th>
                                                            @endif
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($ticket['attachment'] as $attachment)
                                                            @if($attachment->discussion_id!=null )
                                                                <tr class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"text-white":""}}">
                                                                    <td class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}">{{$attachment->id}}</td>
                                                                    <td class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}"><a href="{{route("uploads",[$attachment->attachment])}}" class="link {{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"text-white":"text-primary"}}" target="_blank"> <span @if(strlen($attachment->attachment)>22) data-toggle="tooltip" data-placement="top" title="{{$attachment->attachment}}" @endif>@if(strlen($attachment->attachment)>22) {{substr($attachment->attachment,0,22)."..."}} @else {{$attachment->attachment}} @endif</span></a></td>
                                                                    <td class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}">{{substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</td>
                                                                    <td style="text-align: left" class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}">{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                                    <td style="text-align: center" class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}">{!!$attachment->is_mail==1? '<i class="fa fa-check"></i>':"<span class='font-weight: bold;'>x</span>"!!} </td>
                                                                    <td style="text-align: left" class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}">{{$attachment->UserName}} {{$attachment->SurName}}</td>
                                                                    <td style="text-align: left" class="{{$attachment->private==1 || $attachment->getDiscussion->is_private==1?"bg-primary":""}}">{{$attachment->ParsedCreatedAt}}</td>
                                                                    @if(auth()->user()->role_id != 7)
                                                                        <td>
                                                                            <div class="text-center">
                                                                                <i class="btn btn-sm btn-danger fa fa-trash deleteAttachment" data-id="{{$attachment->id}}" data-toggle="tooltip" data-placement="top" title="Delete Attachment"></i>
                                                                                <i class="btn btn-sm btn-info jumpToComment fa fa-comment" data-id="{{$attachment->discussion_id}}" data-toggle="tooltip" data-placement="top" title="Go to comment"></i>
                                                                                @if(!$attachment->getDiscussion->is_private)
                                                                                <i class="btn btn-sm btn-primary private-att fa {{$attachment->private==1?"fa-lock":"fa-unlock"}}" data-is-private="{{$attachment->private==1?"1":"0"}}" data-id="{{$attachment->id}}" data-toggle="tooltip" data-placement="top" title="{{$attachment->private?"Set Unprivate":"Set Private"}}"></i>
                                                                                @else
                                                                                <a class="btn btn-sm btn-primary fs-14 pl-3 pr-3" data-toggle="tooltip" data-placement="top" title="Comment is already private!"><i class="fa fa-info"></i></a>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- attt END --}}

                                <div class="form-label" style="padding-top: 10px;" id="addAttachmentText">
                                    {{ucfirst(trans('words.add_attachment'))}}
                                    @if(auth()->user()->role_id == 1)
                                        <span style="color:red">(max. 5 Files | max. File size 500 MB)</span>
                                    @else
                                        <span style="color:red">(max. 5 Files | max. File size 100 MB)</span>
                                    @endif
                                </div>

                                <div id="attachmentResponse">
                                </div>
                                <input type="hidden" name="save_close" id="save_close" value="">
                            </form>
                            <form id="ticket-export-form" action="/getReportSummary/{{$ticket->id}}" method="POST">

                                <input type="hidden" id="report-summery-token" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" id="ticket-export-submit" style="display: none;">
                            </form>
                        </div>

                        {{-- Right Part: Logs --}}
                        <div class="col-lg-3 col-md-3">
                            <div class="d-flex justify-content-end">
                                <div class="flex-column w-100">
                                    <div class="card-body  px-1">
                                        <div class="latest-timeline scrollbar3" id="scrollbar3">
                                            <ul class="timeline mb-0 " style="overflow-y: scroll;max-height: 400px;">
                                                @if($ticket->created_at)
                                                    <li class="mt-0">
                                                        <div class="d-flex"><span
                                                                class="time-data">Ticket Created </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($ticket->created_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from <span
                                                                class="text-info">{{$ticket["created_from"] != null ? $ticket["created_from"]->first_name: "-"}} {{$ticket["created_from"] != null ? $ticket["created_from"]->surname: "-"}} {{$ticket["ticketRobotFromMail"] ? " (".$ticket["ticketRobotFromMail"].")":""}} </span>
                                                        </p>
                                                        @if($mail_log != null && auth()->user()->role_id != 7)
                                                            <span id="mail_log_toggle" class="time-data fs-14"
                                                                  style="cursor:pointer;">Mail Log <span
                                                                    class="fs-10">[ Sent to
                                                                @if($mail_log->who==1) assigned user
                                                                    @elseif($mail_log->who==2)ticket holder
                                                                    @elseif($mail_log->who==3)assigned user and ticket
                                                                    holder
                                                                    @elseif($mail_log->who==4)other person
                                                                    @elseif($mail_log->who==5) ticket holder and other
                                                                    person
                                                                    @elseif($mail_log->who==6) assigned user. Automatic
                                                                    mail created by customer.
                                                                    @elseif($mail_log->who==7) assigned user.
                                                                    Automatic mail created by personnel.
                                                                    @endif]</span></span>
                                                            <div id="mail_log" style="display: none;">
                                                                <div class="d-flex flex-column">
                                                                    <ul class="mt-1">
                                                                        @if($mail_log->mail_to)
                                                                            <li class="font-weight-semibold border-bottom">
                                                                                @if($mail_log->who==1 || $mail_log->who==3 || $mail_log->who == 6)
                                                                                    Assigned user
                                                                                @elseif($mail_log->who==4 || $mail_log->who==5)
                                                                                    Other person
                                                                                @endif
                                                                            </li>
                                                                            <li><span
                                                                                    class="fs-12 font-weight-bold">To</span>
                                                                                <ul class="fs-12 font-italic">
                                                                                    @foreach(explode(";",$mail_log->mail_to) as $mailto)
                                                                                        <li>
                                                                                            {{$mailto}}
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </li>
                                                                            @if($mail_log->mail_cc)
                                                                                <li><span
                                                                                        class="fs-12 font-weight-bold">Cc</span>
                                                                                    <ul class="fs-12 font-italic">
                                                                                        @foreach(explode(";",$mail_log->mail_cc) as $mailcc)
                                                                                            <li>
                                                                                                {{$mailcc}}
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </li>
                                                                            @endif
                                                                            @if($mail_log->mail_bcc)
                                                                                <li><span
                                                                                        class="fs-12 font-weight-bold">Bcc</span>
                                                                                    <ul class="fs-12 font-italic">
                                                                                        @foreach(explode(";",$mail_log->mail_bcc) as $mailbcc)
                                                                                            <li>
                                                                                                {{$mailbcc}}
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </li>
                                                                            @endif

                                                                        @endif
                                                                    </ul>
                                                                    @if($mail_log->mail_holder_to)
                                                                        <ul class="mt-2">
                                                                            <li class="border-bottom font-weight-semibold ">
                                                                                Ticket holder
                                                                            </li>
                                                                            <li><span
                                                                                    class="fs-12 font-weight-bold">To</span>
                                                                                <ul class="fs-12 font-italic">
                                                                                    @foreach(explode(";",$mail_log->mail_holder_to) as $mailto)
                                                                                        <li>
                                                                                            {{$mailto}}
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </li>
                                                                            @if($mail_log->mail_holder_cc)
                                                                                <li><span
                                                                                        class="fs-12 font-weight-bold">Cc</span>
                                                                                    <ul class="fs-12 font-italic">
                                                                                        @foreach(explode(";",$mail_log->mail_holder_cc) as $mailcc)
                                                                                            <li>
                                                                                                {{$mailcc}}
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </li>
                                                                            @endif
                                                                            @if($mail_log->mail_holder_bcc)
                                                                                <li><span
                                                                                        class="fs-12 font-weight-bold">Bcc</span>
                                                                                    <ul class="fs-12 font-italic">
                                                                                        @foreach(explode(";",$mail_log->mail_holder_bcc) as $mailbcc)
                                                                                            <li>
                                                                                                {{$mailbcc}}
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </li>
                                                                            @endif
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </li>
                                                @else
                                                    <li class="mt-0">
                                                        <div class="d-flex">
                                                            <span class="time-data">Ticket Created </span>
                                                            <span class="ml-auto text-muted fs-11">-</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from
                                                            <span class="text-info">-</span>
                                                        </p>
                                                    </li>
                                                @endif
                                                @if($ticket->updated_at)
                                                    <li class="mb-1 updated_log">
                                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($ticket->updated_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13 mb-1">Last updated from <span
                                                                class="text-info">{{$ticket["updated_from"] != null ? $ticket["updated_from"]->first_name: "-"}} {{$ticket["updated_from"] != null ? $ticket["updated_from"]->surname: "-"}} {{($ticket["update_by"]==206 && $ticket["ticketRobotFromMail"]) ? " (".$ticket["ticketRobotFromMail"].")" : "" }} </span>
                                                        </p>
                                                    </li>
                                                @else
                                                    <li class="mb-1 updated_log">
                                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                                class="ml-auto text-muted fs-11">-</span></div>
                                                        <p class="text-muted fs-12">Last updated from <span
                                                                class="text-info">-</span></p>
                                                    </li>
                                                @endif



                                                {{-- Proofed Att --}}
                                                @if($ticket->proofed_at)
                                                    <li class="mb-1">
                                                        <div class="d-flex"><span class="time-data">Proofed </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($ticket->proofed_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13 mb-1">Proofed from <span
                                                                class="text-info">{{$ticket->getProofedName()}}</span>
                                                        </p>
                                                    </li>
                                                @else
                                                    <li class="mb-1 d-none" id="proof-log">
                                                    </li>
                                                @endif

                                                {{-- invoiced Att --}}
                                                @foreach($data["invoiced_and_correction_log"] as $log)
                                                    @if($log->status == 7)
                                                        <li class="mb-1 circle-color-green">
                                                            <div class="d-flex"><span class="time-data">Invoiced </span><span
                                                                    class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($log->created_at)->format("d.m.Y H:i:s")}}</span>
                                                            </div>
                                                            <p class="text-muted fs-13 mb-1">Invoiced from <span
                                                                    class="text-info">{{$log->getUser()}}</span>
                                                            </p>
                                                        </li>
                                                        {{-- correction Att --}}
                                                    @elseif($log->status == 10)
                                                        <li class="mb-1 circle-color-yellow">
                                                            <div class="d-flex"><span class="time-data">Correction after invoice </span><span
                                                                    class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($log->created_at)->format("d.m.Y H:i:s")}}</span>
                                                            </div>
                                                            <p class="text-muted fs-13 mb-1">Correction from <span
                                                                    class="text-info">{{$log->getUser()}}</span>
                                                            </p>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                @if(auth()->user()->role_id != 7)
                                                    @if(count($data["ticket_status_log"])>0)
                                                        <br>
                                                        <span class="time-data fs-14 pl-5" id="show-details-log" style="cursor:pointer;">Details <x-infobox info="Shown ticket status changes."/></span>
                                                        <br>
                                                        @endif
                                                        @endif
                                                        </li>

                                            </ul>
                                            @if(auth()->user()->role_id != 7)
                                                <div class="latest-timeline scrollbar3" id="scrollbar3">
                                                    <ul class="timeline mb-0" id="details-log" style="display:none;overflow-y: scroll;max-height: 300px;">
                                                        @foreach($data["ticket_status_log"] as $key => $status_log)

                                                            @php
                                                                if($status_log->status == 6 || $status_log->status == 7)
                                                                     $color_class ="circle-color-green";
                                                                else if($status_log->status == 3)
                                                                    $color_class ="circle-color-yellow";

                                                                else if($status_log->status == 8)
                                                                    $color_class = "circle-color-gray";
                                                                else if($status_log->status == 9)
                                                                    $color_class = "circle-color-black";
                                                                else
                                                                    $color_class = "circle-color-red";
                                                            @endphp
                                                            <li class="{{$color_class}}">
                                                                <div class="d-flex">
                                                                    <span class="time-data fs-12">Update Status: {{ isset($data["ticket_status_log"][$key + 1]) ? $data["ticket_status_log"][$key + 1]->TicketStatusName: "?" }} --> {{$status_log->TicketStatusName}}</span><span class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($status_log->created_at)->format("d.m.Y H:i:s")}}</span>
                                                                </div>
                                                                <p class="text-muted fs-12">Changed by <span class="text-info">{{ $status_log->StatusUserName??"-" }}</span></p>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 col-md-10">
                            <div class="row">
                                <div class="col-lg-9 col-md-9">
                                    <form class="dropzone" id="ticketAttachments"> @csrf</form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="buttonRow">
                        <div class="col-lg-10 col-md-10">
                            <div class="row">
                                <div class="col-lg-9 col-md-9">
                                    @if((in_array(auth()->user()->org_id, [3, 8])) && auth()->user()->role_id != 7)
                                    @if($ticket->is_private)
                                    <button type="button" class="btn btn-primary mt-4 mb-0 float-left ticket-privacy-button" data-ticket-privacy="0">
                                        <i class="fe fe-unlock mr-1"></i>Set Public
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-primary mt-4 mb-0 float-left ticket-privacy-button" data-ticket-privacy="1">
                                        <i class="fe fe-lock mr-1"></i>Set {{ ucfirst(trans('words.private')) }}
                                    </button>
                                    @endif
                                    @endif
                                    <button type="submit" class="btn btn-outline-success mt-4 ml-4 mb-0 float-right updateTicketButtonSaveClose">{{trans('words.save')}}& Close</button>
                                    <button type="submit" class="btn btn-success mt-4 mb-0 float-right updateTicketButton">{{trans('words.save')}}</button>
                                    <a href="{{url('/tickets')}}" class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{trans('words.cancel')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Important Decision Form --}}
    {{-- We use this form inside the important-decisions.blade.php file --}}
    <form method="POST" class="d-none" id="important-form" action="{{url('/important/add')}}">
        @csrf
        <input type="hidden" name="important_text" id="important-form-text">
        <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
    </form>

    @if(auth()->user()->role_id == 1)
        <div class="card">
            <div class="card-body">
                <div class="row ">
                    <div class="col-lg-9 col-md-9">
                        <div class="card-header" style="border: 0!important;">
                            <h3 class="card-title">Accounting</h3>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row" style="padding-left: 10px;">
                            @if (in_array(auth()->id(), [5, 9, 81, 82, 86, 119, 126, 158, 161, 162, 199, 201, 202]))
                                <div class="col-md-3 pb-4">
                                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                                        <div class="card-body text-left">
                                            <h4 class="accounting-sweet-alert-title">getucon GmbH</h4>
                                            <ul class="popup-menu-links">
                                                <li>
                                                    <a href="{{ url('/getucon/accounting/add/offer?ticket_id=' . $ticket->id) }}" target="_blank">Offer</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/getucon/accounting/add/proforma?ticket_id=' . $ticket->id) }}" target="_blank">Proforma</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/getucon/accounting/add/invoice?ticket_id=' . $ticket->id) }}" target="_blank">Invoice</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (in_array(auth()->id(), [5, 86, 119, 158, 161, 199, 201, 202]))
                                <div class="col-md-3 pb-4">
                                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                                        <div class="card-body text-left">
                                            <h4 class="accounting-sweet-alert-title">getucon Ltd. (TR)</h4>
                                            <ul class="popup-menu-links">
                                                <li>
                                                    <a href="{{ url('/accounting-tr/add/getucon-tr/offer?ticket_id=' . $ticket->id) }}" target="_blank">Offer</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/accounting-tr/add/getucon-tr/invoice?ticket_id=' . $ticket->id) }}" target="_blank">Invoice</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 pb-4">
                                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                                        <div class="card-body text-left">
                                            <h4 class="accounting-sweet-alert-title">Guler Consulting Ltd. (TR)</h4>
                                            <ul class="popup-menu-links">
                                                <li>
                                                    <a href="{{ url('/accounting-tr/add/guler-consulting/offer?ticket_id=' . $ticket->id) }}" target="_blank">Offer</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/accounting-tr/add/guler-consulting/invoice?ticket_id=' . $ticket->id) }}" target="_blank">Invoice</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 pb-4">
                                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                                        <div class="card-body text-left">
                                            <h4 class="accounting-sweet-alert-title">MediaKit Production A.Ş.</h4>
                                            <ul class="popup-menu-links">
                                                <li>
                                                    <a href="{{ url('/accounting-tr/add/media-kit/offer?ticket_id=' . $ticket->id) }}" target="_blank">Offer</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/accounting-tr/add/media-kit/invoice?ticket_id=' . $ticket->id) }}" target="_blank">Invoice</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Comments Bottom --}}
    <div class="card">
        <div class="card-body">

            <div class="row ">
                <div class="col-lg-9 col-md-9">

                    <div class="card-header" style="border: 0!important;">
                        <h3 class="card-title">Comment/New Update <x-infobox info="-You can do adding and updating efforts in the comment sections.<br>
                        -You can add attacment to comment.<br>
                        -You can change assigned user,ticket status and ticket due date while comment saving.<br>
                        -If you want to send email to anyone,you can use right pane send e-mail section."/></h3>
                    </div>

                    <div class="card-body p-0">
                        {{-- Comment --}}
                        <form id="discussionForm" method="post">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                            <div class="row">
                                <div class="col-lg-10 col-md-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <textarea id="discussion" name="discussion" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($ticket['discussionNotPrivate']>0 && in_array(auth()->user()->role_id,[1, 2, 3, 4]))
                            <div class="row mt-2 mb-1">
                                <div class="col-lg-10 col-md-10">
                                    <div>
                                        <button type="button" id="take-last" onclick="takeComment('last',{{$ticket->id}})" class="btn btn-info mb-1 mr-2 take-comment">
                                            Take Last Comment
                                        </button>
                                        <button type="button" id="take-all" onclick="takeComment('all',{{$ticket->id}})" class="btn btn-primary mb-1 take-comment">
                                            Take All Comments
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-lg-10 col-md-10">
                                    <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4"><span class="form-label m-0 text-center">Ticket Assigned User</span></div>
                                                    <div class="col-md-4"><span class="form-label m-0 text-center">Ticket Status</span></div>
                                                    <div class="col-md-4"><span class="form-label m-0 text-center">Ticket Due Date</span></div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select name="personnel_comment" id="main-personnel" class="form-control form-control-sm personnel-comment " {{ auth()->user()->role_id == 7 || auth()->user()->role_id == 4 ? "disabled" : ""}} style="max-height: 5%!important;">
                                                            <option value="{{$ticket->personnel}}" selected="selected">
                                                                {{$ticket->PersonnelName}} {{$ticket->PersonnelSurName}}
                                                            </option>
                                                        </select>
                                                <input type="hidden" id="last_select_ticket_assigned_user" value="{{$ticket->PersonnelEmail}}">

                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="status_comment" id="statusComment" class="form-control form-control-sm"  {{ (in_array($ticket->status_id, [6,7,9,10]) && (auth()->user()->role_id == 7 || auth()->user()->role_id == 4)) ? "readonly" : ""}}>
                                                            @foreach($data['status'] as $status)
                                                                <option value="{{$status->id}}" {{$ticket->status_id == $status->id ? "selected": ""}}>
                                                                    {{$status->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input class="form-control form-control-sm privateValidate" id="dueDateComment" type="date" name="due_date_comment" required min="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{$ticket->due_date}}" {{ auth()->user()->role_id == 7 ? "disabled" : ""}}>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="col-lg-3 col-md-3 d-flex justify-content-end align-items-end">
                                            <div class="row" id="discussionSendButtonRow">
                                                <div class="col-lg-12 col-md-12 pl-0">

                                                    <button type="button" id="sendDiscussion" class="btn btn-warning mb-0 float-right">
                                                        <i class="fe fe-edit"></i>{{ucfirst(trans('words.response'))}}
                                                    </button>

                                                    @if((in_array(auth()->user()->org_id, [3, 7, 8])) && auth()->user()->role_id!=7)
                                                        <button type="button" id="sendPrivateDiscussion"
                                                                class="btn btn-primary mr-2 mb-0 float-right">
                                                            @if(auth()->user()->role_id !=7)
                                                                <i class="fe fe-lock"></i>{{ucfirst(trans('words.private'))}}
                                                            @else
                                                                <i class="fe fe-edit"></i>{{ucfirst(trans('words.response'))}}
                                                            @endif
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 mb-1">
                                <div class="col-lg-10 col-md-10">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-4"><span class="form-label m-0 text-center">{{ucfirst(trans('words.assigned_user'))}} (Secondary)</span></div>
                                                    <div class="col-md-4"><span id="commentDueDateLabel" style="display: {{$ticket->status_id == 5 ? "block" : "none"}}" class="form-label m-0 text-center">{{ucfirst(trans('words.comment_due_date'))}} <x-infobox info="A reminder e-mail will be sent to the customer on the first Monday after 7 days from the date you selected." /></span></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select  name="assigned_personnel[]" id="assigned_personnel"  class="form-control personnel comment-assigned-personnel assigned_personnel js-example-basic-multiple" {{ auth()->user()->role_id == 7 ? "disabled" : ""}} multiple="multiple">
                                                        @foreach($ticket_personnel as $assigned_personnel)
                                                            <option value="{{$assigned_personnel["id"]}}" selected="selected">
                                                                {{$assigned_personnel["name"]}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input class="form-control form-control-sm privateValidate" style="display: {{$ticket->status_id == 5 ? "block" : "none"}}" id="commentDueDate" value="{{$ticket->comment_due_date}}" type="date" name="comment_due_date" required min="{{\Carbon\Carbon::now()->format("Y-m-d")}}"  {{ auth()->user()->role_id == 7 ? "disabled" : ""}}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Comment Effort --}}
                            @if(auth()->user()->role_id ==1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id==4 || auth()->user()->role_id == 7)
                                <div class="row">

                                    <div class="col-lg-7 col-md-7 pl-5">
                                        <div class="row pt-2">
                                            <div class="col-lg-9 col-md-9 px-0">
                                                <div class="form-group mb-0">
                                                    <label class="custom-switch">
                                                        <input type="checkbox" id="commentEffortToggle" name="commentEffortToggle" class="custom-switch-input">
                                                        <span class="custom-switch-indicator"></span>
                                                        <span class="custom-switch-description">Add Effort</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="commentEffortRow" style="display: none;">
                                            <div class="col-md-12 col-lg-12">
                                                {{-- Effort --}}
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12">

                                                        <div class="row text-center">
                                                            <div class="col-md-4 col-lg-4">
                                                                Type of effort
                                                            </div>
                                                            <div class="col-md-3 col-lg-3">
                                                                Hours
                                                            </div>
                                                            <div class="col-md-3 col-lg-3">
                                                                Minutes
                                                            </div>
                                                            <div class="col-md-2 col-lg-1">
                                                                &nbsp;
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12" id="effortAreaComment">
                                                                <div class="row effortRow">
                                                                    <div class="col-lg-4 col-md-4">
                                                                        <div class="form-group">
                                                                            <select name="commentEfforts[effort_types][]" class="form-control form-control-sm effortTypesComment">
                                                                                <option value="" @if(!$data['userEffortType']) selected @endif ></option>
                                                                                @foreach($data['effortTypes'] as $effort_type)
                                                                                <option {{ $effort_type->id == $data['userEffortType'] ? 'selected' : '' }}value="{{$effort_type->id}}">{{$effort_type->type}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3">
                                                                        <input class="form-control form-control-sm" type="number" name="commentEfforts[hours][]">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3">
                                                                        <input class="form-control form-control-sm" type="number" name="commentEfforts[mints][]">
                                                                    </div>
                                                                    <div class="col-md-2 col-lg-2 pr-0">
                                                                        <button type="button" id="addEffortButton" class="btn btn-sm btn-success" data-effort-id="1" onclick="addEffortComment()"><i class="fa fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    {{-- Comment Buttons --}}

                                </div>

                                <div id="discussionAttachmentResponse">
                                </div>
                        </form>

                        <div class="row" id="discussionAttachmentSwitch">


                            {{-- Comment Attachment --}}
                            <div class="col-lg-7 col-md-7">
                                <div class="row pt-2">
                                    <div class="col-lg-9 col-md-9">
                                        <div class="form-group mb-0">
                                            <label class="custom-switch">
                                                <input type="checkbox" id="discussionAttachmentToggle" name="discussionAttachmentToggle" class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Attach File</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="discussionAttacmentRow" style="display: none;">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-label" style="padding-top: 10px;font-size:.8rem;" id="addAttachmentText">
                                            {{ucfirst(trans('words.add_attachment'))}}
                                            <span style="color:red;">(max. 5 Files | max. File size 100 MB)</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <form class="dropzone" id="discussionAttachment"> @csrf</form>
                                    </div>
                                    <input style="display: none" id="attachmentTotalSize" value="0" >
                                </div>
                            </div>
                        </div>


                        <input type="hidden" name="ticket_id" value="{{$ticket->id}}" id="ticket_id">
                        @php
                            $user_assigned = App\User::where('id', $ticket->personnel)->first();
                            $user_holder = App\User::where('id', $ticket->user)->first();
                        @endphp
                        <input type="hidden" name="assigned_user_mail" id="assigned_user_mail" value="{{$user_assigned->email ??""}}">
                        <input type="hidden" name="holder_user_mail" id="holder_user_mail" value="{{$user_holder->email ??""}}">

                        {{-- Old Comments Part --}}
                        @foreach($ticket['discussion'] as $discussion)
                            <div class="row">
                                <div class="col-sm-10 col-md-10 mt-3" id="discussion-section{{$discussion->id}}">

                                    <div class="alert alert-light-{{$discussion->is_private==1 ? 'primary':'private'}}" style="border: 1px solid #dcd8c3;">
                                        <strong class="pr-4">{{$discussion['UserName']}} {{$discussion["commentRobotFromMail"] ? "(". "from ".$discussion["commentRobotFromMail"].")" : ""   }}</strong>


                                        <strong class="float-right">
                                            {{ \Carbon\Carbon::parse($discussion['created_at'])->format('d.m.Y [H:i:s]')}}</strong>
                                        <hr class="message-inner-separator">
                                        <div class="row retrieve-comment-height">
                                            <div class="col-lg-10 col-md-10" id="discussion-text-area-{{$discussion->id}}" data-discussion-isprivate="{{$discussion->is_private}}">
                                                {!!$discussion->message!!}
                                            </div>
                                        </div>

                                            <div class="row mt-3">
                                                <div class="col-md-8 col-lg-8">
                                                    <div class="row border-bottom">
                                                        <div class="col-md-12 col-lg-12">
                                                            <strong>Comment Efforts</strong>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-12">
                                                            @unless($discussion->user_id != auth()->user()->id && auth()->user()->role_id==4)
                                                            <div class="row text-center">
                                                                <div class="col-md-4 col-lg-4">
                                                                    <span class="fs-11">Type of effort</span>
                                                                </div>
                                                                <div class="col-md-3 col-lg-3">
                                                                    <span class="fs-11">Hours</span>
                                                                </div>
                                                                <div class="col-md-3 col-lg-3">
                                                                    <span class="fs-11"> Minutes</span>
                                                                </div>
                                                            </div>
                                                            <input id="comment-effort-type-inp" type="hidden" value="{{$data['userEffortType']}}" >
                                                            <form id="comment-effort-form-{{$discussion->id}}">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-4 col-md-4">
                                                                                <div class="form-group">
                                                                                    <select id="comment-effort-type-{{$discussion->id}}" class="form-control form-control-sm effort-input-height" name="effort_type">
                                                                                        <option value="" @if(!$data['userEffortType']) selected @endif></option>
                                                                                        @foreach($data['effortTypes'] as $effort_type)
                                                                                        <option {{ $effort_type->id == $data['userEffortType'] ? 'selected' : '' }} value="{{$effort_type->id}}">{{$effort_type->type}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3">
                                                                                <input id="comment-effort-hour-{{$discussion->id}}" class="form-control form-control-sm effort-input-height privateValidate only-positive-int" type="number" name="effort_hour">
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3">
                                                                                <select id="comment-effort-minute-{{$discussion->id}}"  class="form-control form-control-sm effort-input-height  privateValidate only-positive-int" type="number" name="effort_minute">
                                                                                    <option>0</option>
                                                                                    <option value="15">15</option>
                                                                                    <option value="30">30</option>
                                                                                    <option value="45">45</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-2 col-lg-2 pr-0">
                                                                                <button type="button" class="btn btn-sm btn-success fs-8 comment-effort-save" data-discussion-id="{{$discussion->id}}">
                                                                                    <i class="fa fa-check"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            @endunless
                                                            @unless($discussion->effort_logs->isEmpty())
                                                                    <div class="row comment-effort-log-row">
                                                                        <div class="col-md-12 col-lg-12 p-0">
                                                                            <div class="d-flex flex-column">
                                                                                <div class="comment-effort-log-table-{{$discussion->id}}">
                                                                                    @php $vCreatDate =\Carbon\Carbon::now(); @endphp
                                                                                    @foreach($discussion->effort_logs as $effort_log )
                                                                                        <table class="w-100 comment-effort-section">
                                                                                            <tr>
                                                                                                <td class="font-weight-semibold fs-11" style="width: 1rem; !important">{{$loop->index+1}}</td>
                                                                                                <td style="width: 0.5rem; !important">&#8226;</td>
                                                                                                <td class="text-center fs-11" style="width: 10rem; !important">{{\Carbon\Carbon::parse($effort_log->created_at)->format("d.m.Y H:i:s")}}</td>
                                                                                                <td class="textcenter" style="width: 1rem; !important">&#8226;</td>
                                                                                                <td class="text-center fs-11" style="width: 7rem; !important">
                                                                                                    @foreach ($data["effortTypes"] as $effort_type)
                                                                                                        @if($effort_log->effort_type == $effort_type->id)
                                                                                                            @if($effort_type->id == 5){{--Eğer effort type internal ise eğik yaz çünkü faturalandırmaya dahil değil--}}
                                                                                                            <em>{{$effort_type->type}}</em>
                                                                                                            @else
                                                                                                                {{$effort_type->type}}
                                                                                                            @endif
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </td>
                                                                                                <td style="width: 0.5rem; !important">&#8226;</td>
                                                                                                <td class="text-center fs-11" style="width: 7rem; !important">{{sprintf("%02d",$effort_log->hours)}} Hours and {{sprintf("%02d",$effort_log->minutes)}} Minutes</td>
                                                                                                <td style="width: 0.5rem; !important">&#8226;</td>
                                                                                                <td style="width: 5rem; !important">
                                                                                                    @unless($discussion->user_id != auth()->id() && auth()->user()->role_id==4)
                                                                                                    <span class="text-right fs-10 d-flex align-items-center justify-content-center">
                                                                                                        <i style="cursor: pointer;" class="fa fa-pencil text-info comment-effort-update-btn mr-3" data-effort-id="{{$effort_log->id}}" data-discussion-id="{{$discussion->id}}"></i>
                                                                                                        <i style="cursor: pointer;" class="fa fa-trash text-danger pl-1 comment-effort-delete-btn" data-effort-id="{{$effort_log->id}}" data-discussion-id="{{$discussion->id}}"></i>
                                                                                                    </span>
                                                                                                    @endunless
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            <div class="row comment-total-effort-row pt-2">
                                                                <div class="col-md-12 col-lg-12 p-0">
                                                                    <div class="row">
                                                                        <div class="col-md-1 col-lg-1 pr-0 d-flex align-items-center">
                                                                            <span class="fs-12">Total:</span>
                                                                        </div>
                                                                        <div class="col-md-11 col-lg-11">
                                                                            <div class="row flex-column" id="comment-effort-total-time-section-{{$discussion->id}}">
                                                                                @foreach($discussion->total_logs as $key => $value)
                                                                                    <div class="col-md-4 col-lg-4">
                                                                                        @foreach($data["effortTypes"] as $effort_type)
                                                                                        @if($key == $effort_type->id)
                                                                                        <span class="font-weight-semibold fs-11" style="color:#384B6E; min-width: 200px">
                                                                                            @if($effort_type->id == 5) {{--Eğer effort type internal ise eğik yaz çünkü faturalandırmaya dahil değil--}}
                                                                                            <em>{{$effort_type->type}}:</em>
                                                                                            @else
                                                                                            {{$effort_type->type}}:
                                                                                            @endif
                                                                                        </span>
                                                                                        @endif
                                                                                        @endforeach
                                                                                        <span class="fs-10">{{ $value }}</span>
                                                                                    </div>
                                                                                @endforeach

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endunless
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    @if($ticket["attachment"]->where("is_mail",0)->where("discussion_id",$discussion->id)->count()>0)
                                                        <div class="row border-bottom">
                                                            <div class="col-md-12 col-lg-12 text-center">
                                                                <strong>Comment Attachments</strong>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12 d-flex ">
                                                                <div class="d-flex flex-wrap">
                                                                    @foreach($ticket["attachment"] as $attachment)
                                                                        @if($attachment->discussion_id!=null && $attachment->is_mail==0)
                                                                            @php
                                                                                $filetype = \File::extension(storage_path("app/uploads/").$attachment->attachment);
                                                                            @endphp

                                                                            @if($attachment->discussion_id == $discussion->id)
                                                                                <a href="{{route("uploads",[$attachment->attachment])}}" target="_blank" data-toggle="tooltip" data-placement="top" title="{{$attachment->attachment}}" class="d-flex flex-column">
                                                                                    @if(Str::contains($filetype,["png","jpg","jpeg"]))
                                                                                    <img class="ticket-comment-attachments object-fit-cover @if($attachment->private == 1) private-attachment-border @endif" src="{{route("uploads",[$attachment->attachment])}}" alt="Ticket Comment's Attachment(Image)">
                                                                                    @elseif(Str::contains($filetype,["doc","docx"]))
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/doc.png')}}" alt="Ticket Comment's Attachment(Docx)">
                                                                                    @elseif(Str::contains($filetype,["xls","xlsx"]))
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/xls.png')}}" alt="Ticket Comment's Attachment(XLSX)">
                                                                                    @elseif(Str::contains($filetype,["csv"]))
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/csv.png')}}" alt="Ticket Comment's Attachment(CSV)">
                                                                                    @elseif(Str::contains($filetype,["zip"]))
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/zip.png')}}" alt="Ticket Comment's Attachment(Zip)">
                                                                                    @elseif(Str::contains($filetype,["pdf"]))
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/pdf.png')}}" alt="Ticket Comment's Attachment(PDF)">
                                                                                    @elseif(Str::contains($filetype,["video"]))
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/play.png')}}" alt="Ticket Comment's Attachment(Video)">
                                                                                    @else
                                                                                    <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/file.png')}}" alt="Ticket Comment's Attachment(File)">
                                                                                    @endif
                                                                                    <span class="ticket-span-clamp">{{ $attachment->attachment }}</span>
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($ticket["attachment"]->where("is_mail",1)->where("discussion_id",$discussion->id)->count()>0)
                                                        <div class="row border-bottom">
                                                            <div class="col-md-12 col-lg-12 text-center">
                                                                <strong>Mail Attachments</strong>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12 d-flex ">
                                                                <div class="d-flex flex-wrap">
                                                                    @foreach($ticket["attachment"] as $attachment)
                                                                        @if($attachment->is_mail==1)
                                                                            @php
                                                                                $filetype = \File::extension(storage_path("app/uploads/").$attachment->attachment);
                                                                            @endphp

                                                                            @if($attachment->discussion_id == $discussion->id)
                                                                                <a href="{{route("uploads",[$attachment->attachment])}}" target="_blank" data-toggle="tooltip" data-placement="top" title="{{$attachment->attachment}}" class="d-flex flex-column">
                                                                                    @if(Str::contains($filetype,["png","jpg","jpeg"]))
                                                                                        <img class="ticket-comment-attachments object-fit-cover @if($attachment->private == 1) private-attachment-border @endif" src="{{route("uploads",[$attachment->attachment])}}" alt="Ticket Comment's Attachment(Image)">
                                                                                    @elseif(Str::contains($filetype,["doc","docx"]))
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/doc.png')}}" alt="Ticket Comment's Attachment(Docx)">
                                                                                    @elseif(Str::contains($filetype,["xls","xlsx"]))
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/xls.png')}}" alt="Ticket Comment's Attachment(XLSX)">
                                                                                    @elseif(Str::contains($filetype,["csv"]))
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/csv.png')}}" alt="Ticket Comment's Attachment(CSV)">
                                                                                    @elseif(Str::contains($filetype,["zip"]))
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/zip.png')}}" alt="Ticket Comment's Attachment(Zip)">
                                                                                    @elseif(Str::contains($filetype,["pdf"]))
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/pdf.png')}}" alt="Ticket Comment's Attachment(PDF)">
                                                                                    @elseif(Str::contains($filetype,["video"]))
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/play.png')}}" alt="Ticket Comment's Attachment(Video)">
                                                                                    @else
                                                                                        <img class="ticket-comment-attachments @if($attachment->private == 1) private-attachment-border @endif" src="{{asset('/assets/images/fileicons/file.png')}}" alt="Ticket Comment's Attachment(File)">
                                                                                    @endif
                                                                                    <span class="ticket-span-clamp">{{ $attachment->attachment }}</span>
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($ticket["attachment"]->where("discussion_id",$discussion->id)->count()>0)
                                                        <div class="row mt-4">
                                                            <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                                                                <a  style="text-decoration: underline;" class="comment-attachment-download-btn cursor-pointer" data-discussion-id="{{$discussion->id}}">Download All <i class="fa fa-download" style="text-decoration:underline;"></i></a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>


                                        @php
                                            $discussion_logs = \App\DiscussionLog::query()->where("discussion_id",$discussion->id)->get();
                                            $discussion_message_update_logs = \App\DiscussionMessageUpdateLog::query()->where("discussion_id",$discussion->id)->orderBy("created_at","desc")->get();
                                        @endphp

                                        <div id="history-text-{{$discussion->id}}" class="w-25" style="cursor: pointer;" onclick="$.showHistory('{{$discussion->id}}')"><strong>History</strong></div>
                                        @if($discussion_logs->count() + $discussion_message_update_logs->count() == 0)
                                        <span style="font-size: 0.75rem;">No Logs</span>
                                        @endif
                                        <div id="history{{$discussion->id}}">
                                            @foreach($discussion_logs as $log)
                                                @php
                                                    $sender_user = \App\User::query()->find($log->sender_user_id);
                                                    $receiver_user  = \App\User::query()->find($log->receiver_user_id);
                                                    $attachments = \App\TicketAttachment::where("discussion_id",$log->discussion_id)->where("discussion_log_id",$log->id)->get();
                                                @endphp
                                                <div style="line-height: 0.9rem;display: flex;flex-direction: row;">
                                                    <div style="display: flex;flex-direction: column;" class="border-bottom">
                                                        <span style="font-size: 0.75rem;font-weight: bold;cursor: pointer;" onclick="$.show_log('{{$log->id}}')">[{{\Carbon\Carbon::parse($log->created_at)->format("d.m.Y H:i:s")}}] Sent from <strong>{{$sender_user !=null ? $sender_user->first_name." ".$sender_user->surname : "-"}} <small>{{ isset($sender_user) ? "(".$sender_user->email.")" : " " }}</small></strong></span>
                                                        <span style="font-size: 0.75rem;" id="log_td{{$log->id}}">
                                                                TO : @if($receiver_user != null) {{$receiver_user->first_name." ".$receiver_user->surname}} @else {{$log->emails != null ? $log->emails : ""}} @endif
                                                                <br>
                                                                CC : {{$log->emails_cc ?? ""}}
                                                                <br>
                                                                BCC : {{$log->is_bcc_possible === "Yes" ? ($log->emails_bcc ?? "") : "Not Possible"}}
                                                                        <br>
                                                                @foreach($attachments as $attachment)
                                                                @if($attachment->is_mail)
                                                                <a href="{{route("uploads",[$attachment->attachment])}}" target="_blank">Attachment {{$loop->index+1}} : <span> {{substr($attachment->attachment,0,20)." | ".round($attachment->size*0.000001,2)  ." Mb | ".substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</span></a><br>
                                                                @endif
                                                            @endforeach
                                                            </span>
                                                    </div>

                                                </div>

                                            @endforeach
                                        </div>
                                        <div>

                                            @foreach($discussion_message_update_logs as $update_log)
                                                @php
                                                    $user = \App\User::where("id",$update_log->user)->first();
                                                @endphp
                                                <div style="line-height: 0.7rem;display: flex;flex-direction: row;">
                                                    <div style="display: flex;flex-direction: column;"
                                                         class="border-bottom">
                                                                    <span
                                                                        style="font-size: 0.75rem;font-weight: bold;">[{{\Carbon\Carbon::parse($update_log->created_at)->format("d.m.Y H:i:s")}}] Comment updated by <strong>{{$user !=null ? $user->first_name." ".$user->surname : "-"}}</strong></span>
                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                        <div class="row mt-2 discussionSwitchs">
                                            <div class="col-lg-6 col-md-6 p-0 m-0">
                                                <div class="row">
                                                    @if(auth()->user()->role_id != 7)
                                                        <div class="col-md-6 col-lg-6">
                                                            <div class="form-group pl-0 m-0">
                                                                <label class="custom-switch">
                                                                    <input
                                                                        onchange="$.showDiscussion('{{$discussion->id}}')"
                                                                        id="show-discussion-log{{$discussion->id}}"
                                                                        type="checkbox" name="custom-switch-checkbox1"
                                                                        class="custom-switch-input" autocomplete="off">
                                                                    <span class="custom-switch-indicator"></span>
                                                                    <small class="pl-2">Send this comment to</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6 col-lg-6 locked-ticket-comment-section">
                                                        <div class="form-group pl-0 m-0">
                                                            <label class="custom-switch">
                                                                <input id="add-attach-comment-switch-{{$discussion->id}}" onchange="$.showAddAttachment('{{$discussion->id}}')" type="checkbox" name="custom-switch-checkbox1" class="custom-switch-input" autocomplete="off">
                                                                <span class="custom-switch-indicator"></span>
                                                                <small class="pl-2">Attach File</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                @if(in_array(auth()->user()->org_id, [3, 7, 8]))
                                                    <div class="d-flex justify-content-end">
                                                        <div class="d-flex align-items-end">
                                                            @if(auth()->user()->role_id != 7)
                                                                <button type="button"
                                                                        class="btn btn-sm btn-primary lockToggle"
                                                                        message-id="{{$discussion->id}}"
                                                                    {{$discussion->is_private==1 ? "data-id=0":"data-id=1"}}>
                                                                    <i class="fe fe-unlock"></i>&nbsp;Private
                                                                </button>
                                                            @endif
                                                            @if(auth()->user()->role_id==1)

                                                                <div id="hidden-button-area{{$discussion->id}}">
                                                                    <button type="button" class="btn btn-info btn-sm ml-1 fs-13 update-comment-button" data-discussion-id="{{$discussion->id}}"><i class="fa fa-edit "></i>&nbsp;<span>Edit</span></button>
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-sm ml-1 fs-13 delete-comment-button" data-discussion-id="{{$discussion->id}}"><i class="fa fa-trash "></i>&nbsp;<span>Delete</span></button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row" id="add-attach-comment-area-{{$discussion->id}}"
                                             style="display: none;">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">

                                                        <form class="dropzone"
                                                              id="add-attach-comment-form-{{$discussion->id}}"> @csrf
                                                            <div id="add-attach-comment-response-{{$discussion->id}}">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-8 col-lg-8">
                                                        <div class="d-flex justify-content-end">
                                                            <div class="d-flex align-items-end">
                                                                <button class="btn btn-sm btn-primary add-attach-comment-button mt-1" data-discussion-id="{{$discussion->id}}">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row" id="discussion-area{{$discussion->id}}"
                                             style="display: none;">
                                            <div style="font-size: 13px" class="col-lg-5 col-md-5">


                                                <div class="form-group row mt-1 mb-1">
                                                    <label style="color: #494444;"
                                                           class="col-md-1 form-label my-auto p-0"><small>To:</small>
                                                    </label>
                                                    <div class="col-md-7 p-0">
                                                        <span id="badge_to{{$discussion->id}}"
                                                              class="badge badge-danger"></span>
                                                        <div class="email_to">
                                                            <input class="bg-white" id="email_to{{$discussion->id}}" name="email_to" value="" placeholder="Maximum 5 items">
                                                            <button data-disabled="false" id="tagify-disable-email_to{{$discussion->id}}" class="d-none"></button>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row mt-1 mb-1">
                                                    <label style="color: #494444;"
                                                           class="col-md-1 form-label my-auto p-0"><small>Cc:</small>
                                                    </label>
                                                    <div class="col-md-7 p-0">
                                                        <span id="badge_cc{{$discussion->id}}"
                                                              class="badge badge-danger"></span>
                                                        <div class="email_cc">
                                                            <input class="bg-white" id="email_cc{{$discussion->id}}" name="email_cc" value="" placeholder="Maximum 5 items">
                                                            <button data-disabled="false" id="tagify-disable-email_cc{{$discussion->id}}" class="d-none"></button>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1 mb-1">
                                                    <label style="color: #494444; "
                                                           class="col-md-1 form-label my-auto p-0"><small>Bcc:</small>
                                                    </label>
                                                    <div class="col-md-7 p-0">
                                                        <span id="badge_bcc{{$discussion->id}}"
                                                              class="badge badge-danger"></span>
                                                        <div class="email_bcc">
                                                            <input class="bg-white" id="email_bcc{{$discussion->id}}" name="email_bcc" value="" placeholder="Maximum 5 items">
                                                            <button data-disabled="false" id="tagify-disable-email_bcc{{$discussion->id}}" class="d-none"></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-check pl-2 m-0">
                                                    <input class="form-check-input" name="assigned_user"
                                                           type="checkbox"
                                                           value="1"
                                                           id="assign_user{{$discussion->id}}"
                                                           onchange="$.assign_user({{$discussion->id}})">

                                                    <label class="form-check-label" for="assign_user{{$discussion->id}}">
                                                        <small>Send this update to assigned user</small>
                                                    </label>
                                                </div>
                                                <div class="form-check pl-2 m-0">
                                                    <input class="form-check-input" name="holder_user"
                                                           type="checkbox"
                                                           value="1"
                                                           id="holder_user{{$discussion->id}}"
                                                           onchange="$.holder_user({{$discussion->id}})">
                                                    <label class="form-check-label" for="holder_user{{$discussion->id}}">
                                                        <small>Send this update to ticket holder.</small>
                                                    </label>
                                                </div>
                                                <input type="hidden" id="holder_or_personnel{{$discussion->id}}"
                                                       value="">

                                                <div class="row pt-1">
                                                    <div class="col-lg-4-col-md-4 p-0 m-0">
                                                        <div class="form-group pl-0 m-0">
                                                            <label class="custom-switch">

                                                                <input
                                                                    onchange="$.showDropzone('{{$discussion->id}}')"
                                                                    id="show-dropzone{{$discussion->id}}"
                                                                    type="checkbox" name="custom-switch-checkbox1"
                                                                    class="custom-switch-input" autocomplete="off">

                                                                <span class="custom-switch-indicator"></span>
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8">
                                                        <small>Attach File</small>
                                                    </div>
                                                </div>
                                                <div class="row " id="dropzone-area{{$discussion->id}}" style="display: none;">
                                                    <div class="col-lg-8 col-md-8">
                                                        <form class="dropzone" id="discussionLogAttachments{{$discussion->id}}"> @csrf
                                                            <div id="discussionResponse{{$discussion->id}}">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8 p-0 text-right">
                                                        <button data-discussion-id="{{$discussion->id}}" type="button" class="btn btn-primary btn-sm mt-1 mb-0 p-1 send-button">
                                                            Send <i id="btn-loader{{$discussion->id}}" style="display: none;" class="fa fa-circle-o-notch fa-spin"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            @if(auth()->user()->role_id!=7)
                <!-- left side mailbox-->
                    <div class="col-lg-3 col-md-3" id="email-component">
                        <div class="row pl-0 pt-5 border-bottom mr-2">
                            <div class="col-lg-1 col-md-1 pl-0">
                                <div class="form-group pl-0 m-0">
                                    <label class="custom-switch">
                                        <input onchange="$.showEmail()" id="show-email" type="checkbox" name="custom-switch-checkbox1" class="custom-switch-input" autocomplete="off">
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 form-label">
                                Send E-mail <x-infobox info="-You can send the content of the comment by e-mail.<br>
                                -The attachment you add will be sent as e-mail attachment and saved in the comments.<br>
                                -Even if you mark the comment as private from here, the content of the comment will be sent to the e-mail addresses entered." />
                            </div>
                        </div>

                        <div class="row" id="email-box" style="display: none;">
                            <div class="card card-body mr-2 ">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444;"
                                               class="col-md-1 form-label my-auto p-0"><small>To:</small>
                                        </label>
                                        <div class="col-md-12 p-0">
                                            <span id="badge_to" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_to" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444;"
                                               class="col-md-1 form-label my-auto p-0"><small>Cc:</small>
                                        </label>
                                        <div class="col-md-12 p-0">
                                            <span id="badge_cc" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_cc" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444; "
                                               class="col-md-1 form-label my-auto p-0"><small>Bcc:</small>
                                        </label>
                                        <div class="col-md-12 p-0">
                                            <span id="badge_bcc" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_bcc" />
                                        </div>
                                    </div>
                                    @if(auth()->user()->role_id != 7)
                                    @if(count($ticket["discussion"])>0)
                                    <div class="form-check pl-2 m-0">
                                        <input class="form-check-input" id="email-last-receivers" type="checkbox" autocomplete="off">
                                        <label class="form-check-label" for="email-last-receivers">
                                            <small>Last Receivers
                                                <x-infobox info="Send comment to last recipients." />
                                            </small>
                                        </label>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="form-check pl-2 m-0">
                                        <input class="form-check-input" name="assigned_user" type="checkbox" value="1" id="assign_user" onchange="$.assign_comment_user()">
                                        <label class="form-check-label" for="assign_user">
                                            <small>Send this update to assigned user.
                                                <x-infobox info="Detailed email for personnel!" />
                                            </small>
                                        </label>
                                    </div>
                                    <div class="form-check pl-2 m-0">
                                        <input class="form-check-input" name="holder_user" type="checkbox" value="1" id="holder_user" onchange="$.holder_user()">
                                        <label class="form-check-label" for="holder_user">
                                            <small>Send this update to ticket holder.
                                                <x-infobox info="Non-detailed email for customer!" />
                                            </small>
                                        </label>
                                    </div>
                                    <div class="form-check pl-2 m-0">
                                        <input class="form-check-input" name="set_private" type="checkbox" onchange="$.set_private()" id="set_private">
                                        <label class="form-check-label" for="set_private">
                                            <small>Set private comment.
                                                <x-infobox info="If checked,comment will be set to private!" />
                                            </small>
                                        </label>
                                    </div>
                                    <input type="hidden" id="holder_or_personnel" value="">

                                    <div class="row pt-3">
                                        <div class="col-lg-4-col-md-4 p-0 m-0">
                                            <div class="form-group pl-0 m-0">
                                                <label class="custom-switch">

                                                    <input
                                                        onchange="$.showDropzone()"
                                                        id="show-dropzone"
                                                        type="checkbox" name="custom-switch-checkbox1"
                                                        class="custom-switch-input" autocomplete="off">

                                                    <span class="custom-switch-indicator"></span>
                                                </label>

                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8">
                                            <small>Attach File</small>
                                        </div>
                                    </div>

                                    <div class="row " id="dropzone-area"
                                         style="display: none;">
                                        <div class="col-lg-12 col-md-8 p-0">
                                            <div class="form-label" style="padding-top: 5px;font-size:.7rem;" id="addAttachmentText">
                                                <span style="color:red;">(max. 5 Files | max. File size 10 MB)</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-8 p-0">

                                            <form class="dropzone"
                                                  id="discussionLogAttachments"> @csrf
                                                <div id="discussionResponse">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-8 p-0 text-right">
                                            <button
                                                type="button"
                                                class="btn btn-primary btn-sm mt-1 mb-0 p-1 fs-12" id="sendmail-button">
                                                Save & Send Email <i id="btn-loader"
                                                                     style="display: none;"
                                                                     class="fa fa-circle-o-notch fa-spin"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Effort Log Modal --}}
    <div class="modal" id="effortLogModal" tabindex="-1" role="dialog" aria-labelledby="effortLogModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="effortLogModalLabel">Update Effort</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-4 col-lg-4">
                            Type of Effort
                        </div>
                        <div class="col-md-4 col-lg-4">
                            Hours
                        </div>
                        <div class="col-md-4 col-lg-4">
                            Minutes
                        </div>
                    </div>

                    <div class="row effortRow">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group">
                                <select id="effortType" name="effort_type" class="form-control form-control-sm">
                                    @foreach($data['effortTypes'] as $effort_type)
                                    <option value="{{$effort_type->id}}">{{$effort_type->type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <input id="effortHour" class="form-control form-control-sm only-positive-int" type="number" name="hour">
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <select id="effortMint"  class="form-control form-control-sm only-positive-int " type="number" name="mint">
                                <option>0</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-1 mb-1">
                    <button type="button" id="update-effort-modal-button" class="btn btn-sm btn-success">Update Effort</button>
                    <button type="button" data-dismiss="modal" class="btn btn-sm btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="effort-discount-modal" tabindex="-1" role="dialog" aria-labelledby="effort-discount-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="effort-discount-modal-label">Apply Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-4 col-lg-4">Type of Effort</div>
                        <div class="col-md-8 col-lg-8">Total Effort</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group">
                                <input type="text" id="discount-modal-selected-effort-type" class="form-control form-control-sm" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" id="discount-modal-total-efforts" class="form-control form-control-sm only-positive-int" readonly="readonly">
                            <input type="hidden" id="discount-modal-total-efforts-minutes">
                            <input type="hidden" id="discount-modal-effort-id">
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-4 col-lg-4">Calculated Discount</div>
                        <div class="col-md-4 col-lg-4">Final Hour(s)</div>
                        <div class="col-md-4 col-lg-4">Final Minute(s)</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <input type="text" id="discount-modal-calculated-discount" class="form-control form-control-sm" readonly="readonly">
                        </div>
                        <div class="col-lg-4 col-md-4 position-relative">
                            <input type="number" id="discount-modal-final-hours" class="form-control form-control-sm">
                            <span id="discount-modal-final-hours-label">Hour(s)</span>
                        </div>
                        <div class="col-lg-4 col-md-4 position-relative">
                            <input type="number" id="discount-modal-final-minutes" class="form-control form-control-sm">
                            <span id="discount-modal-final-minutes-label">Minute(s)</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-1 mb-1">
                    <button type="button" id="effort-discount-modal-button" class="btn btn-sm btn-success">Edit Discount</button>
                    <button type="button" data-dismiss="modal" class="btn btn-sm btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>

        <div class="modal" id="transportModal" tabindex="-1" role="dialog" aria-labelledby="transportModal"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ ucfirst(trans('words.transport')) }} {{ ucfirst(trans('words.price')) }} (<span id="transportTypeText"></span> )</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">0-25 KM</th>
                                <th class="text-center">25-60 KM</th>
                                <th class="text-center">60-100 KM</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center"><input class="form-control text-center" type="text" value="" disabled id="transportPrice1"> </td>
                                <td class="text-center"><input class="form-control text-center" type="text" value="" disabled id="transportPrice2"> </td>
                                <td class="text-center"><input class="form-control text-center" type="text" value="" disabled id="transportPrice3"> </td>
                            </tr>
                            <tr>
                                <td class="text-center"><button onclick="appendTransportPrice('transportPrice1')" class="btn btn-sm btn-success">Preis hinzufügen</button></td>
                                <td class="text-center"><button onclick="appendTransportPrice('transportPrice2')" class="btn btn-sm btn-success">Preis hinzufügen</button></td>
                                <td class="text-center"><button onclick="appendTransportPrice('transportPrice3')" class="btn btn-sm btn-success">Preis hinzufügen</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal" id="calendar-tasks-modal" tabindex="-1" role="dialog" aria-labelledby="calendar-tasks-modal-label" aria-hidden="true">
        <div class="modal-dialog @if(count($calendar) > 0) modal-xl @else modal-sm @endif" role="document" @if(count($calendar) > 0) style="min-width: 1140px !important;" @endif>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendar-tasks-modal-label">Calendar Tasks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(count($calendar) > 0)
                    <table class="table w-100 calendar-tasks-table table-bordered" id="calendar-tasks-table">
                        <thead>
                            <tr class="default-cursor">
                                <th scope="col" class="text-center">User</th>
                                <th scope="col" class="text-center">Date & Time</th>
                                <th scope="col" class="text-center">Message</th>
                                <th scope="col" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendar as $cal)
                            @php $user = App\User::query()->find($cal->user_id); @endphp
                            @if(in_array(auth()->user()->role_id, [1, 2]))
                            <tr class="calendar-row" id="calendar-{{ $cal->id }}" data-id="{{ $cal->user_id }}">
                            @else
                            <tr>
                            @endif
                                <td class="text-center">{{ ($user->first_name ?? "") . " " . ($user->surname ?? "") }}</td>
                                <td class="text-center d-flex flex-column"><span>{{ \Carbon\Carbon::parse($cal->start)->format("d.m.Y") }}</span><span>{{ \Carbon\Carbon::parse($cal->start)->format("H:i") }}&nbsp;-&nbsp;{{ \Carbon\Carbon::parse($cal->end)->format("H:i") }}</span></td>
                                <td class="text-center">{{ $cal->message ?? "-" }}</td>
                                <td class="text-center">{{ $calendar_statuses[$cal->status - 1]->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="font-weight-semibold text-danger">There is no calendar task for this ticket!</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

        @include('tickets.ticket-calendar')
@endsection
@push('scripts')
<script src="{{ asset('assets/js/update-ticket.js') }}"></script>
@endpush
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{ asset('drop-zone/dropzone.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{ asset('assets/js/select2.js')}}"></script>
    <script src="{{ asset('text-editor/trumbowyg.min.js')}}"></script>
    <script src="{{ asset('text-editor/trumbowyg.allowtagsfrompaste.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.inputmask.min.js')}}"></script>
    <script src="{{ asset("assets/js/numberFormat.js")}}"></script>

    {{-- Script for discussion update send mail tagify --}}
    <script src="{{asset('assets/plugins/tagify/tagify.min.js')}}"></script>
    <script src="{{asset('assets/plugins/tagify/tagify.polyfills.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function postProof(){
            $("form :input").not("#attachmentToggle").not("#ticket-status").not("#ticket-export-submit").not("input[name='_token'],#ticket-id,textarea[name='name']").prop("disabled",true);
            $("textarea[name='name']").prop("readonly",true);
            $("#description").trumbowyg("disable");
            $(".trumbowyg-editor").css("background","#eaeaea")
            $(".dropzone").hide();
            $(".proofBtnCont").hide();
            $("#email-component").hide();
            $("#discussionSendButtonRow").hide();
            $("#discussionForm").hide();
            $("#discussionAttachmentSwitch").hide();
            $("#addAttachmentText").hide();
            $(".effortDeleteButton,.effortUpdateButton").remove();
            $(".apply-discount-button").remove();
            $(".deleteAttachment").remove();
            $("*[id^='comment-effort-form-']").remove();
            $(".comment-effort-update-btn,.comment-effort-delete-btn").remove();
            $(".important-dec-btn").remove();
            $("#add-external-partner").remove();
            $("#important-toggle").parent().remove();
            $(".trumbowyg-fullscreen-button").removeAttr("disabled");
        }

        if({{ $ticket->is_private }}) {
            $(".first-card").css("border", "2px solid #705ec8");
            $(".first-card .card-header").addClass("private-ticket");
            $(".first-card .card-header .card-title").css("margin-left", "54px");
        }

        $(".retrieve-comment-height").each(function() {
            if($(this).height() > 226) {
                $(this).addClass("custom-discussion-section");
                let more_section = `<div class="d-flex flex-column"><span class="font-weight-bold">...</span><a class="comment-read-more-text">Read More</a></div>`;
                $(this).after(more_section);
            }
        });


        $(document).ready(function() {
            $(".comment-read-more-text").on("click", function() {
                if($(this).parent().siblings(".retrieve-comment-height").hasClass("custom-discussion-section")) {
                    $(this).siblings("span").text("");
                    $(this).parent().siblings(".retrieve-comment-height").removeClass("custom-discussion-section");
                    $(this).text("Read Less");
                }
                else {
                    $(this).siblings("span").text("...");
                    $(this).parent().siblings(".retrieve-comment-height").addClass("custom-discussion-section");
                    $(this).text("Read More");
                }
            });

            $("#open-calendar-tasks-button").on("click", function() {
                $("#calendar-tasks-modal").modal("show");
            });

            $(".ticket-privacy-button").on("click", function() {
                toggleLoader(true);
                $.ajax({
                    url: "/tickets/privacy/update",
                    type: "POST",
                    data: {
                        status: $(".ticket-privacy-button").data("ticket-privacy"),
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        ticket_id: $("#ticket_id").val()
                    },
                    success: function(response) {
                        if(response.status === "Success") {
                            location.reload();
                            toastr.success(response.message, "Success");
                        }
                        else {
                            toggleLoader(false);
                            toastr.error("Something went wrong while trying to change ticket's privacy status!", "Error");
                        }
                    }
                });
            });

            $("#effort-discount-modal-button").on("click", function() {
               toggleLoader(true);
               $.ajax({
                   url: "/ticket/discount",
                   type: "POST",
                   data: {
                       _token: $('meta[name="csrf-token"]').attr("content"),
                       final_hour: $("#discount-modal-final-hours").val(),
                       final_minutes: $("#discount-modal-final-minutes").val(),
                       effort_total_id: $("#discount-modal-effort-id").val()
                   },
                   success: function(response) {
                       if(response.status === "Success") {
                           location.reload();
                           toastr.success(response.message, "Success");
                       }
                       else {
                           toggleLoader(false);
                           toastr.error(response.message, "Error");
                       }
                   }
               });
            });

            $("#ticket-category").on("input", function() {
                let id = $(this).val();
                let row = `
                <div id="ticket-sub-category-row">
                    <div class="form-group row border-bottom mr-4">
                        <label for="ticket-sub-category" class="col-md-3 mr-5 form-label my-auto">IT Category</label>
                        <div class="col-md-8">
                            <select name="sub_category" class="form-control" id="ticket-sub-category" @if(in_array(auth()->user()->role_id, [4, 7])) disabled="disabled" @endif>
                                @foreach($data["sub_category"] as $category)
                                <option value="{{ $category->id }}" @if($ticket->sub_category_id === $category->id) selected="selected" @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>`;

                if(id === "14") {
                    $("#hidden-category-row").append(row);
                }
                else {
                    $("#ticket-sub-category-row").remove();
                }
            });

            $('#discussion').trumbowyg({
                autogrow: true,
                removeformatPasted: true,
                defaultLinkTarget: '_blank'
            });

            $('#description').trumbowyg({
                autogrow: true,
                removeformatPasted: true,
                defaultLinkTarget: '_blank'
            });

            @if($ticket->status_id == 7 || $ticket->status_id == 9 || ($ticket->status_id == 6 && $ticket->proofed == 1) || $ticket->status_id === 11)
            @if(in_array($ticket->status_id, [6, 7, 11]))
            $("form :input").not("#attachmentToggle").not("#ticket-status").not(".remove-reference-button").not("#add-reference-ticket-button").not("#ticket-export-submit").not("input[name='_token'],#ticket-id,textarea[name='name']").prop("disabled",true);
            $("textarea[name='name']").prop("readonly",true);
            @else
            $("form :input").not("#attachmentToggle").not(".remove-reference-button").not("#add-reference-ticket-button").prop("disabled",true);
            $("#ticket-export-submit").prop("disabled",false);
            $("#report-summery-token").prop("disabled",false);
            $("#buttonRow").hide();
            $("#top-button-row").html(`<button class='btn btn-outline-primary mr-4' id='ticket-export'>Export Ticket</button><button type="button" class="btn btn-primary mb-0 mr-4" onclick="copyTicket({{ $ticket->id }})">Copy Ticket</button><a href='{{url('/tickets')}}' class='btn btn-info'><i class='fa fa-backward mr-1'></i> Back </a>`);
            @endif
            $("#description").trumbowyg("disable");
            $(".trumbowyg-editor").css("background","#eaeaea")
            $(".dropzone").hide();
            $(".proofBtnCont").hide();
            $("#email-component").hide();
            $("#discussionSendButtonRow").hide();
            $("#discussionForm").hide();
            $("#discussionAttachmentSwitch").hide();
            $("#addAttachmentText").hide();
            $(".locked-ticket-comment-section").remove();
            $("*[id^='hidden-button-area']").remove();
            $(".delete-comment-button").remove();
            $(".effortDeleteButton,.effortUpdateButton").remove();
            $(".apply-discount-button").remove();
            $(".deleteAttachment").remove();
            $("*[id^='comment-effort-form-']").remove();
            $(".comment-effort-update-btn,.comment-effort-delete-btn").remove();
            $(".important-dec-btn").remove();
            $("#add-external-partner").remove();
            $("#important-toggle").parent().remove();
            @endif


            function initTagifies(classname){


                let inputElements = document.getElementsByClassName(classname)

                let pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                let maxTags = 5;
                for(let i = 0; i < inputElements.length; i++) {
                    let tagifyDiv = $(inputElements[i]);
                    let inputElm = tagifyDiv.find("input")[0];

                    let tagify = new Tagify(inputElm, {
                        whitelist: [],
                        pattern: pattern,
                        maxTags: maxTags,
                        dropdown : {
                            enabled       : 5,              // show the dropdown immediately on focus
                            maxItems      : 8,
                            closeOnSelect : true,          // keep the dropdown open after selecting a suggestion
                            highlightFirst: true,

                        },
                        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(";")
                    });

                    tagify.on("input",onInput);


                    function onInput(e){
                        let controller;
                        let value = e.detail.value;
                        tagify.whiteList = null;
                        controller && controller.abort()
                        controller = new AbortController()

                        tagify.loading(true).dropdown.hide();

                        fetch('/tagAndSearch/getOptions',{signal:controller.signal}).
                        then(RES => RES.json()).
                        then(function (newWhitelist){
                            tagify.whitelist = newWhitelist;
                            tagify.loading(false).dropdown.show(value);
                        });

                    }

                }
            }

            initTagifies("email_to");
            initTagifies("email_cc");
            initTagifies("email_bcc");
        });
    </script>
    <script>
        function collapseEffortLog() {
            if($('.switch-history').hasClass('hide-effort-log-history')) {
                $(".effortLogTables").removeAttr("style");
                $('.switch-history').removeClass('hide-effort-log-history');
                $('.history-switcher').text('Show Less');
            }
            else {
                $('.switch-history').addClass('hide-effort-log-history');
                $('.history-switcher').text('Show More');
            }
        }

        // Proof Scripts
        function proofModalShow(e){
            let ticket_id = $(e.target).data("id");
            confirmModal('This ticket will be proofed. This action cannot be undone.',"Are you sure?","Proof the Ticket","Cancel").then(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/ticket/proof",
                    type: "post",
                    data: {
                        id: ticket_id
                    },
                    dataType: "json",
                    success: function (response) {
                        toggleLoader(false);
                        toastr.success("Ticket is proofed.","Success");
                        $(".proofBtnCont").empty();
                        $(".proofBtnCont").html(`
                        <i data-id="{{$ticket->id}}" class="btn btn-primary p-2 px-3 fa fa-lock"
                            aria-hidden="true" style="font-size:1.2rem;"></i>`)
                        $("#proofModal").modal("hide");

                        let proofLog = $("#proof-log");
                        let proofLogHtml = `
                        <li class="mb-1">
                            <div class="d-flex">
                                <span class="time-data">Proofed </span>
                                <span class="ml-auto text-muted fs-11">${response.proofed_at}</span>
                            </div>
                            <p class="text-muted fs-13 mb-1">Proofed from
                                <span class="text-info">${response.proofed_by}</span>
                            </p>
                        </li>`;
                        proofLog.removeClass("d-none");
                        proofLog.html(proofLogHtml);

                        postProof();


                    },
                    error: function (err){
                        toggleLoader(false);
                        toastr.error("Something went wrong","error");
                        $("#proofModal").modal("hide");
                    }
                });
            });
        }
        // Proof Scripts End

        tippy(".comment-effort-tooltip",
            {
                content: "This effort belong to comment. "
            });

        function time_print(value) {
            return value.toString().padStart(2, "0");
        }

        @if(count($ticket["attachment"]) == 0)
        $('#attachments').css("display","none");
        @endif

        $('#attachmentToggle').on('change', function () {
            var isAttachment = $("#attachmentToggle").is(":checked");
            if (isAttachment) {
                $('#attachments').show();
                $('#downloadAllField').css('display', 'block');
            } else{
                $('#attachments').hide();
                $('#downloadAllField').css('display', 'none');
            }
        });

        // Discussion and Effort Toggles
        $("#discussionAttachmentToggle").prop("checked", false);

        $('#discussionAttachmentToggle').on("change", function () {
            var isAttachment = $(this).is(":checked");
            if (isAttachment) {
                $('#discussionAttacmentRow').show(100);
            } else {
                $('#discussionAttacmentRow').hide(100);
                Dropzone.forElement('#discussionAttachment').removeAllFiles(true)
                ("#attachmentTotalSize").val(0)
                $("#discussionAttachmentResponse").html("");

            }
        });

        $('#commentEffortToggle').on("change", function () {
            var isAttachment = $(this).is(":checked");
            if (isAttachment) {
                $('#commentEffortRow').show(100);
            } else {
                $('#commentEffortRow').hide(100);
            }
        });

        // Sayfa yenilendiğinde comment effortun içini temizliyoruz ve toggle ı kapatıyoruz (firefox ile alakalı bir kısım)
        addEffortComment(true);
        $('#commentEffortToggle').prop("checked", false)

        function validateEffortComments(){
            let isValid = true;

            if($("#commentEffortToggle").prop("checked") == true){


                let type = $(".effortTypesComment")
                let hour = $(".effortHourComment")
                let min = $(".effortMinuteComment")

                type.each(function(){
                    if($(this).val() == "0" || $(this).val() == "" || $(this).val() == null){
                        $(this).addClass("border-danger");
                        isValid = false;
                    }
                })
                if((hour.last().val() == "" && min.last().val() == "") || (hour.last().val() == 0 && min.last().val() == 0)){
                    hour.last().addClass("border-danger")
                    min.last().addClass("border-danger")
                    isValid = false;
                }

                if(isValid == false){
                    $(".effortTypesComment, .effortHourComment, .effortMinuteComment").on("change keyup", function(){
                        let val = $(this).val()
                        if(val == "0" || val == "" || val == null){
                            $(this).addClass("border-danger")
                        }else{
                            $(this).removeClass("border-danger")
                        }
                    })
                }
            }

            return isValid;
        }

        function addEffortComment(clear = false) {

            if(clear == false){
                let isValid = validateEffortComments()
                if(isValid == false){
                    return;
                }
            }

            let effortTypes = "";
            if(clear){
                @foreach($data['effortTypes'] as $effort_type)
                    effortTypes += `<option @if($effort_type->id == $data['userEffortType']) selected  @else  @endif
                                    value="{{$effort_type->id}}">
                                        {{$effort_type->type}}
                                    </option>`;
                @endforeach
            }else{
                @foreach($data['effortTypes'] as $effort_type)
                    effortTypes += `<option value="{{$effort_type->id}}">
                                    {{$effort_type->type}}
                                    </option>`;
                @endforeach
            }




            let effortBtn = ""
            if(clear) {
                effortBtn = `
                    <button type="button" id="addEffortButton"
                        class="btn btn-sm btn-success"
                        data-effort-id="1" onclick="addEffortComment()">
                        <i class="fa fa-plus"></i>
                    </button>`;
            }
            else {
                effortBtn = `
                    <button type="button" class="btn btn-sm btn-danger removeEffortButton">
                        <i class="fa fa-minus"></i>
                    </button>`;
            }

            let html = `
                <div class="row appendedEffort effortRow" >
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <select name="commentEfforts[effort_types][]"
                                class="form-control form-control-sm effortTypesComment">
                                <option value="" selected></option>
                                ${effortTypes}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <input min="0" class="form-control form-control-sm effortHourComment only-positive-int" type="number"
                            name="commentEfforts[hours][]">
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <select  class="form-control form-control-sm effortMinuteComment " type="number"
                            name="commentEfforts[mints][]">
                        <option>0</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2 pr-0">
                        ${effortBtn}
                    </div>
                </div>`;
            if (clear) {
                $('#effortAreaComment').html(html);
            } else {
                $('#effortAreaComment').append(html);
            }
        }

        $(document).on("click", ".removeEffortButton", function (e) {
            $(this).closest(".appendedEffort").remove();
        });

        // for changing hidden isPrivate value onChange of switchinput outside of the form
        const changePrivateValue = (id) => {
            value = $(`#isPrivate-${id}`).val();
            value = value == "off" ? "on" : "off"
            $(`#isPrivate-${id}`).val(value);

            let totalSize=parseInt($("#attachmentTotalSize").val());
            let newTotalSize =0;
            if(value == "on"){
                newTotalSize = totalSize - $(`#attachmentLink-${id}`).data("size")
                $("#attachmentTotalSize").val(newTotalSize);
            }else{
                newTotalSize = totalSize + $(`#attachmentLink-${id}`).data("size")
                $("#attachmentTotalSize").val(newTotalSize);
            }
        }

        Dropzone.autoDiscover = false;
        $('#ticketAttachments').dropzone({
            maxFiles: 5,
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 500,
            timeout: 180000000,
            acceptedFiles: "{{\App\Helpers\Helper::accepted_files()}}",
            url: '/attachFiles',
            success: function (file, response) {

                if (response.error) {
                    toastr.error(response.error, 'Error');
                    $('#buttonRow').show();
                } else {

                    $.each(response.data, function (key, data) {
                        $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                        $(file.previewTemplate).append(`<div class="custom-control custom-switch w-50 ml-5 pl-5" style="{{auth()->user()->role_id==7 ? 'display:none;':''}}">
                                <input id="privateSwitch-${data.size}" onChange="changePrivateValue('${data.size}')" type="checkbox"
                                    class="custom-control-input"  ">
                                <label class="custom-control-label text-primary" for="privateSwitch-${data.size}"><b>PRIVATE</b></label>
                            </div>`);
                        // hidden inputs for attachment values
                        $('#attachmentResponse').append(
                            `<input type="hidden" name="ticketAttachments[${data.size}][link]" id="attachmentLink-${data.size}" value="${data.link}"/>
                            <input type="hidden" name="ticketAttachments[${data.size}][isPrivate]" id="isPrivate-${data.size}" value="off">`
                        );

                    });
                    toastr.success(response.success, 'Success');
                    $('#buttonRow').show()
                }
            },
            init: function () {

                this.on("removedfile", function (file) {
                    $("#attachmentLink-" + file.size).remove();
                    $("#isPrivate-" + file.size).remove();
                });
                this.on("maxfilesexceeded", function (file) {
                    if (this.files.length >= 5) {
                        toastr.error("Maximum 5 files can be uploaded!");
                    }
                    this.removeFile(this.files[5]);

                });
                this.on("sending", function () {
                    $('#buttonRow').hide();
                });

            }
        });

        Dropzone.autoDiscover = false;
        $('#discussionAttachment').dropzone({
            maxFiles: 5,
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 100,
            timeout: 180000000,
            acceptedFiles: "{{\App\Helpers\Helper::accepted_files()}}",
            url: '/attachFiles',
            success: function (file, response) {

                if (response.error) {
                    toastr.error(response.error, 'Error');
                    $('#discussionSendButtonRow').show();
                } else {

                    $.each(response.data, function (key, data) {
                        $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                        $(file.previewTemplate).append(`<div class="custom-control custom-switch w-50 ml-5 pl-5" style="{{auth()->user()->role_id==7 ? 'display:none;':''}}">
                                <input id="privateSwitch-${data.size}" onChange="changePrivateValue('${data.size}')" type="checkbox"
                                    class="custom-control-input">
                                <label class="custom-control-label text-primary" for="privateSwitch-${data.size}"><b>PRIVATE</b></label>
                            </div>`);
                        // hidden inputs for attachment values
                        $('#discussionAttachmentResponse').append(
                            `<input type="hidden" name="discussionAttachments[${data.size}][link]"   id="attachmentLink-${data.size}" value="${data.link}"/>
                            <input type="hidden" name="discussionAttachments[${data.size}][isPrivate]" id="isPrivate-${data.size}" value="off">`
                        );

                    });
                    toastr.success(response.success, 'Success');
                    $('#discussionSendButtonRow').show();
                }
            },
            init: function () {

                this.on("removedfile", function (file) {
                    $("#attachmentLink-" + file.size).remove();
                    $("#isPrivate-" + file.size).remove();
                });
                this.on("maxfilesexceeded", function (file) {
                    if (this.files.length >= 3) {
                        toastr.error("Maximum file must be 3!");
                    }
                    this.removeFile(this.files[3]);

                });
                this.on("sending", function () {
                    $('#discussionSendButtonRow').hide();
                });

            }
        });


        var discussionIDs = [];
        var dropzones = [];
        var dropzones_comment_attach = [];

        for (let i = 0; i < $('[id^="history"]').length; i++) {
            discussionIDs.push($('[id^="history"]')[i]["id"].replace("history", "")); // get discussion ids for create different dropzones.
        }

        for (let i = 0; i < discussionIDs.length; i++) {
            Dropzone.autoDiscover = false;
            dropzones[discussionIDs[i]] = $('#discussionLogAttachments' + discussionIDs[i]).dropzone({
                maxFiles: 5,
                parallelUploads: 1,
                uploadMultiple: true,
                addRemoveLinks: true,
                maxFilesize: 100,
                timeout: 180000000,
                acceptedFiles: "{{\App\Helpers\Helper::accepted_files()}}",
                createImageThumbnails: false,

                url: '/attachFiles',
                success: function (file, response) {

                    $(file.previewTemplate).children(".dz-image").remove();
                    $(file.previewTemplate).children(".dz-details").remove();
                    $(file.previewTemplate).children(".dz-progress").remove();
                    $(file.previewTemplate).children(".dz-error-message").remove();
                    $(file.previewTemplate).children(".dz-success-mark").remove();
                    $(file.previewTemplate).children(".dz-error-mark").remove();

                    //$(file.previewTemplate).html("");
                    $(file.previewTemplate).css("width", "100%").css("min-height", "20px").css("margin", "0").css("text-align", "center");
                    $(file.previewTemplate).children(".dz-remove").css("width", "75px").css("display", "inline-block");
                    if (response.error) {
                        toastr.error(response.error, 'Error');
                        $(file.previewTemplate).remove();
                        $(".send-button[data-discussion-id='"+discussionIDs[i]+"']").show();
                    } else {
                        $.each(response.data, function (key, data) {
                            $(file.previewTemplate).prepend('<span style="display: none;" class="server_file">' + data.link + '</span>');
                            $(file.previewTemplate).prepend("<span class='filename' style='font-size: 0.7rem;text-align: center'>" + data.link + "</span>");

                            $('#discussionResponse' + discussionIDs[i]).append('<input type="hidden" name="discussionLogAttachments[' + data.size + ']" value="' + data.link + '"/>');
                        });
                        toastr.success(response.success, 'Success');
                        $(".send-button[data-discussion-id='"+discussionIDs[i]+"']").show();
                    }
                },
                init: function () {
                    let totalSize=parseInt($("#attachmentTotalSize").val());
                    this.on("sendingmultiple", function(file, xhr, formData){
                        totalSize += file[0].size;
                    });
                    this.on("removedfile", function (file) {
                        totalSize = $("#attachmentTotalSize").val();

                        totalSize= totalSize - file.size;

                        $("#attachmentTotalSize").val(totalSize);
                        var server_file = $(file.previewTemplate).children('.server_file').text();
                        $("#discussionResponse" + discussionIDs[i] + " input[value='" + server_file + "']").remove();
                    });
                    this.on("complete", function(){
                        $("#attachmentTotalSize").val(totalSize)
                    });
                    this.on("maxfilesexceeded", function (file) {
                        if (this.files.length >= 5) {
                            toastr.error("Maximum 5 files can be uploaded!");
                        }
                        this.removeFile(this.files[5]);

                    });
                    this.on("sending",function (){

                        $(".send-button[data-discussion-id='"+discussionIDs[i]+"']").hide();
                    });
                }
            });
            ///Add attachment to comment dropzones
            dropzones_comment_attach[discussionIDs[i]] = $('#add-attach-comment-form-' + discussionIDs[i]).dropzone({
                maxFiles: 5,
                parallelUploads: 1,
                uploadMultiple: true,
                addRemoveLinks: true,
                maxFilesize: 100,
                timeout: 180000000,
                acceptedFiles: "{{\App\Helpers\Helper::accepted_files()}}",
                createImageThumbnails: false,

                url: '/attachFiles',
                success: function (file, response) {
                    if (response.error) {
                        toastr.error(response.error, 'Error');
                        $(file.previewTemplate).remove();
                        $(".add-attach-comment-button[data-discussion-id='"+discussionIDs[i]+"']").show();
                    } else {
                        $.each(response.data, function (key, data) {
                            $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                            $(file.previewTemplate).append(`<div class="custom-control custom-switch w-50 ml-5 pl-5" style="{{auth()->user()->role_id==7 ? 'display:none;':''}}">
                                <input id="privateSwitch-${data.size}" onChange="changePrivateValue('${data.size}')" type="checkbox"
                                    class="custom-control-input">
                                <label class="custom-control-label text-primary" for="privateSwitch-${data.size}"><b>PRIVATE</b></label>
                            </div>`);

                            // hidden inputs for attachment values
                            $('#add-attach-comment-response-'+ discussionIDs[i]).append(
                                `<input type="hidden" name="add_attach_comment[${data.size}][link]" id="attachmentLink-${data.size}" value="${data.link}"/>
                            <input type="hidden" name="add_attach_comment[${data.size}][isPrivate]" id="isPrivate-${data.size}" value="off">`
                            );
                        });
                        toastr.success(response.success, 'Success');
                        $(".add-attach-comment-button[data-discussion-id='"+discussionIDs[i]+"']").show();
                    }
                },
                init: function () {
                    this.on("removedfile", function (file) {
                        $("#attachmentLink-" + file.size).remove();
                        $("#isPrivate-" + file.size).remove();
                    });
                    this.on("maxfilesexceeded", function (file) {
                        if (this.files.length >= 3) {
                            toastr.error("Maximum file must be 3!");
                        }
                        this.removeFile(this.files[3]);

                    });
                    this.on("sending",function (){
                        $(".add-attach-comment-button[data-discussion-id='"+discussionIDs[i]+"']").hide();
                    });
                }
            });

        }


        Dropzone.autoDiscover = false;
        $('#discussionLogAttachments').dropzone({
            maxFiles: 5,
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 10,
            timeout: 180000000,
            acceptedFiles: "{{\App\Helpers\Helper::accepted_files()}}",
            createImageThumbnails: false,

            url: '/attachFiles',
            success: function (file, response) {

                $(file.previewTemplate).children(".dz-image").remove();
                $(file.previewTemplate).children(".dz-details").remove();
                $(file.previewTemplate).children(".dz-progress").remove();
                $(file.previewTemplate).children(".dz-error-message").remove();
                $(file.previewTemplate).children(".dz-success-mark").remove();
                $(file.previewTemplate).children(".dz-error-mark").remove();

                //$(file.previewTemplate).html("");
                $(file.previewTemplate).css("width", "100%").css("min-height", "20px").css("margin", "0").css("text-align", "center");
                $(file.previewTemplate).children(".dz-remove").css("width", "75px").css("display", "inline-block");
                if (response.error) {
                    toastr.error(response.error, 'Error');
                    $(file.previewTemplate).remove();
                    $("#sendmail-button").show();
                } else {
                    $.each(response.data, function (key, data) {
                        $(file.previewTemplate).prepend('<span style="display: none;" class="server_file">' + data.link + '</span>');
                        $(file.previewTemplate).prepend("<span class='filename' style='font-size: 0.7rem;text-align: center'>" + data.link + "</span>");

                        $('#discussionResponse').append('<input type="hidden" data-size="${data.size}" name="discussionLogAttachments[' + data.size + ']" value="' + data.link + '"/>');
                    });
                    toastr.success(response.success, 'Success');
                    $("#sendmail-button").show();
                }
            },
            init: function () {
                let totalSize=parseInt($("#attachmentTotalSize").val());
                this.on("sendingmultiple", function(file, xhr, formData){
                    totalSize += file[0].size;
                });
                this.on("removedfile", function (file) {
                    totalSize= $("#attachmentTotalSize").val();
                    totalSize= totalSize - file.size;
                    $("#attachmentTotalSize").val(totalSize);
                    var server_file = $(file.previewTemplate).children('.server_file').text();
                    $("#discussionResponse" + " input[value='" + server_file + "']").remove();
                });
                this.on("complete", function(){
                    $("#attachmentTotalSize").val(totalSize)
                });
                this.on("maxfilesexceeded", function (file) {
                    if (this.files.length >= 3) {
                        toastr.error("Maximum file must be 3!");
                    }
                    this.removeFile(this.files[3]);

                });
                this.on("sending",function (){
                    $("#sendmail-button").hide();
                });
            }
        });

        function resetAssignedUser() {
            let master_user = $('#personnel').val();
            let assigned_user = $('.assigned_personnel').val();
            if (assigned_user.includes(master_user)) {
                $('.assigned_personnel option[value="' + master_user + '"]').remove();
            }
        }

        function resetCommentAssignedUser() {
            let master_user = $('.personnel-comment').val();
            let assigned_user = $('.comment-assigned-personnel').val();
            if (assigned_user.includes(master_user)) {
                $('.comment-assigned-personnel option[value="' + master_user + '"]').remove();
            }
        }

        function assigned_personnel_url(params) {
            if($(this).hasClass("comment-assigned-personnel"))
            return '/getPersonnelRawData?except=' + $(".personnel-comment").val();
            else
            return '/getPersonnelRawData?except=' + $("#personnel").val();
        }
        function commentAssignedPersonnelUrl(params) {
            return '/getPersonnelRawData?except=' + $(".personnel-comment").val();
        }

        function calculateDiscountPercentage() {
            let hours = $("#discount-modal-final-hours").val();
            let minutes = $("#discount-modal-final-minutes").val();

            if(hours === "") {
                hours = "0";
            }

            if(minutes === "") {
                minutes = "0";
            }

            let final_total = (parseInt(hours) * 60) + parseInt(minutes);
            let total_minutes = parseInt($("#discount-modal-total-efforts-minutes").val());
            let discount = ((total_minutes - final_total) / total_minutes) * 100;
            $("#discount-modal-calculated-discount").val(numberFormat(discount, 1, ",", ".") + "%");
        }

        $(document).ready(function () {

            $('#organization').select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
            $('#personnel').select2({
                ajax: {
                    url: '/getPersonnelRawData',
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
            $('.assigned_personnel').select2({
                maximumSelectionLength: 5,
                ajax: {
                    url:assigned_personnel_url,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
            });
            $('.comment-assigned-personnel').select2({
                maximumSelectionLength: 5,
                ajax: {
                    url:commentAssignedPersonnelUrl,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
            });
            $('.personnel-comment').select2({
                ajax: {
                    url: '/getPersonnelRawData',
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                },
                height:"resolve"
            });
            $('.personnel-comment').on('select2:select', function(e) {
                $('#last_select_ticket_assigned_user').val(e.params.data.email);
                if ($('#assign_user').is(':checked')) {
                    $('#email_to').val(e.params.data.email);
                }
            });
            $('#users').select2({
                ajax: {
                    url: '/getOrganizationUsersRawData/' + <?php echo $ticket->org_id; ?> +'?returnType=raw',
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
            $('#ticket-references').select2({
                ajax: {
                    url: '/tickets/reference/get/{{ $ticket->id }}',
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
                placeholder: 'Select an existing ticket.',
                maximumSelectionLength: 10
            });
            $('#personnel').on('change', function() {
                resetAssignedUser();
            });
            $(".personnel-comment").on("change",function(){
                resetCommentAssignedUser();
            });

            $('#organization').on('change', function () {
                var tmp = 1;
                var orgId = this.value;
                $('#users').html("");
                $.ajax({
                    url: '/getOrganizationUsersRawData/' + orgId + '?returnType=raw',
                    type: "get",
                    success: function (response) {
                        if (response.length >= 1) {
                            tmp = 0;
                            $('#users').html("<option value='" + response[0].id + "'>" + response[0].text + "</option>");
                            $('#users').val(response[0].id).trigger("change");
                        }
                    }
                });
                $.ajax({
                    url: '/getOrganizationContract/' + orgId + '?returnType=raw',
                    type: "get",
                    success: function(response) {
                        if (response.status === 1) {
                            $('#transportTypeText').html(response.contract.transportTypeText);
                            $('#transportPrice1').val(response.contract.transportPrice1);
                            $('#transportPrice2').val(response.contract.transportPrice2);
                            $('#transportPrice3').val(response.contract.transportPrice3);
                            $('#transport_price').val('').prop('readonly', true);
                            $('#transportAdd').show();
                            $('#transportReload').show();
                        } else {
                            $('#transport_price').val('{{$ticket->transport_price}}').prop('readonly', false);
                            $('#transportAdd').hide();
                            $('#transportReload').hide();
                        }
                    }
                });
                if (tmp === 1) {
                    $('#users').select2({
                        ajax: {
                            url: '/getOrganizationUsersRawData/' + orgId + '?returnType=raw',
                            processResults: function (data, page) {

                                return {
                                    results: data
                                };
                            }
                        }
                    });
                }
            });

            $(window).on("resize", function () {

                var users_parents = $("#select2-users-container").parents();
                $(users_parents[2]).css("width", $(users_parents[3]).width());
                var organization_parents = $('#select2-organization-container').parents();
                $(organization_parents[2]).css("width", $(organization_parents[3]).width());
                var personnel_parents = $('#select2-personnel-container').parents();
                $(personnel_parents[2]).css("width", $(personnel_parents[3]).width());
            });
            var set_private_discussion = 0;
            $.set_private = function () {
                let isChecked = $('#set_private').is(":checked");
                if (isChecked) {
                    set_private_discussion = 1;
                } else {
                    set_private_discussion = 0;
                }
            }
            $.showEmail = function () {
                let isChecked = $('#show-email').is(":checked");
                if (isChecked) {
                    $('#email-box').show(100);
                    $('#sendDiscussion').hide();
                    $('#sendPrivateDiscussion').hide();
                } else {
                    $('#email-box').hide(100);
                    $('#sendDiscussion').show();
                    $('#sendPrivateDiscussion').show();
                }
            }
            $.showDiscussion = function (discussion_id) {
                let element = $('#show-discussion-log' + discussion_id);
                if (element.is(":checked")) {
                    $('#discussion-area' + discussion_id).show(100);
                    $('#add-attach-comment-switch-'+discussion_id).prop("checked",false).trigger("change");
                } else {
                    $('#discussion-area' + discussion_id).hide(100);
                }
            }
            $.showDropzone = function (discussion_id) {
                if (discussion_id === undefined)
                    discussion_id = "";
                let element = $('#show-dropzone' + discussion_id);
                if (element.is(":checked")) {

                    $('#dropzone-area' + discussion_id).show(100);
                } else {
                    $('#dropzone-area' + discussion_id).hide(100);
                }
            }

            $.showAddAttachment = function (discussion_id){

                let element = $('#add-attach-comment-switch-'+discussion_id);
                if(element.is(":checked")){
                    $('#add-attach-comment-area-' + discussion_id).show(100);
                    $('#show-discussion-log' + discussion_id).prop("checked",false).trigger("change");
                }
                else{
                    $('#add-attach-comment-area-' + discussion_id).hide(100);
                }
            }

            const emailPattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;


            function mail_control(mails, isTrue = true) {
                if (mails !== '') {

                    let emails = mails.split(";");

                    let tmp = true;
                    if (emails.length <= 5) {
                        emails.forEach(function (email) {
                            isTrue = emailPattern.test($.trim(email));
                            if (isTrue === false) {
                                tmp = false;
                            }

                        });
                        if (tmp) {
                            return true;
                        } else {
                            return false;
                        }

                    } else {
                        isTrue = false;
                        return isTrue;
                    }
                } else {
                    isTrue = true;
                    return isTrue;

                }
            }

            $.assign_user = function (id) {
              let data = [];

              let ccPersonels=$("#assignedPersonels");

              let ccCounter=  ccPersonels.data("counter");

                for(let i=0; i<ccCounter; i++){
                    data.push({"value":$(`.personnel${i}`).data(`personnel-${i}`)})
                }
              let jsonData=  JSON.stringify(data)


                if (id === undefined)
                    id = "";
                let element = $('#assign_user' + id);
                let holder_element = $('#holder_user' + id);
                //let assigned_user_mail = $('#assigned_user_mail').val();
                let email_to = $('#email_to' + id);
                let last_select_ticket_assigned_user  = $('#last_select_ticket_assigned_user').val();

                // let readonlyBtn = $("#tagify-disable-email_to"+id);
                // readonlyBtn.click();

                if (element.is(":checked")) {
                    email_to.val(last_select_ticket_assigned_user);
                    $("#email_cc"+ id).val(jsonData)
                    email_to.prop("readonly", true);
                    $('#badge_to' + id).html("");
                    holder_element.prop("checked", false);
                    holder_element.prop("disabled", true);
                    $('#holder_or_personnel' + id).val("true");
                } else {
                    $("#email_cc"+ id).val("")
                    email_to.val("");
                    email_to.prop("readonly", false);
                    holder_element.prop("disabled", false);
                    $('#holder_or_personnel' + id).val("");

                }
            }

            $.assign_comment_user = function() {
                let isChecked = $('#assign_user').is(':checked');
                let holder_element = $('#holder_user');
                let email_to = $('#email_to');

                if (isChecked) {
                    let personnelCcIds=$("#assigned_personnel").val()
                    if(personnelCcIds.length>0){
                        $.ajax({
                            url: "/getUser/cc",
                            data:"cc_ids="+personnelCcIds+"&_token=" + "{{csrf_token()}}",
                            type: "post",
                            success: function(response) {
                                let data=[];
                                for(let i=0; i<response.users.length; i++){
                                    data.push({"value":response.users[i].email})
                                }
                                let jsonData=  JSON.stringify(data)
                                $("#email_cc").val(jsonData)
                            }
                        });
                    }
                    let personnel_id = $('#main-personnel').val();

                    $.ajax({
                        url: "/getUser/" + personnel_id,
                        type: "get",
                        success: function(response) {
                            personnel_email = response.email;
                            $('#email_to').val(personnel_email);
                        }
                    });

                    email_to.prop("readonly", true);
                    $('#badge_to').html("");
                    holder_element.prop("checked", false);
                    holder_element.prop("disabled", true);
                    $('#holder_or_personnel').val("true");
                }
                else {
                    $("#email_cc").val("")
                    email_to.val("");
                    email_to.prop("readonly", false);
                    holder_element.prop("disabled", false);
                    $('#holder_or_personnel').val("");
                }
            }
            $.holder_user = function (id) {
                if (id === undefined)
                    id = "";
                let element = $('#holder_user' + id);
                let assign_element = $('#assign_user' + id);
                let holder_user_mail = $('#holder_user_mail').val();
                let email_to = $('#email_to' + id);

                // let readonlyBtn = $("#tagify-disable-email_to"+id);
                // readonlyBtn.click();

                if (element.is(":checked")) {
                    email_to.val(holder_user_mail);
                    email_to.prop("readonly", true);
                    $('#badge_to' + id).html("");
                    assign_element.prop("checked", false);
                    assign_element.prop("disabled", true);
                    $('#holder_or_personnel' + id).val("false");
                } else {
                    email_to.val("");
                    email_to.prop("readonly", false);
                    assign_element.prop("disabled", false);
                    $('#holder_or_personnel' + id).val("");
                }
            }

            $.email_to_change = function (id) {
                if (id === undefined)
                    id = "";
                $('#badge_to' + id).html("");
            }
            $.email_cc_change = function (id) {
                if (id === undefined)
                    id = "";
                $('#badge_cc' + id).html("");
            }
            $.email_bcc_change = function (id) {
                if (id === undefined)
                    id = "";
                $('#badge_bcc' + id).html("");
            }

            function reset_fields(discussion_id) {
                $("#email_to" + discussion_id).val("");
                $("#email_cc" + discussion_id).val("");
                $("#email_bcc" + discussion_id).val("");
                $("#email_to" + discussion_id).prop("readonly", false);
                $('#holder_or_personnel' + discussion_id).val("");
                $('#assign_user' + discussion_id).prop("checked", false);
                $('#holder_user' + discussion_id).prop("checked", false);
                $('#assign_user' + discussion_id).prop("disabled", false);
                $('#holder_user' + discussion_id).prop("disabled", false);
            }

            @foreach($ticket["discussion"] as $discussion)
            reset_fields({{$discussion->id}});
            @endforeach
            reset_fields("");
            // Discussion send email
            $('.send-button').on('click', function (event) {
                let sendButton = $(this);
                if (sendButton.data("running") !== 1) {
                    sendButton.data("running", 1);
                }
                else {
                    return;
                }

                let discussion_id = $(this).attr('data-discussion-id');

                $('#discussion-text-update-box-'+discussion_id).trumbowyg("toggle")
                $('#discussion-text-update-box-'+discussion_id).trumbowyg("toggle")

                let email_to = $("#email_to" + discussion_id).val();
                let email_cc = $("#email_cc" + discussion_id).val();
                let email_bcc = $("#email_bcc" + discussion_id).val();
                let ticket_id = $('#ticket_id').val();
                if(email_to === "") {

                    $('#badge_to' + discussion_id).html("This field is required!");
                    sendButton.data("running", 0);
                }
                else {

                    if(checkAttachmentSize()){
                        confirmModal('Your attachment files will not be sent as e-mail because they exceed 10Mb, do you confirm?',"Are you sure?","Ok","Cancel").then(function() {
                            sendData();
                        }).fail(function() {
                            sendButton.data("running", 0);
                        })
                        return false;
                    }


                    confirmModal('Are you sure? Mail will be sent!',"Are you sure?","Ok","Cancel").then(function() {
                        sendData();
                        return false;
                    }).fail(function() {
                        sendButton.data("running",0);
                        return false;
                    })
                    function sendData(){
                        toggleLoader(true);
                        let isPersonnel = $('#holder_or_personnel' + discussion_id).val();
                        $('#btn-loader' + discussion_id).show();
                        let data = $('#discussionLogAttachments' + discussion_id).serialize();
                        let discussionResponse = $('#discussionResponse' + discussion_id);
                        $.ajax({
                            url: "/send-update/" + ticket_id + "/" + discussion_id,
                            type: "post",
                            data: "email_to=" + email_to + "&email_cc=" + email_cc + "&email_bcc=" + email_bcc + "&personnel=" + isPersonnel + "&" + data,
                            dataType: "json",
                            success: function (response) {
                                if(response !== "" && response !== "undefined") {
                                    if(response.status === 1) {
                                        toggleLoader(false);
                                        toastr.success('Mail sent ' + response.who + ' successfully!', 'Success');
                                        reset_fields(discussion_id);

                                        $.ajax({
                                            url: "/getDiscussionLog/" + discussion_id + "/" + response.batchCount,
                                            type: "get",
                                            dataType: "json",
                                            success: function (response) {

                                                if (response !== "" && response !== "undefined") {
                                                    for (let i = 0; i < response.batch_count; i++) {
                                                        let sender = response.sender_user[i].first_name + " " + response.sender_user[i].surname;
                                                        let receiver = response.receiver_user[i] ? response.receiver_user[i].first_name + " " + response.receiver_user[i].surname : "";
                                                        let emails = response.emails[i] ? response.emails[i] : "";
                                                        let created_at = response.created_at[i];
                                                        let log_id = response.log_id[i];
                                                        let emails_cc = response.emails_cc[i] ? response.emails_cc[i] : "";
                                                        let emails_bcc = response.emails_bcc[i] ? response.emails_bcc[i] : "";
                                                        let attachments = response.attachments[i] ? response.attachments[i] : "";

                                                        if (sender === '') {
                                                            sender = '';
                                                        }

                                                        if (receiver === '') {
                                                            receiver = emails;
                                                        }


                                                        let attachment_hmtl = "";
                                                        $.each(attachments, function (index, attachment) {
                                                            if(attachment.is_mail){
                                                                attachment_hmtl += " \n" +
                                                                    "<a href=\"/uploads/" + attachment.attachment + "\" target=\"_blank\">Attachment " + (index + 1) + " : <span> " + attachment.attachment.substring(0, 20) + " | " + (Math.round(attachment.size * 0.000001 * 100) / 100) + " Mb | " + attachment.attachment.substring(attachment.attachment.lastIndexOf('.') + 1) + " </span></a>\n" +
                                                                    "<br>\n"
                                                            }
                                                        });
                                                        let html = " <div style='line-height: 0.9rem;display: flex;flex-direction: row;'> <div style=\"display: flex;flex-direction: column;\" class=\"border-bottom\">\n" +
                                                            "                                                             <span style=\"font-size: 0.75rem;font-weight: bold;cursor: pointer;\" onclick=\"$.show_log('" + log_id + "')\">[" + created_at + "] Sent from <strong>" + sender + "</strong></span>\n" +
                                                            "                                                            <span style=\"display: inline;font-size: 0.75rem;\" id=\"log_td" + log_id + "\" >\n" +
                                                            "                                                              TO : " + receiver + " \n" +
                                                            "                                                             " +
                                                            "  <br>\n" +
                                                            "                                                                CC : " + emails_cc +
                                                            "                                                                <br>\n" +
                                                            "                                                                BCC : " + emails_bcc + "" +
                                                            "<br>" + attachment_hmtl +
                                                            "</span>\n" +
                                                            "                                                        </div></div>";


                                                        $('#history' + discussion_id).append(html);
                                                        $('#btn-loader' + discussion_id).hide(100);
                                                        dropzones[discussion_id][0].dropzone.removeAllFiles(); // remove all files from front face after email sent
                                                        discussionResponse.html("");//remove inputs
                                                        $("#history"+discussion_id).show(200);

                                                    }
                                                    toggleLoader(false);
                                                    sendButton.data("running", 0);
                                                }
                                                else {
                                                    toggleLoader(false);
                                                    toastr.error('An error thrown!', 'Error!');
                                                    sendButton.data("running", 0);
                                                }

                                            }
                                        });

                                    }
                                    else {
                                        toggleLoader(false);
                                        sendButton.data("running", 0);
                                        toastr.error('An error thrown!', 'Error!');

                                    }
                                }
                                else {
                                    toggleLoader(false);
                                    sendButton.data("running", 0);
                                    toastr.error('An error thrown!', 'Error!');
                                }
                            }
                        });
                    }



                }
                sendButton.data("running", 0); // Delete Later
            });
            $('#discussion').parent().on("change keyup", function () {
                $(this).removeClass("error-border");
            });

            // Normal send email
            $('#sendmail-button').on("click", function () {
                let dueDate= new Date($("#dueDateComment").val());
                let currentTime= new Date();
                let commentStatus=$("#statusComment").val();
                currentTime= currentTime.setHours(0,0,0);
                if( commentStatus!=6 && dueDate < new Date(currentTime)){
                    $("#dueDateComment").addClass("error-border");
                    return false;
                }
                if(commentStatus==5 && !($("#commentDueDate").val())){
                    $("#commentDueDate").addClass("error-border");
                    return false;
                }
                $('#discussion').trumbowyg("toggle");
                $('#discussion').trumbowyg("toggle");

                if(!due_date_check($("input[name='due_date_comment']"))){
                    return false;
                }

                let sendButton = $(this);
                if (sendButton.data("running") !== 1) {
                    sendButton.data("running", 1);
                }
                else {
                    return;
                }

                let data = $('#discussionForm').serialize();
                if($('#discussion').val() === "") {
                    $('#discussion').parent().addClass("error-border");
                    sendButton.data("running", 0);
                    return false;
                }

                let email_to = $("#email_to").val();
                let email_cc = $("#email_cc").val();
                let email_bcc = $("#email_bcc").val();
                let ticket_id = $('#ticket_id').val();
                let isPersonnel = $('#holder_or_personnel').val();
                if (email_to === "") {
                    $('#badge_to').html("This field is required!");
                    sendButton.data("running", 0);
                }
                else {
                    if(checkAttachmentSize()){
                        confirmModal('Your attachment files will not be sent as e-mail because they exceed 10Mb, do you confirm?',"Are you sure?","Ok","Cancel").then(function() {
                            sendMailData();
                        }).fail(function() {
                            sendButton.data("running", 0);
                        });
                        return false
                    }

                    confirmModal('Are you sure? Mail will be sent!',"Are you sure?","Ok","Cancel").then(function() {
                        sendMailData();
                        return false;
                    }).fail(function() {
                        sendButton.data("running",0);
                        return false;
                    });

                    function sendMailData(){
                        toggleLoader(true);
                        $('#btn-loader').show();
                        $.ajax({
                            url: '/create-discussion/' + <?php echo $ticket->id; ?> +'?private=' + set_private_discussion + '&sendmail=1',
                            data: data,
                            type: "post",
                            success: function (response) {
                                if (response !== undefined) {
                                    if (response.sendmail === 1) {
                                        let discussion_id = response.discussion_id;
                                        let attachData = $('#discussionLogAttachments').serialize();

                                        $.ajax({
                                            url: "/send-update/" + ticket_id + "/" + discussion_id,
                                            type: "post",
                                            data: "email_to=" + email_to + "&email_cc=" + email_cc + "&email_bcc=" + email_bcc + "&personnel=" + isPersonnel + "&" + attachData,
                                            dataType: "json",
                                            success: function (response) {
                                                document.getElementById('discussion').value = "";
                                                location.reload(true);
                                            }
                                        });
                                    } else {
                                        toggleLoader(false);
                                        toastr.error('An error thrown!', 'Error!');
                                        $('#btn-loader').hide();
                                        sendButton.data("running", 0);
                                    }
                                } else {
                                    toggleLoader(false);
                                    toastr.error('An error thrown!', 'Error!');
                                    $('#btn-loader').hide();
                                    sendButton.data("running", 0);
                                }

                            }
                        });
                    }
                }



            });

            // Mail End



            $.show_log = function (id) {
                $('#log_td' + id).toggle(200);
            }

            $.showHistory = function (id){
                $("#history"+id).toggle(200);
            }





            $(document).on('click', '.deleteAttachment', function (e) {
                var id = $(this).attr('data-id');
                confirmModal('Are you sure you want to delete this attachment?',"Delete Attachment","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        type: "GET",
                        url: '/removeAttachment/' + id,
                        success: function (response) {
                            if (!response.error) {

                                window.location.reload();
                            } else {
                                toastr.error(response.error, 'Error');
                            }
                        }
                    });
                })
            });


            function currentTime(){
                let d = new Date();
                let date = d.getDate();
                let month = d.getMonth() + 1; // Since getMonth() returns month from 0-11 not 1-12
                let year = d.getFullYear();
                let dateStr = date + "-" + month + "-" + year;
                return dateStr;
            }

            $('#sendDiscussion').on('click', function (e) {

                let dueDate= new Date($("#dueDateComment").val());
                let currentTime= new Date();
                let commentStatus=$("#statusComment").val();
                currentTime= currentTime.setHours(0,0,0);
                if( commentStatus!=6 && dueDate < new Date(currentTime)){
                    $("#dueDateComment").addClass("error-border");
                    return false;
                }
                if(commentStatus==5 && !($("#commentDueDate").val())){
                    $("#commentDueDate").addClass("error-border");
                    return false;
                }

                $('#discussion').trumbowyg("toggle");
                $('#discussion').trumbowyg("toggle");

                if ($('#discussion').val() === "") {
                    $('#discussion').parent().addClass("error-border");
                }

                let isValid = validateEffortComments()
                if(isValid == false){
                    return false;
                }



                let sendButton = $(this);
                if (sendButton.data("running") !== 1) {
                    sendButton.data("running", 1);
                } else {
                    return;
                }



                if ($('#discussion').val() === "") {
                    $('#discussion').parent().addClass("error-border");
                    sendButton.data("running", 0);

                } else {
                    if(!due_date_check($("input[name='due_date_comment']"))){
                        sendButton.data("running", 0);
                        return false;
                    }

                    var form = $('#discussionForm');
                    var url = '/create-discussion/' + <?php echo $ticket->id; ?> +'?private=0';
                    toggleLoader(true);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function (response) {
                                if (!response.error) {
                                    document.getElementById('discussion').value = "";
                                    location.reload(true);
                                }
                                else {
                                    toastr.error('An error thrown!', 'Error!');
                                    sendButton.data("running", 0);
                                    toggleLoader(false);
                                }
                        }
                    });
                }
            });
            $('#sendPrivateDiscussion').on('click', function (e) {
                let dueDate= new Date($("#dueDateComment").val());
                let currentTime= new Date();
                let commentStatus=$("#statusComment").val();
                currentTime= currentTime.setHours(0,0,0);
                if( commentStatus!=6 && dueDate < new Date(currentTime)){
                    $("#dueDateComment").addClass("error-border");
                    return false;
                }
                $('#discussion').trumbowyg("toggle");
                $('#discussion').trumbowyg("toggle");

                let isValid = validateEffortComments()
                if(isValid == false){
                    return false;
                }



                let sendButton = $(this);

                if (sendButton.data("running") !== 1) {
                    sendButton.data("running", 1);
                } else {
                    return;
                }



                if ($('#discussion').val() === "") {
                    $('#discussion').parent().addClass("error-border");
                    sendButton.data("running", 0);

                } else {
                    if(!due_date_check($("input[name='due_date_comment']"))){
                        sendButton.data("running", 0);
                        return false;
                    }
                    var form = $('#discussionForm');
                    var url = '/create-discussion/' + <?php echo $ticket->id; ?> +'?private=1';
                    toggleLoader(true);

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function (response) {
                                if (!response.error) {
                                    document.getElementById('discussion').value = "";
                                    location.reload(true);
                                } else {
                                    location.reload();
                                    toastr.error('An error thrown!', 'Error!');
                                    sendButton.data("running", 0);
                                }
                        }
                    });
                }
            });
            $('.lockToggle').on('click', function (e) {
                var status = $(this).attr('data-id');
                var messageId = $(this).attr('message-id');
                var url = '/changeMessageStatus/' + messageId + '?private=' + status;
                toggleLoader(true);
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        if (!response.error) {
                            window.location.reload();
                        }
                        else{
                            toggleLoader(false);
                        }
                    }
                });
            });

            $('#mail_log_toggle').on('click', function () {
                if ($(this).data("hide") === 0) {
                    $(this).data("hide", 1);
                    $('#mail_log').hide(100);
                } else {
                    $('#mail_log').show(100);
                    $(this).data("hide", 0);
                }

            });
            $('#show-details-log').on('click', function () {
                if ($(this).data("hide") === 0) {
                    $(this).data("hide", 1);
                    $("#details-log").hide(100);
                } else {
                    $("#details-log").show(100);
                    $(this).data("hide", 0);
                }

            });

            var effortTypesHtml = "";
            var effortTypes = [];
            @foreach($data['effortTypes'] as $effort_type)
                effortTypesHtml += "<option\n" +
                "                value=\"{{$effort_type->id}}\" >{{$effort_type->type}}\n" +
                "\n" +
                "            </option>";
            effortTypes.push([{{$effort_type->id}}, "{{$effort_type->type}}"]);
            @endforeach


            $('#effortTypes').val(""); // default.it is necessary for control.
            $('#effortHours').val(""); // default.it is necessary for control.
            $('#effortMints').val(""); // default.it is necessary for control.


            $(document).on("keyup change", "#effortMints", function () {
                if (!/^[0-9]+$/.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }

                if ($(this).val() > 59 || $(this).val() < 0) {
                    $(this).addClass("error-border");
                    if ($(this).parent().children(".infoBox").length !== 1) {
                        $(this).parent().append("<span class='infoBox text-danger fs-10'>Please enter value between 0-59!</span>");
                    }

                } else {
                    $(this).removeClass("error-border");
                    $(this).parent().children(".infoBox").remove();

                }
                $('#effortHours').removeClass("error-border");
            });
            $(document).on("keyup change", "#effortHours", function () {
                if (!/^[0-9]+$/.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
                if ($(this).val() < 0) {
                    $(this).addClass("error-border");
                    if ($(this).parent().children(".infoBox").length !== 1) {
                        $(this).parent().append("<span class='infoBox text-danger fs-10'>Please enter value greater than 0!</span>");
                    }

                } else {
                    $(this).removeClass("error-border");
                    $(this).parent().children(".infoBox").remove();

                }
                $('#effortMints').removeClass("error-border");
            });
            $(document).on("change", '#effortTypes', function () {
                let val = $(this).val();
                if (val !== "") {
                    $(this).removeClass("error-border");
                }
            });

            $(document).on("click", ".private-att", function () {

                let type = $(this).data("type");//request
                let id = $(this).data("id"); // request according to type
                let is_private = $(this).data("is-private");

                if (is_private === 1) {
                    is_private = 0;
                } else {
                    is_private = 1;
                }
                let element = $(this);
                $.ajax({
                    url: "/tickets/change-private-status",
                    type: "get",
                    data: "type=" + type + "&id=" + id + "&is_private=" + is_private + "&_token=" + "{{csrf_token()}}",
                    success: function (response) {
                        if (response !== "" && response !== undefined) {
                            if (response.success === 1) {
                                let message;
                                if (response.is_private === 1) {
                                    message = "set private";
                                    element.removeClass("fa-unlock");
                                    element.addClass("fa-lock");
                                    element.closest("tr").children().not(":last-child").addClass("bg-primary");
                                    element.closest("tr").addClass("text-white");
                                    element.closest("tr").find(".link").removeClass("text-primary").addClass("text-white");

                                    element.data("is-private", 1);
                                    element.attr("title","Set Unprivate");
                                    element.attr("data-original-title","Set Unprivate");
                                } else {
                                    message = "set unprivate";
                                    element.removeClass("fa-lock");
                                    element.addClass("fa-unlock");
                                    element.closest("tr").children().not(":last-child").removeClass("bg-primary");
                                    element.closest("tr").removeClass("text-white");
                                    element.closest("tr").find(".link").addClass("text-primary");
                                    element.data("is-private", 0);
                                    element.attr("title","Set Private");
                                    element.attr("data-original-title","Set Private");

                                }

                                toastr.success("Attachment " + message, "Success");

                            }
                            else {
                                location.reload();
                                toastr.error("An error thrown!", "Error");
                            }
                        }
                    }
                })

            });
            $("input[name='due_date']").on("keyup change",function () {
                $(this).removeClass("error-border");
            });

            $('.updateTicketButton').on('click', function () {
                $("#description").trumbowyg("toggle")
                $("#description").trumbowyg("toggle")
                let due_date_element = $("input[name='due_date']");
                let due_date = new Date(due_date_element.val());
                let current_time = new Date();
                let current_ticket_status = $("#current-ticket-status").val();
                const ticket_statuses = ["6", "7", "9", "11"];
                current_time = current_time.setHours(0, 0, 0);

                if(!ticket_statuses.includes(current_ticket_status)) {
                    if(due_date_element.val() === "" || due_date < new Date(current_time)) {
                        due_date_element.addClass("error-border");
                        return false;
                    }
                }

                $('#updateTicket').submit();
            });

            $('#save_close').val("");
            //update and save button
            $('.updateTicketButtonSaveClose').on('click', function () {
                $("#description").trumbowyg("toggle")
                $("#description").trumbowyg("toggle")
                let due_date_element = $("input[name='due_date']");
                let due_date = new Date(due_date_element.val());
                let current_time = new Date();
                let current_ticket_status = $("#current-ticket-status").val();
                const ticket_statuses = ["6", "7", "9", "11"];
                current_time = current_time.setHours(0, 0, 0);

                if(!ticket_statuses.includes(current_ticket_status)) {
                    if(due_date_element.val() === "" || due_date < new Date(current_time)) {
                        due_date_element.addClass("error-border");
                        return false;
                    }
                }

                $('#save_close').val("1");
                $('#updateTicket').submit();
            });

            function validateEffort(hourElement, minuteElement, effortTypeElement) {
                let hour = hourElement.val();
                let minute = minuteElement.val();
                let effortType = effortTypeElement.val();

                if(effortType === "") {
                    effortTypeElement.addClass("error-border");
                    return false;
                }
                if((hour === "" || hour == 0) && (minute === "" || minute == 0)) {
                    hourElement.addClass("error-border");
                    minuteElement.addClass("error-border");
                    return false;
                }
                else {
                    if(hour < 0) {
                        hourElement.addClass("error-border");
                        return false;
                    }
                    if(minute < 0 || minute >= 60) {
                        minuteElement.addClass("error-border");
                        return false;
                    }
                }

                hourElement.removeClass("error-border");
                minuteElement.removeClass("error-border");
                effortTypeElement.removeClass("error-border");
                return true;
            }


            $('#saveEffortButton').on("click", function () {


                let effort_type = $('#effortTypes').val();
                let hour = $('#effortHours').val();
                let minute = $('#effortMints').val();
                if (!validateEffort($('#effortHours'), $('#effortMints'), $('#effortTypes'))) {
                    return false;
                }

                $.ajax({
                    url: "/ticket/addEffort",
                    type: "post",
                    data: "effort_type=" + effort_type + "&effort_hour=" + hour + "&effort_minute=" + minute + "&ticket_id={{$ticket->id}}" + "&_token=" + "{{csrf_token()}}",
                    dataType: "json",
                    success: function (response) {
                        if (response !== "" && response !== undefined) {
                            $('.effortLogTables').html("");
                            $("#effortTypes").val("");
                            $('#effortHours').val("");
                            $('#effortMints').val("");
                            toastr.success("Effort added successfuly!", "Success");
                        }
                        else {
                            location.reload();
                            toastr.error("An error thrown!", "Failed")
                        }
                    }
                });

            });

            //Show effort update modal
            $(document).on("click", ".effortUpdateButton", function () {
                let effort_id = $(this).data("effort-id");
                let discussion_id = $(this).data("discussion-id");

                if(discussion_id !== null && discussion_id !== undefined && discussion_id !== "") {
                    $('#update-effort-modal-button').data("discussion-id", discussion_id);
                }

                $('#effortLogModal').modal("show");
                $('#update-effort-modal-button').data("effort-id", effort_id);
                $.ajax({
                    url: "/ticket/getEffort/" + effort_id,
                    type: "get",
                    success: function (response) {
                        if (response !== "" || response !== undefined) {
                            $.each(effortTypes, function (index, value) {
                                let id = value[0];
                                const valid_values = [0, 15, 30, 45];
                                if (id === response.effort_type) {
                                    $('#effortType').val(id);
                                    $('#effortHour').val(response.hours);
                                    $('#effortMint').val(valid_values.includes(response.minutes) ? response.minutes : 0);
                                }
                            });
                        }
                    }
                });
            });

            $(".apply-discount-button").on("click", function() {
                $("#effort-discount-modal").modal("show");
                $("#discount-modal-selected-effort-type").val($(this).data("effort-type-text"));
                $("#discount-modal-total-efforts").val($(this).data("total-efforts"));
                $("#discount-modal-total-efforts-minutes").val($(this).data("total-efforts-minutes"));
                $("#discount-modal-calculated-discount").val((numberFormat($(this).data("discount"), 1, ",", ".") + "%"));
                $("#discount-modal-effort-id").val($(this).data("effort-type-id"));
                $("#discount-modal-final-hours").val($(this).data("final-hours"));
                $("#discount-modal-final-minutes").val($(this).data("final-minutes"));
            });

            $("#discount-modal-final-hours, #discount-modal-final-minutes").on("input", function() {
                calculateDiscountPercentage();
            });

            $(document).on('click', '#update-effort-modal-button', function() {
                let button_element = $(this);
                let discussion_id = $(this).data("discussion-id"); // get discussion id if request coming from discussion section
                let is_discussion;
                if (discussion_id !== null && discussion_id !== undefined && discussion_id !== "") {
                    is_discussion = true;

                }
                else {
                    is_discussion = false;
                    discussion_id = 0;
                }

                if(!validateEffort($('#effortHour'), $('#effortMint'), $('#effortType'))) {
                    return false;
                }

                let effort_id = $(this).data("effort-id");
                let effort_type = $('#effortType').val();
                let hour = $('#effortHour').val();
                let minute = $('#effortMint').val();
                toggleLoader(true);

                $.ajax({
                    url: "/ticket/updateEffort",
                    type: "post",
                    data: "effort_id=" + effort_id + "&effort_type=" + effort_type + "&effort_hour=" + hour + "&effort_minute=" + minute + "&ticket_id={{$ticket->id}}&discussion_id=" + discussion_id + "&_token=" + "{{csrf_token()}}",
                    success: function (response) {
                        if (response !== "" && response !== undefined && response.status !== "Error") {
                            if (is_discussion) {
                                button_element.removeData("discussion-id"); // if comment effort update,after update remove discussion-id data because controlled trigger for main effort or comment effort
                            }
                            location.reload();
                            toastr.success("Effort updated successfully!", "Success");

                        }
                        else {
                            toggleLoader(false);
                            toastr.error("An error thrown!", "Error");
                        }
                    }
                });

            });

            $(document).on("click", ".effortDeleteButton", function () {
                let effort_id = $(this).data("effort-id");
                let discussion_id = $(this).data("discussion-id");
                removeEffort(effort_id,discussion_id)
            });

            function removeEffort(effort_id,discussion_id){
                confirmModal('Effort information will be delete!',"Are you sure?","Delete","Cancel","#0275d8","#d9534f").then(function() {
                    let is_discussion;
                    if (discussion_id !== null && discussion_id !== undefined && discussion_id !== "") {
                        is_discussion = true;
                    } else {
                        is_discussion = false;
                        discussion_id = 0;
                    }
                    $.ajax({
                        url: "/ticket/deleteEffort/" + effort_id,
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if(response !== "" && response !== undefined && response.status !== "Error") {
                                location.reload();
                                toastr.success("Effort deleted successfully", "Success");
                            }
                            else {
                                location.reload();
                                toastr.error("An error thrown!", "Error");
                            }
                        }
                    });
                });
            }

            $(document).on("click", ".jumpToComment", function () {
                let id = $(this).data("id");
                $('html, body').animate({
                    scrollTop: $('#discussion-section' + id).offset().top - $(".header").outerHeight() - 24,
                }, 1000);

                $('#discussion-section' + id).animate({
                    "left": "+=50"
                }, 2000, function () {
                    $(this).animate({"left": "-=50"}, 1000, function () {
                    });
                });
            });

            $(document).on("click", ".comment-effort-save", function () {

                toggleLoader(true);
                let discussion_id = $(this).data("discussion-id");
                if (!validateEffort($("#comment-effort-hour-" + discussion_id), $("#comment-effort-minute-" + discussion_id), $("#comment-effort-type-" + discussion_id))) {
                    toggleLoader(false);
                    return false;
                }
                else {
                    let ticket_id = $("#ticket_id").val();
                    let data = $("#comment-effort-form-" + discussion_id).serialize();
                    data = data + "&ticket_id=" + ticket_id + "&is_discussion=1&discussion_id=" + discussion_id + "&_token=" + "{{csrf_token()}}";

                    $.ajax({
                        url: "/ticket/addEffort",
                        data: data,
                        type: "post",
                        success: function (response) {
                            if(response !== "" && response !== undefined && response.status !== "Error") {
                                location.reload();
                                toastr.success("Effort has added successfully!", "Success");
                            }
                            else {
                                location.reload();
                                toastr.error("An error thrown!", "Error");
                            }
                        }
                    })
                }
            });
            //Show effort update modal
            $(document).on("click", ".comment-effort-update-btn", function () {

                let effort_id = $(this).data("effort-id");
                let discussion_id = $(this).data("discussion-id");

                $('#effortLogModal').modal("show");//global
                $('#update-effort-modal-button').data("effort-id", effort_id);//global

                $('#update-effort-modal-button').data("discussion-id", discussion_id);
                $.ajax({
                    url: "/ticket/getEffort/" + effort_id,
                    type: "get",
                    success: function (response) {
                        if (response !== "" || response !== undefined) {
                            $.each(effortTypes, function (index, value) {
                                let id = value[0];
                                const valid_values = [0, 15, 30, 45];
                                if (id === response.effort_type) {
                                    $('#effortType').val(id);
                                    $('#effortHour').val(response.hours);
                                    $('#effortMint').val(valid_values.includes(response.minutes) ? response.minutes : 0);
                                }
                            });
                        }
                    }
                });
            });
            $(document).on("click", ".comment-effort-delete-btn", function () {
                let effort_id = $(this).data("effort-id");
                let discussion_id = $(this).data("discussion-id");
                removeEffort(effort_id,discussion_id)
            });
            $(".privateValidate").on("keyup change", function () {
                $(this).removeClass("error-border");
            });
            //Eğer ticketta partner varsa sayısını select2 lere id vermek için alıyoruz
            @if($ticket["partner_count"]>0)
            var select2index = {{$ticket["partner_count"]}};
            var partner_ids = [];
            //select 2 ye url vermek için partner id leri dizide depoluyoruz
            @foreach($ticket["partners"] as $partner)
            partner_ids.push({{$partner->partner_id}});
            @endforeach
            //birden fazla partner varswa partner user ı için
            $("#external-partner-contact").select2({
                ajax: {
                    url: '/external-partners/get-partner-users-raw?partner_id='+partner_ids[0],
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.name+" "+item.surname,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }

            });

            @else
            var select2index = 1;//ticketta partner yoksa birden fazla eklenebileceği ihtimalinden indexi 1 den başlatıyoruz
            @endif
            //initial selects undependency partner count
            $('#external-partner').select2({

                ajax: {

                    url: '/external-partners/get-raw-data',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.organization_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true,
                },
                allowClear:true,
                placeholder: "Select external partner"
            });


            $("#external-partner").on("select2:select",function (e){
                let partner_id = e.params.data.id;
                $("#external-partner-contact").select2({
                    ajax: {
                        url: '/external-partners/get-partner-users-raw?partner_id='+partner_id,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.name+" "+item.surname,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
                $.ajax({
                    url:'/external-partners/get-partner-users-raw?partner_id='+partner_id,
                    type:"get",
                    dataType:"json"

                }).then(function (response){
                    if(response.length>0){
                        let option = new Option(response[0].name+" "+response[0].surname,response[0].id,true,true);
                        $("#external-partner-contact").append(option).trigger("change");
                        $("#external-partner-contact").trigger({
                            type:"select2:select",
                        });
                        $(".plus-button-area").show();
                    }else{
                        $("#external-partner-contact").val(null).trigger("change");
                        $(".plus-button-area").hide();
                    }
                });
                $(".responsible-person-area").show();
            });

            $("#external-partner").on("select2:unselecting",function (){

                $("#external-partner-contact").val(null).trigger("change");
                $(".responsible-person-area").hide();
                $(".plus-button-area").hide();
                $(".appendedRow").remove();
            });
            //end initial select2 independency partner count
            $(document).on("click", "#add-external-partner", function () {
                $(".plus-button-area").hide();
                let selectBoxes = $(this).parents().find("#external-partner-field").children().last().find("select");
                if($(selectBoxes[0]).val() === "" || $(selectBoxes[1]).val()=== ""){
                    return 0;
                }

                select2index++;
                let tmpselect2index = select2index;
                let html = `
                        <div class="row appendedRow">
                            <div class="col-md-5 col-lg-5 mb-1">
                                <select name="external_partners[]" class="form-control" id="external-partner${tmpselect2index}">
                                    <option selected="selected"></option>
                                    <option selected="selected"></option>
                                </select>
                            </div>
                            <div class="col-md-5 col-lg-5 mb-1 responsible-person-area-${tmpselect2index} responsible-person" style="display: none;">
                                <select name="external_partner_contacts[]" class="form-control" id="external-partner-contact${tmpselect2index}">
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-danger remove-external-partner mt-1"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
`;
                $('#external-partner-field').append(html);

                $('#external-partner' + tmpselect2index).select2({

                    ajax: {
                        url: '/external-partners/get-raw-data',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.organization_name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });


                $(document).on("select2:select","#external-partner"+tmpselect2index,function (e){
                    let partner_id = e.params.data.id;

                    $("#external-partner-contact"+tmpselect2index).select2({
                        ajax: {
                            url: '/external-partners/get-partner-users-raw?partner_id='+partner_id,
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results:  $.map(data, function (item) {
                                        return {
                                            text: item.name+" "+item.surname,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }

                    });

                    $.ajax({
                        url:'/external-partners/get-partner-users-raw?partner_id='+partner_id,
                        type:"get",
                        dataType:"json"

                    }).then(function (response){
                        if(response.length>0){
                            let option = new Option(response[0].name+" "+response[0].surname,response[0].id,true,true);
                            $("#external-partner-contact"+tmpselect2index).append(option).trigger("change");
                            $("#external-partner-contact"+tmpselect2index).trigger({
                                type:"select2:select",
                            });
                            $(".plus-button-area").show();
                        }else{
                            $("#external-partner-contact"+ tmpselect2index).val(null).trigger("change");
                            $(".plus-button-area").hide();
                        }
                    });
                    $(".responsible-person-area-"+ tmpselect2index).show();


                });

            });
            //initial select2 dependency from partner count

            for(let i =1;i<select2index;i++){

                $('#external-partner' + i).select2({

                    ajax: {
                        url: '/external-partners/get-raw-data',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.organization_name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });


                //ilk gelen zaten statik id alıyor
                $("#external-partner-contact"+ i).select2({
                    ajax: {
                        url: '/external-partners/get-partner-users-raw?partner_id='+partner_ids[i],
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.name+" "+item.surname,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }

                });


                $(document).on("select2:select", "#external-partner" +  i, function (e) {
                    let partner_id = e.params.data.id;

                    $("#external-partner-contact" + i).select2({
                        ajax: {
                            url: '/external-partners/get-partner-users-raw?partner_id=' + partner_id,
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.name + " " + item.surname,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }

                    });

                    $.ajax({
                        url: '/external-partners/get-partner-users-raw?partner_id=' + partner_id,
                        type: "get",
                        dataType: "json"

                    }).then(function (response) {

                        let option = new Option(response[0].name + " " + response[0].surname, response[0].id, true, true);
                        $("#external-partner-contact" + i).append(option).trigger("change");
                        $("#external-partner-contact" +  i).trigger({
                            type: "select2:select",
                        });

                    });


                });


            }



            $(document).on("click", ".remove-external-partner", function () {
                $(this).closest(".appendedRow").remove();
                $(".plus-button-area").show();
            });


            @if(auth()->user()->role_id == 1)//if auth user super admin , comment is changable

            function get_discussion_old_data(discussion_id){
                let old_text = "";
                toggleLoader(true);
                $.ajax({
                    url:"/get-discussion-data/"+discussion_id,
                    type:"get",
                    async:false,
                    success:function (response){
                        old_text = response.text;

                    }
                });
                toggleLoader(false);

                return old_text;
            }
            function comment_post_load(element){
                $("html, body").animate({
                    scrollTop: element.offset().top-200,
                }, 1000);
            }

            $(document).on("click",".update-comment-button",function (){
                toggleLoader(true);
                let discussion_id = $(this).data("discussion-id");
                $(`.delete-comment-button[data-discussion-id="${discussion_id}"]`).hide();
                let discussion_text_area =  $("#discussion-text-area-"+discussion_id);


                $('#discussion-text-update-box-'+discussion_id).trumbowyg("toggle")
                $('#discussion-text-update-box-'+discussion_id).trumbowyg("toggle")

                if($(this).data("update") !== "1"){



                    discussion_text_area.hide(200);
                    discussion_text_area.removeClass("pl-3 pr-3").addClass("pl-0").addClass("pr-0");
                    let old_text = get_discussion_old_data(discussion_id);


                    let html = "<textarea id='discussion-text-update-box-"+discussion_id+"' class='form-control mb-2'>"+old_text+"</textarea>";

                    discussion_text_area.html(html);

                    $('#discussion-text-update-box-'+discussion_id).trumbowyg(
                        {
                            autogrow: true,
                            removeformatPasted: true,
                            resetCss:true,
                            defaultLinkTarget: '_blank',
                        }

                    );



                    $(this).addClass("btn-success");
                    $(this).children().closest("i").removeClass("fa-edit").addClass("fa-check");
                    $(this).children().closest("span").text("Save");
                    $(this).data("update","1");
                    //create abort button if edit clicked
                    $('#hidden-button-area'+discussion_id).append("<button type='button' class='btn btn-sm btn-secondary fs-13 abort-button' data-discussion-id='"+discussion_id+"'><i class='fa fa-ban'></i> Abort</button>");

                    discussion_text_area.css("background","#ffffff");
                    toggleLoader(false);
                    discussion_text_area.delay(100).fadeIn();
                    comment_post_load(discussion_text_area);


                }
                else{
                    discussion_text_area.hide(200);
                    let update_btn = $(this);
                    let data = $('#discussion-text-update-box-'+discussion_id).val();
                    let textarea = document.createElement("textarea");
                    textarea.innerHTML = data;
                    data = encodeURIComponent(textarea.value);
                    toggleLoader(true);
                    $.ajax({
                        url:"/discussion/update-comment/"+discussion_id,
                        data:{
                            message:data,
                            _token:"{{csrf_token()}}",
                        },
                        type:"post",
                        success:function (response){
                            if(response!=="" && response !== undefined){
                                if(response.success === 1){
                                    update_btn.removeData("update");
                                    update_btn.removeClass("btn-success").addClass("btn-info");
                                    update_btn.children().closest("i").addClass("fa-edit").removeClass("fa-check");
                                    update_btn.children().closest("span").text("Edit");
                                    discussion_text_area.html(response.text);

                                    if(discussion_text_area.data("discussion-isprivate")=== 1) {
                                        discussion_text_area.css("background", "#CDC8E5");
                                    }
                                    else {
                                        discussion_text_area.css("background", "#FCF8E3");
                                    }
                                    $('#hidden-button-area'+discussion_id).children().last().remove()
                                    toastr.success("Comment updated successfully!","Success");
                                }
                                else{
                                    toastr.error("An error thrown!","Error!");
                                    discussion_text_area.delay(100).fadeIn();
                                    comment_post_load(discussion_text_area);
                                }
                            }
                        }
                    });
                    discussion_text_area.addClass("pl-3").addClass("pr-3");
                    toggleLoader(false);
                    discussion_text_area.delay(100).fadeIn();
                    comment_post_load(discussion_text_area);
                }
            });


            //if user cancel edit comment
            $(document).on('click',".abort-button",function (){
                let discussion_id = $(this).data("discussion-id");
                $(`.delete-comment-button[data-discussion-id="${discussion_id}"]`).show();
                let comment_area = $("#discussion-text-area-"+discussion_id);
                comment_area.hide(200);

                let old_text = get_discussion_old_data(discussion_id);
                comment_area.html(old_text);

                if(comment_area.data("discussion-isprivate")=== 1) {
                    comment_area.css("background", "#CDC8E5");
                }
                else{
                    comment_area.css("background", "#FCF8E3");
                }
                //remove abort button if abort clicked
                $('#hidden-button-area'+discussion_id).children().last().remove();
                let update_btn = $('#hidden-button-area'+discussion_id).children().first();
                update_btn.removeData("update");
                update_btn.removeClass("btn-success").addClass("btn-info");
                update_btn.children().closest("i").addClass("fa-edit").removeClass("fa-check");
                update_btn.children().closest("span").text("Edit");
                comment_area.addClass("pl-3").addClass("pr-3");
                comment_area.delay(100).fadeIn();
                comment_post_load(comment_area);

            });

            $(document).on("click",".delete-comment-button",function (){
                let discussion_id = $(this).data("discussion-id");
                confirmModal('Comment will be deleted with its efforts and attachments!',"Are you sure?","Delete","Cancel","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url:"/delete-discussion/"+discussion_id,
                        type:"get",
                        success:function (response){
                            location.reload();
                        }
                    })
                })
            });


            @endif

            $(document).on("click",".add-attach-comment-button",function (){

                let discussion_id = $(this).data("discussion-id");

                let data = $("#add-attach-comment-form-"+discussion_id).serialize();

                $.ajax({
                    url:"/discussion/add-attachment/"+discussion_id,
                    data:data,
                    type:"get",
                    success:function (response){
                        if(response.success === 1){
                            location.reload();
                        }
                    }
                })

            });
            $(document).on("click","#ticket-export",function (){
                $("#ticket-export-submit").trigger("click");
            });


            $($(".personnel-comment").siblings()[0]).children().find(".select2-selection--single").css("cssText","height:2rem!important");

            function due_date_check(element){
                if(element.val() === ""){
                    element.addClass("error-border");
                    return false;
                }else{
                    return true;
                }
            }

            $(document).on("keyup",".only-positive-int",function (){
                this.value = this.value.replace(/[^0-9\.]/g,'');
            });

            @if(auth()->user()->role_id !=7)
            $("#email-last-receivers").on("change",function (){
                if($(this).is(":checked")){

                    $.ajax({
                        url:"/discussion/comment/last-receivers",
                        type:"post",
                        data:{
                            ticket_id:{{$ticket->id}},
                            _token:"{{csrf_token()}}"
                        },
                        success:function (response){
                            if(response.to) {
                                $("#email_to").val(response.to);
                                $("#email_cc").val(response.cc);
                                $("#email_bcc").val(response.bcc);
                            }else{
                                toastr.warning("Any receivers not found!","Warning!");
                            }
                        }
                    });

                }else{
                    $("#email_to").val("");
                    $("#email_cc").val("");
                    $("#email_bcc").val("");
                }
            });
            @endif
        });

        // transport price

        $(document).ready(function (){
            $.ajax({
                url: '/getOrganizationContract/{{$ticket->org_id}}',
                type: "get",
                success: function(response) {
                    if (response.status === 1) {
                        $('#transportTypeText').html(response.contract.transportTypeText);
                        $('#transportPrice1').val(response.contract.transportPrice1);
                        $('#transportPrice2').val(response.contract.transportPrice2);
                        $('#transportPrice3').val(response.contract.transportPrice3);
                        $('#transport_price').prop('readonly', true);
                        $('#transportAdd').show();
                        $('#transportReload').show();
                    } else {
                        $('#transport_price').prop('readonly', false);
                        $('#transportAdd').hide();
                        $('#transportReload').hide();
                    }
                }
            });
        });

        function transportPriceReload(){
            $('#transport_price').val('{{$ticket->transport_price}}');
        }

        function appendTransportPrice(priceId){
            var currentPrice = parseFloat($('#transport_price').val());
            if(isNaN(currentPrice))
                currentPrice = 0;
            var addPrice = parseFloat($('#'+priceId).val());
            $('#transport_price').val(currentPrice+addPrice);
            $('#transportModal').modal('hide');
            toastr.success("Transport price add!", "Success");
        }

        /* Comment Attachments download all*/

        $(".comment-attachment-download-btn").on("click",function (){
            let discussionId = $(this).data("discussion-id");

            $(`<form id='tempform' action="/attachment/commentAttachmentDownloadAll" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="discussion_id" value='${discussionId}'>
                </form>`).appendTo('body').submit();

            $("#tempform").remove();

        });
        $(".attachment-download-btn").on("click",function (){
            let ticketId = $(this).data("ticket-id");

            $(`<form id='tempform' action="/attachment/attachmentDownloadAll" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="ticket_id" value='${ticketId}'>
                </form>`).appendTo('body').submit();

            $("#tempform").remove();

        });

        function checkAttachmentSize(){
            let totalSize=parseInt($("#attachmentTotalSize").val());
            if(totalSize > 10485760){
                return true;
            }else{
                return false;
            }
        }


        $("#statusComment").on("change",function (){
           if($(this).val()==5){
               $("#commentDueDate").show();
               $("#commentDueDateLabel").show();
           } else{
               $("#commentDueDate").hide();
               $("#commentDueDateLabel").hide();
           }
        });
        function takeComment(type,ticket_id){
            $(".take-comment").prop("disabled", true);
            $.ajax({
                url: "/tickets/take-comment",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    type: type,
                    ticket_id: ticket_id,
                }
            }).done(function (response) {
                if(response.status === "Success") {
                    let existingContent = $('#discussion').trumbowyg('html');

                    let mergedContent = existingContent + response.text;
                    $("#discussion").trumbowyg('html', mergedContent)
                }
                else {
                    toastr.error("Something went wrong while trying to take comment!", "Error");
                }
                $(".take-comment").prop("disabled", false);
            }).fail(function () {
                toastr.error("Something went wrong while trying to take comment!", "Error");
                $(".take-comment").prop("disabled", false);
            });

        }

    </script>


    @include('tickets.ticket-calendar-scripts')

    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
        <!-- Calendar Links -->
        <script>
            $(document).ready( function() {
                $('.calendar-tasks-table tbody tr').click( function() {
                    let calendarId = $(this).attr('id').split('-')[1];
                    let userId = $(this).attr('data-id');
                    window.open(`/calendar/${userId}?ticket=1&search=${calendarId}`, '_blank');
                });
            });
        </script>
    @endif
@endsection