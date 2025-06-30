@extends('layouts.master')
@section('css')
<!--INTERNAL Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<style>
	.border-bottom{
		border-bottom: 1px solid #ebecf1;
    	padding-bottom: 7px;
    	margin-bottom: 7px;
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
</style>

@endsection
@section('page-header')
<!--Page header-->
<!-- <div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">Add User</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>Users</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Add User</a></li>
		</ol>
	</div>
</div> --><br>
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
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
				</button>
				{{$error}}
			</div>
			@endforeach
			@endif
			<div class="card-header d-flex justify-content-between">
				<h3 class="card-title">Reset Password</h3>
				<div>
					<a href="{{url('/users')}}" class="btn btn-info"><i class="fa fa-backward mr-1"></i> Back </a>
				</div>
			</div>
			<div class="card-body">
				<form id="resetPassword">
					@csrf
					<div class="row">
						<div class="col-lg-2 col-md-2">
							&nbsp;
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<label class="form-label">New Password</label>
								<div class="input-group">
									<input type="password" name="password" id="password" class="form-control validate[required,funcCall[checkStrongPassword]]]">
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<label class="form-label">Confirm Password</label>
								<div class="input-group">
									<input type="password" id="confirm_password" name="confirm_password" class="form-control validate[equals[password]]]">
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-lg-12 text-center">
							<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Reset Password</button>
						</div>
					</div>

			</form>
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
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>

<script>

    function checkStrongPassword(field, rules, i, options){
        console.log(rules);
        console.log(i);
        console.log(options);
        const passwordPattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&\.])[A-Za-z\\d@$!%*?&\.]{8,}$";

        const passwordRegex = new RegExp(passwordPattern);
        const password = field.val();
        const isStrongPassword = passwordRegex.test(password);

        if (!isStrongPassword) {

            return "* Your password must be 8-16 characters that includes at least 1 uppercase, least lowercase, 1 digit and 1 special characters (@$!%*?&.)!";
        }
    }

$('#resetPassword').on('submit', function(e) {
		e.preventDefault();

    var form = $('#resetPassword');
        let valid = form.validationEngine("validate",{
            promptPosition : "topLeft",
            scroll: false,
            custom_error_messages: {
                "#confirm_password":{
                    "equals":{
                        "message":"* Password does not match!"
                    }
                },
            }
        }
        );

        if(valid) {
            var url = '/reset-password/{{$id}}';
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                beforeSend:function (){
                    toggleLoader(true);
                },
                success: function (response) {
                    if (!response.error) {
                        $("#resetPassword")[0].reset();
                        $('#resetPasswordModal').modal('hide');
                        setTimeout(()=>{
                            toggleLoader(false);
                            toastr.success(response.success, 'Success');
                        },1000);
                    }
                    else {

                        setTimeout(()=>{
                            toggleLoader(false);
                            toastr.error(response.error, 'Error');
                        },1000);
                    }
                }
            });
        }
	});








$(document).ready(function() {
	$("#organization").select2();
	$("#role").select2();
});
</script>



@endsection
