@extends('layouts.master')
@section('css')
<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Slect2 css -->
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<style>
	input#Organization {
		width: 130px !important;
	}

	input#Personnel {
		width: 100px !important;
	}

	input#Category {
		width: 96px !important;
	}

	input#Subject {
		width: 437px !important;
	}

	input#Priority {
		width: 85px !important;
	}

	.btn-status {
		/* color: #fff !important;
		background-color: #fb1c52;
		border-color: #fb1c52; */
		box-shadow: 0 0 10px -5px rgb(251 28 82 / 50%);
	}

    .blink-button {
        background-color: #BA3129;
        -webkit-border-radius: 10px;
        border-radius: 10px;
        border: none;
        color: #eeeeee;
        cursor: pointer;
        display: inline-block;
        font-family: sans-serif;
        font-size: 16px;

        text-align: center;
        text-decoration: none;
    }

    @keyframes glowing {
        0% {
            background-color: #BA3129;
            box-shadow: 0 0 5px #BA3129;
        }

        50% {
            background-color: #d53830;
            box-shadow: 0 0 20px #d53830;
        }

        100% {
            background-color: #d53830;
            box-shadow: 0 0 5px #d53830;
        }
    }

    .blink-button {
        animation: glowing 1000ms infinite;
    }

    .main-filter-text {
        font-size: .8rem;
    }

    .btn-status {
        background-color: #96A9B5;
        color: #FFFFFF !important;
        width: 10rem;
        padding: 2px;
    }

    .statusFilter{
        border-radius: 20px!important;
        transition: box-shadow .4s ease-out;
    }

    .statusFilter:hover{
        box-shadow: 0px 2px 8px 2px #3333333b
    }

</style>
@endsection
@section('page-header')
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{trans('words.tickets')}}</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Tickets</a></li>
		</ol>

	</div>



</div>

