@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('text-editor/trumbowyg.min.css')}}">
    <link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">
    <style>
        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 40px;
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

    </style>
@endsection
@section('page-header')



@endsection
@section('content')
    <!-- Row -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            {{$error}}
                        </div>
                    @endforeach
                @endif
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Own {{ucfirst(trans('words.todo'))}} #{{$todo->todo_number}}
                        | {{$todo->subject}} </h3>
                      <a class="btn btn-info" href="{{route("todo.index")}}"><i
                                class="fa fa-backward mr-1"></i>Back</a>
                </div>
                <div class="card-body">
                    <form id="formSection" action="{{route("todo.updatetodo.post")}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.id'))}}<span
                                            class="text-danger">*</span> </label>
                                    <div class="col-md-10">
                                        <input readonly type="text" name="todo_id" class="form-control"
                                               value="{{$todo->todo_number}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.subject'))}} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="subject" class="form-control"
                                               value="{{$todo->subject}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-2 form-label my-auto">{{ucfirst(trans('words.organization'))}}</label>
                                    <div class="col-md-10">
                                        <select id="organization" type="text" name="org_id" class="form-control">
                                            <option
                                                value="@if($todo->org_name != null){{ App\Organization::where('org_name', $todo->org_name)->first()->id }}@endif"
                                                selected>
                                                @if($todo->org_name != null){{$todo->org_name}}@endif
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-lg-6 col-md-6">

                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-2 form-label my-auto">{{ucfirst(trans('words.description'))}} </label>
                                    <div class="col-md-10">
                                        <textarea id="description" name="description" class="form-control"
                                        >{{$todo->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.status'))}} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select name="status" class="form-control">
                                            @foreach($todos_status as $status)
                                                <option value="{{$status->id}}"
                                                        @if($todo->status == $status->id) selected @endif>{{$status->status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.due_date'))}}</label>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" name="due_date" value="{{$todo->due_date}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                @if($todo_attachments)
                                    @php $attachment_count = count($todo_attachments); @endphp
                                    <div class="row pt-2">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                            <div class="form-group border">
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
                                                    </div>
                                                </div>
                                            <div class="row "
                                         id="attachments" {{$attachment_count == 0 ? "style=display:none;" : ""}}>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="table-responsive border">
                                                <table class="table table-bordered text-wrap" id="" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:5%">{{ucfirst(trans('words.id'))}}</th>
                                                        <th style="width:25%">{{ucfirst(trans('words.file_name'))}}</th>
                                                        <th style="width: 15%;">Extension</th>
                                                        <th style="width:15%">{{ucfirst(trans('words.file_size'))}}</th>
                                                        <th style="width:20%">{{ucfirst(trans('words.uploaded_date'))}}</th>
                                                        <th style="width:20%">{{ucfirst(trans('words.action'))}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($todo_attachments as $attachment)
                                                        <tr>
                                                            <td>{{$attachment->id}}</td>
                                                            <td>{{substr($attachment->attachment,0,20)}}</td>
                                                            <td>{{substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</td>
                                                            <td>{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                            <td>{{\Carbon\Carbon::parse($attachment->created_at)->format("d.m.Y H:i:s")}}</td>
                                                            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4)
                                                                <td>
                                                                    <i class="btn btn-danger deleteAttachment fa fa-trash"
                                                                       data-id="{{$attachment->id}}"></i> &nbsp;
                                                                    <a href="{{route("uploads",[$attachment->attachment])}}"
                                                                       class="btn btn-primary" target="_blank"
                                                                       style="padding: 2px; padding-left: 12px; padding-right: 12px;"><i
                                                                            class="fa fa-eye"></i></a>
                                                                </td>
                                                            @endif
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
                            </div>
                        </div>

                        <div id="attachmentResponse">
                        </div>
                    </form>
                    <div class="row  mt-1">
                        <div class="col-md-12 col-lg-12">
                            <label class="form-label">Attachments <span class="text-danger">{{auth()->user()->role_id==1?"(max. 5 Files | max. File size 100 MB)":"(max. 5 Files | max. File size 10 MB)"}}</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">

                                <form class="dropzone" id="todoAttachments"> @csrf</form>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 d-flex align-items-end justify-content-end">
                            <div>
                                <button id="sendButton"
                                        class="btn btn-success mt-4 mb-0 float-right">{{trans('words.save')}}</button>
                                <a href="{{url('/todos')}}"
                                   class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{trans('words.cancel')}}</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>
    </div><!-- end app-content-->
    </div>
@endsection
@section('js')

    <script src="{{asset('drop-zone/dropzone.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>
    <script src="{{asset('text-editor/trumbowyg.min.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function () {


            $('#description').trumbowyg({
                autogrow: true,
                removeformatPasted: true,
                btns:[
                    ['viewHTML'],
                    ['formatting'],
                    ['strong', 'em', 'del'],
                    ['link'],
                    ['insertImage'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ['unorderedList', 'orderedList'],
                    ['horizontalRule'],
                    ['fullscreen']
                ]
            });

            $("#organization").select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    }
                },
                allowClear: true,
                placeholder: {id: '', text: 'Select an organization', selected: 'selected'},


            });

            $('#attachmentToggle').on("change", function () {
                let checked = $(this).is(":checked");
                console.log(checked);
                if (checked) {
                    $('#attachments').show(100);
                } else {
                    $('#attachments').hide(100);
                }
            });

            let maxfilesize = 10;
            @if(auth()->user()->role_id == 1)
                 maxfilesize = 100;
            @endif

            $('#todoAttachments').dropzone({
                maxFiles: 5,
                parallelUploads: 10,
                uploadMultiple: true,
                addRemoveLinks: true,
                maxFilesize: maxfilesize,
                timeout: 180000000,
                acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.webm,.zip,.rar,.msg,.7z,.tar',
                url: '/attachFiles',
                success: function (file, response) {
                    if (response.error) {
                        toastr.error(response.error, 'Error');
                    } else {
                        $.each(response.data, function (key, data) {
                            $('#attachmentResponse').append('<input type="hidden" name="todoAttachments[' + data.size + ']" value="' + data.link + '"/>');
                        });
                        toastr.success(response.success, 'Success');
                    }
                }
            });
            $('.deleteAttachment').on('click',function (){
                let attach_id = $(this).data("id");
                confirmModal('Attachment will be delete!',"Are you sure?","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url:"/delete-todo-attachment/"+attach_id,
                        type:"get",
                        success:function (response){
                            if(response!==undefined && response!==""){
                                if(response.success === 1){
                                    toastr.success("Attachment deleted successfully!","Success");
                                    location.reload();
                                }
                                else{
                                    toastr.error("An error thrown!","Error");
                                }
                            }else{
                                toastr.error("An error thrown!","Error");
                            }
                        }
                    });
                });

            });



            $('#sendButton').on("click", function () {
                $('#formSection').submit();

            });

        });
    </script>
@endsection
