@extends('layouts.master')
@section('css')
<!--INTERNAL Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('text-editor/trumbowyg.min.css')}}">
<link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">
<style>
    .trumbowyg-box, .trumbowyg-editor {
        min-height: 80px!important;
        width: 100% !important;
    }

    .trumbowyg-editor{
        max-height: 450px !important;
        resize: vertical !important;
    }
	.trumbowyg-editor p img{
		width: 400px !important;
	}
	.border-bottom{
		border-bottom: 2px solid #ebecf1;
    	padding-bottom: 5px;
    	margin-bottom: 5px;
	}
	input[type="text"]{
		border: 1px solid #D5D5D5;
	}
	input[type="select"]{
		border: 1px solid #D5D5D5;
	}
	input[type="number"]{
		border: 1px solid #D5D5D5;
	}
	input[type="password"]{
		border: 1px solid #D5D5D5;
	}
    .error-border {
            border: 1px solid red !important;
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
				<h3 class="card-title">Create New Ticket</h3>
                <div style="text-align: right;">
                    <a href="{{url('/tickets')}}" class="btn btn-info"><i class="fa fa-backward mr-1"></i> Back </a>
                </div>
			</div>
			<div class="card-body">
				<form id="createTicket">
					@csrf
					<div class="row">
						<div class="col-lg-8 col-md-8">
							<div class="form-group row border-bottom ">
								<label class="col-md-2 form-label my-auto">{{trans('words.subject')}}<span class="text-danger">*</span> </label>
								<div class="col-md-10">
									<input type="text" id="subject" name="name" class="form-control" placeholder="Subject" >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-8">
							<div class="form-group row border-bottom">
								<label class="col-md-2 form-label my-auto">{{trans('words.description')}}<span class="text-danger">*</span> </label>
								<div class="col-md-10">
									<textarea id="description" name="description" class="form-control"></textarea>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div id="attachmentResponse">
						</div>
					</div>
				</form>
				<div class="form-label" style="padding-top: 10px;">
					{{trans('words.add_attachment')}}
					<span style="color:red">(max. 5 Files | max. File size 100 MB)</span>
				</div>
				@if(in_array('CREATE_TICKET_ATTACHMENT', auth()->user()->Permissions))
				<div class="row">
					<div class="col-md-8">
						<form class="dropzone" id="ticketAttachments"> @csrf</form>
					</div>
				</div>
				@endif
				<div class="row" id="buttonRow">
					<div class="col-lg-8 col-md-8">
						<button type="button" id="createTicketButton" class="btn btn-success mt-4 mb-0 float-right">{{trans('words.save')}}</button>

						<a href="{{url('/tickets')}}" class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{trans('words.cancel')}}</a>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
<!--INTERNAL Select2 js -->
<script src="{{asset('drop-zone/dropzone.js')}}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>
<script src="{{asset('text-editor/trumbowyg.min.js')}}"></script>

<script>
	$('#description').trumbowyg({
		autogrow: true,
		removeformatPasted: true,
         defaultLinkTarget:'_blank'
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
                }
                else {
                    $.each(response.data, function (key, data) {
                        $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                        $('#attachmentResponse').append(`<input type="hidden" name="ticketAttachments[${data.size}]" value="${data.link}"/>`);
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
              this.on("sending",function(){
                  $('#buttonRow').hide();
              });
            }
	});

    function valitate(){
        if($('#subject').val() ===""){

            $('#subject').addClass("error-border");
            toggleLoader(false);
            return false;
        }else if($('#description').val() === ""){
            $('#description').parent().addClass("error-border");
            toggleLoader(false);
            return false;
        }
        else {
            return true;
        }
    }
	$(document).ready(function() {

        $('#subject').on("change keyup",function (){
            $(this).removeClass("error-border");
        });
        $('#description').parent().on("change keyup",function (){
            $(this).removeClass("error-border");
        });

        $('#createTicketButton').on('click', function () {
            toggleLoader(true);
            if(valitate()) {
                let data = $('#createTicket').serialize();
                $.ajax({
                    url:'/create-ticket',
                    data:data,
                    type:"post",
                    dataType:"json",
                    success:function (response){
                        if(response !== undefined){
                            if(response.success === 1){
                                location.href = "/tickets";
                            }
                            else {
                                toggleLoader(false);
                            }
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
