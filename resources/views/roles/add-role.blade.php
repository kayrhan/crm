@extends('layouts.master')
@section('css')
<!--INTERNAL Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ucfirst(trans('words.add_role'))}}</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>{{ucfirst(trans('words.roles'))}}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ucfirst(trans('words.add_role'))}}</a></li>
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
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
				</button>
				{{$error}}
			</div>
			@endforeach
			@endif
			<div class="card-header">
				<h3 class="card-title">{{ucfirst(trans('words.role_information'))}}</h3>
			</div>
			<div class="card-body">
				<form action="{{url('/create-role')}}" method="POST" id="createRole">
					@csrf
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="form-group">
								<label class="form-label">
								{{ucfirst(trans('words.role_name'))}}</label>
								<div class="input-group">
									<input type="text" name="name" class="form-control" placeholder="John Corporate" value="{{old('name')}}">
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="form-group">
								<label class="form-label">{{ucfirst(trans('words.description'))}}</label>
								<div class="input-group">
									<input type="text" name="description" class="form-control" placeholder="Role for xyz user" value="{{old('description')}}">
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
					@foreach($permissions as $index=>$permission)
					<div class="col-lg-6 col-md-6">
						<div class="form-group">
							<label class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" <?php if (old('permissions')) {
																																			if (in_array($permission->id, old('permissions'))) echo 'checked';
																																		} ?>>
								<span class="custom-control-label">{{$permission->name}}</span>
							</label>
						</div>
					</div>
					@endforeach
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<button type="submit" class="btn btn-primary mt-4 mb-0 float-right">{{ucfirst(trans('words.save'))}}</button>
					</div>
				</div>
				</form>
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
@endsection