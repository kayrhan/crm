@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet"/>
    <!-- Slect2 css -->
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>

@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="#">{{ucfirst(trans('words.todolist'))}}</a></li>
            </ol>

        </div>


    </div>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                @if(Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close">×</button>
                        <i class="fa fa-file mr-2" aria-hidden="true"></i><span
                            class="white">{{ session()->get('success') }}</span>
                    </div>
                @endif
                <div class="card-header" style="display: flex;justify-content: space-between;">
                    <div class="card-title">Own Todo's</div>

                    <div class="btn btn-list">
                        <a href="{{url('/add-todo')}}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> New Own To-do </a>
                    </div>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap datatable-custom-row" id="todosTable" width="100%">
                            <thead>
                            <tr align="center">
                                <th class="border-bottom-0">#{{ucfirst(trans('words.todo_id'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.subject'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.organization'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.due_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.description'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                            </tr>
                            <tr align="center">
                                <th class="border-bottom-0">#{{ucfirst(trans('words.todo_id'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.subject'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.organization'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.due_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
                                 <th class="border-bottom-0">{{ucfirst(trans('words.description'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                            </tr>
                            </thead>
                            <tbody id="dataTableTbody" class="dataTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Row -->

    </div>
    </div><!-- end app-content-->
    </div>

@endsection
@section('js')
    <!-- INTERNAL Data tables -->
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

    <!-- INTERNAL Select2 js -->
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script>


        $(document).ready(function () {

            $(document).on('click','.deleteTodo',function (){
                let todo_number = $(this).attr("data-todo-number");
                confirmModal('Are you sure you want to delete this Todo?',"Delete Todo","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url:"/delete-todo/"+todo_number,
                        type:"get",
                        success:function (response){
                            location.reload();
                        }
                    })
                });
            });

            function todoData() {

                var dt = $('#todosTable').DataTable({
                    initComplete: function () {

                             this.api().columns(4).every(function () {
                                var column = this;
                                var select = $('<select class="form-control form-control-sm"><option value="">All</option><option value="1">Open</option><option value="2">In Progress</option><option value="3">Done</option></select>')
                                    .appendTo($(column.header()).empty())
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
                                        column
                                            .search(val ? '^' + val + '$' : '', true, false)
                                            .draw();
                                    });
                            });

                    },

                    "processing": false,
                    "serverSide": true,
                    "paging": true,
                    "ajax": {
                        url: '/todos/list',
                        type: "GET",
                    },
                    select: true,
                    fixedColumns: true,
                    "columns": [{
                        "data": "todo_number",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data)
                                return data;
                            else
                                return '-';
                        }
                    },
                        {
                            "data": "subject",
                            "visible": true,
                            "orderable": true,
                            render: function (data, type, row) {
                                if (data)
                                    return data;
                                else
                                    return '-';
                            }
                        },

                         {
                            "data": "org_name",
                            "visible": true,
                            "orderable": true,
                            render: function (data, type, row) {
                                if (data) {

                                    return data.length > 15 ? data.substr(0,15)+"..." : data;
                                }
                                else {
                                    return '-';
                                }
                            }
                        },


                        {
                            "data": "due_date",
                            "visible": true,
                            "orderable": true,
                            render: function (data, type, row) {
                                if (data)
                                    return data;
                                else
                                    return '-';
                            }
                        },
                        {
                            "data": "status",
                            "visible": true,
                            "orderable": false,
                            render: function (data, type, row) {
                                if (data)
                                    if (data === 1) {
                                        return '<span class="badge badge-secondary" style="font-size: 14px;">Open</span>';
                                    } else if (data === 2) {
                                        return '<span class="badge badge-primary" style="font-size: 14px;">In Progress</span>';
                                    } else if (data === 3) {
                                        return '<span class="badge badge-success" style="font-size: 14px;">Done</span>';
                                    } else
                                        return '-';
                            }
                        },

                        {
                            "data": "description",
                            "visible": true,
                            "orderable": false,
                            render: function (data, type, row) {

                                if (data) {
                                    if($(window).width()>1920 && $(window).width()<2560)
                                            return data.length > 120 ? data.substr(0,120)+"..." : data;
                                    if ($(window).width() < 1366)
                                        return data.length > 40 ? data.substr(0, 40) + "..." : data;
                                    if ($(window).width() > 1366)
                                        return data.length > 80 ? data.substr(0, 80) + "..." : data;
                                }
                                else {
                                    return '-';
                                }
                            }
                        },

                        {
                            "data": "actions",
                            "visible": true,
                            "orderable": false,
                            render: function (data, type, row) {
                                if (data) {
                                    return data;
                                } else {
                                    return '-';
                                }
                            }
                        },
                    ],
                    "iDisplayLength": 25,
                "language":{
                    "thousands":".",
                    "processing": "<i class='fa fa-refresh fa-spin'></i>",
                },
                    "columnDefs": [{className: 'text-center', targets: [0, 2,4, 3]},
                        {className: 'text-left',targets: [1,5]},
                        {width:25,targets: [0]},
                        {width:250,targets: [1]},
                        {width:75,targets: [2]},
                        {width:50,targets: [3]},
                        {width:65,targets: [4]},
                        {width:300,targets: [5]},
                        {width:80,targets: [6]},
                    ],


                });

                dt.on('click', 'tbody tr td:not(:last-child)', function (e) {
                    var data = dt.row($(this).parents('tr')).data();
                    window.location.href = '/update-todo/' + data['todo_number'];
                });
            }

            function resetDataTable() {
                $('#todosTable').DataTable().ajax.reload();
            }


            $('#todosTable thead tr:eq(1) th').each(function (i) {
                if(i !== 6) {
                    var title = $(this).text();
                    var html = '';
                    html = '<input type="text" class="form-control form-control-sm" placeholder="Search"  />';
                    $(this).html(html);
                    $('input', this).on('keyup change', function () {
                        if ($('#todosTable').DataTable().column(i).search() !== this.value) {
                            $('#todosTable').DataTable()
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });

                }
                else{
                    $('#todosTable thead tr:eq(1) th:eq(6)').html("");
                }
            });

            todoData();


        });
    </script>
@endsection
