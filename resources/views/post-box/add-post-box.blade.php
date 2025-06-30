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
    <!--Page header-->
    <!-- <div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">Add Ticket</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/tickets')}}"><i class="fe fe-file-text mr-2 fs-14"></i>Tickets</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Add Ticket</a></li>
		</ol>
	</div>
</div> -->

    <!--End Page header-->
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

                      <h3 class="card-title">{{ucfirst(trans('words.create_new_post'))}}</h3>

                        </div>
                        <div class="col-lg-6 col-md-6">

                        <div style="display: flex; flex-direction: row-reverse;">
                            <a href="{{url('/post-box')}}" class="btn btn-info"><i
                                    class="fa fa-backward mr-1"></i> {{ucfirst(trans('words.back'))}} </a>
                        </div>
                            </div>

                </div>

                <div class="card-body">
                    <form action="{{route("post-box.add-post-box.post")}}" method="POST" id="post-box-form">
                        @csrf


                        <div class="row">
                            <div class="col-lg-8 col-md-8">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-2 form-label my-auto">#{{ucfirst(trans('words.id'))}} </label>
                                    <div class="col-md-10">
                                        <input disabled type="text" name="post_id" class="form-control" placeholder="ID"
                                               value="{{$counter}}">
                                    </div>
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
                                               >
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
                                        <input  id="received_date" type="date" name="received_date" class="form-control" >
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="attachmentResponse">
                        </div>

                        <div class="form-label" style="padding-top: 10px;">
                            {{ucfirst(trans('words.add_attachment'))}} <span class="text-danger">*</span>

                            <span style="color:red"><span style="color:red;">(max. 5 File | max. File size 5 MB)</span></span>
                        </div>
                        <input type="submit" id="submitHidden" style="opacity:0;z-index:-1;">
                    </form>
                    <div class="row">
                        <div class="col-md-8">
                            <form class="dropzone" id="ticketAttachments"> @csrf</form>
                        </div>
                    </div>
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
                    isFileUploaded = 0;// validation

                });

            }
        });


        $(document).ready(function () {

            $('#consignor').on("keyup change",function (){
               $('#consignor_badge').html("");
            });
            $('#received_date').on("keyup change",function (){
                $('#date_badge').html("");
            });


            $('#save_button').on('click', function () {
                console.log(isFileUploaded);
                let isConsigner = $('#consignor').val().trim();
                let isReceivedDate = $('#received_date').val();
                console.log(isReceivedDate);
                if (isConsigner === '') {
                    $('#consignor_badge').html("Consignor is required!");

                } else if(isReceivedDate === ""){
                    $('#date_badge').html('Received Date is required!');
                }
                else if(isFileUploaded !== 1) {

                     toastr.error("File required!", "Error!");

                }

                else{
                     $('#post-box-form').find('[type="submit"]').trigger('click');
                }
            });



        });

    </script>
@endsection
