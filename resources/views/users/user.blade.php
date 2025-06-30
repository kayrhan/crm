@extends('layouts.master')
@section('css')
<!--INTERNAL Select2 css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">User</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/users')}}"><i class="fe fe-file-text mr-2 fs-14"></i>Users</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{$user->first_name}} {{$user->surname}}</a></li>
		</ol>
	</div>
	<div class="page-rightheader">
		<div class="btn btn-list">
		 @if(auth()->user()->role_id ==1)
			@if(in_array('UPDATE_USER', auth()->user()->Permissions))
			<a href="{{url('/update-user').'/'.$user->id}}" class="btn btn-warning"><i class="fe fe-edit mr-1"></i> Edit </a>
			@endif
			@if(in_array('DELETE_USER', auth()->user()->Permissions))
			<a href="#" data-toggle="modal" id="deleteUserModal" class="btn btn-danger"><i class="fe fe-trash mr-1"></i> Delete </a>
			@endif

			<a href="#" data-toggle="modal" data-target="#resetPasswordModal" class="btn btn-info"><i class="fe fe-rotate-ccw mr-1"></i> Reset Password </a>
			<a href="{{url('login-from-user').'/'.$user->id}}" class="btn btn-primary"><i class="fe fe-user-check mr-1"></i> Login from user </a>
			@endif
		</div>
	</div>
</div>
<!--End Page header-->
@endsection
@section('content')
<!-- Row -->
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card">
			@if($message)
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
				</button>
				{{$message}}
			</div>
			@endif
			<div class="card-header">
				<h3 class="card-title">User Information</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 border pt-5 bg-theme text-white">
						<div class="form-group">
							<label class="form-label">First Name</label>
							<div class="input-group">
								<span>{{$user->first_name}}</span>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 border pt-5">
						<div class="form-group">
							<label class="form-label">Last Name</label>
							<div class="input-group">
								<span>{{$user->surname}}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 border pt-5">
						<div class="form-group">
							<label class="form-label">Role</label>
							<div class="input-group">
								<span>{{$user->roleName}}</span>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 border pt-5">
						<div class="form-group">
							<label class="form-label">Organization</label>
							<div class="input-group">
								<a href="{{url('/organization').'/'.$user->org_id}}"><span>{{$user->organizationName}}</span></a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 border pt-5">
						<div class="form-group">
							<label class="form-label">Phone</label>
							<div class="input-group">
								<span>{{$user->phone_no}}</span>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 border pt-5">
						<div class="form-group">
							<label class="form-label">Email</label>
							<div class="input-group">
								<span>{{$user->email}}</span>
							</div>
						</div>
					</div>
				</div>

				@if(auth()->user()->role_id ==1)
				<div class="row pt-5">
					<div class="col-xl-6 col-lg-6 col-md-6 ">
						<div class="form-group">
							<div class="form-label">Active</div>
							<label class="custom-switch">
								<input type="checkbox" id="userToggle" name="userToggle" class="custom-switch-input" <?php if ($user->in_use) echo 'Checked'; ?>>
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description"></span>
							</label>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6">
						<div class="form-group">
							<div class="form-label">Get Emails</div>
							<label class="custom-switch">
								<input type="checkbox" id="emailToggle" name="emailToggle" class="custom-switch-input" <?php if ($user->get_email) echo 'Checked'; ?>>
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description"></span>
							</label>
						</div>
					</div>
				</div>
				@endif


			</div>
		</div>
	</div>
</div>
</div>
</div><!-- end app-content-->
</div>

<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Reset User Password</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="resetPassword">
					@csrf
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="form-group">
								<label class="form-label">New Password</label>
								<div class="input-group">
									<input type="password" name="new_password" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12">
							<div class="form-group">
								<label class="form-label">Confirm Password</label>
								<div class="input-group">
									<input type="password" name="confirm_password" class="form-control">
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success">Reset</button>
			</div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('js')
<!--INTERNAL Select2 js -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/js/datatables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>
<script>
    $("#deleteUserModal").on("click",function (){
        confirmModal('Are you sure you want to delete this user?',"Delete User","Delete","Close","#0275d8","#d9534f").then(function() {
            var id = {{$user->id}};
            var url = '/delete-user/' + id;
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function(response) {
                    if (!response.error) {
                        $('#message').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response + '</div>');
                        window.location.href = "/users";
                    }
                }
            });
        })
    })

	$('#resetPassword').on('submit', function(e) {
		e.preventDefault();
		var form = $('#resetPassword');
		var url = '/reset-password/' + <?php echo $user->id; ?>;
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(),
			success: function(response) {
				if (!response.error) {
					$("#resetPassword")[0].reset();
					$('#resetPasswordModal').modal('hide');
					toastr.success(response.success, 'Success');
				} else {
					toastr.error(response.error, 'Error');
				}
			}
		});
	});
	$('#userToggle').on('change', function() {
		var isActive = $("#userToggle").is(":checked") ? 1 : 0;
		$.ajax({
			type: "GET",
			url: '/updateUserStatus/' + <?php echo $user->id; ?> + '?status=' + isActive,
			success: function(response) {
				if (!response.error) {
					toastr.success(response.success, 'Success');
				}
			}
		});
	});
	$('#emailToggle').on('change', function() {
		var sendEmail = $("#emailToggle").is(":checked") ? 1 : 0;
		$.ajax({
			type: "GET",
			url: '/updateEmailStatus/' + <?php echo $user->id; ?> + '?status=' + sendEmail,
			success: function(response) {
				if (!response.error) {
					toastr.success(response.success, 'Success');
				}
			}
		});
	});
</script>
@endsection