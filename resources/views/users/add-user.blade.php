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
    .password-eye {
        pointer-events: unset;
        position: absolute;
        top: 8px;
        right: 24px;
    }
    .password-eye:hover {
        cursor: pointer !important;
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
				<h3 class="card-title">{{ucfirst(trans('words.add_user'))}}</h3>
				<div>
					<a href="{{$org_id ? url("/organizations/".$org_id."/edit") : url('/users')}}" class="btn btn-info">
						<i class="fa fa-backward mr-1"></i> {{ucfirst(trans('words.back'))}}
					</a>
				</div>
			</div>
			<div class="card-body">
                <div class="row ">

                    <div class="col-md-12 ">
                        <form action="{{url('/create-user')}}" method="post">
                            @csrf
                            <div class="row pb-7">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class=" w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.first_name'))}} <span class="text-danger">*</span> </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{old('first_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.last_name'))}} <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{old('last_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.organization'))}} <span class="text-danger">*</span></label>

                                                @if(auth()->user()->role_id == 5 || auth()->user()->role_id == 6)
                                                    <div class="col-md-9">
                                                        <input type="text" name="organization_name" class="form-control" placeholder="Organization" value="{{$organizations[0]->org_name}}" disabled="disabled">

                                                        <input type="hidden" name="organization" class="form-control" placeholder="Organization" value="{{$organizations[0]->id}}">
                                                    </div>
                                                @else
                                                    <div class="col-md-9">
                                                        <select name="organization" id="organization" class="form-control select2">
                                                            <option disabled selected>Select Organization</option>
                                                            @foreach($organizations as $organization)
                                                                @if($org_id != null)
                                                                    @if($organization->id == $org_id)
                                                                        <option value="{{$organization->id}}" selected>{{$organization->org_name}}</option>
                                                                    @endif
                                                                @else
                                                                    <option value="{{$organization->id}}">{{$organization->org_name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="org_id" value="{{$org_id}}">
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.role'))}} <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    @if(auth()->user()->role_id == 5 || auth()->user()->role_id == 6)
                                                        <select name="role" class="form-control custom-select select2">
                                                            <option value="" disabled selected>Select Role</option>
                                                            <option value="5">Firma Admin</option>
                                                            <option value="6">Firma User</option>
                                                        </select>
                                                    @else
                                                        <select name="role" id="role" class="form-control custom-select select2">

                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(in_array(auth()->user()->role_id,[1,2,3]))
                                    <div class="row" id="positionType">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">Speciality <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="position_type" class="form-control custom-select select2">
                                                        <option selected disabled >Select Position</option>
                                                        @foreach ($position_types as $position_type)
                                                            <option value="{{ $position_type->id }}">
                                                                {{ $position_type->type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.email'))}} <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{old('email')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.phone'))}}</label>
                                                <div class="col-md-9">
                                                    <input type="number" name="phone" class="form-control" placeholder="Phone" value="{{old('phone')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.gsm'))}}</label>
                                                <div class="col-md-9">
                                                    <input  name="gsm" class="form-control" placeholder="Gsm" value="{{old('gsm')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.password'))}}</label>
                                                <div class="col-md-9">
                                                    <input type="password" id="password" name="password" class="form-control">
                                                    <img class="password-eye" id="show-password" src="{{ asset("images/icons/third-login-icon.png") }}" alt="Show Password Icon" onclick="switchPasswordType(0, this.id)">
                                                    <img class="password-eye d-none" id="hide-password" src="{{ asset("images/icons/fourth-login-icon.png") }}" alt="Hide Password Icon" onclick="switchPasswordType(0, this.id)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="w-100">
                                            <div class="form-group row border-bottom">
                                                <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.confirm'))}} {{ucfirst(trans('words.password'))}}</label>
                                                <div class="col-md-9">
                                                    <input type="password" id="password-confirmation" name="confirm_password" class="form-control">
                                                    <img class="password-eye" id="show-password-confirmation" src="{{ asset("images/icons/third-login-icon.png") }}" alt="Show Password Icon" onclick="switchPasswordType(1, this.id)">
                                                    <img class="password-eye d-none" id="hide-password-confirmation" src="{{ asset("images/icons/fourth-login-icon.png") }}" alt="Hide Password Icon" onclick="switchPasswordType(1, this.id)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-4">
                                    <div style="height: 210px"></div>
                                    <div class="row">
                                        <div class="w-100 ">
                                            @unless(auth()->user()->role_id === 5)
                                            <div class="form-group row ">
                                                <label class=" form-label col-md-4 ">Internal Info: </label>
                                                <div class="col-md-11">
                                        <textarea name="description" id="description"  class="form-control"
                                                  rows="9" >{{old('description')}}</textarea>
                                                </div>
                                            </div>
                                            @endunless
                                            <div class="row ">
                                                <div class=" w-100 text-center d-flex justify-content-end ">
                                                    <button type="submit"  class="btn btn-primary  mt-4 mb-0" style="margin-right: 60px"><i class="fa fa-check"></i> {{ucfirst(trans('words.save'))}}</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('js')
<script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2.js')}}"></script>
<script>
    function switchPasswordType(operation, id) {
        if(operation === 0) {
            let password = document.getElementById("password");

            if(id === "show-password") {
                $("#show-password").addClass("d-none");
                $("#hide-password").removeClass("d-none");
                password.style.fontSize = "0.82rem";
                password.type = "text";
            }
            else {
                $("#show-password").removeClass("d-none");
                $("#hide-password").addClass("d-none");
                password.style.fontSize = "0.82rem";
                password.type = "password";
            }
        }
        else {
            let password = document.getElementById("password-confirmation");

            if(id === "show-password-confirmation") {
                $("#show-password-confirmation").addClass("d-none");
                $("#hide-password-confirmation").removeClass("d-none");
                password.style.fontSize = "0.82rem";
                password.type = "text";
            }
            else {
                $("#show-password-confirmation").removeClass("d-none");
                $("#hide-password-confirmation").addClass("d-none");
                password.style.fontSize = "0.82rem";
                password.type = "password";
            }
        }
    }

$(document).ready(function() {
	$("#organization").select2();
	$("#role").select2();


    $(document).on("change","#organization",function (){
        let value = $(this).val();
        $("#role").html("<option disabled selected>Select Role</option>");
        $.ajax({
           url:"{{route("add-user.getRoles")}}",
           type:"post",
           data:"org_id="+value+"&_token="+"{{csrf_token()}}",
            success:function (response){
               if(response !== "" && response !== undefined){
                   for(let i = 0;i<response.length;i++) {
                       $("#role").append("<option value='"+response[i].id+"'>"+response[i].name+"</option>");
                   }
               }
            }
        });

    });
    $('#organization').trigger("change");
});

$('#organization').on('change', function (){
    if($(this).val() == 8 || $(this).val() == 3|| $(this).val() == 6){
        $('#positionType').show()
        $('#positionLevel').show()
    }else{
        $('#positionType').hide()
        $('#positionLevel').hide()
    }
})
</script>

@endsection
