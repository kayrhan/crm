@extends('layouts.master')
@section('css')
<!-- Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
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
		<h4 class="page-title mb-0">{{ucfirst(trans('words.contracts'))}} getucon GmbH</h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ucfirst(trans('words.contracts'))}} getucon GmbH </a></li>
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
				<div class="card-title">{{ucfirst(trans('words.contracts'))}} {{ucfirst(trans('words.data'))}} getucon GmbH  <x-infobox info="If the contracts are terminated or the contracts are upgraded, they are recorded according to the selected date. And a new contract can be created with the same contract number. However, this only applies if the contract is terminated or upgraded. Otherwise, a contract cannot be created with the same contract number."/></div>
				<div class="page-rightheader">
					<div class="btn btn-list">
						<a href="{{url('/add-contract/'.$owner_company)}}" class="btn btn-sm btn-info"><i class="fa fa-plus-circle mr-1"></i> {{ucfirst(trans('words.add_contracts'))}} </a>
					</div>
				</div>

			</div>
			<div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap datatable-custom-row" id="contractData">
                        <thead>
                        <tr>
                            <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.contract_customer'))}}</th>
                            <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.contract_id'))}}</th>
                            <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.contract_service'))}}</th>
                            <th class="wd-15p border-bottom-0">Contract Status</th>
                            <th class="wd-10p border-bottom-0">{{ucfirst(trans('words.contract_start'))}}</th>
                            <th class="wd-20p border-bottom-0">{{ucfirst(trans('words.contract_end'))}}</th>
                            <th class="role wd-15p border-bottom-0">{{ucfirst(trans('words.price'))}} with discount</th>
                            <th class="status wd-15p border-bottom-0">{{ucfirst(trans('words.contract_company'))}}</th>
                            <th class="status wd-15p border-bottom-0">{{ucfirst(trans('words.contract_payment_status'))}}</th>
                            <th class="wd-25p border-bottom-0">Action</th>
                        </tr>
                        <tr>
                            <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.contract_customer'))}}</th>
                            <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.contract_id'))}}</th>
                            <th class="wd-15p border-bottom-0">{{ucfirst(trans('words.contract_service'))}}</th>
                            <th class="wd-15p border-bottom-0">Contract Status</th>
                            <th class="wd-10p border-bottom-0">{{ucfirst(trans('words.contract_start'))}}</th>
                            <th class="wd-20p border-bottom-0">{{ucfirst(trans('words.contract_end'))}}</th>
                            <th class="role wd-15p border-bottom-0">{{ucfirst(trans('words.price'))}} with discount</th>
                            <th class="status wd-15p border-bottom-0">{{ucfirst(trans('words.contract_company'))}}</th>
                            <th class="status wd-15p border-bottom-0">{{ucfirst(trans('words.contract_payment_status'))}}</th>
                            <th class="wd-25p border-bottom-0">Action</th>
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
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js?v=2')}}"></script>
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
<script src="{{URL::asset('assets/js/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/jquery.number.min.js')}}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script>
    const companies = [];
    @foreach($companies as $company)
        companies.push({id:"{{$company->id}}",name:"{{$company->name}}"});
    @endforeach
    function contractData() {

        var dt = $('#contractData').DataTable({
            initComplete: function() {
                this.api().columns(2).every(function() {

                    let column = this;
                    let select = $('<select id="type-select" class="form-control form-control-sm"><option value=""></option><option value="1">DataCenter</option><option value="2">Support-Service-Maintance</option><option value="5">Leasing-Firewall</option><option value="3">Non-Service</option><option value="4">Web Contract</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                });
                 this.api().columns(3).every(function() {
                    var column = this;
                    var select = $('<select class="form-control form-control-sm"><option value=""></option><option value="1">Continious</option><option value="2">Terminated</option><option value="3">Upgraded</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {

                               var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val , true, false)
                                .draw();

                        });
                });
                this.api().columns(7).every(function() {
                    var column = this;
                    let html = "";
                    $.each(companies,function (index,value){
                       html+="<option value='"+value.id+"'>"+value.name+"</option>";
                    });
                    var select = $('<select class="form-control form-control-sm"><option value=""></option>'+html+'</select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                });
                this.api().columns(8).every(function() {
                    var column = this;
                    var select = $('<select class="form-control form-control-sm"><option value=""></option><option value="1">{{ucfirst(trans("words.contact_payment_status_1"))}}</option><option value="2">{{ucfirst(trans("words.contact_payment_status_2"))}}</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });


                });

                let contract_type = "{{$contract_type}}"
                setTimeout(() => {
                    $("#type-select").val(contract_type).trigger("change");
                }, 200);
            },
            "processing": false,
            "serverSide": true,
            "paging": true,
            "ajax": {
                url: '/getContracts/{{$owner_company}}/',
                type: "GET",
            },
            select: true,
            "columns": [
                {
                    "data": "org_name",
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
                    "data": "contractId",
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
                    "data": "type",
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
                    "data": "contract_status",
                    "visible": true,
                    "orderable": true,
                    render: function(data, type, row) {
                            if(data) {
                                if(row.terminated_date){
                                    return "Terminated on "+moment(data).format("DD.MM.YYYY");
                                }
                                if(row.upgraded_date){
                                    return "Upgraded on "+moment(data).format("DD.MM.YYYY");
                                }

                            }
                            else{
                                return "Continious";
                            }
                    }
                },
                {
                    "data": "start",
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
                    "data": "end",
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
                    "data": "price",
                    "visible": true,
                    "orderable": false,
                    render: function(data, type, row) {

                        if (data!=null)
                            return $.number(data,2,",",".")+ " €";
                        else
                            return '-';
                    }
                },
                {
                    "data": "cid",
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
                    "data": "status",
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
                    "orderable":false,
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
        dt.on('click', 'tbody tr td:not(:last-child)', function(e) {
            var data = dt.row($(this).parents('tr')).data();
            window.open('/update-contract/{{$owner_company}}/' + data['id'] + '', '_blank');
        });

        dt.on('click', 'td .deleteContract', function(e) {
            var id = $(this).attr('data-id');
            confirmModal('Are you sure you want to delete this Contract?',"Delete Contract","Delete","Close","#0275d8","#d9534f").then(function() {
                $.ajax({
                    url: '/delete-contract/'+id,
                    type: "get",
                    dataType: 'json',
                }).done(function (data) {
                    if(data.status===200){
                        window.location.reload();
                    } else {
                        alert('Error');
                    }
                });
            });
        });
    }



    function resetDataTable() {
        $('#contractData').DataTable().ajax.reload();
    }
    $(document).ready(function() {
        $('#contractData thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            var html = '';
            html = '<input type="text" class="form-control form-control-sm" placeholder="Search" />';
            if(i === 9)
                html = "";
            $(this).html(html);
            $('input', this).on('keyup change', function() {
                if ($('#contractData').DataTable().column(i).search() !== this.value) {
                    $('#contractData').DataTable()
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        contractData();





    });
</script>
@endsection
