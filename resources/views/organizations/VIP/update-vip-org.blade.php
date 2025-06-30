@extends('layouts.master')
@section('css')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
<style>
    input[type="text"] {
        border: 1px solid #D5D5D5;
    }

    input[type="select"] {
        border: 1px solid #D5D5D5;
    }

    input[type="number"] {
        border: 1px solid #D5D5D5;
    }

    input[type="password"] {
        border: 1px solid #D5D5D5;
    }
</style>
@endsection
@section('page-header')
@endsection
@section('content')
<div class="row mt-4">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ucfirst(trans('words.vip_organizations'))}} </h3>
            </div>
            <div class="card-body">
                <div class="row w-100">
                    <div class="col-lg-6 col-md-6 mb-4">
                        <form>
                            @csrf
                            <div class="form-group standard-input-field-border">
                                <div class="row">
                                    <label for="organization" class="col-md-3 form-label my-auto">Organization</label>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-group">
                                            <select name="organization_id" id="organization" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3-col-md-3">
                                        <button type="button" id="save_button" class="btn btn-primary float-right">Set VIP</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row standard-input-field-border w-100">
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered nowrap w-100" id="org_table">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th class="border-bottom-0">Organization ID</th>
                                                        <th class="border-bottom-0">Organization Name</th>
                                                        <th class="border-bottom-0">Actions</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.js')}}"></script>
<script>
    function removeOrganization(ID) {
        confirmModal('This organization will be removed from the list of VIP organizations!',"Are you sure?","Remove","Close","#0275d8","#d9534f",600).then(function() {
            window.location.href = "/vip-organizations/remove/" + ID;
        })
    }

    $(document).ready(function() {
        $('#organization').select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                },
                allowClear: true,
                placeholder: 'Select an organization.'
            }
        );

        $('#org_table').DataTable({
            "processing": false,
            "serverSide": true,
            "searching": false,
            "ajax": {
                url: "/vip-organizations/get",
                type: "GET",
            },
            "paging": true,
            select: true,
            fixedColumns: true,
            "columns": [
                {
                    "data": "id",
                    "visible": true,
                    "orderable": true,
                    render: function (data) {
                        if(data) {
                            return data;
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "org_name",
                    "visible": true,
                    "orderable": true,
                    render: function (data) {
                        if(data) {
                            return data;
                        }
                        else {
                            return "-";
                        }
                    }
                },
                {
                    "data": "actions",
                    "visible": true,
                    "orderable": true,
                    render: function (data) {
                        if(data) {
                            return `<a onclick="removeOrganization(` + data + `)" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i><a/>`;
                        }
                        else {
                            return "-";
                        }
                    }
                }
            ],
            "language": {
                "lengthMenu": "Entries Per Page: _MENU_"
            },
            columnDefs:[{
                className:"text-center",
                targets:[2]
            }]
        });

        $('#save_button').on('click', function() {
            let org_id = $('#organization').val();

            if(org_id !==null){
                $.ajax({
                    url:'/vip-organizations/setvip',
                    type:'POST',
                    data:'organization_id=' + org_id + '&_token='+'{{csrf_token()}}',
                    dataType:'json',
                    success:function(response) {
                        if(response.status === 1) {
                            toastr.success("VIP organization added successfully!", "Success");
                            location.reload();
                        }
                        if(response.status === 0) {
                            toastr.error("You can not add VIP Organization more than 20!", "Error!");
                        }
                        if(response.status === 2) {
                            toastr.info("This organization is already VIP!", "Info!");
                        }
                    }
                });
            }
            else {
                toastr.error("Please select an organization!","Error!");
            }
        });
    });
</script>
@endsection
