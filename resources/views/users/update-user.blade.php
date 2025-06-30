@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <style>
        .border-bottom {
            border-bottom: 1px solid #ebecf1;
            padding-bottom: 7px;
            margin-bottom: 7px;
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
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ ucfirst(trans('words.update_user')) }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/users') }}"><i
                            class="fe fe-file-text mr-2 fs-14"></i>{{ ucfirst(trans('words.user')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $user->first_name }}
                        {{ $user->surname }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                @if (auth()->user()->role_id == 1)
                    @if (in_array('UPDATE_USER', auth()->user()->Permissions))
                    @endif
                    @if (in_array('DELETE_USER', auth()->user()->Permissions))
                        <a class="btn btn-danger" id="delete-user-modal"><i class="fe fe-trash mr-1"></i> {{ ucfirst(trans('words.delete')) }} </a>
                    @endif

                    <a class="btn btn-info" id="reset-password-btn"><i class="fe fe-rotate-ccw mr-1"></i> {{ ucfirst(trans('words.reset_password')) }} </a>
                        @if(auth()->user()->role_id == 1)
                            <a href="{{ url('login-from-user') . '/' . $user->id }}" class="btn btn-primary"><i class="fe fe-user-check mr-1"></i> {{ ucfirst(trans('words.login_from_user')) }} </a>
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
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">{{ ucfirst(trans('words.update_user')) }}</h3>
                    <div>
                        <a href="{{ $org_id ? url('/organizations/' . $org_id . "/edit") : url('/users') }}" class="btn btn-info">
                            <i class="fa fa-backward mr-1"></i> {{ ucfirst(trans('words.back')) }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form style="height: 500px" action="{{ url('/edit-user') . '/' . $user->id }}" method="post">
                    <div class="row">
                        <div class="col-lg-7 col-md-7  ">

                                @csrf
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.first_name')) }}
                                                            <span class="text-danger">*</span> </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="first_name" class="form-control"
                                                                   value="{{ $user->first_name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.last_name')) }}
                                                            <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="last_name" class="form-control"
                                                                   value="{{ $user->surname }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.organization')) }}
                                                            <span class="text-danger">*</span></label>
                                                        @if (auth()->user()->role_id == 5 || auth()->user()->role_id == 6)
                                                            <div class="col-md-9">
                                                                <input type="text" name="organization_name" class="form-control"
                                                                       placeholder="Organization" value="{{ $organizations[0]->org_name }}"
                                                                       disabled="disabled">

                                                                <input type="hidden" name="organization" class="form-control"
                                                                       placeholder="Organization" value="{{ $organizations[0]->id }}">
                                                            </div>
                                                        @else
                                                            <div class="col-md-9">
                                                                <select name="organization" id="organization" class="form-control select2">
                                                                    <option disabled selected>Select Organization</option>
                                                                    @foreach ($organizations as $organization)
                                                                    @if ($org_id != null)
                                                                            @if ($organization->id == $org_id)
                                                                                <option value="{{ $organization->id }}"
                                                                                    {{ $user->org_id == $organization->id ? 'selected' : '' }}>
                                                                                    {{ $organization->org_name }}
                                                                                </option>
                                                                            @endif
                                                                        @else
                                                                            <option value="{{ $organization->id }}"
                                                                                {{ $user->org_id == $organization->id ? 'selected' : '' }}>
                                                                                {{ $organization->org_name }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="org_id" value="{{ $org_id }}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.role')) }} <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            @if(auth()->user()->role_id == 5)
                                                                <select name="role" class="form-control custom-select select2">
                                                                    <option value="5" {{ $user->role_id == 5 ? 'selected' : '' }}>Firma Admin</option>
                                                                    <option value="8" {{ $user->role_id == 8 ? 'selected' : '' }}>Firma Personnel Admin</option>
                                                                    <option value="6" {{ $user->role_id == 6 ? 'selected' : '' }}>Firma User</option>
                                                                </select>
                                                            @else
                                                                <select name="role" id="role" class="form-control custom-select select2"></select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(in_array(auth()->user()->role_id,[1,2,3]))
                                            <div class="row" id="positionType">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">Speciality <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                                <select name="position_type" class="form-control custom-select select2">
                                                                    <option selected disabled >Select speciality</option>
                                                                    @foreach ($position_types as $position_type)
                                                                                <option value="{{ $position_type->id }}"
                                                                                    {{ $user->position_type == $position_type->id ? 'selected' : '' }}>
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
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.email')) }} <span
                                                                class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="email" name="email" class="form-control"
                                                                   value="{{ $user->email }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.phone')) }}</label>
                                                        <div class="col-md-9">
                                                            <input type="number" name="phone" class="form-control"
                                                                   value="{{ $user->phone_no }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ucfirst(trans('words.gsm'))}}</label>
                                                        <div class="col-md-9">
                                                            <input name="gsm" class="form-control" placeholder="Gsm" value="{{$user->gsm}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.active')) }}</label>
                                                        <div class="col-md-9">
                                                            <label class="custom-switch">
                                                                <input type="checkbox" id="userToggle" name="userToggle"
                                                                       class="custom-switch-input" {{($user->in_use? "checked": "") }}>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row border-bottom">
                                                        <label
                                                            class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.get_emails')) }}</label>
                                                        <div class="col-md-9">
                                                            <label class="custom-switch">
                                                                <input type="checkbox" id="emailToggle" name="emailToggle"
                                                                       class="custom-switch-input" {{($user->get_mail?"checked":"")}}>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2">
                                            <div class="row mt-md-9">
                                                <div class="w-100 mt-md-8">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                        </div>
                        <div class="col-md-1" ></div>
                        <div class="col-lg-4 col-md-4">
                            <div class="d-flex justify-content-end  ">
                                <div class="flex-column w-100">
                                    <div class="card-body ">
                                        @unless(auth()->user()->role_id === 5)
                                        <div class="latest-timeline scrollbar3 " id="scrollbar3">
                                            <ul class="timeline mb-0 " style="overflow-y: scroll;max-height: 400px;">
                                                @if($user->created_at)
                                                    <li class="mt-0">
                                                        <div class="d-flex"><span
                                                                class="time-data">User Created </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($user->created_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from <span
                                                                class="text-info">{{$user["add_by_name"] != null ? $user["add_by_name"]: "-"}}</span>
                                                        </p>
                                                    </li>
                                                @else
                                                    <li class="mt-0">
                                                        <div class="d-flex">
                                                            <span class="time-data">User Created </span>
                                                            <span class="ml-auto text-muted fs-11">-</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from
                                                            <span class="text-info">-</span>
                                                        </p>
                                                    </li>
                                                @endif
                                                @if($user->updated_at)
                                                    <li class="mb-1 updated_log">
                                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($user->updated_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13 mb-1">Last updated from <span
                                                                class="text-info">{{$user["update_by_name"] != null ? $user["update_by_name"]: "-"}}</span>
                                                        </p>
                                                    </li>
                                                @else
                                                    <li class="mb-1 updated_log">
                                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                                class="ml-auto text-muted fs-11">-</span></div>
                                                        <p class="text-muted fs-12">Last updated from <span
                                                                class="text-info">-</span></p>
                                                    </li>
                                                @endif
                                            </ul>
                                            <div class="form-group row mt-md-8 ">
                                                <label class=" form-label col-md-8 ">Internal Info: </label>
                                                <div class="col-md-12">
                                        <textarea name="description"  id="description" class="form-control" rows="5">{{$user->description}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endunless
                                        <div class="row ">
                                            <div class="col-md-12 d-flex text-center justify-content-end ">
                                                <button  type="submit" class="btn btn-primary mt-2 mb-0"><i class="fa fa-check"></i>{{ ucfirst(trans('words.save')) }}</button>
                                            </div>
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

    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ ucfirst(trans('words.reset_password')) }}</h5>
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
                                    <label class="form-label">{{ ucfirst(trans('words.new_password')) }}</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control validate[required,funcCall[checkStrongPassword]]]">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ ucfirst(trans('words.confirm_password')) }}</label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control validate[equals[password]]]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">{{ ucfirst(trans('words.close')) }}</button>
                            <button type="submit" class="btn btn-success">{{ ucfirst(trans('words.reset')) }}</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    </div>

@endsection
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#organization").select2();
            $("#role").select2();
            var user_role_id = {{ $user->role_id }};
            $(document).on("change", "#organization", function() {
                let value = $(this).val();
                $("#role").html("")
                $.ajax({
                    url: "{{ route('add-user.getRoles') }}",
                    type: "post",
                    data: "org_id=" + value + "&_token=" + "{{ csrf_token() }}",
                    success: function(response) {
                        if (response !== "" && response !== undefined) {
                            for (let i = 0; i < response.length; i++) {
                                let is_select = "";
                                if (response[i].id === user_role_id) {
                                    is_select = "selected";
                                } else {
                                    is_select = "";
                                }

                                $("#role").append("<option value='" + response[i].id + "' " +
                                    is_select + " >" + response[i].name + "</option>");
                            }
                        }
                    }
                });

            });
            $('#organization').trigger("change");

            $("#reset-password-btn").on("click",function (){
                $('#resetPasswordModal').modal('show');
            });
            $("#delete-user-modal").on("click",function (){
                confirmModal('{{ ucfirst(trans('words.delete_user_message')) }}',"Delete User",'{{ ucfirst(trans('words.delete')) }}','{{ ucfirst(trans('words.close')) }}',"#0275d8","#d9534f").then(function() {
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
                                window.location.href = "/users";
                            }
                        }
                    });
                });

            });

        });


        $('#userToggle').on('change', function() {
            var isActive = $("#userToggle").is(":checked") ? 1 : 0;
            $.ajax({
                type: "GET",
                url: '/updateUserStatus/' + {{$user->id}} + '?status=' + isActive,
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
                url: '/updateEmailStatus/' + {{$user->id}} + '?status=' + sendEmail,
                success: function(response) {
                    if (!response.error) {
                        toastr.success(response.success, 'Success');
                    }
                }
            });
        });

        function checkStrongPassword(field, rules, i, options){
            console.log(rules);
            console.log(i);
            console.log(options);
            let is_valid = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,16}$/.test($(field).val());

            if (!is_valid) {

                return "* Your password must be 8-16 characters that includes at least 1 uppercase, 1 digit and 1 special charachters!";
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
                var url = '/reset-password/{{$user->id}}';
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
