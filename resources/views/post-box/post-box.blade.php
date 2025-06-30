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
                        href="#">{{ucfirst(trans('words.post_list'))}}</a></li>
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

                <div class="card-header" style="display: flex;justify-content: space-between;">
                    <div class="card-title">{{ucfirst(trans('words.post_list'))}}</div>

                    <div class="btn btn-list">
                        <a href="{{route("post-box.add-post-box")}}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> {{ucfirst(trans('words.add_post_box'))}} </a>
                    </div>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap datatable-custom-row" id="postBoxTable" width="100%">
                            <thead>
                            <tr align="center">
                                <th class="border-bottom-0">#{{ucfirst(trans('words.post_id'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.received_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.consignor'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.review'))}}</th>

                                <th class="border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                            </tr>

                            <tr align="center">
                                <th class="border-bottom-0">#{{ucfirst(trans('words.post_id'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.received_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.consignor'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.review'))}}</th>

                                <th class="border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
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

    <script>



        $(document).ready(function () {


            function postboxData() {

            var dt = $('#postBoxTable').DataTable({
                initComplete: function () {


                },

                "processing": false,
                "serverSide": true,
                "paging": true,
                "ajax": {
                    url: '/post-box/list',
                    type: "GET",
                },
                select: true,
                fixedColumns: true,
                "columns": [{
                    "data": "id",
                    "visible": true,
                    "orderable": true,
                    render: function (data, type, row) {
                        if (data) {

                            return data;
                        } else
                            return '-';
                    }
                },
                    {
                        "data": "received_date",
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
                        "data": "consignor",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data) {

                                return data;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "review",
                        "visible": true,
                        "orderable": true,
                        render: function (data, type, row) {
                            if (data) {

                                return data;
                            } else {
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
                                return "<a data-id='"+data+"' class='btn btn-sm btn-danger delete-btn'><i class='fa fa-trash'></i></a>";
                            } else {
                                return '-';
                            }
                        }
                    }
                ],
                "iDisplayLength": 10,
                "columnDefs": [{className: 'text-center', targets: [0, 1, 2, 3, 4]},

                    {width: 15, targets: [0]},
                    {width: 75, targets: [1]},
                    {width: 75, targets: [2]},
                    {width: 50, targets: [3]},
                    {width: 50, targets: [4]}
                ],

            });

            dt.on('click', 'tbody tr td:not(:last-child,:nth-child(4))', function (e) {
                    var data = dt.row($(this).parents('tr')).data();
                    data['id'] = parseInt(data['id']);
                    window.location.href = '/post-box/update-post-box/' + data['id'];
                });

        }




            $('#postBoxTable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                if(i !== 4) {

                var html = '';
                html = '<input type="text" class="form-control" placeholder="Search" />';
                 $(this).html(html);

                    $('input', this).on('keyup change', function () {
                        if ($('#postBoxTable').DataTable().column(i).search() !== this.value) {
                            $('#postBoxTable').DataTable()
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });

                }else{

                    $('#postBoxTable thead tr:eq(1) th:eq(4)').html("");

                }
            });


            postboxData();

        $(document).on('click','.delete-btn',function (e){
            let id = $(this).attr('data-id');
            confirmModal('Are you sure you want to delete this post from postbox?',"Delete Post?","Delete","Close","#0275d8","#d9534f").then(function() {
                $.ajax({
                    url:"{{route("post-box.delete")}}",
                    data:"post_id="+id+"&_token="+"{{csrf_token()}}",
                    type:"get",
                    dataType:"json",
                    success:function (response){
                        if(response !== "" && response !== "undefined"){
                            if(response.status ===1){
                                toastr.success("Post deleted successfully!","Succes!");
                                location.reload();
                            }
                            else{
                                toastr.error("Unknown error","Error!");
                            }
                        }
                    }

                });

            });
        });


        });


    </script>
@endsection
