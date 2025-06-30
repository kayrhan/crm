@extends('layouts.master')
@section('css')
        <!-- Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet"/>
    <!--INTERNAL Select2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('drop-zone/dropzone.css')}}">

    <style>
        .error-border {
            border: 1px solid red;
        }
        table th{

            font-size: 14px!important;
        }
         #usersTable_filter{
            display:none!important;
        }
    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{$update == 1?"Update":"Add"}} External Partner</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>
                        External Partners</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">External Partners</a></li>
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

                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">External partner information</h3>
                    <div>
                        <a href="{{url('/external-partners')}}" class="btn btn-info"><i
                                class="fa fa-backward mr-1"></i>{{ucfirst(trans('words.back'))}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-2 form-label my-auto pr-0">Organization Name
                                            <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" name="organization_name" id="organization-name"
                                                   class="form-control privateValidateControl"
                                                   placeholder="Organization Name"
                                                   value="{{$update==1?$partner->organization_name:""}}">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-2 form-label my-auto">Email
                                            <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" name="email" id="email"
                                                   class="form-control privateValidateControl"
                                                   placeholder="Email" value="{{$update == 1?$partner->email:""}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-2 form-label my-auto">Phone
                                            <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" name="phone" id="phone"
                                                   class="form-control privateValidateControl"
                                                   placeholder="Phone"
                                                   value="{{$update==1?$partner->phone:""}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-2 form-label my-auto">Address
                                        </label>
                                        <div class="col-md-10">
                                            <input type="text" name="address" id="address" class="form-control"
                                                   placeholder="Address" value="{{$update == 1?$partner->address:""}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12">

                                    <div class="row">
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group row">
                                                <label
                                                    class="col-md-6 form-label my-auto">City
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" name="city" id="city" class="form-control"
                                                           placeholder="City"
                                                           value="{{$update==1 ? $partner->city:""}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group row">
                                                <label
                                                    class="col-md-6 form-label my-auto">Zip Code
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="number" id="zip-code" name="zip_code"
                                                           class="form-control"
                                                           placeholder="Zip Code"
                                                           value="{{$update == 1?$partner->zip_code:""}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-group row">
                                                <label
                                                    class="col-md-6 form-label my-auto">Rating
                                                </label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="rating" id="rating">
                                                        <option value=""></option>
                                                        <option
                                                            value="1" {{$update==1?($partner->rating==1?"selected":""):""}}>
                                                            Good Partner
                                                        </option>
                                                        <option
                                                            value="2" {{$update==1?($partner->rating==2?"selected":""):""}}>
                                                            Normal Partner
                                                        </option>
                                                        <option
                                                            value="3" {{$update==1?($partner->rating==3?"selected":""):""}}>
                                                            Blacklist Partner
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-label">Comment</label>
                                        <div class="col-md-10">
                                                <textarea id="comment" rows="8" name="comment"
                                                          class="form-control">
                                                         {{ $update == 1?$partner->comment:"" }}
                                                </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($update==1)
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <label class=" form-label">Attachments</label>
                                        <table class="table table-bordered text-wrap w-100" id="">
                                            <thead>
                                            <tr>
                                                <th class="w-5">{{ucfirst(trans('words.id'))}}</th>
                                                <th class="w-25">{{ucfirst(trans('words.file_name'))}}</th>
                                                <th class="w-10">Extension</th>
                                                <th class="w-10">Size</th>

                                                <th class="w-25">{{ucfirst(trans('words.uploaded_from'))}}</th>
                                                <th class="w-20">{{ucfirst(trans('words.uploaded_date'))}}</th>
                                                <th class="w-5">{{ucfirst(trans('words.action'))}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($attachments as $attachment)
                                                <tr>
                                                    <td>{{$attachment->id}}</td>
                                                    <td>
                                                        <a href="{{route("uploads",[$attachment->name])}}"
                                                           class="link text-primary" target="_blank"
                                                        >{{substr($attachment->name,0,20)}}</a></td>
                                                    <td>{{substr($attachment->name, strrpos($attachment->name, '.')+1)}}</td>
                                                    <td>{{round($attachment->size * 0.000001, 2)}} MB</td>
                                                    @php
                                                        $user = App\User::where("id",$attachment->add_by)->first();
                                                    @endphp
                                                    <td>{{$user->first_name}} {{$user->surname}}</td>
                                                    <td>{{$attachment->created_at}}</td>
                                                    <td align="center">
                                                        <i class="btn btn-sm btn-danger fa fa-trash delete-attachment"
                                                           data-id="{{$attachment->id}}"></i>
                                                    </td>
                                                </tr>

                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <label class="form-label" for="comment">Attachments <span style="color:red;">(max. 5 Files | max. File size 100 MB)</span></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <form class="dropzone"
                                                          id="external-partner-attachment"> @csrf</form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                    <a class="btn btn-secondary mt-4 mr-2 mb-0" href="/external-partners">Cancel</a>
                                    <button type="button" class="btn btn-success mt-4 mb-0 mr-2 send-button">Save
                                    </button>
                                    @if($update == 1)
                                        <button type="button" data-save-and-close="1" class="btn btn-outline-success send-button mt-4 mb-0">Save & Close</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($update==1)
                            <div class="col-md-6 col-lg-6">

                                <div class="row">
                                    <div class="col-md-12 col-lg-12 ">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered nowrap datatable-custom-row "
                                                   id="usersTable" width="100%" style="margin-top: 0!important;">
                                                <thead>
                                                <tr>
                                                    <th class="w-14 border-bottom-0">Name</th>
                                                    <th class="w-13 border-bottom-0">Surname</th>
                                                    <th class="w-20 border-bottom-0">Email</th>
                                                    <th class="w-15 border-bottom-0">Gsm</th>
                                                    <th class="w-15 border-bottom-0">Fax No.</th>
                                                    <th class="w-20 border-bottom-0">Position</th>
                                                    <th class="w-3 border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                                                </tr>

                                                <tr>
                                                    <th class="w-14 border-bottom-0">Name</th>
                                                    <th class="w-13 border-bottom-0">Surname</th>
                                                    <th class="w-20 border-bottom-0">Email</th>
                                                    <th class="w-15 border-bottom-0">Gsm</th>
                                                    <th class="w-15 border-bottom-0">Fax No.</th>
                                                    <th class="w-20 border-bottom-0">Position</th>
                                                    <th class="w-3  border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                                                </tr>

                                                </thead>
                                                <tbody id="dataTableTbody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row mt-2">
                                    <div class="col-md-12 col lg-12 d-flex justify-content-end">
                                        <button class="btn btn-sm btn-info" id="add-contact">Add Contact</button>
                                    </div>
                                </div>

                            </div>
                        @endif

                    </div>


                    <form id="attachmentResponse">

                    </form>

                </div>
            </div>
        </div>
    </div>
    @if($update == 1)

<!-- Modal add contact -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contact-modal-title">Add Contact</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
         <form action="/external-partners/add-contact" method="post" id="contact-form">
             @csrf
      <div class="modal-body">

                <div class="form-group row">
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                    </div>
                     <div class="col-md-9 col-lg-9">
                        <input type="text" class="form-control form-control-sm" name="contact_name" id="contact-name" placeholder="Name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">Surname <span class="text-danger">*</span></label>
                    </div>
                     <div class="col-md-9 col-lg-9">
                        <input type="text" class="form-control form-control-sm" name="contact_surname" id="contact-surname" placeholder="Surname" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">E-Mail <span class="text-danger">*</span></label>
                    </div>
                     <div class="col-md-9 col-lg-9">
                        <input type="email" class="form-control form-control-sm" name="contact_email" id="contact-email" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">Gsm</label>
                    </div>
                     <div class="col-md-9 col-lg-9">
                        <input type="text"  class="form-control form-control-sm" name="contact_gsm" id="contact-gsm" placeholder="Gsm">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">Fax No.</label>
                    </div>
                     <div class="col-md-9 col-lg-9">
                        <input type="text" class="form-control form-control-sm" name="contact_fax_no" id="contact-fax-no" placeholder="Fax No.">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">Position <span class="text-danger">*</span></label>
                    </div>
                     <div class="col-md-9 col-lg-9">
                         <select type="text" class="form-control form-control-sm" name="contact_position" id="contact-position" required>
                             <option value="" disabled selected>Select Position</option>
                             <option value="1">Purchasing</option>
                             <option value="2">Consultant</option>
                             <option value="3">Technical Service</option>
                             <option value="4">System Administrator</option>
                         </select>
                    </div>
                </div>
                <input type="hidden" value="{{$partner->id}}" name="partner_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Save</button>

      </div>
              </form>
    </div>
  </div>
</div>

        @endif
        </div><!-- end app-content-->
        </div>
@endsection
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('drop-zone/dropzone.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>
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
    <script>
        Dropzone.autoDiscover = false;
        const emailPattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        function someFunction() {
            var output = document.getElementById("comment").value;
            output = output.trimStart().trimEnd();
            document.getElementById("comment").innerHTML = output;
        }

        $(document).ready(function () {
            someFunction();

            $('#external-partner-attachment').dropzone({
                maxFiles: 5,
                parallelUploads: 1,
                uploadMultiple: true,
                addRemoveLinks: true,
                maxFilesize: 100,
                timeout: 180000000,
                acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.webm,.zip,.rar,.msg,.7z,.tar',
                url: '/attachFiles',
                success: function (file, response) {
                    if (response.error) {
                        toastr.error(response.error, 'Error');
                    } else {
                        $.each(response.data, function (key, data) {
                            $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                            $('#attachmentResponse').append('<input type="hidden" name="external_partner_attachments[' + data.size + ']" value="' + data.link + '"/>');
                        });
                        toastr.success(response.success, 'Success');
                    }
                },
                init: function () {

                    this.on("removedfile", function (file) {
                        var server_file = $(file.previewTemplate).children('.server_file').text();
                        $("#attachmentResponse input[value='" + server_file + "']").remove();
                    });

                }
            });

            function valitate() {
                let organization_name = $("#organization-name");
                let email = $('#email');
                let phone = $('#phone');
                if (organization_name.val() === "") {
                    organization_name.addClass("error-border");
                    return false;
                }

                let test = emailPattern.test($.trim(email.val()));

                if (email.val() === "" || test === false) {
                    email.addClass("error-border");
                    return false;
                }
                if (phone.val() === "") {
                    phone.addClass("error-border");
                    return false;
                }

                return true;
            }

            $('.send-button').on("click", function () {

                if (valitate()) {

                    let organization_name = $("#organization-name").val();
                    let email = $('#email').val();
                    let phone = $('#phone').val();
                    let address = $('#address').val();
                    let city = $("#city").val();
                    let zip_code = $('#zip-code').val();
                    let comment = $('#comment').val();
                    let rating = $('#rating').val();
                    let attachments = JSON.stringify($('#attachmentResponse').serializeArray());


                    let url = @if($update==1) "/external-partners/update-post"
                    @else "/external-partners/add" @endif ;
                    let id = @if($update==1) {{$partner->id}} @else "" @endif ;
                    let save_close = $(this).data("save-and-close");
                    $.ajax({
                        url: url,
                        data: "id=" + id + "&organization_name=" + organization_name + "&email=" + email + "&phone=" + phone + "&address=" + address + "&city=" + city + "&zip_code=" + zip_code + "&rating=" + rating + "&comment=" + comment + "&external_partner_attachments=" + attachments + "&_token=" + "{{csrf_token()}}&"+"save_close="+save_close,
                        type: "post",
                        success: function (response) {
                            if(response) {
                                @if($update==1)
                                if (response.save_close==1)
                                    window.location.href = "/external-partners";
                                else
                                    window.location.href = "/external-partners/update/" + response.id;
                                @else
                                    window.location.href = "/external-partners";
                                @endif
                            }
                        }
                    });


                }
            });

            $(".privateValidateControl").on("keyup change", function () {
                $(this).removeClass("error-border");
            });

            @if($update == 1)
            $(document).on('click', '.delete-attachment', function () {
                let attach_id = $(this).data("id");
                confirmModal('Are you sure you want to delete this attachment?',"Delete attachment!","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url: "/external-partners/attachement/delete/" + attach_id,
                        type: "get",
                        success: function (response) {
                            if (response.success === 1) {
                                location.reload();
                            } else {
                                toastr.error("An error thrown!", "Error");
                            }
                        }
                    })
                });
            });

            function usersDataTable() {
                $('thead tr input').on("click", function (e) {
                    e.stopPropagation();
                });
                var dt = $("#usersTable").DataTable(
                    {
                        initComplete: function () {


                        },

                        "processing": false,
                        "serverSide": true,
                        "paging": false,
                        "info":false,
                        "ordering":false,
                        "ajax": {
                            url: "/external-partners/get-partner-contacts/{{$partner->id}}",
                            type: "get",
                        },
                        select: true,
                        "columns": [{
                            "data": "name",
                            "visible": true,
                            "orderable": false,
                            render: function (data, type, row) {
                                if (data)
                                    return data;
                                else
                                    return '-';
                            }
                        },
                             {
                                "data": "surname",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        return data;

                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {
                                "data": "email",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        return data;

                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {
                                "data": "gsm",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        return data;

                                    } else {
                                        return "-";
                                    }
                                }
                            },

                            {
                                "data": "fax_no",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        return data;

                                    } else {
                                        return "-";
                                    }
                                }
                            },

                            {
                                "data": "position",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        if(data === 1)
                                            return "Purchasing";
                                        if(data === 2)
                                            return "Consultant";
                                        if(data === 3)
                                            return "Technical Service";
                                        if(data === 4)
                                            return "System Administrator"
                                        return data;

                                    } else {
                                        return "-";
                                    }
                                }
                            },

                            {
                                "data": "actions",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        return "<i class='btn btn-sm btn-danger fa fa-trash delete-contact-btn' data-contact-id='"+data+"'></i>";

                                    } else {
                                        return "-";
                                    }
                                }
                            },

                        ],

                        "iDisplayLength": 10,
                        "language": {
                            "thousands": ".",
                            "processing": "<i class='fa fa-refresh fa-spin'></i>",
                        }

                    }
                );


                dt.on('click', 'tbody tr td:not(:last-child) ', function (e) {
                    let data = dt.row($(this).parents('tr')).data();
                    let user_id = data.id;
                    $("#contact-modal").modal("show");
                    $("#contact-modal-title").html("Update Contact");
                    $.ajax({
                        url:"/external-partners/get-user-info/"+user_id,
                        type:"get",
                        success:function (response){
                            $("#contact-name").val(response.name);
                            $("#contact-surname").val(response.surname);
                            $("#contact-email").val(response.email);
                            $("#contact-gsm").val(response.gsm);
                            $("#contact-fax-no").val(response.fax_no);
                            $("#contact-position").val(response.position);
                            $("#contact-form").attr("action","/external-partners/update-contact/"+response.id);
                        }
                    });
                });

            }

            $('#usersTable thead tr:eq(1) th').each(function (i) {

                    var html = '';
                    html = '<input type="text" class="form-control form-control-sm" placeholder="Search"  />';
                    if(i === 6)
                        html = "";
                    $(this).html(html);
                    $('input', this).on('keyup change', function () {
                        if ($('#usersTable').DataTable().column(i).search() !== this.value) {
                            $('#usersTable').DataTable()
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });

            });

            usersDataTable();

            $("#add-contact").on("click",function (){
               $("#contact-form")[0].reset();
               $("#contact-modal").modal("show");
               $("#contact-modal-title").html("Add Contact");
               $("#contact-form").attr("action","/external-partners/add-contact");

            });

            $(document).on("click",".delete-contact-btn",function (){
               let contact_id = $(this).data("contact-id");
                confirmModal('Are you sure you want to delete this contact?',"Delete contact!","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url:"/external-partners/delete-contact",
                        data:"contact_id="+contact_id+"&_token={{csrf_token()}}",
                        type:"post",
                        success:function (response){
                            if(response.success === 1){
                                location.reload();
                            }
                            else{
                                toastr.error("An error thrown!","Error!");
                            }
                        }
                    });
                });
            });

            @endif

        });


    </script>
@endsection
