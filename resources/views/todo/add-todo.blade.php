@extends('layouts.master')
@section('css')
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('text-editor/trumbowyg.min.css')}}">
    <link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">
    <style>
        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 140px;
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
            <div class="card-header  d-flex justify-content-between">
                <h3 class="card-title">{{ucfirst(trans('words.new'))}} Own {{ucfirst(trans('words.todo'))}}</h3>
                <a class="btn btn-info" href="{{route("todo.index")}}"><i
                            class="fa fa-backward mr-1"></i>Back</a>

            </div>
            <div class="card-body">
                <form id="formSection" action="{{route("todo.addtodo.post")}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row border-bottom">
                                <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.id'))}}<span
                                        class="text-danger">*</span> </label>
                                <div class="col-md-10">
                                    <input readonly type="text" name="todo_id" class="form-control"
                                           value="@if($last_todo) {{$last_todo->todo_number + 1 }} @else 1 @endif">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row border-bottom">
                                <label class="col-md-2 form-label my-auto">{{ucfirst(trans('words.subject'))}}<span class="text-danger">*</span> </label>
                                <div class="col-md-10">
                                    <input type="text" name="subject" class="form-control" placeholder="Subject" value="{{old('subject')}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row border-bottom">
                                <label
                                    class="col-md-2 form-label my-auto">{{ucfirst(trans('words.organization'))}} </label>
                                <div class="col-md-10">
                                    <select id="organization" type="text" name="org_id" class="form-control">


                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-6 col-md-6">

                            <div class="form-group row border-bottom">
                                <label
                                    class="col-md-2 form-label my-auto">{{ucfirst(trans('words.description'))}}</label>
                                <div class="col-md-10">
                                    <textarea id="description" name="description" class="form-control"
                                    >{{old('description')}}</textarea>
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
                                        @foreach($todo_status as $status)
                                            <option value="{{$status->id}}">{{$status->status}}</option>
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
                                    <input type="date" class="form-control" name="due_date" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="attachmentResponse">
                    </div>


                </form>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
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
                placeholder: 'Select an organization'

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

            $('#sendButton').on("click", function () {
                $('#formSection').submit();

            });


        });

    </script>
@endsection
