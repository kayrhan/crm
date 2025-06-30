@extends('layouts.master')
@section('css')
<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Slect2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<style>
	.selected {
		background-color: #EBEEF1;
	}
</style>
@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{trans('words.ticket')}} {{trans('words.attachments')}}</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{trans('words.ticket')}} {{trans('words.attachments')}}</a></li>
		</ol>
	</div>
	<!-- <div class="page-rightheader">
		<div class="btn btn-list">
			<a href="{{url('/add-ticket')}}" class="btn btn-info"><i class="fe fe-settings mr-1"></i> Add Ticket </a>
		</div>
	</div> -->
</div>
<!--End Page header-->
@endsection
@section('content')
<!-- Row -->
<div class="row">
	<div class="col-6">
		<!--div-->
		<div class="card">
			@if(Session::get('success'))
			<div class="alert alert-success" role="alert">
				<button type="button" class="close">×</button>
				<i class="fa fa-file mr-2" aria-hidden="true"></i><span class="white">{{ session()->get('success') }}</span>
			</div>
			@endif
			<div class="card-header">
				<div class="card-title">{{trans('words.tickets')}} {{trans('words.data')}}</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-wrap datatable-custom-row" id="ticketAttachmentData" style="width:100%">
						<thead>
							<tr>
								<th  class="border-bottom-0 w-10">{{trans('words.ticket')}}</th>
								<th  class="border-bottom-0 w-20">{{trans('words.organization')}}</th>
								<th  class="border-bottom-0 w-15">{{trans('words.installer')}}</th>
								<th  class="border-bottom-0 w-30">{{trans('words.file')}}</th>
								<th  class="border-bottom-0 w-12">{{trans('words.tickets')}} {{trans('words.size')}}</th>
								<th  class="border-bottom-0 w-13">{{trans('words.date')}}</th>
							</tr>
							<tr>
								<th  class="border-bottom-0 w-10">{{trans('words.ticket')}}</th>
								<th  class="border-bottom-0 w-20">{{trans('words.organization')}}</th>
								<th  class="border-bottom-0 w-15">{{trans('words.installer')}}</th>
								<th  class="border-bottom-0 w-30">{{trans('words.file')}}</th>
								<th  class="border-bottom-0 w-12">{{trans('words.tickets')}} {{trans('words.size')}}</th>
								<th  class="border-bottom-0 w-13">{{trans('words.date')}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6">
		<iframe id="attachmentFrame" src="" frameBorder="0" style="width: 100%; height: 1200px;"></iframe>
	</div>
</div>
<!-- /Row -->

</div>
</div><!-- end app-content-->
</div>
<div class="modal fade" id="deleteTicketModal" tabindex="-1" role="dialog" aria-labelledby="deleteTicketModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{trans('words.delete')}} {{trans('words.ticket')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="deleteTicket">
					@csrf
					<input type="hidden" name="deleteTicketId" id="deleteTicketId">
					<p>{{trans('words.delete_message')}} {{trans('words.ticket')}}?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">{{trans('words.close')}}</button>
				<button type="submit" class="btn btn-danger">{{trans('words.delete')}}</button>
			</div>
			</form>
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
	function ticketAttachmentData() {
		$('#ticketAttachmentData thead tr:eq(1) th').each(function(i) {
			var title = $(this).text();
			if (title != 'File Size' && title != 'Date' && title != 'Action') {
				$(this).html('<input type="text" placeholder="Search"  class="form-control ' + title + '" />');
				$('input', this).on('keyup change', function() {
					if ($('#ticketAttachmentData').DataTable().column(i).search() !== this.value) {
						$('#ticketAttachmentData').DataTable()
							.column(i)
							.search(this.value)
							.draw();
					}
				});
			} else {
				$(this).html('');
			}
		});
		var dt = $('#ticketAttachmentData').DataTable({
			"processing": true,
			"autoWidth": false,
			"serverSide": true,
			"ajax": {
				url: '/getTicketAttachments',
				type: "GET",
			},
			select: true,
			"columns": [{
					"data": "ticket_id",
					"visible": true,
					"orderable": false,
					"searchable": true,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
				{
					"data": "organization",
					"visible": true,
					"orderable": false,
					"searchable": true,
					render: function(data, type, row) {
						if (data) {
                            if(data.length <10)
                                return data;
                            else{
                                 return `<span class="tippy-tooltip" data-tippy-content="${data}">
                                                ${data.substr(0, 15)}...
                                        </span>`;
                            }
                        }
						else
							return '-';
					}
				},
				{
					"data": "installer",
					"visible": true,
					"orderable": false,
					"searchable": true,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},
				{
					"data": "attachment",
					"visible": true,
					"orderable": false,
                    "searchable": true,
					render: function(data, type, row) {
						if (data) {
                            if(data.length < 15)
                                return data;
                            else{
                                 return `<span class="tippy-tooltip" data-tippy-content="${data}">
                                                ${data.substr(0, 25)}...
                                        </span>`;
                            }
                        }
						else
							return '-';
					}
				},
				{
					"data": "size",
					"visible": true,
					"orderable": false,
					render: function(data, type, row) {
						if (data)
							return (data / 1048576).toFixed(4) + ' MB';
						else
							return '-';
					}
				},
				{
					"data": "ParsedCreatedAt",
					"visible": true,
					"orderable": false,
					"searchable": false,
					render: function(data, type, row) {
						if (data) {
                            if(data.length <10)
                                return data;
                            else{
                                 return `<span class="tippy-tooltip" data-tippy-content="${data}">
                                                ${data.substr(0, 10)}
                                        </span>`;
                            }
                        }
						else
							return '-';
					}
				},
			],
			"iDisplayLength": 25
		});
		dt.on('click', 'tbody tr td:not(:last-child)', function(e) {
			var data = dt.row($(this).parents('tr')).data();
			var url = data['url'];
			$('#attachmentFrame').attr('src', url)
		});
		dt.on('click', 'td .deleteTicket', function(e) {
			var id = $(this).attr('data-id');
			$('#deleteTicketModal').modal('show');
			$.ajax({
				type: "GET",
				url: 'getOrganization/' + id,
				success: function(response) {
					if (!response.error) {
						$('#deleteTicketId').val(response.id);
					}
				}
			});
		});
	};

	$('#ticketAttachmentData tbody').off('click', 'tr').on('click', 'tr', function() {
		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		} else {
			$('#ticketAttachmentData').DataTable().$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}

	});

	function resetDataTable() {
		$('#ticketAttachmentData').DataTable().clear();
		$('#ticketAttachmentData').DataTable().destroy();
	}

	$('#deleteTicket').on('submit', function(e) {
		e.preventDefault();
		var form = $('#deleteTicket');
		var id = $('#deleteTicketId').val();
		var url = '/delete-ticket/' + id;
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(),
			success: function(response) {
				if (!response.error) {
					$('#message').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response + '</div>');
					$("#deleteTicket")[0].reset();
					$('#deleteTicketModal').modal('hide');
					resetDataTable();
					ticketsData();
				}
			}
		});
	});
	$(document).ready(function() {
		ticketAttachmentData();
        // TIPPY
        tippy.delegate("#ticketAttachmentData", {
            target: ".tippy-tooltip",
        })

	});
</script>
@endsection
