@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->

    <link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">
    <style>


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
            <div class="row">

                <div class="col-lg-9 col-md-9">

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            {{$error}}
                        </div>
                    @endforeach
                @endif

                <div class="card-header row" style="border: 0!important;">

                        <div class="col-lg-2 col-md-2" >

                    <h3 class="card-title">{{ucfirst(trans('words.update_post'))}}</h3>

                        </div>
                        <div class="col-lg-6 col-md-6">

                        <div style="display: flex; flex-direction: row-reverse;">
                            <a href="{{url('/post-box')}}" class="btn btn-info"><i
                                    class="fa fa-backward mr-1"></i> {{ucfirst(trans('words.back'))}} </a>
                        </div>
                            </div>

                </div>



                <div class="card-body">
                    <form action="{{route("post-box.update-post-box.post")}}" method="POST" id="post-box-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8 col-md-8">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">#{{ucfirst(trans('words.id'))}} </label>
                                    <div class="col-md-10">
                                        <input disabled type="text" name="post_id" class="form-control" placeholder="ID"
                                               value="{{sprintf("%04d",$post->id)}}">
                                    </div>
                                    <input type="hidden" value="{{$post->id}}" name="post_id">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 col-md-8">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-2 form-label my-auto">{{ucfirst(trans('words.consignor'))}} <span class="text-danger">*</span> </label>
                                    <div class="col-md-10">
                                        <span id="consignor_badge" class="badge badge-danger"></span>
                                        <input id="consignor" type="text" name="consignor" class="form-control" placeholder="Consignor"
                                              value=" {{$post->consignor}}" >
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-lg-8 col-md-8">

                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-2 form-label my-auto">{{ucfirst(trans('words.received_date'))}} <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <span id="date_badge" class="badge badge-danger"></span>
                                        <input value="{{$post->received_date}}" id="received_date" type="date" name="received_date" class="form-control" >
                                    </div>
                                </div>
                            </div>
                        </div>







                        @if($attachment)

                            <div class="row " id="attachments" <?php
                            if ($attachment == null) { ?> style="display:none;" <?php } ?>>
                                <div class="col-lg-8 col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-wrap" id="" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th style="width:5%">{{ucfirst(trans('words.id'))}}</th>
                                            <th style="width:20%">{{ucfirst(trans('words.file_name'))}}</th>
                                            <th style="width:10%">Extension</th>
                                            <th style="width:10%">{{ucfirst(trans('words.file_size'))}}</th>
                                            <th style="width:20%">{{ucfirst(trans('words.uploaded_from'))}}</th>
                                            <th style="width:20%">{{ucfirst(trans('words.uploaded_date'))}}</th>
                                            <th style="width:25%">{{ucfirst(trans('words.action'))}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            <tr data-name="{{$attachment->attachment}}" class="new_tab" id="attach{{$attachment->id}}">

                                                <td>{{$attachment->id}}</td>
                                                <td>{{substr($attachment->attachment,0,20)}}</td>
                                                <td>{{substr($attachment->attachment, strrpos($attachment->attachment, '.')+1)}}</td>
                                                <td>{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                @php
                                                    $user = App\User::find($attachment->add_by);
                                                @endphp
                                                <td>{{$user->first_name}} {{$user->surname}}</td>
                                                <td>{{\Carbon\Carbon::parse($attachment->created_at)->format('d.m.Y H:i:s')}}</td>
                                                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4)
                                                    <td align="center"><i class="btn btn-danger deleteAttachment fa fa-trash"
                                                           data-id="{{$attachment->id}}"></i> &nbsp;

                                                    </td>

                                                @endif

                                            </tr>

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($attachment ==null)
                        <div id="attachmentResponse">
                        </div>

                        <div class="form-label" style="padding-top: 10px;">
                            {{ucfirst(trans('words.add_attachment'))}} <span class="text-danger">*</span>

                            <span style="color:red">(max. 5 File | max. File size 5 MB)</span>
                        </div>
                        <input type="submit" id="submitHidden" style="opacity:0;z-index:-1;">
                    </form>
                    <div class="row">
                        <div class="col-md-8">
                            <form class="dropzone" id="ticketAttachments"> @csrf</form>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-8 col-md-8">
                            <button type="button"
                                    class="btn btn-success mt-4 mb-0 float-right"
                                    id="save_button">{{trans('words.save')}}</button>
                            <a href="{{url('/post-box')}}"
                               class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{trans('words.cancel')}}</a>
                        </div>
                    </div>

                </div>
            </div>

                       <div class="col-lg-3 col-md-3">
                        <div class="d-flex flex-row-reverse m-5">
                            <div class="flex-column bg-light p-3">
                                @php
                                $add_by_user = \App\User::find($post->add_by);
                                $updated_by_user = \App\User::find($post->updated_by);

                                @endphp
                                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    @if($add_by_user !=null)
                                    <tr><td>Post Created From</td><td>&nbsp;:&nbsp;</td><td>{{$add_by_user->first_name." ".$add_by_user->surname}} </td></tr>
                                    <tr><td>Post Created On</td><td>&nbsp;:&nbsp;</td><td>{{\Carbon\Carbon::parse($post->created_at)->format("d.m.Y H:i:s")}}</td></tr>
                                    @endif
                                    <tr><td colspan="3">&nbsp;</td></tr>
                                    @if($updated_by_user)
                                    <tr><td>{{ucfirst(trans("words.last_updated_from"))}}</td><td>&nbsp;:&nbsp;</td><td>{{$updated_by_user->first_name}} {{$updated_by_user->surname}}</td></tr>
                                    <tr><td>{{ucfirst(trans("words.last_updated_on"))}}</td><td>&nbsp;:&nbsp;</td><td>{{$post->updated_at ? \Carbon\Carbon::parse($post->updated_at)->format("d.m.Y H:i:s") : ""}}</td></tr>
                                    @else
                                    <tr><td>{{ucfirst(trans("words.last_updated_from"))}}</td><td>&nbsp;:&nbsp;</td><td align="center">-</td></tr>
                                    <tr><td>{{ucfirst(trans("words.last_updated_on"))}}</td><td>&nbsp;:&nbsp;</td><td align="center">-</td></tr>
                                    @endif
                                </table>
