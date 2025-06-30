@extends('layouts.master')
@section('css')
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
<div class="page-header">
    <div class="page-leftheader">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="#">{{ucfirst(trans('words.packages'))}}</a>
            </li>
        </ol>
    </div>
</div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @if(Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close">×</button>
                        <i class="fa fa-file mr-2" aria-hidden="true"></i><span
                            class="white">{{ session()->get('success') }}</span>
                    </div>
                @endif
                <div class="card-header" style="display: flex;justify-content: space-between;">
                    <div class="card-title">{{ucfirst(trans('words.package_tracking'))}}</div>

                    <div class="btn btn-list">
                        <a href="{{url('/add-package')}}" class="btn btn-info">
                            <i class="fa fa-plus-circle"></i> {{ucfirst(trans('words.add_package_order_button'))}} </a>
                    </div>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap datatable-custom-row" id="packageTable" width="100%">
                            <thead>
                            <tr align="center">
                                <th class="border-bottom-0">{{ucfirst(trans('words.no'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.shop'))}}</th>
                                <th class="border-bottom-0">Organization</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.order_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.expected_delivery_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.cargo_company'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
                                <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.personnel'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.tracking_id'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.ticket_id'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.express'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.description'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                            </tr>

                            <tr align="center">
                                <th class="border-bottom-0">{{ucfirst(trans('words.no'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.shop'))}}</th>
                                <th class="border-bottom-0">Organization</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.order_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.expected_delivery_date'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.cargo_company'))}}</th>
                                <th class="border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
                                <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.personnel'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.tracking_id'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.ticket_id'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.express'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.description'))}}</th>
                                <th class="category wd-15p border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
                            </tr>
                            </thead>
                            <tbody id="dataTableTbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
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
    <script>

        $(document).ready(function () {

            $(document).on('click','.deletePackage',function (){
                let package_id = $(this).attr("data-package-id");
                confirmModal('Are you sure you want to delete this tracking?',"Delete Tracking","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        url:'/delete-package/'+package_id,
                        type:'get',
                        success:function (response){
                            location.reload();
                        }
                    })
                });
            });

            function packageDataTable() {
                 $('thead tr input').on("click",function (e){
                e.stopPropagation();
            });
                var dt = $("#packageTable").DataTable(
                    {
                        initComplete: function () {

                            this.api().columns(6).every(function () {
                                var column = this;
                                var select = $('<select class="form-control"><option value="">All</option><option value="1">Open</option><option value="2">In Delivery</option><option value="3">Delivered</option><option value="4">Reklamation</option></select>')
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

                            this.api().columns(10).every(function () {
                                var column = this;
                                var select = $('<select class="form-control"><option value="">All</option><option value="1">Yes</option><option value="0">No</option></select>')
                                    .appendTo($(column.header()).empty())
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
                                        if(val === "1"){
                                            val = "yes";
                                        }
                                        if(val === "0"){
                                            val = "No";
                                        }
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
                            url: "{{route("package-tracking.list")}}",
                            type: "get",
                        },
                        select: true,
                        fixedColumns: true,
                        "columns": [
                            {
                                "data": "id",
                                "visible": true,
                                "orderable": true,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "shop",
                                "visible": true,
                                "orderable": true,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "organization",
                                "visible": true,
                                "orderable": true,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "order_date",
                                "visible": true,
                                "orderable": true,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "expected_delivery_date",
                                "visible": true,
                                "orderable": true,
                                render: function (data) {
                                    return (data) ? data[0] : "-";
                                }
                            },
                            {
                                "data": "cargo_company",
                                "visible": true,
                                "orderable": false,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "status",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {

                                        if (data === "1") {
                                            return "<span class='badge badge-danger' style='font-size: 14px;'>Open</span>"
                                        }
                                        if (data === "2") {
                                            return "<span class='badge badge-warning' style='font-size: 14px;'>In Delivery</span>";
                                        }
                                        if (data === "3") {
                                            return "<span class='badge badge-success' style='font-size: 14px;'>Delivered</span>";
                                        }
                                        if(data === "4"){
                                            return "<span class='badge badge-danger' style='font-size: 14px;'>Reklamation</span>";
                                        }

                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {
                                "data": "user_id",
                                "visible": true,
                                "orderable": true,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "tracking_id",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {

                                        return "<a style='color: #1384e1' class='link' href='" + data[1] + "' target='_blank'>" + data[0]+ "</a>";

                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {
                                "data": "ticket_id",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {

                                        return "<a style='color: #1384e1' class='link' href='" + data[0] + "' target='_blank'>#" + data[1]+ "</a>";

                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {
                                "data": "express",
                                "visible": true,
                                "orderable": false,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                            {
                                "data": "description",
                                "visible": true,
                                "orderable": false,
                                render: function (data, type, row) {
                                    if (data) {
                                        if (data.length > 40){
                                            return `<span class="tippy-tooltip" data-tippy-content="${data}"> ${data.substr(0,40)+"..."} </span>`
                                        } else {
                                            return data;
                                        }
                                    } else {
                                        return "-";
                                    }
                                }
                            },
                            {
                                "data": "actions",
                                "visible": true,
                                "orderable": false,
                                render: function (data) {
                                    return (data) ? data : "-";
                                }
                            },
                        ],

                        "columnDefs": [{className: 'text-center', targets: [0, 1, 2, 3, 4, 5, 6,7,8, 9,11]}
                        ,{className:'text-left',targets:10},
                            {width:25,targets: [0,8]},
                            {width:100,targets: [1]},
                            {width:50,targets: [3,4,5,7]},
                            {width:80,targets: [6,11,9]},
                            {width:150,targets: [10]}
                        ],
                        "iDisplayLength": 25,
                    "language":{
                        "thousands":".",
                        "processing": "<i class='fa fa-refresh fa-spin'></i>",
                    },
                     "createdRow":function (row,data,index){
                    //Kargo beklenen tarihte gelmediyse satırı kırmızıya boya

                         if(data.expected_delivery_date!==null) {
                             if (data.expected_delivery_date[1] === 1) {

                                 $(row).css("background", "#ad2903").addClass("text-white");
                             }
                         }
                }

                    }
                );

                dt.on('click', 'tbody tr td:not(:last-child,:nth-child(n+9):nth-child(-n+10)) ', function (e) {
                    var data = dt.row($(this).parents('tr')).data();
                    window.open('/update-package/' + data['id'] + '', '_blank');
                });

            }

            $('#packageTable thead tr:eq(1) th').each(function (i) {

                if (i !== 12) {
                    var title = $(this).text();
                    var html = '';
                    html = '<input type="text" class="form-control" placeholder="Search"  />';
                    $(this).html(html);
                    $('input', this).on('keyup change', function () {
                        if ($('#packageTable').DataTable().column(i).search() !== this.value) {
                            $('#packageTable').DataTable()
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });
                }
                else {
                    $('#packageTable thead tr:eq(1) th:eq(12)').html("");
                    $('#packageTable thead tr:eq(1) th:eq(8)').html("");
                    $('#packageTable thead tr:eq(1) th:eq(9)').html("");
                }
            });

            packageDataTable();
        });

        tippy.delegate("#packageTable", {
            target: ".tippy-tooltip",
        })


    </script>
@endsection
