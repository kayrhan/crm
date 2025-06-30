@extends('layouts.master')
@section('css')

    <!-- Data table css -->

    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .cursor-default {
            cursor: default !important;
        }

        .table tr {
            cursor: default !important;
        }

        .badge-green {
            background: #089226;
        }

        .badge-general {
            background: #96A9B5;
        }

    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header mt-0 mb-1">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ trans('words.hi') }} {{ auth()->user()->first_name }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}"><i class="fe fe-home mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="">{{ trans('words.document_templates') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <button type="button" onclick="openUploadModal()" class="btn btn-primary"><i class="fe fe-plus"></i> {{ trans('words.upload_new_file') }}</button>

            </div>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        {{ trans('words.document_templates') }}
                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-bordered text-nowrap datatable-custom-row" id="dataTableID">
                        <thead>
                            <tr>
                                <th>{{trans('words.file_name')}}</th>
                                <th>{{trans('words.description')}}</th>
                                <th>{{trans('words.company')}}</th>
                                <th>{{trans('words.category')}}</th>
                                <th>{{trans('words.size')}}</th>
                                <th>{{trans('words.uploaded_from')}}</th>
                                <th>{{trans('words.uploaded_date')}}</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>{{trans('words.file_name')}}</th>
                                <th>{{trans('words.description')}}</th>
                                <th>{{trans('words.company')}}</th>
                                <th>{{trans('words.category')}}</th>
                                <th>{{trans('words.size')}}</th>
                                <th>{{trans('words.uploaded_from')}}</th>
                                <th>{{trans('words.uploaded_date')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End app-content-->
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('words.upload_new_file') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="uploadFile">
                        @csrf
                        <div class="form-group ">
                            <label class="form-label">
                                {{trans('words.upload_file_title')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control validate[required]" name="title" id="title" placeholder="{{trans('words.upload_file_title')}}" required>
                        </div>
                        <div class="form-group ">
                            <label class="form-label">
                                {{trans('words.company')}} <span class="text-danger">*</span>
                            </label>
                            <select type="text" class="form-control validate[required]" name="company" id="company" required>
                                <option value="" selected="selected">{{trans('words.select')}}</option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group ">
                            <label class="form-label">
                                {{trans('words.category')}} <span class="text-danger">*</span>
                            </label>
                            <select type="text" class="form-control validate[required]" name="category" id="category" required>
                                <option value="" selected="selected">{{trans('words.select')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group ">
                            <label class="form-label">
                                {{trans('words.file')}} <small class="text-muted">(pdf,xls,doc)</small> <span class="text-danger">*</span>
                            </label>

                            <input type="file" class="form-control validate[required]" name="file" id="file" placeholder="{{trans('words.upload_select_file')}}" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/msword
,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf
" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{trans('words.close')}}</button>
                    <button type="button" onclick="uploadFile()" class="btn btn-primary">{{trans('words.upload_new_file')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')


    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js?v=2') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/js/numberFormat.js') }}"></script>
    <script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>



        function documentTemplates() {
            // disable sorting when clicking search input
            $('thead tr input').on("click", function(e) {
                e.stopPropagation();
            });
            var table = $('#dataTableID').DataTable({
                initComplete: function() {
                    let input = '<input type="text" class="form-control form-control-sm" placeholder="Search"  />';
                    let input_date = '<input type="date" class="form-control form-control-sm" placeholder="Search"  />';
                    this.api().columns([0,1,4,5]).every(function () {
                        let column = this;

                        $(input)
                            .appendTo($(this.header()).empty())
                            .on("keyup", function () {
                                let val = $(this).val();
                                column.search(val, true, false).draw();
                            });
                    });
                    this.api().columns([6]).every(function () {
                        let column = this;

                        $(input_date)
                            .appendTo($(this.header()).empty())
                            .on("change", function () {
                                let val = $(this).val();
                                column.search(val, true, false).draw();
                            });
                    });
                    this.api().columns([2]).every(function () {
                        let column = this;
                        let input="<select class='form-control form-control-sm'><option value=''>Alle</option>";
                        @foreach($companies as $company)
                            input+='<option value="{{$company->name}}">{{$company->name}}</option>';
                        @endforeach
                        input+='</select>';
                        $(input)
                            .appendTo($(this.header()).empty())
                            .on("change", function () {
                                let val = $(this).val();

                                column.search(val, true, false).draw();
                            });
                    });
                    this.api().columns([3]).every(function () {
                        let column = this;
                        let input="<select class='form-control form-control-sm'><option value=''>Alle</option>";
                        @foreach($categories as $category)
                            input+='<option value="{{$category->name}}">{{$category->name}}</option>';
                        @endforeach
                            input+='</select>';
                        $(input)
                            .appendTo($(this.header()).empty())
                            .on("change", function () {
                                let val = $(this).val();

                                column.search(val, true, false).draw();
                            });
                    });
                },
                "processing": true,
                "serverSide": true,
                "stateSave": false,
                "destroy": true,
                "paging": true,
                "order":false,
                "ajax": {
                    url: "/document-templates",
                    type: "GET",
                },
                select: true,

                "columns": [
                    {
                        "data": "orginal_file_name",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            var icon = '';
                            if(data) {
                                if(row['type']=='doc' || row['type']=='docx'){
                                    icon = '<img class="mb-1 ml-1" src="/assets/images/fileicons/doc.png" alt="" width="16">'
                                } else if(row['type']=='xlsx' || row['type']=='xls'){
                                    icon = '<img class="mb-1 ml-1" src="/assets/images/fileicons/xls.png" alt="" width="16">'
                                } else if(row['type']=='pdf'){
                                    icon = '<img class="mb-1 ml-1" src="/assets/images/fileicons/pdf.png" alt="" width="16">'
                                } else{
                                    icon = '<img class="mb-1 ml-1" src="/assets/images/fileicons/file.png" alt="" width="16">'
                                }
                                return icon+" "+data;
                            }
                            else {
                                return "-";
                            }
                        }
                    },
                    {
                        "data": "title",
                        "visible": true,
                        "orderable": false
                    },
                    {
                        "data": "company",
                        "visible": true,
                        "orderable": false
                    },
                    {
                        "data": "category",
                        "visible": true,
                        "orderable": false
                    },
                    {
                        "data": "size",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if(data){
                                return data+' MB';
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "user",
                        "visible": true,
                        "orderable": false
                    },
                    {
                        "data": "created_at",
                        "visible": true,
                        "orderable": false
                    },
                    {
                        "data": "actions",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if(data) {
                                return '<a class="btn btn-sm btn-primary" href="/uploads/'+row['file']+'/'+row['orginal_file_name']+'" target="_blank"><i class="fa fa-download"></i></a><button class="ml-1 btn btn-sm btn-danger" onclick="deleteFile('+row['id']+')"><i class="fa fa-times"></i></button>'
                            }
                            else {
                                return "-";
                            }
                        }
                    }
                ],

                "iDisplayLength": 25,
                "language": {
                    "thousands": ".",
                    "processing": "<i class='fa fa-refresh fa-spin'></i>",
                    @if(Session::get('applocale')=='gn')
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json",
                    @elseif(Session::get('applocale')=='tr')
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Turkish.json",
                    @else
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json",
                    @endif
                },

                fixedColumns:true,

            });



        }

        function resetDataTable() {

            $('#dataTableID').DataTable().clear();
            $('#dataTableID').DataTable().destroy();

        }

        documentTemplates();

        function openUploadModal(){
            $('#title').val('');
            $('#company').val('');
            $('#category').val('');
            $('#file').empty();
            $('#uploadModal').modal('show');
        }

        function uploadFile() {

            var formData = new FormData($('#uploadFile')[0]);
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

            var formid = 'uploadFile';
            var validate = $('#'+formid).validationEngine('validate',{ scroll: false });
            if (!validate) {
                return false;
            }
            else {
                toggleLoader(true);
                $.ajax({
                    url: '/document-templates/upload',
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    dataType:'JSON',
                    cache: false,
                    timeout: 800000,
                    data: formData
                }).done(function (data) {
                    $('#uploadModal').modal('hide');
                    $('#dataTableID').DataTable().ajax.reload();
                    toastr.success("{{trans('words.upload_file_success_title')}}", "{{trans('words.upload_file_success_text')}}");
                    toggleLoader(false);
                }).error(function(data) {
                    toastr.error("{{trans('words.upload_file_error_title')}}", "{{trans('words.upload_file_error_text')}}");
                    toggleLoader(false);
                });
            }

        }

        function deleteFile(id) {

            Swal.fire({
                title: '{{trans('words.confirm_delete_file')}}',
                showDenyButton: true,
                showCancelButton: false,
                text: '{{trans('words.confirm_delete_file_text')}}',
                confirmButtonText: '{{trans('words.delete_yes_button')}}',
                denyButtonText: `{{trans('words.delete_no_button')}}`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    toggleLoader(true);

                    $.ajax({
                        url: '/document-templates/delete',
                        type: "post",
                        data: {id: id, _token: '{{csrf_token()}}'}
                    }).done(function (data) {
                        $('#uploadModal').modal('hide');
                        $('#dataTableID').DataTable().ajax.reload();
                        toastr.success("{{trans('words.delete_file_success_title')}}", "{{trans('words.delete_file_success_text')}}");
                        toggleLoader(false);
                    }).error(function (data) {
                        toastr.error("{{trans('words.delete_file_error_title')}}", "{{trans('words.delete_file_error_text')}}");
                        toggleLoader(false);
                    });
                }
            })
        }

    </script>
@endsection
