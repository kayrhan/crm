@extends('layouts.master')
@section('css')
<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/custom.datatable.row.css')}}" rel="stylesheet"/>
<!-- Slect2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<style>
	.redClass {
		background: #EF5858;
		color: white;
	}

    tr {
        cursor: default !important;
    }


</style>
@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ucfirst(trans('words.users'))}}</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ucfirst(trans('words.users'))}}</a></li>
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
			<div class="card-header">
				<div class="card-title">{{ucfirst(trans('words.users'))}} {{ucfirst(trans('words.data'))}}</div>
				@if(in_array('CREATE_USER', auth()->user()->Permissions))
				<div class="page-rightheader" style="width: 100%; text-align: right;">
					<div class="btn btn-list">
						<a href="{{url('/add-user')}}" class="btn btn-info"><i class="fa fa-plus-circle mr-1"></i> {{ucfirst(trans('words.add_user'))}} </a>
					</div>
				</div>
				@endif
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-nowrap datatable-custom-row" id="usersData">
						<thead>
							<tr>
								<th class="wd-15p border-bottom-0">{{ucfirst(trans('words.first_name'))}}</th>
								<th class="wd-15p border-bottom-0">{{ucfirst(trans('words.last_name'))}}</th>
								<th class="wd-10p border-bottom-0">{{ucfirst(trans('words.email'))}}</th>
								<th class="wd-20p border-bottom-0">{{ucfirst(trans('words.organization'))}}</th>
                                @unless(auth()->user()->role_id == 5)
								<th class="role wd-15p border-bottom-0">{{ucfirst(trans('words.role'))}}</th>
                                @endunless
								<th class="status wd-15p border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
								<th class="actions wd-25p border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
							</tr>
							<tr>
								<th class="wd-15p border-bottom-0">{{ucfirst(trans('words.first_name'))}}</th>
								<th class="wd-15p border-bottom-0">{{ucfirst(trans('words.last_name'))}}</th>
								<th class="wd-10p border-bottom-0">{{ucfirst(trans('words.email'))}}</th>
								<th class="wd-20p border-bottom-0">{{ucfirst(trans('words.organization'))}}</th>
                                @unless(auth()->user()->role_id == 5)
								<th class="role wd-15p border-bottom-0">{{ucfirst(trans('words.role'))}}</th>
                                @endunless
								<th class="status wd-15p border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
								<th class="actions wd-25p border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
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
    tippy.delegate("#usersData", {
        target: ".tippy-tooltip",
    });

    var dt;
	function usersData() {
		 dt = $('#usersData').DataTable({
			initComplete: function() {
				this.api().columns('.status').every(function() {
					var column = this;
					var select = $('<select class="form-control"><option value=""></option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function() {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
							column
								.search(val ? '^' + val + '$' : '', true, false)
								.draw();
						});
					for (d = 0; d <= 1; d++) {
						if (d == 1)
							value = 'Active';
						else
							value = 'Inactive';
						select.append('<option value="' + d + '">' + value + '</option>')
					}
				});
				this.api().columns('.role').every(function() {
					var column = this;
					var select = $('<select class="form-control"><option value="">All</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function() {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
							column
								.search(val ? '^' + val + '$' : '', true, false)
								.draw();
						});
					for (d = 1; d <= 7; d++) {
						if (d == 1)
							value = 'Super Admin';
						if (d == 2)
							value = 'Admin';
						if (d == 3)
							value = 'Personnel Admin';
						if (d == 4)
							value = 'Personnel';
						if (d == 5)
							value = 'Firma Admin';
						if (d == 6)
							value = 'Firma User';
                        if(d == 7)
                            value = 'Freelancer';
						select.append('<option value="' + d + '">' + value + '</option>')
					}
				});

                this.api().columns('.actions').every(function() {
                    $(this.header()).empty();
                });
			},
			"processing": true,
			"serverSide": true,
			"paging": true,
			"ajax": {
				url: '/getUsers',
				type: "GET",
			},
			select: true,
			"createdRow": function(row, data, dataIndex) {
				if (data['in_use'] == 0) {
					$(row).addClass('redClass');
				}
			},
			"columns": [{
					"data": "first_name",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
				{
					"data": "surname",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
				{
					"data": "email",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
				{
					"data": "org_id",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data)
							return row['organizationName'];
						else
							return '-';
					}
				},
                @unless(auth()->user()->role_id == 5)
				{
					"data": "role_id",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data)
							return row['roleName'];
						else
							return '-';
					}
				},
                @endunless
				{
					"data": "in_use",
					"visible": true,
					"orderable": false,
					render: function(data, type, row) {
						if (data)
							return 'Active';
						else
							return 'Inactive';
					}
				},
				{
					"data": "actions",
					"visible": true,
                    "orderable": false,
					render: function(data, type, row) {
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
		});
		dt.on('click', 'td .deleteUser', function(e) {
			var id = $(this).attr('data-id');
            confirmModal('{{ucfirst(trans('words.delete_user_message'))}}','{{ucfirst(trans('words.delete_user'))}}','{{ucfirst(trans('words.delete'))}}','{{ucfirst(trans('words.close'))}}',"#0275d8","#d9534f").then(function() {
                $.ajax({
                    type: "GET",
                    url: 'getUser/' + id,
                    success: function(response) {
                        if (!response.error) {
                            var url = '/delete-user/' + response.id;
                            $.ajax({
                                type: "POST",
                                url: url,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    deleteUserId:response.id,
                                },
                                success: function(response) {
                                    if (!response.error) {
                                        $('#message').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response + '</div>');
                                        location.reload()
                                    }
                                }
                            });
                        }
                    }
                });
            });

		});

	}

	function resetDataTable() {
		$('#usersData').DataTable().clear();
		$('#usersData').DataTable().destroy();
	}

	$(document).ready(function() {
		$('#usersData thead tr:eq(1) th').each(function(i) {
			var title = $(this).text();
			var html = '';
			html = '<input type="text" class="form-control" placeholder="Search"  class="' + title + '"/>';
			$(this).html(html);
			// $(this).html('<input type="text" placeholder="Search"  class="' + title + '" style="width: ' + width + '"/>');
			$('input', this).on('keyup change', function() {
				if ($('#usersData').DataTable().column(i).search() !== this.value) {
					$('#usersData').DataTable()
						.column(i)
						.search(this.value)
						.draw();
				}
			});
		});
		usersData();
        dt.on('click', 'tbody tr td:last-child .userStatus', function(e) {
			var userId = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			$.ajax({
				type: "GET",
				url: '/updateUserStatus/' + userId + '?status=' + status,
				success: function(response) {
                    console.log(response.error)
					if (!response.error) {
                        console.log(response.success);
						toastr.success(response.success, 'Success');
						resetDataTable();
						usersData();
					}
				}
			});
		});
	});
</script>
@endsection
