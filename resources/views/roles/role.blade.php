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
		<h4 class="page-title mb-0">{{ucfirst(trans('words.user'))}}</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/roles')}}"><i class="fe fe-file-text mr-2 fs-14"></i>{{ucfirst(trans('words.user'))}}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{$role->name}}</a></li>
		</ol>
	</div>
	<div class="page-rightheader">
		<div class="btn btn-list">
			@if(in_array('UPDATE_ROLE', auth()->user()->Permissions))
			<a href="{{url('/update-role').'/'.$role->id}}" class="btn btn-warning"><i class="fe fe-edit mr-1"></i>{{ucfirst(trans('words.edit'))}}</a>
			@endif
			@if(in_array('DELETE_ROLE', auth()->user()->Permissions))
			@if(!$role->system)
			<a href="#" data-toggle="modal" id="deleteRoleModal" class="btn btn-danger"><i class="fe fe-trash mr-1"></i> {{ucfirst(trans('words.delete'))}} </a>
			@endif
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
			<div class="card-header">
				<h3 class="card-title">{{ucfirst(trans('words.role_information'))}}</h3>
			</div>
			<div class="card-body">

				<div class="row">
					<div class="col-lg-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{ucfirst(trans('words.name'))}}</label>
							<div class="input-group">
								<span>{{$role->name}}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4">
						<div class="form-group">
							<label class="form-label">{{ucfirst(trans('words.description'))}}</label>
							<div class="input-group">
								<span>{{$role->role_desc}}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-header">
				<h3 class="card-title">{{ucfirst(trans('words.permission'))}}</h3>
			</div>
			<div class="card-body">
				<div class="row">
					@foreach($role['permissions'] as $permission)
					<div class="col-lg-6 col-md-6">
						<div class="form-group">
							<div class="input-group">
								<span>{{$permission->permissionSlug}}</span>
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
    $("#deleteRoleModal").on("click",function (){
        confirmModal('{{ucfirst(trans('words.delete_message'))}}?','{{ucfirst(trans('words.deleted_organization'))}}','{{ucfirst(trans('words.delete'))}}','{{ucfirst(trans('words.close'))}}',"#0275d8","#d9534f").then(function() {
            var id = $('#deleteRoleId').val();
            var url = '/delete-role/' + id;
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function(response) {
                    if (!response.error) {
                        window.location.href = "/roles";
                        $('#message').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response + '</div>');
                    }
                }
            });
        });
    });

</script>
@endsection