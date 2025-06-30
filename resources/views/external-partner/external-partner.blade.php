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


</style>
@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">External Partners</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">External Partners</a></li>
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
			<div class="card-header d-flex justify-content-between">
				<div class="card-title">External Partner List</div>

				<div class="page-rightheader">
					<div class="btn btn-list">
						<a href="{{url('/external-partners/add')}}" class="btn btn-sm btn-info"><i class="fa fa-plus-circle mr-1"></i> New Partner </a>
					</div>
				</div>

			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-nowrap datatable-custom-row" id="partner-data">
						<thead>
							<tr>
                                <th class="w-10 border-bottom-0">{{ucfirst(trans('words.id'))}}</th>
								<th class="w-25 border-bottom-0">{{ucfirst(trans('words.organization'))}}</th>
								<th class="w-20 border-bottom-0">{{ucfirst(trans('words.email'))}}</th>
								<th class="w-15 border-bottom-0">{{ucfirst(trans('words.phone'))}}</th>
								<th class="w-10 border-bottom-0">{{ucfirst(trans('words.rating'))}}</th>
								<th class="w-10 border-bottom-0">Created at</th>
								<th class="w-10 border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
							</tr>
							<tr>
                                <th class="w-10 border-bottom-0">{{ucfirst(trans('words.id'))}}</th>
								<th class="w-25 border-bottom-0">{{ucfirst(trans('words.organization'))}}</th>
								<th class="w-20 border-bottom-0">{{ucfirst(trans('words.email'))}}</th>
								<th class="w-15 border-bottom-0">{{ucfirst(trans('words.phone'))}}</th>
								<th class="w-10 border-bottom-0">{{ucfirst(trans('words.rating'))}}</th>
								<th class="w-10 border-bottom-0">Created at</th>
								<th class="w-10 border-bottom-0">{{ucfirst(trans('words.action'))}}</th>
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
	function partner_data() {
        $('thead tr input').on("click",function (e){
                e.stopPropagation();
            });
		 $('#partner-data').DataTable({
			initComplete: function() {
                 this.api().columns(4).every(function () {
                        var column = this;
                        var select = $('<select class="form-control form-control-sm"><option value="">' +
                            '<option value="1">Good Partner</option>' +
                            '<option value="2">Normal Partner</option>' +
                            '<option value="3">Blacklist Partner</option>' +
                            '</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? val : '', true, false)
                                    .draw();
                            });


                    });
			},
			"processing": true,
			"serverSide": true,
			"paging": true,
			"ajax": {
				url: '/external-partners/get-partners',
				type: "GET",
			},
			select: true,

			"columns": [
                {
					"data": "id",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data)
							return data+"<input type='hidden' class='partner-id' value='"+row.id+"'>";
						else
							return '-';
					}
				},
                {
					"data": "organization_name",
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
					"data": "phone",
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
					"data": "rating",
					"visible": true,
					"orderable": true,
					render: function(data, type, row) {
						if (data){
                            if(data === 1){
                                return "<div class='text-center'><i class='fa fa-flag btn btn-sm btn-success'></i></div>";
                            }
                            if(data === 2){
                                return "<div class='text-center'><i class='fa fa-flag btn btn-sm btn-warning'></i></div>";
                            }
                            if(data === 3){
                                return "<div class='text-center'><i class='fa fa-flag btn btn-sm btn-danger'></i></div>";
                            }
                            return data;
                        }

						else
							return '-';
					}
				},
                {
					"data": "created_at",
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
					"data": "actions",
					"visible": true,
					render: function(data, type, row) {
						if (data) {
							return "<div class='text-center'><a class='btn btn-sm btn-danger delete-partner' data-id='"+row.id+"'><i class='fa fa-trash'></i></a></div>";
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



	}

	function resetDataTable() {
		$('#partner-data').DataTable().clear();
		$('#partner-data').DataTable().destroy();
	}

	$(document).ready(function() {
		$('#partner-data thead tr:eq(1) th').each(function(i) {
			var html = '';
			html = '<input type="text" class="form-control" placeholder="Search"  />';
            if(i===6)
                html = "";
            if(i===5)
                html = '<input type="date" class="form-control"   />';
			$(this).html(html);

			$('input', this).on('keyup change', function() {
				if ($('#partner-data').DataTable().column(i).search() !== this.value) {
					$('#partner-data').DataTable()
						.column(i)
						.search(this.value)
						.draw();
				}
			});
		});
		partner_data();

        $('#partner-data tbody').on('click', ' tr td:not(:last-child)', function () {
            var data = $(this).closest('tr').find('input').val();
            window.open('/external-partners/update/' + data + '', '_blank');
        });

          $(document).on('click','.delete-partner',function (){
              let partner_id = $(this).data("id");
              confirmModal('Are you sure you want to delete this partner?',"Delete External Partner","Delete","Close","#0275d8","#d9534f").then(function() {
                  $.ajax({
                      url:"/external-partners/delete/"+partner_id,
                      type:"get",
                      success:function (response){
                          if(response.success === 1) {
                              resetDataTable();
                              partner_data();
                              $('#delete-external-partner').modal("hide");
                              toastr.success("External partner deleted successfully!","Success");
                          }
                          else{
                              toastr.error("An error thrown!","Error");
                          }
                      }
                  })
              });
            });
	});
</script>
@endsection
