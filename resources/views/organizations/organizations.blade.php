@extends('layouts.master')
@section('css')
<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Slect2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

<style>
	tr {
		cursor: pointer;
	}

	.redClass {
		background: #EF5858;
		color: white;
	}

	#loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  margin-left:250px;
  margin-top:250px;
}


@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>

@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ucfirst(trans('words.organizations'))}}</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{trans('words.organizations')}}</a></li>
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
			<div id="message">
			</div>
			<div class="card-header d-flex justify-content-between">
				<div class="card-title">{{ucfirst(trans('words.organizations'))}} {{ucfirst(trans('words.data'))}}</div>
				@if(in_array('CREATE_ORGANIZATION', auth()->user()->Permissions))
				<div class="page-rightheader">
					<div class="btn btn-list">
						<a href="{{ route('organizations.create') }}" class="btn btn-sm btn-info">
							<i class="fa fa-plus-circle mr-1"></i> {{ucfirst(trans('words.add_organization'))}}
						</a>
					</div>
				</div>
				@endif
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-nowrap datatable-custom-row" id="organizationsData" width="100%">
						<thead>
							<tr>
								<th  class="w-6 border-bottom-0">ID</th>
								<th  class="w-7 border-bottom-0">Customer No</th>

								<th  class="w-20 border-bottom-0">{{ucfirst(trans('words.name'))}}</th>
                                <th  class="w-10 border-bottom-0">Email</th>
                                <th  class="w-10 border-bottom-0">Phone</th>
                                <th  class="w-15 border-bottom-0">Assigned Organization</th>
								<th  class="w-10 border-bottom-0 assigned-personnel">Assigned Personnel</th>
								<th  class="w-5 status border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
                                <th  class="w-10 rating border-bottom-0">{{ucfirst(trans('words.rating'))}}</th>
								<th  class="w-7 border-bottom-0 last-column">{{ucfirst(trans('words.action'))}}</th>
							</tr>
							<tr>
								<th  class="w-6 border-bottom-0">ID</th>
                                <th  class="w-7 border-bottom-0">Customer No</th>
								<th  class="w-20 border-bottom-0">{{ucfirst(trans('words.name'))}}</th>
                                <th  class="w-10 border-bottom-0">Email</th>
                                <th  class="w-10 border-bottom-0">Phone</th>
								<th  class="w-10 border-bottom-0">Assigned Personnel</th>
                                <th  class="w-15 border-bottom-0 assigned-personnel">Assigned Organization</th>
								<th  class="w-5  status border-bottom-0">{{ucfirst(trans('words.status'))}}</th>
                                <th  class="w-10 rating border-bottom-0">{{ucfirst(trans('words.rating'))}}</th>
								<th  class="w-7 border-bottom-0 last-column">{{ucfirst(trans('words.action'))}}</th>
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
<!-- /Row -->

</div>
</div><!-- end app-content-->
</div>
<div class="modal fade" id="deleteOrganizationModal" tabindex="-1" role="dialog" aria-labelledby="deleteOrganizationModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ucfirst(trans('words.deleted_organization'))}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="deleteOrganization">
					@csrf
					<input type="hidden" name="deleteOrganizationId" id="deleteOrganizationId">
					<p>{{ucfirst(trans('words.delete_message'))}} {{ucfirst(trans('words.organization'))}}??</p>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">{{ucfirst(trans('words.close'))}}</button>
				<button type="submit" class="btn btn-danger">Delete</button>
			</div>
			</form>
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
	function organizationsData() {
        $('thead tr input').on("click",function (e){
                e.stopPropagation();
            });
		var dt = $('#organizationsData').DataTable({
			initComplete: function() {
				this.api().columns('.rating').every(function() {
					var column = this;
					var select = $('<select class="form-control"><option value=""></option><option value="3">Good Client</option><option value="2">Normal Client</option><option value="1">Blacklist Client</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function() {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
							column
								.search(val ? val  : '', true, false)
								.draw();
						});
				});
				this.api().columns('.status').every(function() {
					var column = this;
					var select = $('<select class="form-control"><option value=""></option><option value="1">Active</option><option value="0">In Active</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function() {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);

							column
								.search(val ? val : '', true, false)
								.draw();
						});

				});
                this.api().columns(".assigned-personnel").every(function (){
                    let column = this;
                    let input = $("<input class='form-control' type='text' placeholder='Search'>").appendTo($(column.header()).empty())
                                .on('keyup change',function (){
                                   let val = $.fn.dataTable.util.escapeRegex($(this).val());

                                   column.search(val?val:'',true,false)
                                   .draw();
                                });
                });
                this.api().columns(".last-column").every(function (){

                    $(this.header()).empty();
                });
			},
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: '/getOrganizations',
				type: "GET",
			},
			select: true,
			"createdRow": function(row, data, dataIndex) {
				if (data['is_active'] == 0) {
					$(row).addClass('redClass');
				}
			},
			"columns": [{
					"data": "id",
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
					"data": "customer_no",
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
					"data": "org_name",
					"visible": true,
					"orderable": false,
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
					"orderable": false,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
                 {
					"data": "phone_no",
					"visible": true,
					"orderable": false,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
                {
                    "data": "personnel_org",
                    "visible": true,
                    "orderable": false,
                    render: function(data, type, row) {
                        if (data) {
                            return data;
                        }
                        else {
                            return '-';
                        }
                    }
                },
                {
					"data": "personnel_id",
					"visible": true,
					"orderable": false,
					render: function(data, type, row) {

						if (data)
							return data;
						else
							return '-';
					}
				},

				{
					"data": "is_active",
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
					"data": "rating_flag",
					"visible": true,
					"orderable": false,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
				{
					"data": "actions",
					"visible": true,
                    "orderable": false,
					render: function(data, type, row) {
						if (data) {
							return "<div class='text-center'>"+data+"</div>";
						} else {
							return '-';
						}
					}
				},
			],
			"iDisplayLength": 25,
			"language":
			{
			"processing": "<i class='fa fa-refresh fa-spin'></i>",
			}
		});
		dt.on('click', 'tbody tr td:not(:last-child)', function(e) {
			var data = dt.row($(this).parents('tr')).data();
            window.open('/organizations/' + data["id"] + '/edit', '_blank');
		});
		dt.on('click', 'td .deleteOrganization', function(e) {
			var id = $(this).attr('data-id');
			$('#deleteOrganizationModal').modal('show');
			$.ajax({
				type: "GET",
				url: 'getOrganization/' + id,
				success: function(response) {
					if (!response.error) {
						$('#deleteOrganizationId').val(response.id);
					}
				}
			});
		});
		dt.on('click', 'td .updateStatus', function(e) {
			var organzationId = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			$.ajax({
				type: "GET",
				url: '/updateOrganizationStatus/' + organzationId + '?status=' + status,
				success: function(response) {
					if (!response.error) {
						toastr.success(response.success, 'Success');
						resetDataTable();
						organizationsData();
					}
				}
			});
		});
	}

	function resetDataTable() {
		$('#organizationsData').DataTable().clear();
		$('#organizationsData').DataTable().destroy();
	}

	$('#deleteOrganization').on('submit', function(e) {
		e.preventDefault();
		var form = $('#deleteOrganization');
		var id = $('#deleteOrganizationId').val();
		var url = '/delete-organization/' + id;
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(),
			success: function(response) {
				if (!response.error) {
					$('#message').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response + '</div>');
					$("#deleteOrganization")[0].reset();
					$('#deleteOrganizationModal').modal('hide');
					resetDataTable();
					organizationsData();
				}
			}
		});
	});
	$(document).ready(function() {
		$('#organizationsData thead tr:eq(1) th').each(function(i) {

			let html = '<input type="text" class="form-control" placeholder="Search"  />';
                if(i===6)
                    html="";
				$(this).html(html);
				// $(this).html('<input type="text" placeholder="Search"  class="' + title + '" style="width: ' + width + '"/>');
				$('input', this).on('keyup change', function() {
					if ($('#organizationsData').DataTable().column(i).search() !== this.value) {
						$('#organizationsData').DataTable()
							.column(i)
							.search(this.value)
							.draw();
					}
				});

		});
		organizationsData();
	});
</script>
@endsection