<div class="row mt-3">

    <div class="col-md-11 col-lg-11">

        <div class="d-flex flex-column">
            <div class="d-flex">
                <div class="btn-group  w-100 button-height-status" role="group">


                    <a id="resetDataTable" class="btn btn-status statusFilter mr-3 mb-2" data-id="all"
                       style="background-color: #add8e6; color: #FFFFFF; width: 3rem;">
                        <span class="main-filter-text  "><i class="fa fa-refresh fs-14"></i></span>
                    </a>


                    <a class="btn btn-status statusFilter mr-3 mb-2" id="opened-button" data-id="1">
                                <span class="main-filter-text">{{ ucfirst(trans('words.opened')) }} (<span id="opened-tickets"></span>)</span>
                    </a>

                    <a class="btn btn-status statusFilter mr-3 mb-2" data-id="3">
                                <span class="main-filter-text">{{ ucfirst(trans('words.in_progress')) }} (<span id="in-progress-tickets"></span>)</span>
                    </a>

                    <a class="btn btn-status statusFilter mr-3 mb-2 " id="question-button" data-id="5"
                       style="background-color: #BA3129; color: #FFFFFF;width: 10rem;padding: 2px;font-size: 1rem;">
                                <span class="main-filter-text">{{ ucfirst(trans('words.question')) }} (<span id="question-tickets"></span>)</span>
                    </a>
                    <a class="btn btn-status statusFilter mr-3 mb-2" data-id="6">
                                <span class="main-filter-text">{{ ucfirst(trans('words.done')) }} (<span id="done-tickets"></span>)</span>
                    </a>
                    <a class="btn btn-status statusFilter mr-3 mb-2" data-id="7">
                                <span class="main-filter-text">{{ ucfirst(trans('words.invoiced')) }} (<span id="invoiced-tickets"></span>)</span>
                    </a>
                    <a class="btn btn-status statusFilter mr-3 mb-2" data-id="8">
                                <span class="main-filter-text">{{ ucfirst(trans('words.on_hold')) }} (<span id="on-hold-tickets"></span>)</span>
                    </a>
                    <a class="btn btn-status statusFilter mr-3 mb-2" data-id="9">
                                <span class="main-filter-text">{{ ucfirst(trans('words.closed')) }} (<span id="closed-tickets"></span>)</span>
                    </a>
                    <a class="btn btn-status statusFilter mr-3 mb-2" data-id="all">
                                <span class="main-filter-text">{{ ucfirst(trans('words.all_tickets')) }} (<span id="total-tickets"></span>)</span>
                    </a>
                </div>
            </div>
        </div>

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
				<i class="fa fa-file mr-2" aria-hidden="true"></i><span class="white">{{ session()->get('success') }}</span>
			</div>
			@endif
			<div class="card-header">
				<div class="card-title">{{trans('words.ticket_list')}}</div>
				@if(in_array('CREATE_TICKET', auth()->user()->Permissions))
				<div class="page-rightheader" style="width: 94%; text-align: right;">
					<div class="btn btn-list">
						<a href="{{url('/add-ticket')}}" class="btn btn-info">
							<i class="fa fa-plus-circle"></i> {{trans('words.add_ticket_button')}} </a>
					</div>
				</div>
				<div style="clear: both;"></div>
				@endif
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-wrap datatable-custom-row" id="ticketsData" width="100%">
						<thead>
							<tr>
								<th class="border-bottom-0" style="width: 5%">{{trans('words.no')}}</th>
								<th class="border-bottom-0" style="width: 13%">{{trans('words.ticket_holder')}}</th>
								<th class="wd-15p border-bottom-0"  style="width: 10%">{{trans('words.status')}}</th>

								<th class="wd-15p border-bottom-0" style="width: 50%">{{trans('words.subject')}}</th>
								<th class="wd-10p border-bottom-0" style="width: 12%">Ticket Create Date</th>
							</tr>
							<tr>
								<th class="border-bottom-0" id="no_sorting">{{trans('words.no')}}</th>
								<th class="border-bottom-0">{{trans('words.ticket_holder')}}</th>
								<th class="status wd-15p border-bottom-0">{{trans('words.status')}}</th>

								<th class="wd-15p border-bottom-0">{{trans('words.subject')}}</th>
								<th class="wd-10p border-bottom-0">Ticket Create Date</th>


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
	$(document).ready(function() {
		var table = $('#ticketsData').DataTable();
		table.state.clear();
	});

	function ticketsData(status) {
		var dt = $('#ticketsData').DataTable({

			initComplete: function() {
                //$("#ticketsData_filter").hide();
				this.api().columns('.status').every(function() {
					var column = this;
					var select = $('<select class="form-control form-control-sm"><option value="">All</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function() {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
							column
								.search(val ? val : '', true, false)
								.draw();
						});
					var options = 9;
					for (d = 1; d <= options; d++) {
						if (d != 2 && d != 4) {
							if (d == 1)
								text = 'Opened';
							if (d == 3)
								text = 'In Progress';
							if (d == 5)
								text = 'Question';
							if (d == 6)
								text = 'Done';
							if (d == 7)
								text = 'Invoiced';
							if (d == 8)
								text = 'On Hold';
							if (d == 9)
								text = 'Closed';

							if(status == d){
							select.append('<option value="' + d + '" selected>' + text + '</option>')
							}else{
								select.append('<option value="' + d + '">' + text + '</option>')
							}

						}
					}
				});


                this.api().columns('.priority').every(function() {
					var column = this;
					var select = $('<select class="form-control form-control-sm"><option value="">All</option><option value="0">Low</option> <option value="1">Normal</option><option value="2">High</option><option value="3">Very High</option></select>')
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



			},
			"processing": true,
			"serverSide": true,
			"stateSave": false,
			"destroy": true,
			"paging": true,
            "pageLength": 25,
             globalSearch:false,
			"ajax": {
				url: '/getTickets?status=' + status,
				type: "GET",
			},
			select: true,
			"columns": [{
					"data": "id",
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
					"data": "user",
					"visible": true,
					"orderable": false,
					"searchable": true,
					render: function(data, type, row) {
						if (data){
							var user_data = row['UserName'] + " " + row['SurName'];
							if (user_data.length < 25) {
								return row['UserName'] + " " + row['SurName'];
							}else{
								return user_data.substr( 0, 25 )+'…';
							}
						}
						else{
							return '-';
						}
					}
				},
				{
					"data": "status_id",
					"visible": true,
					"orderable": false,
					"searchable": true,
					render: function(data, type, row) {
						if(data) {
                            if(row["StatusName"] == "Answered" || row["StatusName"] == "Question INTERNAL") {
                                return "In Progress";
                            }
                            else {
                                return row["StatusName"];
                            }
                        }
						else {
                            return "-";
                        }
					}
				},

				{
					"data": "name",
					"visible": true,
					"orderable": false,
					render: function(data, type, row) {
						if (data){
							var user_data = data;
							if (user_data.length < 80) {
								return user_data;
							}else{
								return `<span class="tippy-tooltip" data-tippy-content="${user_data}">${user_data.substr( 0, 80 )+"..."}</span>`;
							}
						}
						else{
							return '-';
						}
					}
				},
				{
					"data": "ParsedCreatedAt",
					"visible": true,
					"orderable": false,
					"searchable": false,
					render: function(data, type, row) {
						if (data)
							return data;
						else
							return '-';
					}
				},

			],
			"iDisplayLength": 10
		});
		dt.on('click', 'tbody tr td:not(:last-child)', function(e) {
			var data = dt.row($(this).parents('tr')).data();
			if (data)
				window.location.href = '/update-ticket/' + data['id'];
		});

	}

	function resetDataTable() {
		$('#ticketsData').DataTable().clear();
		$('#ticketsData').DataTable().destroy();
	}


	$('.statusFilter').on('click', function() {
		var status = $(this).attr('data-id');
		resetDataTable();
		ticketsData(status);

	});

    function updateStatusCounters() {
        $.ajax({
            url: "/ticket/update-status-counter",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if(response !== undefined && response !== "") {
                    if(response.question_tickets > 0) {
                        $("#question-button").addClass("blink-button");
                    }
                    else {
                        $("#question-button").removeClass("blink-button");
                    }

                    $('#closed-tickets').html(response.closed_tickets);
                    $('#done-tickets').html(response.done_tickets);
                    $('#in-progress-tickets').html(response.in_progress_tickets);
                    $('#invoiced-tickets').html(response.invoiced_tickets);
                    $('#on-hold-tickets').html(response.on_hold_tickets);
                    $('#opened-tickets').html(response.opened_tickets);
                    $('#question-tickets').html(response.question_tickets);
                    $('#total-tickets').html(response.total_tickets);
                }
            }
        });
    }

	$(document).ready(function() {
		$('#resetDataTable').on('click', function() {
			$(':input').val('');
            updateStatusCounters();
		});

        tippy.delegate("#ticketsData", {
            target: ".tippy-tooltip",
        });
        updateStatusCounters();
		$('#ticketsData thead tr:eq(1) th').each(function(i) {
			var title = $(this).text();

			if (title != 'Ticket Date' && title != 'Due Date' && title != 'Action') {
				$(this).html('<input type="text" placeholder="Search"  class="form-control form-control-sm ' + title + '"/>');
				$('input', this).on('keyup change', function() {
					if ($('#ticketsData').DataTable().column(i).search() !== this.value) {
						$('#ticketsData').DataTable()
							.column(i)
							.search(this.value)
							.draw();
					}
				});
			} else {
				$(this).html('');
			}
		});
		ticketsData('all');

        setInterval(function() {
            updateStatusCounters();
        }, 60000);

        var observer = new MutationObserver(function(mutations){
            mutations.forEach(function (mutation){
                $("#no_sorting").removeClass( "sorting_asc" ).addClass( "sorting_disabled" );
                $("#no_sorting_due").removeClass( "sorting_asc" ).addClass( "sorting_disabled" );
            });
        });

        var observeTargets = document.querySelectorAll('tbody');
        observeTargets.forEach(function (item){
            observer.observe(item, { childList: true });
        });

	});
</script>
@endsection
