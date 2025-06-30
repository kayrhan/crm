@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('text-editor/trumbowyg.min.css') }}">
<link rel="stylesheet" href="{{ asset('drop-zone/dropzone.css') }}">
<style>
    .trumbowyg-box,
    .trumbowyg-editor {
        min-height: 80px !important;
        width: 100% !important;
    }

    .trumbowyg-editor {
        max-height: 450px !important;
        resize: vertical !important;
    }

    .trumbowyg-editor p img {
        width: 400px !important;
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


    #effortHours{
        text-align: center;
    }
    #effortMints{
        text-align: center;
    }
    .responsible-person .select2-container{
        min-width: 150.56px;
    }

</style>
@endsection
@section('page-header')
@endsection
@section('content')
    <div class="card mt-3">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                    </button>
                    {{ $error }}
                </div>
            @endforeach
        @endif
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">
                <span>Create New Ticket </span>
                @if($parent_ticket && !isset($copy))
                <span class="text-warning">with reference from
                    <a class="text-primary" target="_blank" href="{{ url('/update-ticket/' . $parent_ticket->id) }}">{{ $parent_ticket->getNameWithID() }}</a>
                </span>
                @endif
            </h3>
            <a class="btn btn-info" href="/tickets">Back</a>
        </div>
        <div class="card-body">
            <div class="row" style="margin-top: 20px;">

                <div class="col-lg-9 col-md-9">

                    <form id="createTicket">
                        @csrf
                        @if($parent_ticket && !isset($copy))
                        <input type="hidden" name="parent_ticket" value="{{ $parent_ticket->id }}">
                        @endif
                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.subject')) }}<span class="text-danger">*</span> </label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="name" rows="1" id="subject" placeholder="Subject" style="resize: none; white-space: nowrap; overflow-x: hidden; color: black">{{ isset($parent_ticket) ? $parent_ticket->name : old("name")}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.description')) }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-10">
                                        <textarea id="description" name="description" class="form-control">{{ isset($parent_ticket) ? $parent_ticket->description : old("description") }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.organization')) }}</label>
                                    <div class="col-xl-5 col-lg-5 col-md-5">
                                        <select id="organization" name="organization" class="form-control">
                                            @if (old('organization'))
                                                <option value="{{ old('organization') }}" selected="selected">
                                                    {{ App\Organization::where('id', old('organization'))->value('org_name') }}
                                                </option>
                                            @elseif(isset($parent_ticket))
                                                <option value="{{ $parent_ticket->org_id }}" selected="selected">
                                                    {{ $parent_ticket->organization->org_name }}
                                                </option>
                                            @else
                                                <option value="{{ auth()->user()->org_id }}" selected="selected">
                                                    {{ App\Organization::where('id', auth()->user()->org_id)->value('org_name') }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-5">
                                        <select id="users" name="user" class="form-control">
                                            <option value="">&nbsp;</option>
                                            @if (old('user'))
                                                <option value="{{ old('user') }}" selected>
                                                    @php
                                                        $user = App\User::where('id', old('user'))
                                                            ->select(['first_name', 'surname'])
                                                            ->first();
                                                    @endphp
                                                    {{ $user->first_name . ' ' . $user->surname }}
                                                </option>

                                            @elseif(isset($parent_ticket))
                                                <option value="{{ $parent_ticket->ticketHolder->id }}" selected>
                                                    {{ $parent_ticket->getTicketHolderName() }}
                                                </option>
                                            @else
                                                <option value="{{ auth()->id() }}" selected>
                                                    @php
                                                        $user = App\User::where('id', auth()->id())
                                                            ->select(['first_name', 'surname'])
                                                            ->first();
                                                    @endphp
                                                    {{ $user->first_name . ' ' . $user->surname }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-xl-2 col-lg-2 col-md-2 form-label my-auto">{{ ucfirst(trans('words.assigned_to')) }} (Master)</label>
                                    <div class="col-xl-5 col-lg-5 col-md-5">
                                        <select id="personnel" name="personnel" class="form-control ">
                                            @if (old('personnel'))
                                                <option value="{{ old('personnel') }}" selected="selected">
                                                    @php
                                                        $user = App\User::where('id', old('personnel'))
                                                            ->select(['first_name', 'surname'])
                                                            ->first();
                                                    @endphp
                                                    {{ $user->first_name . ' ' . $user->surname }}
                                                </option>
                                            @elseif(isset($parent_ticket))
                                                <option value="{{ $parent_ticket->assignedTo->id  }}">
                                                    {{ $parent_ticket->getTicketAssignedUserName() }}
                                                </option>
                                            @else
                                                <option value="{{ auth()->id() }}" selected="selected">
                                                    @php
                                                        $user = App\User::where('id', auth()->id())
                                                            ->select(['first_name', 'surname'])
                                                            ->first();
                                                    @endphp
                                                    {{ $user->first_name . ' ' . $user->surname }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-xl-2 col-lg-2 col-md-2 form-label my-auto">{{ ucfirst(trans('words.assigned_to')) }} (Secondary)</label>
                                    <div class="col-xl-5 col-lg-5 col-md-5">
                                        @if(isset($copy))
                                        <select id="assigned_personnel" name="assigned_personnel[]" class="form-control js-example-basic-multiple" multiple="multiple">
                                            @foreach($ticket_personnel as $index=>$assigned_personnel)
                                                <option  class="personnel{{$index}}"  data-personnel-{{$index}}="{{$assigned_personnel["mail"]}}" value="{{$assigned_personnel["id"]}}" selected="selected">
                                                    {{$assigned_personnel["name"]}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @else
                                            <select id="assigned_personnel" name="assigned_personnel[]" class="form-control js-example-basic-multiple" multiple="multiple">
                                                    <select id="assigned_personnel" name="assigned_personnel[]" class="form-control js-example-basic-multiple" multiple="multiple">
                                                    </select>
                                            </select>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->role_id != 7 && auth()->user()->role_id != 4)
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-xl-2 col-lg-2 col-md-2 form-label">External Partner</label>
                                    <div class="col-xl-5 col-lg-5 col-md-5">
                                        <div class="row">
                                            <label class="col-xl-5 col-lg-5 col-md-5 form-label text-center">External Partner</label>
                                            <label class="col-xl-5 col-lg-5 col-md-5 form-label text-center responsible-person-area" style="display: none;">Responsible Person</label>
                                            <label class="col-xl-2 col-lg-2 col-md-2 form-label text-center plus-button-area" style="display: none;"></label>
                                        </div>
                                    </div>

                                    <label class="col-xl-5 col-lg-5 col-md-5 form-label"></label>
                                    <label class="col-xl-2 col-lg-2 col-md-2 form-label"></label>
                                    <div class="col-xl-5 col-lg-5 col-md-5" id="external-partner-field">
                                        @if(isset($copy) && $data["partner_count"]>0)
                                                @foreach($data["partners"] as $partner)
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
                                                            <div class="col-md-5 col-lg-5 mb-1" >
                                                                <select name="external_partner_contacts[]" class="form-control " id="external-partner-contact">
                                                                    <option value="{{$ext_partner_contact->id??""}}">
                                                                        {{($ext_partner_contact->name??"")." ".($ext_partner_contact->surname??"")}}
                                                                    </option>

                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 col-lg-2">
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
                                                        <option value="">
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-5 col-lg-5 mb-1 responsible-person-area responsible-person" style="display: none">
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
                                    <div class="col-xl-5 col-lg-5 col-md-5" id="external-partner-contact-field">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.status')) }}</label>
                                    <div class="col-md-5">
                                        <select name="status" class="form-control">
                                            @foreach ($data["status"] as $status)
                                            <option value="{{ $status->id }}" {{ old("status") == $status->id ? "selected" : ($status->id == 1 ? "selected" : "" ) }}>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.priority')) }}</label>
                                    <div class="col-md-5">
                                        <select name="priority" class="form-control">
                                            <option value="4" {{isset($parent_ticket)?($parent_ticket->priority==4?"selected":""):""}}>Low</option>
                                            <option value="1" {{isset($parent_ticket)?($parent_ticket->priority==1?"selected":""):"selected"}}>Normal</option>
                                            <option value="2" {{isset($parent_ticket)?($parent_ticket->priority==2?"selected":""):""}}>High</option>
                                            <option value="3" {{isset($parent_ticket)?($parent_ticket->priority==3?"selected":""):""}}>Very High</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-10 col-md-10" id="hidden-category-row">
                                <div class="form-group row border-bottom">
                                    <label for="category" class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.category')) }}</label>
                                    <div class="col-md-5">
                                        <select name="category" id="category" class="form-control">
                                            @foreach ($data["category"] as $category)
                                            @if(isset($parent_ticket))
                                            <option value="{{ $category->id }}" @if($category->id == $parent_ticket->category) selected="selected" @endif>{{ $category->name }}</option>
                                            @else
                                            <option value="{{ $category->id }}" @if($category->id == old("category")) selected="selected" @endif>{{ $category->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(isset($parent_ticket) && $parent_ticket->category === 14)
                                <div id="ticket-sub-category-row">
                                    <div class="form-group row border-bottom">
                                        <label for="ticket-sub-category" class="col-md-2 form-label my-auto">IT Category</label>
                                        <div class="col-md-5">
                                            <select name="sub_category" class="form-control" id="ticket-sub-category">
                                                @foreach($data["sub_category"] as $category)
                                                <option value="{{ $category->id }}" @if($category->id === $parent_ticket->sub_category_id) selected="selected" @endif>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.due_date')) }}</label>
                                    <div class="col-md-5">
                                        @if(isset($copy))
                                            <input class="form-control" type="date" name="due_date" min="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{ $parent_ticket->due_date ? $parent_ticket->due_date  : \Carbon\Carbon::now()->addDay(3)->format("Y-m-d")}}">
                                        @else
                                            <input class="form-control" type="date" name="due_date" min="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{ old('due_date') ? old('due_date')  : \Carbon\Carbon::now()->addDay(3)->format("Y-m-d")}}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->role_id != 7 && auth()->user()->role_id != 4)
                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">
                                        {{ ucfirst(trans('words.transport')) }} {{ ucfirst(trans('words.price')) }}
                                    </label>
                                    <div class="col-md-5">
                                        @if(isset($copy))
                                            <input class="form-control" type="number" id="transport_price" name="transport_price" value="{{ $parent_ticket->transport_price ?? '' }}">
                                        @else
                                            <input class="form-control" type="number" id="transport_price" name="transport_price" value="{{ old('transport_price') }}">
                                        @endif
                                    </div>
                                    <button id="transportAdd" style="display: none" data-toggle="modal" data-target="#transportModal" type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
                                    <button id="transportClear" style="display: none" type="button" class="btn btn-sm btn-warning ml-1"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4)
                            <div class="row">
                                <div class="col-md-10 col-lg-10">
                                    {{-- Effort --}}
                                    <div class="row border-bottom">
                                        <div class="col-md-2 col-lg-2 align-self-center">
                                            <span class="form-label">Effort</span>


                                        </div>
                                        <div class="col-md-10 col-lg-10">

                                            <div class="row text-center">
                                                <div class="col-md-3 col-lg-3">Type of effort</div>
                                                <div class="col-md-4 col-lg-4">Hours</div>
                                                <div class="col-md-4 col-lg-4">Minutes</div>
                                                <div class="col-md-1 col-lg-1">&nbsp;</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12" id="effortArea">
                                                    <div class="row effortRow">
                                                        <div class="col-lg-3 col-md-3">
                                                            <div class="form-group">
                                                                <select id="effortTypes" name="effort_types[]"
                                                                    class="form-control form-control-sm">
                                                                    <option value="" @if(!$data["userEffortType"]) selected @endif ></option>
                                                                    @foreach ($data["effortTypes"] as $effort_type)
                                                                    <option @if($effort_type->id == $data['userEffortType']) selected @endif value="{{ $effort_type->id }}">{{ $effort_type->type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <input id="effortHours" class="form-control form-control-sm only-positive-int" type="number" name="hours[]">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <select id="effortMints"  class="form-control form-control-sm only-positive-int " type="number" name="mints[]">
                                                                <option>0</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1 col-lg-1 pr-0">
                                                            <button type="button" id="addEffortButton" class="btn btn-sm btn-success" data-effort-id="1"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div id="attachmentResponse">
                        </div>
                    </form>

                    <div class="form-label" style="padding-top: 10px;">
                        {{ ucfirst(trans('words.add_attachment')) }}
                        @if (auth()->user()->role_id == 1)
                            <span style="color:red">(max. 5 Files | max. File size 500 MB)</span>
                        @else
                            <span style="color:red">(max. 5 Files | max. File size 100 MB)</span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-10">
                            <form class="dropzone" id="ticketAttachments"> @csrf</form>
                        </div>
                    </div>
                    <input style="display: none" id="attachmentTotalSize" value="0" >

                    <div class="row" id="buttonRow">
                        <div class="col-lg-10 col-md-10">
                            <button type="button" id="createTicketButton" class="btn btn-success mt-4 mb-0 float-right">{{ trans('words.save') }}</button>
                            <a href="{{ url('/tickets') }}" class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{ trans('words.cancel') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 ">
                    <div class="row pl-0 border-bottom">
                        <div class="col-lg-1 col-md-1 pl-0">
                            <div class="form-group pl-0 m-0">
                                <label class="custom-switch">
                                    <input onchange="$.showEmail()" id="show-email" type="checkbox" name="custom-switch-checkbox1" class="custom-switch-input" autocomplete="off">
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 form-label">
                            Send E-mail
                        </div>
                    </div>

                    <div class="row" id="email-box" style="display: none;">
                        <div class="card card-body">
                            <div class="col-lg-12 col-md-12">

                                <div id="assignedUserInputs" class="border-bottom">
                                    <span id="warning_badge" class="badge badge-warning" style="display: none;">Assigned user and ticket holder is already same!</span>
                                    <div class="form-check pl-2 m-0">
                                        <input class="form-check-input" name="assigned_user" type="checkbox" value="1" id="assign_user" onchange="$.assign_user()">
                                        <label class="form-check-label" for="assign_user">
                                            <small>Send this update to assigned users.</small><i class="fa fa-info-circle ml-1" data-toggle="tooltip" data-placement="bottom" title="Detailed email for personnel!"></i>
                                        </label>
                                    </div>
                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444;"
                                            class="col-md-1 form-label my-auto p-0"><small>To:</small>
                                        </label>
                                        <div class="col-md-7 p-0">
                                            <span id="badge_to" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_to" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444;"
                                            class="col-md-1 form-label my-auto p-0"><small>Cc:</small>
                                        </label>
                                        <div class="col-md-7 p-0">
                                            <span id="badge_cc" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_cc" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444; "
                                            class="col-md-1 form-label my-auto p-0"><small>Bcc:</small>
                                        </label>
                                        <div class="col-md-7 p-0">
                                            <span id="badge_bcc" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_bcc" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check pl-2 m-0">
                                    <input class="form-check-input" name="holder_user" type="checkbox" value="1"
                                        id="holder_user" onchange="$.holder_user()">
                                    <label class="form-check-label" for="holder_user">
                                        <small>Send this update to ticket holder.</small><i class="fa fa-info-circle ml-1"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Non-detailed email for customer!"></i>
                                    </label>
                                </div>
                                <div id="ticketHolderInputs" style="display: none;">

                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444;"
                                            class="col-md-1 form-label my-auto p-0"><small>To:</small>
                                        </label>
                                        <div class="col-md-7 p-0">
                                            <span id="badge_holder_to" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_holder_to" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444;"
                                            class="col-md-1 form-label my-auto p-0"><small>Cc:</small>
                                        </label>
                                        <div class="col-md-7 p-0">
                                            <span id="badge_holder_cc" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_holder_cc" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1 mb-1">
                                        <label style="color: #494444; "
                                            class="col-md-1 form-label my-auto p-0"><small>Bcc:</small>
                                        </label>
                                        <div class="col-md-7 p-0">
                                            <span id="badge_holder_bcc" class="badge badge-danger"></span>
                                            <x-tag-and-search-input name="email_holder_bcc" />
                                        </div>
                                    </div>
                                </div>


                                <input type="hidden" id="holder_or_personnel" value="">

                                <div class="row">
                                    <div class="col-lg-8 col-md-8 p-0 text-right">
                                        <button type="button" class="btn btn-success btn-sm mt-1 mb-0 p-1" id="sendmail-button">
                                            Save & Send Email <i id="btn-loader" style="display: none;" class="fa fa-circle-o-notch fa-spin"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="transportModal" tabindex="-1" role="dialog" aria-labelledby="transportModal" aria-hidden="true">
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
@endsection
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{ asset('drop-zone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('text-editor/trumbowyg.min.js') }}"></script>

    <script>
        $('#description').trumbowyg({
            autogrow: true,
            removeformatPasted: true,
            defaultLinkTarget: '_blank'
        });

        $('#category').on('change', function() {
            var type = $("#category").val();

            if (type === 6) {
                $('#project_type').css('display', 'block');
                $('#other_type').css('display', 'none');
            }
            else {
                $('#project_type').css('display', 'none');
            }
            if (type === 4) {
                $('#project_type').css('display', 'none');
                $('#other_type').css('display', 'block');
            }
            else {
                $('#other_type').css('display', 'none');
            }

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
            success: function(file, response) {

                if (response.error) {
                    toastr.error(response.error, 'Error');
                    $('#buttonRow').show();
                }
                else {
                    $.each(response.data, function(key, data) {
                        $(file.previewTemplate).append(
                            '<span style="display: none;" class="server_file">' + data.link +
                            '</span>');
                        $(file.previewTemplate).append(`<div class="custom-control custom-switch w-50 ml-5 pl-5">
                                <input id="privateSwitch-${data.size}" onChange="changePrivateValue('${data.size}')" type="checkbox"
                                    class="custom-control-input">
                                <label class="custom-control-label text-primary" for="privateSwitch-${data.size}"><b>PRIVATE</b></label>
                            </div>`);
                        $('#attachmentResponse').append(
                            `<input type="hidden" name="ticketAttachments[${data.size}][link]"  data-size="${data.size}" id="attachmentLink-${data.size}" value="${data.link}"/>
                            <input type="hidden" name="ticketAttachments[${data.size}][isPrivate]" id="isPrivate-${data.size}" value="off">`
                            );

                    });
                    toastr.success(response.success, 'Success');
                    $('#buttonRow').show()
                }
            },
            init: function() {
                let totalSize=parseInt($("#attachmentTotalSize").val());
                this.on("sendingmultiple", function(file, xhr, formData){
                    totalSize += file[0].size;
                });
                this.on("removedfile", function(file) {
                 totalSize = $("#attachmentTotalSize").val();

                    totalSize= totalSize - file.size;

                    $("#attachmentTotalSize").val(totalSize);
                    $("#attachmentLink-" + file.size).remove();
                    $("#isPrivate-" + file.size).remove();
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
                this.on("sending", function() {
                    $('#buttonRow').hide();
                });
            }
        });

        const emailPattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;


        function mail_control(mails, isTrue = true) {
            if (mails !== '') {
                let emails = mails.split(";");
                let tmp = true;
                if (emails.length <= 5) {
                    emails.forEach(function(email) {
                        isTrue = emailPattern.test($.trim(email));
                        if (isTrue === false) {
                            tmp = false;
                        }

                    });
                    if (tmp) {
                        return true;
                    }
                    else {
                        return false;
                    }

                }
                else {
                    isTrue = false;
                    return isTrue;
                }
            }
            else {
                isTrue = true;
                return isTrue;

            }
        }

        $.showEmail = function() {
            let isChecked = $("#show-email").is(":checked");
            if (isChecked) {
                $("#email-box").show(100);
                $("#createTicketButton").hide();
            }
            else {
                $("#email-box").hide(100);
                $("#createTicketButton").show();
            }
        }

        $.email_to_change = function() {
            $('#badge_to').html("");
        }
        $.email_cc_change = function() {
            $('#badge_cc').html("");
        }
        $.email_bcc_change = function() {
            $('#badge_bcc').html("");
        }

        $.email_to_holder_change = function() {
            $('#badge_holder_to').html("");
        }
        $.email_cc_holder_change = function() {
            $('#badge_holder_cc').html("");
        }
        $.email_bcc_holder_change = function() {
            $('#badge_holder_bcc').html("");
        }

        var holder_email = "";
        var personnel_email = "";

        function resetSendMailBox() {
            $('#assign_user').prop("checked", false);
            $('#holder_user').prop("checked", false);
            $('#email_to').prop("readonly", false);
            $('#email_holder_to').prop("readonly", false);
            $('#email_to').val("");
            $('#email_cc').val("");
            $('#email_bcc').val("");
            $('#email_holder_to').val("");
            $('#email_holder_cc').val("");
            $('#email_holder_bcc').val("");
            $('#warning_badge').hide();
            personnel_email = "";
            holder_email = "";
        }

        resetSendMailBox();


        $.assign_user = function() {
            let isChecked = $('#assign_user').is(':checked');
            let personnelCcIds=$("#assigned_personnel").val()
            // let readonlyBtn = $("#tagify-disable-email_to");
            //readonlyBtn.click();

            if (isChecked) {
                let personnel_id = $('#personnel').val();
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

                $.ajax({
                    url: "/getUser/" + personnel_id,
                    type: "get",
                    success: function(response) {
                        if (response !== "undefined") {

                            personnel_email = response.email;
                            $('#email_to').val(personnel_email);
                            $('#email_to').prop("readonly", true);
                            if (personnel_email === holder_email) {
                                $('#warning_badge').show();
                            }
                            else {
                                $('#warning_badge').hide();
                            }
                        }
                    }
                });


            }
            else {
                $('#email_to').val("");
                $('#email_cc').val("");
                $('#email_to').prop("readonly", false);
                personnel_email = "";
                $('#warning_badge').hide();
            }
        }


        $.holder_user = function() {

            let isChecked = $('#holder_user').is(':checked');

            // let readonlyBtn = $("#tagify-disable-email_holder_to");
            // readonlyBtn.click();

            if (isChecked) {
                let holder_id = $('#users').val();
                $('#ticketHolderInputs').show(100);
                $.ajax({
                    url: "/getUser/" + holder_id,
                    type: "get",
                    success: function(response) {
                        if (response !== "undefined") {

                            holder_email = response.email;

                            $('#email_holder_to').val(holder_email);
                            $('#email_holder_to').prop("readonly", true);
                            if (personnel_email === holder_email) {
                                $('#warning_badge').show();
                            } else {
                                $('#warning_badge').hide();
                            }
                        }
                    }
                });

            }
            else {
                console.log(holder_email);
                holder_email = "";
                console.log(holder_email);

                $('#ticketHolderInputs').hide(100);
                $('#email_holder_to').prop("readonly", false);
                $('#email_holder_to').val("");
                $('#warning_badge').hide();
            }

        }
        function resetAssignedUser() {
            let master_user = $('#personnel').val();
            let assigned_user = $('#assigned_personnel').val();
            if (assigned_user.includes(master_user)) {
                $('#assigned_personnel option[value="' + master_user + '"]').remove();
            }
        }
        function assigned_personnel_url(params) {
            return '/getPersonnelRawData?except=' + $("#personnel").val();
        }

        $(document).ready(function() {
            $("#category").on("input", function() {
                let id = $(this).val();
                let row = `
                <div id="ticket-sub-category-row">
                    <div class="form-group row border-bottom">
                        <label for="ticket-sub-category" class="col-md-2 form-label my-auto">IT Category</label>
                        <div class="col-md-5">
                            <select name="sub_category" class="form-control" id="ticket-sub-category">
                                @foreach($data["sub_category"] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
            $('#personnel').select2({
                ajax: {
                    url: '/getPersonnelRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                },

            });

            $('#assigned_personnel').select2({
                maximumSelectionLength: 5,
                ajax: {
                    url:assigned_personnel_url,
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                },
            });

            $('#users').select2({
                ajax: {
                    url: '/getOrganizationUsersRawData/' + $("#organization").val() + '?returnType=raw',
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#users').on("change", function() {
                resetSendMailBox();
                $(this).parent().removeClass("error-border");
            });
            $('#personnel').on("change", function() {
                resetSendMailBox();
                resetAssignedUser();
                $(this).parent().removeClass("error-border");
            });
            $('#assigned_personnel').on("change", function() {
                $(this).parent().removeClass("error-border");
            });

            $('#subject').on('keyup', function() {
                $(this).removeClass("error-border");
            });
            $('#description').parent().on('keyup', function() {
                $(this).removeClass("error-border");
            });
            $("input[name='due_date']").on("keyup change",function (){
               $(this).removeClass("error-border");
            });


            $('#organization').on('change', function() {
                var tmp = 1;
                var orgId = this.value;
                $('#users').html("");
                $.ajax({
                    url: '/getOrganizationUsersRawData/' + orgId + '?returnType=raw',
                    type: "get",
                    success: function(response) {
                        if (response.length === 1) {
                            tmp = 0;
                            $('#users').html("<option value='" + response[0].id + "'>" +
                                response[0].text + "</option>");
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
                            $('#transportClear').show();
                        } else {
                            $('#transport_price').prop('readonly', false);
                            $('#transportAdd').hide();
                            $('#transportClear').hide();
                        }
                    }
                });
                if (tmp === 1) {
                    $('#users').select2({
                        ajax: {
                            url: '/getOrganizationUsersRawData/' + orgId + '?returnType=raw',
                            processResults: function(data, page) {

                                return {
                                    results: data
                                };
                            }
                        }
                    });
                }
                $(this).parent().removeClass("error-border");
                resetSendMailBox();
            });

            function formValidate() {
                let subject = $('#subject').val();
                let description = $('#description').val();
                let organization = $('#organization').val();
                let personnel = $('#personnel').val();
                let customer = $('#users').val();
                let due_date = $("input[name='due_date']").val();
                if (subject === "") {
                    $('#subject').addClass("error-border");
                    return false;
                } else if (description === "") {
                    $('#description').parent().addClass("error-border");

                    return false;
                }else if(due_date ===""){
                    $("input[name='due_date']").addClass("error-border");
                }
                else if (organization === "" || organization === null) {
                    $('#organization').parent().addClass("error-border");
                    return false;
                } else if (personnel === "" || personnel === null) {
                    $('#personnel').parent().addClass("error-border");
                    return false;
                } else if (customer === "" || customer === null) {
                    $('#users').parent().addClass("error-border");
                    return false;
                } else {
                    return true;
                }
            }

            $('#sendmail-button').on('click', function() {
                $('#description').trumbowyg("toggle")
                $('#description').trumbowyg("toggle")

                let sendButton = $(this);
                if (sendButton.data("running") !== 1) {
                    sendButton.data("running", 1);
                } else {
                    return;
                }
                let email_to = $('#email_to').val();
                let email_cc = $('#email_cc').val();
                let email_bcc = $('#email_bcc').val();
                let email_holder_to = $('#email_holder_to').val();
                let email_holder_cc = $('#email_holder_cc').val();
                let email_holder_bcc = $('#email_holder_bcc').val();
                let holder_user_checked = $('#holder_user').is(':checked');
                let assigned_user_checked = $('#assign_user').is(":checked");
                console.log(email_holder_bcc);

                ///validation
                if (!formValidate()) {
                    sendButton.data("running", 0);
                    return false;
                }
                if ((!mail_control(email_to) || email_to === "") && !holder_user_checked) {
                    $("#badge_to").html("This field are required!");
                    sendButton.data("running", 0);
                    return false;
                }
                if (!mail_control(email_cc)) {
                    $("#badge_cc").html("Please enter max 5 emails with ; and correct emails.");
                    sendButton.data("running", 0);
                    return false;
                }
                if (!mail_control(email_bcc)) {
                    $("#badge_bcc").html("Please enter max 5 emails with ; and correct emails.");
                    sendButton.data("running", 0);
                    return false;
                }
                if (holder_user_checked) {
                    if (!mail_control(email_holder_to) || email_holder_to === "") {
                        $("#badge_holder_to").html("This field are required!");
                        sendButton.data("running", 0);
                        return false;
                    }
                    if (!mail_control(email_holder_cc)) {
                        $("#badge_holder_cc").html("Please enter max 5 emails with ; and correct emails.");
                        sendButton.data("running", 0);
                        return false;
                    }
                    if (!mail_control(email_holder_bcc)) {
                        $("#badge_holder_bcc").html("Please enter max 5 emails with ; and correct emails.");
                        sendButton.data("running", 0);
                        return false;
                    }
                }
                if(checkAttachmentSize()){
                    confirmModal('Your attachment files will not be sent as e-mail because they exceed 10Mb, do you confirm?',"Are you sure?","Ok","Cancel").then(function() {
                        sendData();
                    }).fail(function() {
                        sendButton.data("running", 0);
                    })
                    return false
                }

                confirmModal('Are you sure? Mail will be sent!',"Are you sure?","Ok","Cancel").then(function() {
                    sendData()
                }).fail(function() {
                    sendButton.data("running", 0);
                })
                function sendData(){
                    toggleLoader(true);
                    $('#createTicketMessage').show();
                    let data = $('#createTicket').serialize();
                    data = data + "&email_to=" + email_to + "&email_cc=" + email_cc + "&email_bcc=" +
                        email_bcc +
                        "&email_holder_to=" + email_holder_to + "&email_holder_cc=" + email_holder_cc +
                        "&email_holder_bcc=" + email_holder_bcc + "&assigned_user=" + assigned_user_checked;
                    $.ajax({
                        url: "/create-ticket",
                        type: "post",
                        data: data,
                        success: function(response) {
                            if (response !== "undefined") {
                                if (response.success === 1) {
                                    $('#createTicketMessage').hide();
                                    toggleLoader(false);

                                    location.href = "/tickets?created";

                                } else {
                                    toastr.error('An error thrown!', 'Error!');
                                    $('#createTicketMessage').hide();
                                    toggleLoader(false);
                                    sendButton.data("running", 0);
                                }
                            }
                        }
                    });
                }

            });
            var effortTypes = "";

            @foreach ($data['effortTypes'] as $effort_type)
                effortTypes += `<option value="{{ $effort_type->id }}">
                                        {{ $effort_type->type }}
                                </option>`;
            @endforeach


        //    $('#effortTypes').val(""); // default.it is necessary for control.
            $('#effortHours').val(""); // default.it is necessary for control.
            $('#effortMints').val(0); // default.it is necessary for control.

            $('#addEffortButton').on("click", function() {

                let html = "<div class=\"row appendedEffort effortRow\" >\n" +
                    "                                                    <div class=\"col-lg-3 col-md-3\">\n" +
                    "                                                        <div class=\"form-group\">\n" +
                    "<select name=\"effort_types[]\"\n" +
                    "class=\"form-control form-control-sm\">\n" +
                    "<option value=\"\" selected></option>" +
                    effortTypes +
                    "</select>" +
                    "                                                        </div>\n" +
                    "                                                    </div>\n" +
                    "                                                    <div class=\"col-lg-4 col-md-4\">\n" +
                    "                                                        <input class=\"form-control form-control-sm only-positive-int\" type=\"number\"\n" +
                    "                                                               name=\"hours[]\">\n" +
                    "                                                    </div>\n" +
                    "                                                    <div class=\"col-lg-4 col-md-4\">\n" +
                    "                                                     <select class=\"form-control form-control-sm only-positive-int\" type=\"number\"\n" +
                    "                                                          name=\"mints[]\">\n" +
                    "                                                      <option>0</option>" +
                    "                                                      <option value=\"15\">15</option>" +
                    "                                                      <option value=\"30\">30</option>" +
                    "                                                      <option value=\"45\">45</option>" +
                    "                                                     </select>"+
                    "                                                    </div>\n" +
                    "                                                    <div class=\"col-md-1 col-lg-1 pr-0\">\n" +
                    "                                                        <button type=\"button\" class=\"btn btn-sm btn-danger removeEffortButton\"><i\n" +
                    "                                                                class=\"fa fa-minus\"></i></button>\n" +
                    "                                                    </div>\n" +
                    "                                                </div>";
                $('#effortArea').append(html);


            });

            function effortValidate() {
                let value = true;
                $("select[name='effort_types[]']").each(function() {

                    if ($(this).val() !== "") {

                        let hour = $(this).parent().closest(".effortRow").find("input[name='hours[]']")
                            .val();
                        let minute = $(this).parent().closest(".effortRow").find("input[name='mints[]']")
                            .val();

                        if (hour === "" && minute === "") {
                            $(this).parent().closest(".effortRow").find("input[name='hours[]']").addClass(
                                "error-border");
                            $(this).parent().closest(".effortRow").find("input[name='mints[]']").addClass(
                                "error-border");
                            value = false;
                        } else {
                            if (hour < 0) {
                                $(this).parent().closest(".effortRow").find("input[name='hours[]']")
                                    .addClass("error-border");
                                value = false;
                            } else if (minute < 0 || minute >= 60) {
                                $(this).parent().closest(".effortRow").find("input[name='mints[]']")
                                    .addClass("error-border");
                            } else {
                                value = true;
                            }
                        }

                    }
                });
                return value;
            }

            $(document).on("keyup change", "input[name='mints[]']", function() {
                if (!/^[0-9]+$/.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }

                if ($(this).val() > 59 || $(this).val() < 0) {
                    $(this).addClass("error-border");
                    if ($(this).parent().children(".infoBox").length !== 1) {
                        $(this).parent().append(
                            "<span class='infoBox text-danger fs-10'>Please enter value between 0-59!</span>"
                        );
                    }

                } else {
                    $(this).removeClass("error-border");
                    $(this).parent().children(".infoBox").remove();

                }
                $(this).parent().parent().find("input[name='hours[]']").removeClass("error-border");
            });
            $(document).on("keyup change", "input[name='hours[]']", function() {
                if (!/^[0-9]+$/.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
                if ($(this).val() < 0) {
                    $(this).addClass("error-border");
                    if ($(this).parent().children(".infoBox").length !== 1) {
                        $(this).parent().append(
                            "<span class='infoBox text-danger fs-10'>Please enter value greater than 0!</span>"
                        );
                    }

                } else {
                    $(this).removeClass("error-border");
                    $(this).parent().children(".infoBox").remove();

                }
                $(this).parent().parent().find("input[name='mints[]']").removeClass("error-border");
            });
            $(document).on("click", ".removeEffortButton", function(e) {

                $(this).closest(".appendedEffort").remove();

            });


            $('#createTicketButton').on('click', function() {
                $('#description').trumbowyg("toggle");
                $('#description').trumbowyg("toggle");

                if (!formValidate() || !effortValidate()) {
                    return false;
                }
                let data = $('#createTicket').serialize();
                toggleLoader(true);
                $.ajax({
                    url: "/create-ticket",
                    type: "post",
                    data: data,
                    success: function(response) {
                        if (response !== undefined && response !== "") {
                            if (response.success === 1) {
                                location.href = "/tickets";
                            }
                            else {
                                toastr.error("An error thrown!", "Failed");
                                toggleLoader(false);
                            }
                        }
                        else {
                            toastr.error("An error thrown!", "Failed");
                            toggleLoader(false);
                        }
                    }
                });

            });

            $(window).on("resize", function() {

                var users_parents = $("#select2-users-container").parents();
                $(users_parents[2]).css("width", $(users_parents[3]).width());
                var organization_parents = $('#select2-organization-container').parents();
                $(organization_parents[2]).css("width", $(organization_parents[3]).width());
                var personnel_parents = $('#select2-personnel-container').parents();
                $(personnel_parents[2]).css("width", $(personnel_parents[3]).width());
                //test

            });
            $('#external-partner').select2({
                ajax: {
                    url: '/external-partners/get-raw-data',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.organization_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: "Select external partner"
            });


            $("#external-partner").on("select2:select", function(e) {
                let partner_id = e.params.data.id;
                $("#external-partner-contact").select2({
                    ajax: {
                        url: '/external-partners/get-partner-users-raw?partner_id=' + partner_id,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name + " " + item.surname,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
                $.ajax({
                    url: '/external-partners/get-partner-users-raw?partner_id=' + partner_id,
                    type: "get",
                    dataType: "json"

                }).then(function(response) {

                    if(response.length>0){
                        let option = new Option(response[0].name + " " + response[0].surname, response[
                            0].id, true, true);
                        $("#external-partner-contact").append(option).trigger("change");
                        $("#external-partner-contact").trigger({
                            type: "select2:select",
                        });
                        $(".plus-button-area").show();
                    }else{
                        $("#external-partner-contact").val(null).trigger("change");
                        $(".plus-button-area").hide();
                    }


                });
                $(".responsible-person-area").show();

            });

            $("#external-partner").on("select2:unselecting", function() {
                $("#external-partner-contact").val(null).trigger("change");
                $(".responsible-person-area").hide();
                $(".plus-button-area").hide();
                $(".appendedRow").remove();
            });

            var select2index = 0;
            $(document).on("click", "#add-external-partner", function() {
                $(".plus-button-area").hide();
                let selectBoxes = $(this).parents().find("#external-partner-field").children().last().find(
                    "select");
                if ($(selectBoxes[0]).val() === "" || $(selectBoxes[1]).val() === "") {
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
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
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


                $("#external-partner" + tmpselect2index).on("select2:select", function(e) {
                    let partner_id = e.params.data.id;


                    $("#external-partner-contact" + tmpselect2index).select2({
                        ajax: {
                            url: '/external-partners/get-partner-users-raw?partner_id=' +
                                partner_id,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name + " " + item
                                                .surname,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }

                    });

                    $.ajax({
                        url: '/external-partners/get-partner-users-raw?partner_id=' +
                            partner_id,
                        type: "get",
                        dataType: "json",
                        async: false,
                        success: function(response) {
                            if(response.length>0){

                                let option = new Option(response[0].name + " " + response[0]
                                    .surname, response[0].id, true, true);
                                $("#external-partner-contact" + tmpselect2index).append(
                                    option).trigger("change");
                                $("#external-partner-contact" + tmpselect2index).trigger({
                                    type: "select2:select",
                                });
                                 $(".plus-button-area").show();
                            }else{
                                $("#external-partner-contact"+ tmpselect2index).val(null).trigger("change");
                                $(".plus-button-area").hide();
                            }
                        }

                    });
                    $(".responsible-person-area-"+ tmpselect2index).show();


                });





            });


            $(document).on("click", ".remove-external-partner", function() {
                $(this).closest(".appendedRow").remove();
                $(".plus-button-area").show();
            });
            $(document).on("keyup",".only-positive-int",function (){
                this.value = this.value.replace(/[^0-9\.]/g,'');
            });

        });


        $(document).ready(function() {
            // transport prices
            $('#transportClear').click(function (){
                $('#transport_price').val('');
            });
        });

        function appendTransportPrice(priceId){
            var currentPrice = parseFloat($('#transport_price').val());
            if(isNaN(currentPrice))
                currentPrice = 0;
            var addPrice = parseFloat($('#'+priceId).val());
            $('#transport_price').val(currentPrice+addPrice);
            $('#transportModal').modal('hide');
            toastr.success("Transport price add!", "Success");
        }

        function checkAttachmentSize(){
            let totalSize=parseInt($("#attachmentTotalSize").val());
            if(totalSize > 10485760){
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection
