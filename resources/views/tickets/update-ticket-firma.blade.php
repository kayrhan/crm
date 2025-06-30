@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('text-editor/trumbowyg.min.css')}}">
    <link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">


    <style>

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

        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 150px;
            resize: vertical !important;
        }

        .attachment-preview img {
            width: 400px;
            height: auto;
        }

        .trumbowyg-editor img {
            width: 400px;
        }

        tr {
            cursor: auto !important;
        }

        .description img {
            width: 400px;
        }

        .top-card {

        }

        table tr td {
            font-size: 0.9rem;
        }

        .dot {

            border-radius: 50%;
            display: inline-block;
        }

        @media screen and (max-width: 1368px) {
            html {
                font-size: 13px;
            }

            table tr td {
                font-size: 0.8rem;
            }

            .text {
                font-size: 0.8rem;
            }
        }

        .error-border {
            border: 1px solid red !important;
        }


    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{trans('words.update_ticket')}}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/tickets')}}"><i
                            class="fe fe-file-text mr-2 fs-14"></i>{{trans('words.tickets')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$ticket->name}}</li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{$error}}
                        </div>
                    @endforeach
                @endif
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">{{ucfirst(trans('words.ticket'))}} {{ucfirst(trans('words.information'))}}&nbsp;#{{$ticket->id}} | {{$ticket->name}} </h3>
                    <div style="text-align: right;">
                        <a href="{{url('/tickets')}}" class="btn btn-info"><i class="fa fa-backward mr-1"></i> Back </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12" id="left-info-bar">
                                            <div class="card mb-0">
                                                <div class="card-header">
                                                    <h3 class="card-title">Information</h3>
                                                </div>
                                                <div class="card-body" id="informationDiv">
                                                    <div class="list-card">
                                                        <span class="bg-warning list-bar"></span>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-sm-12">
                                                                <div class="media mt-0">
                                                                    <div class="media-body">
                                                                        <div class="d-md-flex align-items-center mt-1">
                                                                            <h6 class="mb-1">Assigned Company</h6>
                                                                        </div>
                                                                        <span class="mb-0 fs-13 text-muted"> {{ App\Organization::where('id', $ticket->org_id)->value('org_name') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="list-card">
                                                        <span class="bg-info list-bar"></span>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-sm-12">
                                                                <div class="media mt-0">
                                                                    <div class="media-body">
                                                                        <div class="d-md-flex align-items-center mt-1">
                                                                            <h6 class="mb-1">Assigned User</h6>
                                                                        </div>
                                                                        <span class="mb-0 fs-13 text-muted">{{$ticket->UserName}} {{$ticket->SurName}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="list-card">
                                                        <span class="bg-success list-bar"></span>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-sm-12">
                                                                <div class="media mt-0">
                                                                    <div class="media-body">
                                                                        <div class="d-md-flex align-items-center mt-1">
                                                                            <h6 class="mb-1">Priority</h6>
                                                                        </div>
                                                                        <span class="mb-0 fs-13 text-muted">
                                                                            {{$ticket->priority == 0 ? "Low":""}}
                                                                            {{$ticket->priority == 1 ? "Medium":""}}
                                                                            {{$ticket->priority == 2 ? "High":""}}
                                                                            {{$ticket->priority == 3 ? "Very High":""}}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="list-card">
                                                        <span class="bg-primary list-bar"></span>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-sm-12">
                                                                <div class="media mt-0">
                                                                    <div class="media-body">
                                                                        <div class="d-md-flex align-items-center mt-1">
                                                                            <h6 class="mb-1">Status</h6>
                                                                        </div>
                                                                        <span class="mb-0 fs-13 text-muted">
                                                                            @if($ticket->status_id == 1)
                                                                                <span>Opened</span>
                                                                            @elseif($ticket->status_id == 2)
                                                                                <span> In Progress</span>
                                                                            @elseif($ticket->status_id == 3)
                                                                                <span>In Progress</span>
                                                                            @elseif($ticket->status_id == 4)
                                                                                <span>In Progress</span>
                                                                            @elseif($ticket->status_id == 5)
                                                                                <span>Query</span>
                                                                            @elseif($ticket->status_id == 6)
                                                                                @if($ticket->proofed)
                                                                                <span>Done & Proofed</span>
                                                                                @else
                                                                                <span>Done</span>
                                                                                @endif
                                                                            @elseif($ticket->status_id == 7)
                                                                                <span>Invoiced</span>
                                                                            @elseif($ticket->status_id == 8)
                                                                                <span>On Hold</span>
                                                                            @elseif($ticket->status_id == 9)
                                                                                <span>Closed</span>
                                                                            @endif</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($ticket->status_id==7 || $ticket->status_id==9)
                                                    <div class="list-card">
                                                        <span class="bg-azure list-bar"></span>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-sm-12">
                                                                <div class="media mt-0">

                                                                    <div class="media-body">
                                                                        <div class="d-md-flex align-items-center mt-1">
                                                                            <h6 class="mb-1">Total Time</h6>
                                                                        </div>
                                                                        <span class="mb-0 fs-13 text-muted">{{$ticket["total_time"]}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-lg-9 col-md-9 p-0 m-0">
                                    <div class="card  h-100 description-resize">
                                        <div class="card-header">
                                            <h3 class="card-title">Description</h3>
                                        </div>
                                        <div class="card-body " id="descriptionHeight">

                                            {!!$ticket->description!!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3">
                            <div class="d-flex justify-content-end  ">
                                <div class="flex-column w-100">
                                    <div class="card-body ">
                                        <div class="latest-timeline scrollbar3" id="scrollbar3">
                                            <ul class="timeline mb-0">
                                                @if($ticket->created_at)
                                                    <li class="mt-0">
                                                        <div class="d-flex">
                                                            <span class="time-data">Ticket Created </span>
                                                            <span class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($ticket->created_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from
                                                            <span class="text-info">{{$ticket["created_from"] != null ? $ticket["created_from"]->first_name: "-"}} {{$ticket["created_from"] != null ? $ticket["created_from"]->surname: "-"}}</span>
                                                        </p>
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
                                                    <li>
                                                        <div class="d-flex">
                                                            <span class="time-data">Last Updated </span>
                                                            <span class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($ticket->updated_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Last updated from
                                                            <span class="text-info">{{$ticket["updated_from"] != null ? $ticket["updated_from"]->first_name: "-"}} {{$ticket["updated_from"] != null ? $ticket["updated_from"]->surname: "-"}}</span>
                                                        </p>
                                                    </li>
                                                @else
                                                    <li>
                                                        <div class="d-flex">
                                                            <span class="time-data">Last Updated </span>
                                                            <span class="ml-auto text-muted fs-11">-</span>
                                                        </div>
                                                        <p class="text-muted fs-12">Last updated from
                                                            <span class="text-info">-</span>
                                                        </p>
                                                    </li>
                                                @endif

                                                    {{-- Proofed Att --}}
                                                @if($ticket->proofed_at)
                                                     <li class="mb-1">
                                                         <div class="d-flex">
                                                             <span class="time-data">Proofed </span>
                                                             <span class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($ticket->proofed_at)->format("d.m.Y H:i:s")}}</span>
                                                         </div>
                                                         <p class="text-muted fs-13 mb-1">Proofed from
                                                             <span class="text-info">{{$ticket->getProofedName()}}</span>
                                                         </p>
                                                     </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @if($ticket['ticket_attachments'] || $ticket["discussion_attachments"])
                        @php
                            $attachment_count = count($ticket['ticket_attachments'])+count($ticket["discussion_attachments"]);
                        @endphp
                        <div class="row">
                            <div class="col-lg-9 col-md-9 pr-0 pl-0 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="form-group ">

                                            <label class="custom-switch">
                                                <input type="checkbox" id="attachmentToggle" name="attachmentToggle"
                                                       class="custom-switch-input" {{$attachment_count > 0 ? "checked":""}}>
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description"></span>
                                                <h3 class="card-title">{{trans('words.attachments')}}
                                                    ({{$attachment_count}})</h3>
                                            </label>

                                        </div>


                                    </div>
                                    <div class="card-body p-0"
                                         id="attachments" {{ (count($ticket['ticket_attachments'])+count($ticket['discussion_attachments']) == 0) ?  "style='display:none;'" : "" }}>
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-nowrap">

                                                @foreach($ticket['ticket_attachments'] as $attachment)
                                                    @if($loop->first)
                                                        <thead>
                                                        <tr align="center">
                                                            <th style="width:100%" align="center" colspan="7">TICKET ATTACHMENTS</th>
                                                        </tr>
                                                        </thead>
                                                        <thead>
                                                        <tr align="center">
                                                            <th style="width:5%">{{trans('words.id')}}</th>
                                                            <th class="text-left" style="width:30%">{{trans('words.file_name')}}</th>
                                                            <th style="width: 10%">Extension</th>
                                                            <th style="width:10%">{{trans('words.file_size')}}</th>
                                                            <th style="width:20%">{{trans('words.uploaded_from')}}</th>
                                                            <th style="width:20%">{{trans('words.uploaded_date')}}</th>
                                                            <th style="width:5%">Review</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                    @endif
                                                    @if($attachment->private != 1)
                                                        <tr align="center">
                                                            <td>{{$attachment->id}}</td>
                                                            <td class="text-left">{{$attachment->attachment}}</td>
                                                            <td>{{substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</td>
                                                            <td>{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                            <td>{{$attachment->UserName}} {{$attachment->SurName}}</td>
                                                            <td>{{$attachment->ParsedCreatedAt}}</td>
                                                            <td class="text-center">
                                                                <a href="{{route("uploads",[$attachment->attachment])}}"
                                                                   class="btn btn-primary btn-sm fa fa-eye" data-toggle="tooltip" data-placement="top" title="See attach"
                                                                   target="_blank"></a>

                                                            </td>
                                                        </tr>
                                                    @endif
                                                @if($loop->last)
                                                </tbody>
                                                @endif
                                                @endforeach



                                                @foreach($ticket['discussion_attachments'] as $attachment)
                                                    @if($loop->first)
                                                        <thead>
                                                        <tr align="center">
                                                            <th style="width:100%" align="center" colspan="7">COMMENT ATTACHMENTS</th>
                                                        </tr>
                                                        </thead>
                                                        <thead>
                                                        <tr align="center">
                                                            <th style="width:5%">{{trans('words.id')}}</th>
                                                            <th class="text-left" style="width:30%">{{trans('words.file_name')}}</th>
                                                            <th style="width: 10%">Extension</th>
                                                            <th style="width:10%">{{trans('words.file_size')}}</th>
                                                            <th style="width:20%">{{trans('words.uploaded_from')}}</th>
                                                            <th style="width:20%">{{trans('words.uploaded_date')}}</th>
                                                            <th style="width:5%">Review</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                    @endif
                                                    @if($attachment->private != 1)
                                                        <tr align="center">
                                                            <td>{{$attachment->id}}</td>
                                                            <td class="text-left">{{$attachment->attachment}}</td>
                                                            <td>{{substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</td>
                                                            <td>{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                            <td>{{$attachment->UserName}} {{$attachment->SurName}}</td>
                                                            <td>{{$attachment->ParsedCreatedAt}}</td>
                                                            <td class="text-center">
                                                                <a href="{{route("uploads",[$attachment->attachment])}}"
                                                                   class="btn btn-primary btn-sm fa fa-eye"  data-toggle="tooltip" data-placement="top" title="See attach"
                                                                   target="_blank"></a>
                                                                    <i class="btn btn-sm btn-info jumpToComment fa fa-comment"
                                                                       data-id="{{$attachment->discussion_id}}" data-toggle="tooltip" data-placement="top" title="Go to comment"></i>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if($loop->last)
                                                        </tbody>
                                                    @endif

                                                @endforeach



                                            </table>
                                        </div>



                                    </div>

                                </div>
                            </div>
                        </div>
                </div>

                @endif
{{--                @if($ticket['status_id'] == 1 || $ticket['status_id'] == 2 || $ticket['status_id'] == 3 || $ticket['status_id'] == 4 || $ticket['status_id'] == 5 || $ticket['status_id'] == 8)--}}
{{--                    --}}
{{--                        <div class="form-label pl-3" style="padding-top: 10px;">--}}
{{--                            {{trans('words.add_attachment')}}--}}

{{--                            <span style="color:red">(max. File size 100 MB)</span>--}}

{{--                        </div>--}}
{{--                        <div class="row  pl-3">--}}
{{--                            <div class="col-lg-9 col-md-9">--}}
{{--                                <form action="{{url('/edit-ticket').'/'.$ticket->id}}" method="POST"--}}
{{--                                      id="ticketAttachments" class="dropzone" enctype="multipart/form-data"> @csrf--}}
{{--                                    <div id="attachmentResponse">--}}
{{--                                    </div>--}}
{{--                                </form>--}}


{{--                            </div>--}}
{{--                        </div>--}}
{{--                @endif--}}
{{--                <div class="row mb-3" id="buttonRow">--}}
{{--                    <div class="col-lg-9 col-md-9">--}}
{{--                        @if($ticket['status_id'] == 1 || $ticket['status_id'] == 2 || $ticket['status_id'] == 3 || $ticket['status_id'] == 4 || $ticket['status_id'] == 5 || $ticket['status_id'] == 8)--}}
{{--                            <button type="button" id="updateTicketButton"--}}
{{--                                    class="btn btn-success mt-1 mb-0 float-right">{{trans('words.save')}}</button>--}}
{{--                            <a href="{{url('/tickets')}}"--}}
{{--                               class="btn btn-danger mt-1 mb-0 mr-4 float-right">{{trans('words.cancel')}}</a>--}}
{{--                        @endif--}}

{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Comment/New Update</h3>
        </div>
        <div class="row">
            <div class="col-lg-10 col-md-10">


                <div class="card-body">

                    {{--if ticket status is done,invoiced and closed,customer must not make comment--}}
                    @if(($ticket["proofed"] != 1) && $ticket["status_id"] != 7 && $ticket["status_id"] != 9 )
                        <form id="discussionForm" class="mb-0">
                        @csrf
                        <div class="row">
                            <div class="col-lg-11 col-md-11">
                                <div class="form-group">
                                    <div class="input-group">
                                        <textarea id="discussion" placeholder="Please write your comment here..." name="discussion" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div id="discussionAttachmentResponse">
                            </div>


                    </form>
                            <div class="row" id="discussionAttachmentSwitch">


                                {{-- Comment Attachment --}}
                                <div class="col-lg-7 col-md-7">
                                    <div class="row pt-2">
                                        <div class="col-lg-9 col-md-9">
                                            <div class="form-group">

                                                <label class="custom-switch">

                                                    <input type="checkbox" id="discussionAttachmentToggle"
                                                           name="discussionAttachmentToggle" class="custom-switch-input">
                                                    <span class="custom-switch-indicator"></span>

                                                    <span class="custom-switch-description">Attach File <span style="color:red;">(Max. 5 Files | Max. File size: 50 MB)</span></span>

                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row" id="discussionAttacmentRow" style="display: none;">
                                        <div class="col-lg-12 col-md-12">
                                            <form class="dropzone" id="discussionAttachment"> @csrf</form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 ">
                                    <button type="button" id="sendDiscussion" class="btn btn-warning mt-1 mb-0 float-right">
                                        <i class="fe fe-edit"></i>{{trans('words.response')}}</button>

                                </div>

                            </div>
                    @endif
                    @foreach($ticket['discussion'] as $discussion)
                        <div class="row">
                            <div class="col-sm-11 col-md-11" id="discussion-section{{$discussion->id}}">

                                <div class="alert alert-light-private"
                                     style="border: 1px solid #dcd8c3;">

                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <strong class="pr-4">{{$discussion['UserName']}}</strong>
                                            <strong class="float-right">

                                                {{ \Carbon\Carbon::parse($discussion['created_at'])->format('d.m.Y [H:i:s]')}}</strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <hr class="message-inner-separator">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8">
                                            <p>{!!$discussion->message!!}</p>
                                        </div>
                                        @php
                                        $counter = 0;
                                        foreach ($ticket["discussion_attachments"] as $attachment){
                                            if($attachment->discussion_id!=null || $attachment->is_mail==1){
                                                if($attachment->discussion_id == $discussion->id){
                                                    $counter++;
                                             }
                                        }
                                        }

                                        @endphp
                                        @if($counter>0)
                                        <div class="col-md-4 col-lg-4 text-center">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12  border-bottom">
                                                    <span class="form-label"> Attachments </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="d-flex align-items-center flex-wrap">
                                                        @foreach($ticket["discussion_attachments"] as $attachment)
                                                            @if(($attachment->discussion_id!=null || $attachment->is_mail==1) && !$attachment->private){{-- Sadece comment atacmentları ve mail atachmentları--}}
                                                            @php
                                                                $filetype = \File::extension(storage_path("app/uploads/").$attachment->attachment);

                                                            @endphp

                                                            @if($attachment->discussion_id == $discussion->id)
                                                                <a href="{{route("uploads",[$attachment->attachment])}}" target="_blank">
                                                                    @if(Str::contains($filetype,["png","jpg","jpeg"]))
                                                                        <img class="mb-1 ml-1"
                                                                             src="{{route("uploads",[$attachment->attachment])}}"
                                                                             alt="" width="65" height="65">
                                                                    @elseif(Str::contains($filetype,["doc","docx"]))
                                                                        <img class="mb-1 ml-1"
                                                                             src="{{asset('/assets/images/fileicons/doc.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @elseif(Str::contains($filetype,["xls","xlsx"]))
                                                                        <img class="mb-1 ml-1 rounded"
                                                                             src="{{asset('/assets/images/fileicons/xls.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @elseif(Str::contains($filetype,["csv"]))
                                                                        <img class="mb-1 ml-1 rounded"
                                                                             src="{{asset('/assets/images/fileicons/csv.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @elseif(Str::contains($filetype,["zip"]))
                                                                        <img class="mb-1 ml-1"
                                                                             src="{{asset('/assets/images/fileicons/zip.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @elseif(Str::contains($filetype,["pdf"]))
                                                                        <img class="mb-1 ml-1"
                                                                             src="{{asset('/assets/images/fileicons/pdf.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @elseif(Str::contains($filetype,["video"]))
                                                                        <img class="mb-1 ml-1"
                                                                             src="{{asset('/assets/images/fileicons/play.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @else
                                                                        <img class="mb-1 ml-1"
                                                                             src="{{asset('/assets/images/fileicons/file.png')}}"
                                                                             alt="" width="65" height="65">
                                                                    @endif
                                                                </a>
                                                            @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            @endif
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
    </div><!-- end app-content-->
    </div>
@endsection
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{asset('drop-zone/dropzone.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>
    <script src="{{asset('text-editor/trumbowyg.min.js')}}"></script>


    <script>
        $('#discussion').trumbowyg({
            autogrow: true,
            removeformatPasted: true,
            defaultLinkTarget: '_blank',
        });
        $('#description').trumbowyg({
            autogrow: true,
            removeformatPasted: true,
            defaultLinkTarget: '_blank'
        });

        $('#attachmentToggle').on('change', function () {
            var isAttachment = $("#attachmentToggle").is(":checked");
            if (isAttachment) {
                $('#attachments').css('display', 'block');
            } else
                $('#attachments').css('display', 'none');

        });
        Dropzone.autoDiscover = false;
        $('#ticketAttachments').dropzone({
            maxFiles: 5,
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 100,
            timeout: 180000000,
            acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.zip,.rar,.msg,.7z,.tar,.waptt,.ogg,.waptt.opus',
            url: '/attachFiles',
            success: function (file, response) {

                if (response.error) {
                    toastr.error(response.error, 'Error');
                    $('#buttonRow').show();
                } else {
                    $.each(response.data, function (key, data) {
                        $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                        $('#attachmentResponse').append('<input type="hidden" name="ticketAttachments[' + data.size + ']" value="' + data.link + '"/>');
                    });
                    toastr.success(response.success, 'Success');
                    $('#buttonRow').show()
                }
            },
            init: function () {

                this.on("removedfile", function (file) {
                    var server_file = $(file.previewTemplate).children('.server_file').text();
                    $("#attachmentResponse input[value='" + server_file + "']").remove();
                });
                this.on("sending", function () {
                    $('#buttonRow').hide();
                });
            }
        });


        $(document).ready(function () {
            function resize() {
                var heightA = $('#informationDiv').outerHeight();
                $('#descriptionHeight').css('max-height', heightA - 20);
                $('#descriptionHeight').css('min-height', heightA - 20);
                $('#descriptionHeight').css('height', heightA - 20);
                $('#descriptionHeight').css('overflow', 'auto');
            }

            let left_bar = [$("#left-info-bar").width(), $("#left-info-bar").height()];
            let description_title = [$(".description-title").width(), $(".description-title").height()];
            $('.description-resize').css("max-height", left_bar[1] - description_title[1] - 3);
            resize();
            $(window).on('resize', function () {

                let left_bar = [$("#left-info-bar").width(), $("#left-info-bar").height()];
                let description_title = [$(".description-title").width(), $(".description-title").height()];
                $('.description-resize').css("max-height", left_bar[1] - description_title[1] - 3);
                resize();

            });


            $('#updateTicketButton').on('click', function () {
                $('#ticketAttachments').submit();
            });

            $('#discussion').parent().on("change keyup", function () {
                $(this).removeClass("error-border");
            });

            $('#sendDiscussion').on('click', function (e) {

                let sendButton = $(this);
                if (sendButton.data("running") !== 1) {
                    sendButton.data("running", 1);
                } else {
                    return;
                }

                if ($('#discussion').val() === "") {
                    $('#discussion').parent().addClass("error-border");
                    sendButton.data("running", 0);
                    return;
                }
                var form = $('#discussionForm');
                var url = '/create-discussion/' + '{{$ticket->id}}' +'?private=0';
                toggleLoader(true);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    async:false,
                    success: function (response) {
                        if (!response.error) {
                            document.getElementById('discussion').value = "";

                        }
                    }
                }).done(function (response){
                    window.location.reload();
                });
                toggleLoader(false);
            });
            $(document).on('click', '.deleteAttachment', function (e) {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: "GET",
                    url: '/removeAttachment/' + id,
                    success: function (response) {
                        if (!response.error) {
                            location.reload();
                        }
                        else {
                            toastr.error(response.error, 'Error');
                        }
                    }
                });
            });
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

            $("#discussionAttachmentToggle").prop("checked", false);

            $('#discussionAttachmentToggle').on("change", function () {
                var isAttachment = $(this).is(":checked");
                if (isAttachment) {
                    $('#discussionAttacmentRow').show(100);
                } else {
                    $('#discussionAttacmentRow').hide(100);
                    Dropzone.forElement('#discussionAttachment').removeAllFiles(true)
                    $("#discussionAttachmentResponse").html("");

                }
            });

        });


        $('#discussionAttachment').dropzone({
            maxFiles: 5,
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 50,
            timeout: 180000000,
            acceptedFiles: "{{\App\Helpers\Helper::accepted_files()}}",
            url: '/attachFiles',
            success: function (file, response) {

                if (response.error) {
                    toastr.error(response.error, 'Error');

                } else {

                    $.each(response.data, function (key, data) {
                        $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');

                        $('#discussionAttachmentResponse').append(
                            `<input type="hidden" name="discussionAttachments[${data.size}][link]" id="attachmentLink-${data.size}" value="${data.link}"/>`
                        );

                    });
                    toastr.success(response.success, 'Success');

                }
            },
            init: function () {

                this.on("removedfile", function (file) {
                    $("#attachmentLink-" + file.size).remove();

                });
                this.on("maxfilesexceeded", function (file) {
                    if (this.files.length >= 3) {
                        toastr.error("Maximum file must be 3!");
                    }
                    this.removeFile(this.files[3]);

                });


            }
        });



    </script>
@endsection