</div>
                        </div>
                    </div>
                        </div>



            </div>
        </div>

    </div>

      <div class="modal fade" id="deleteAttachmentModal" tabindex="-1" role="dialog" aria-labelledby="deleteAttachmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAttachmentModalLabel">Delete Attachment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this attachment?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" id="delete_attachment_accept" class="btn btn-secondary ">Delete</button>
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



    <script>
@if($attachment == null)
        var dp = 1;
        var isFileUploaded = 0;
        $('#ticketAttachments').on('click', function () {
            dp = 1;
        });
        Dropzone.autoDiscover = false;
        $('#ticketAttachments').dropzone({
            maxFiles: 5,
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 5,
            timeout: 180000000,
            acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.xlsx,.xlsm,.xltx,.xlsb',
            url: '/attachFiles',
            success: function (file, response) {
                if (response.error)
                    toastr.error(response.error, 'Error');
                else {
                    $.each(response.data, function (key, data) {
                        $('#attachmentResponse').append('<input type="hidden" name="ticketAttachments[' + data.size + ']" value="' + data.link + '"/>');
                    });
                    toastr.success(response.success, 'Success');
                    isFileUploaded = 1;
                }
            },
            init: function () {
                a = dp;

                this.on("maxfilesexceeded", function (file) {
                    if (this.files.length >= 1) {
                        if (dp === 1) {
                            toastr.error("Maximum file must be 1!");
                            dp = 0;
                        }
                    }
                    this.removeFile(this.files[1]);
                    isFileUploaded = 1;
                });
                this.on("removedfile", function (file) {
                    isFileUploaded = 0;
                });

            }
        });

        @endif


        $(document).ready(function () {

            @if($attachment)
            $(document).on('click','.deleteAttachment',function (){

                let attach_id = $(this).attr("data-id");
                $('#delete_attachment_accept').attr("data-id",attach_id);
                $('#deleteAttachmentModal').modal("show");

            });
            $(document).on('click','#delete_attachment_accept',function (){
                let attach_id = $(this).attr("data-id");
                $.ajax({
                    url:"/post-box/deleteAttachment/"+attach_id,
                    type:"get",
                    dataType:"json",
                    success:function (response){
                        if(response !== "" && response !== "undefined"){
                            if(response.status === 1){
                                location.reload();
                            }

                        }
                    }
                });

            });
            @endif



            $('#save_button').on('click', function () {
                let isConsigner = $('#consignor').val().trim();
                let isReceivedDate = $('#received_date').val();


                if (isConsigner === '' || isConsigner === null) {
                    $('#consignor_badge').html("Consignor is required!");


                } else if(isReceivedDate === ""){
                    $('#date_badge').html('Received Date is required!');

                }
                @if($attachment==null)
                else if(isFileUploaded !== 1) {

                     toastr.error("File required!", "Error!");

                }


                else{
                     $('#post-box-form').find('[type="submit"]').trigger('click');
                }
                @else
                else {
                    $('#post-box-form').submit();
                }
                @endif
            });

            $('.new_tab td').not(':last-child').on('click',function(){
                let name = $(this).parent().attr('data-name');

                window.open('uploads/'+name,'_blank');
            });

        });

    </script>
@endsection
