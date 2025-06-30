@extends('layouts.master')
@section('css')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">
            {{ ucfirst(trans('words.update_organization')) }}
        </h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>
                    {{ ucfirst(trans('words.organizations')) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a
                    href="#">{{ ucfirst(trans('words.update_organization')) }}</a>
            </li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                        </button>
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">{{ ucfirst(trans('words.organization')) }}
                    {{ ucfirst(trans('words.information')) }}</h3>
                <div>
                    <a href="{{ url('/organizations/') }}" class="btn btn-info">
                        <i class="fa fa-backward mr-1"></i>{{ ucfirst(trans('words.back')) }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        @include(
                            'organizations.components.organization-form',
                            ['organization' => $organization,
                            'page_type' => 'update',
                            ]
                        )
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">{{ ucfirst(trans('words.users')) }}</h3>
                <a class="btn btn-info" target="_blank" href="{{ url('/add-user/' . $organization->id) }}">
                    <i class="fa fa-plus-circle mr-1"></i> Add User
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap" id="usersData">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.first_name')) }}</th>
                                <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.last_name')) }}</th>
                                <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.email')) }}</th>
                                <th class="wd-20p border-bottom-0">{{ ucfirst(trans('words.role')) }}</th>
                                <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.phone')) }}</th>
                                <th class="wd-10p border-bottom-0">{{ ucfirst(trans('words.action')) }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@stack('organization-script')

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
    <script src="{{ asset('assets/js/datatables.js') }}"></script>
    <script>
        function userData() {
            let organization_id = "{{ $organization->id }}"

            var dt = $('#usersData').DataTable({
                "processing": false,
                "serverSide": false,
                "ajax": {
                    url: '/getOrganizationUsers/' + organization_id,
                    type: "GET",
                },
                select: true,
                "createdRow": function(row, data, dataIndex) {
                    if (data['in_use'] == 0) {
                        $(row).addClass('bg-danger');
                        $(row).addClass('text-white');
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
                        "data": "role",
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
                        "data": "actions",
                        "visible": true,
                        render: function(data, type, row) {
                            if (data) {
                                data = JSON.parse(data)
                                let toggleActiveIcon = "";
                                if (data.in_use == 1) {
                                    toggleActiveIcon = '<i class="fe fe-user-check btn btn-info"></i>';
                                } else {
                                    toggleActiveIcon = '<i class="fe fe-user-x btn btn-info"></i>'
                                }
                                return `
                                    <a target="_blank" href="{{ url('/update-user') }}/${data.id}/{{ $organization->id }}">
                                        <i class="fa fa-pencil btn btn-theme"></i>
                                    </a>
                                    <a href="#" onclick="toggleActive(event)" data-status="${data.in_use == 1 ? 0 : 1}" data-id="${data.id}">
                                        ${toggleActiveIcon}
                                    </a>`;
                            } else {
                                return '-';
                            }
                        }
                    },
                ],
                "iDisplayLength": 5
            });
        }

        function resetDataTable() {
            $('#usersData').DataTable().clear();
            $('#usersData').DataTable().destroy();
        }

        function toggleActive(e) {

            e.preventDefault();
            let userId = $(e.target).parent().attr('data-id');
            let status = $(e.target).parent().attr('data-status');
            console.log(e.target);
            $.ajax({
                type: "GET",
                url: '/updateUserStatus/' + userId + '?status=' + status,
                success: function(response) {
                    console.log(response.error)
                    if (!response.error) {
                        console.log(response.success);
                        toastr.success(response.success, 'Success');
                        resetDataTable();
                        userData();
                    }
                }
            });
        }

        $('#deleteOrganization').on('submit', function(e) {
            e.preventDefault();
            var form = $('#deleteOrganization');
            var id = $('#deleteOrganizationId').val();
            var url = '/delete-organization/' + id;
            toggleLoader(true);
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    if (!response.error) {
                        $("#deleteOrganization")[0].reset();
                        toggleLoader(false);
                        $('#deleteOrganizationModal').modal('hide');
                        window.location.href = "/organizations";
                        $('#message').html(
                            '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                            response + '</div>');
                    }
                }
            });
        });

        $(document).ready(function() {
            userData();
        });
    </script>
@endsection
