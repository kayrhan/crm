@extends('layouts.master')
@section('css')

    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/plugins/datetimepicker/jquery.datetimepicker.css') }}" />

    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 10px;
        }

        .btn-size {
            width: 6rem !important;
        }

        .default-cursor {
            cursor: default !important;
        }

        .modal-xlg {
            min-width: 80% !important;
        }

        .error-border {
            border: 1px solid red !important;
        }


    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Transactions</a></li>
            </ol>

        </div>

    </div>
    <!--End Page header-->
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <!--div-->
            <div class="card">

                <div class="card-header w-100">
                    <div class="row w-100">
                        <div class="col-lg-5 col-md-5">
                            <div class="tabs-menu">
                                <ul class="nav panel-tabs">
                                    @foreach ($companies as $company)
                                        <li class="">
                                            <a href="#company-tab{{ $company->id }}" class="{{ $loop->first ? 'active' : '' }}" onclick="getCompany({{ $company->id }})" id="tab-button-{{ $company->id }}" data-toggle="tab">
                                                {{ $company->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7"></div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="panel panel-primary tabs-style-3">
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                @foreach ($companies as $company)
                                    <div class="tab-pane {{ $loop->first ? 'active show' : '' }}"
                                        id="company-tab{{ $company->id }}">
                                        <div class="w-100">
                                            <div class="form-group">
                                                <select class="form-control w-30" id="month-select-{{ $company->id }}">
                                                    <option value="100" selected>Bir ay seçiniz.</option>
                                                    @foreach ($months as $key => $month)
                                                        <option value="{{ $key }}">{{ $month }}</option>
                                                    @endforeach
                                                    <option value="all">Hepsi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-bordered nowrap datatable-custom-row" id="transaction-datatable{{ $company->id }}" width="100%">
                                                <thead>
                                                    <tr align="center">
                                                        <th class="border-bottom-0 w-8">Kategori</th>
                                                        <th class="border-bottom-0 w-8">Gider</th>
                                                        <th class="border-bottom-0 w-8">Gelir</th>
                                                        <th class="border-bottom-0 w-19">İsim</th>
                                                        <th class="border-bottom-0 w-37">Açıklama</th>
                                                        <th class="border-bottom-0 w-10">Ekleyen</th>
                                                        <th class="border-bottom-0 w-10 table-date">Tarih</th>
                                                    </tr>
                                                    <tr align="center">
                                                        <th class="border-bottom-0 w-8">Kategori</th>
                                                        <th class="border-bottom-0 w-8">Gider</th>
                                                        <th class="border-bottom-0 w-8">Gelir</th>
                                                        <th class="border-bottom-0 w-19">İsim</th>
                                                        <th class="border-bottom-0 w-37">Açıklama</th>
                                                        <th class="border-bottom-0 w-10">Ekleyen</th>
                                                        <th class="border-bottom-0 w-10 table-date">Tarih</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="dataTable{{ $company->id }}">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction Modal --}}
    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form id="transactionForm">
                                @csrf
                                <div class="row form-group">
                                    <label class="form-label col-md-2 my-auto">Tür <span class="text-danger">*</span></label>
                                    <div class="col-md-5 col-lg-5">
                                        <div class="form-check">
                                            <input class="form-check-input " type="radio" name="transaction_type" id="expense" checked value="2">
                                            <label class="form-check-label form-label text-danger">
                                                Gider
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="transaction_type" id="income" value="1">
                                            <label class="form-check-label form-label text-success">
                                                Gelir
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="category" class="form-label col-md-2 my-auto">
                                        Kategori <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-10 col-lg-10">
                                        <select type="text" name="category_id" class="form-control form-control-sm" id="category">
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="amount" class="form-label col-md-2 my-auto">
                                        Miktar <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-5 col-lg-5">
                                        <input class="form-control form-control-sm  privateValidateControl" data-type="currency" type="text" name="amount" id="amount">
                                    </div>
                                    <label for="currency" class="form-label col-md-2 my-auto">
                                        Para Birimi <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-3 col-lg-3">
                                        <select name="currency" class="form-control form-control-sm" id="currency">
                                            <option value="2">Türk Lirası (₺)</option>
                                            <option value="1">Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="name" class="form-label col-md-2 my-auto">
                                        İsim <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-10 col-lg-10">
                                        <input class="form-control form-control-sm privateValidateControl" type="text" id="name" name="name">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label for="description" class="form-label col-md-2 my-auto">
                                        Açıklama
                                    </label>
                                    <div class="col-md-10 col-lg-10">
                                        <textarea class="form-control form-control-sm" id="description" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label for="date" class="form-label col-md-2 my-auto">
                                        Tarih <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-10 col-lg-10">
                                        <input class="form-control form-control-sm privateValidateControl" type="date" name="date" id="date">
                                    </div>
                                </div>
                                <input type="hidden" id="company-id" name="company_id" value="1">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="action-button" type="button" class="btn btn-success">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Modal --}}
    <div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="category-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modal-label">Kategoriler</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="row ">
                                <div class="col-md-12 col-lg-12 text-center border-bottom">
                                    <h6>Gelir Kategorileri</h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <table class="table table-sm text-center" id="income-category-table">
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <div class="row ">
                                <div class="col-md-12 col-lg-12 text-center border-bottom">
                                    <h6>Gider Kategorileri</h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <table class="table table-sm text-center" id="expense-category-table">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Delete Modal --}}
    <div class="modal fade" id="category-delete-modal" tabindex="-1" role="dialog" aria-labelledby="category-delete-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-delete-modal-label">Kategoriyi silmek istediğinize emin misiniz?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row category-move-box" style="display: none;">
                        <div class="col-lg-12 col-md-12">
                            <div class="row mb-1">
                                <div class="col-md-12 col-sm-12">
                                    <span class="text-danger">Kategoriyi silmeden önce kategorideki işlemleri taşımanız gerekmektedir!</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-lg-5">
                                    <select class="form-control form-control-sm" id="delete-category-request" disabled></select>
                                </div>
                                <div class="col-md-2 col-lg-2 d-flex align-items-center">
                                    <span class="form-label my-auto">Taşı</span>
                                </div>
                                <div class="col-md-5 col-lg-5">
                                    <select class="form-control form-control-sm privateValidateControl" id="before-delete-select" data-request="0">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row category-relax-box" style="display: none;">
                        <div class="col-md-12 col-lg-12">
                            <span class="text-success">Bu kategori herhangi bir işlem içermemektedir.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-secondary" id="accept-delete-category">Sil</button>
                </div>
            </div>
        </div>
    </div>


    </div>
    </div><!-- end app-content-->
    </div>

@endsection
@section('js')
    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datetimepicker/jquery.datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/js/numberFormat.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-number-format.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>


    <script>
        var companies = [];
        @foreach ($companies as $company)
            companies[{{ $loop->index }}] = {{ $company->id }};
        @endforeach
        var income_category = [];
        var expense_category = [];

        @foreach ($categories as $category)

            @if ($category->transaction_type == 1)
                income_category.push({category_id:"{{ $category->id }}",category_name:"{{ $category->name }}",company_id:"{{ $category->company_id }}"});
            @elseif($category->transaction_type == 2)
                expense_category.push({category_id:"{{ $category->id }}",category_name:"{{ $category->name }}",company_id:"{{ $category->company_id }}"});
            @endif

        @endforeach

        function createDatatable(company_id, isNew) {
            $('#transaction-datatable' + company_id).DataTable({
                initComplete: function() {
                    // Table Expense Category Select Search
                    this.api().columns(0).every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-control form-control-sm category-select"></select>'
                            )
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        //select box options according to company and ,income or expense
                        let datatable_category_options = "<optgroup label='Gelir'>";
                        $.each(income_category, function(index, value) {
                            if (company_id == value.company_id) {
                                datatable_category_options += "<option value='" + value
                                    .category_id + "'>" + value.category_name + "</option>";
                            }
                        });

                        datatable_category_options += "</optgroup><optgroup label='Gider'>";

                        $.each(expense_category, function(index, value) {

                            if (company_id == value.company_id) {
                                datatable_category_options += "<option value='" + value
                                    .category_id + "'>" + value.category_name + "</option>";
                            }
                        });
                        datatable_category_options += "</optgroup>";

                        $(".category-select").html('<option value="">Hepsi</option>' +
                            datatable_category_options);

                    });

                    // Date SelectBox search
                    this.api().columns(".table-date").every(function() {
                        let column = this;
                        if (isNew) {
                            $(".dataTable" + company_id).addClass("d-none")
                        }

                        $("#month-select-" + company_id).on("change", function() {
                            let month = $("#month-select-" + company_id).val();
                            if (month != "all") {
                                month = parseInt(month) + 1;
                                month = month > 9 ? month : "0" + month; // 01, 02, .. format
                            }

                            column.search(month == "all" ? "" : `2022-${month}`).draw();

                            $(".dataTable" + company_id).removeClass("d-none");
                        })

                        if (isNew) {
                            $("#month-select-" + company_id).val("100");
                        }
                        else {
                            let month = $("#month-select-" + company_id).val();
                            if (month != "all") {
                                month = parseInt(month) + 1;
                                month = month > 9 ? month : "0" + month; // 01, 02, .. format
                            }
                            setTimeout(() => {
                                column.search(month == "all" ? "" : `2022-${month}`).draw();
                            }, 50);

                        }

                    });

                    $('thead tr input').on("click", function(e) {
                        e.stopPropagation();
                    });

                },

                "processing": true,
                "serverSide": true,
                "paging": true,
                "order": [],
                "ajax": {
                    url: '/transactions/list/' + company_id,
                    type: "GET",
                },
                select: true,
                fixedColumns: true,

                "columns": [{
                        "data": "category_id",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if (data) {
                                return data;
                            }
                            else
                                return '-';
                        }
                    },
                    {
                        "data": "expense_amount",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if (data) {
                                let currency;
                                if (row['currency'] === 1)
                                    currency = "€";
                                else if (row['currency'] === 2)
                                    currency = "₺";
                                else
                                    currency = "";
                                return numberFormat(data, 2, ",", ".") + " " + currency;
                            }
                            else {
                                return '-';
                            }
                        }
                    },
                    {
                        "data": "income_amount",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {
                            if (data) {
                                let currency;
                                if (row['currency'] === 1)
                                    currency = "€";
                                else if (row['currency'] === 2)
                                    currency = "₺";
                                else
                                    currency = "";

                                return numberFormat(data, 2, ",", ".") + " " + currency;

                            }
                            else {
                                return '-';
                            }
                        }
                    },

                    {
                        "data": "name",
                        "visible": true,
                        "orderable": true,
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
                        "data": "description",
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
                        "data": "add_by",
                        "visible": true,
                        "orderable": false,
                        render: function(data, type, row) {

                            if (data) {
                                return data;
                            } else {
                                return '-';
                            }
                        } //tset
                    },
                    {
                        "data": "date",
                        "visible": true,
                        "orderable": true,
                        render: function(data, type, row) {

                            if (data) {
                                return data + "<input type='hidden' value='" + row["id"] + "'>";
                            } else {
                                return '-';
                            }
                        }
                    },

                ],
                "columnDefs": [{
                        "width": "8%",
                        "targets": 0
                    },
                    {
                        "width": "8%",
                        "targets": 1
                    },
                    {
                        "width": "8%",
                        "targets": 2
                    },
                    {
                        "width": "19%",
                        "targets": 3
                    },
                    {
                        "width": "37%",
                        "targets": 4
                    },
                    {
                        "width": "10%",
                        "targets": 5
                    },
                    {
                        "width": "10%",
                        "targets": 6
                    },
                ],
                "iDisplayLength": 25,
                "language": {
                    "thousands": ".",
                    "processing": "<i class='fa fa-refresh fa-spin'></i>",
                }
            });



            $('#transaction-datatable' + company_id + ' thead tr:eq(1) th').each(function(i) {
                if (i !== 7) {
                    var title = $(this).text();
                    var html = '';

                    html = '<input type="text" class="form-control form-control-sm" placeholder="Ara"  />';
                    if (i === 1 || i === 2)
                        html = "";
                    $(this).html(html);
                    $('input', this).on('keyup change', function() {
                        if ($('#transaction-datatable' + company_id).DataTable().column(i).search() !== this
                            .value) {
                            $('#transaction-datatable' + company_id).DataTable()
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });

                }
                else {
                    $('#transaction-datatable' + company_id + ' thead tr:eq(1) th:eq(6)').html("");
                }
            });
            $('#transaction-datatable' + company_id + '_filter').html("");
            $('#transaction-datatable' + company_id + '_filter').append(
                "<button class='btn btn-sm btn-info ml-2 show-transaction-modal'>Yeni İşlem</button>");
            $('#transaction-datatable' + company_id + '_filter').append(
                "<button class='btn btn-sm btn-info ml-2 show-category-modal' data-company-id='" + company_id +
                "'>Kategoriler</button>");


            $('#transaction-datatable' + company_id + ' tbody').on('click', 'tr td:not(:last-child)', function() {
                let id = $(this).closest('tr').find('input').val();
                $("#transactionModal").modal("show");
                $("#transactionModalTitle").html("İşlemi Düzenle");
                $('#action-button').data("action", "update").data("data-id", id);
                getData(id);

            });


        }

        function destroyTables(company_id) {
            let filtered = companies.filter(function(value, index, arr) {
                return value !== company_id;
            });
            $.each(filtered, function(i, value) {
                $("#transaction-datatable" + value).DataTable().destroy();
            });
        }

        function getCompany(company_id) {

            // #company-id is active company in form element type hidden
            if ($('#company-id').val() !== company_id.toString()) {
                $("#company-id").val(company_id);

                destroyTables(company_id);

                createDatatable(company_id, true);

            }
            else { // data added active company
                let month = $("#month-select" + company_id).val();
                $("#transaction-datatable" + company_id).DataTable().destroy();
                createDatatable(company_id, false);

            }

        }

        function getTotals(company_id) {

            $.ajax({
                url: "/transactions/get-totals/" + company_id,
                type: "post",
                data: "_token=" + "{{ csrf_token() }}",
                success: function(response) {
                    if (response !== "" && response !== undefined) {

                    }
                }
            });

        }

        function getData(id) { //get transactions data and enter the field

            $.ajax({
                url: "/transactions/get-data/" + id,
                type: "get",
                success: function(response) {

                    if (response !== "" && response !== undefined) {

                        if (response.transaction_type === 2) // expense 2
                            $('#expense').prop("checked", true).trigger("click");
                        else if (response.transaction_type === 1) // income 1
                            $('#income').prop("checked", true).trigger("click");
                        $('#name').val(response.name);
                        $('#amount').val(numberFormat(response.amount, 2, ",", "."));
                        $('#description').val(response.description);
                        $('#date').val(response.date);
                        $('#category').val(response.category_id);
                        $('#currency').val(response.currency)

                    }
                }
            });
        }

        //######## Category Functions
        function get_categories(company_id) {
            let categories;
            $.ajax({
                url: "/transactions/get-categories/" + company_id,
                type: "get",
                async: false,
                success: function(response) {
                    if (response !== "" && response !== undefined) {
                        categories = response;
                    }
                }
            });
            return categories;
        }

        function get_categories_raw() {
            $.ajax({
                url: "/transactions/get-categories-raw",
                type: "get",
                success: function(response) {
                    if (response !== "" && response !== undefined) {
                        income_category = [];
                        expense_category = [];
                        $.each(response, function(index, value) {
                            if (value.transaction_type === 1) {
                                income_category.push({
                                    category_id: value.id.toString(),
                                    category_name: value.name,
                                    company_id: value.company_id.toString()
                                });
                            }
                            if (value.transaction_type === 2) {
                                expense_category.push({
                                    category_id: value.id.toString(),
                                    category_name: value.name,
                                    company_id: value.company_id.toString()
                                });
                            }

                        });

                    }
                }
            })
        }

        function delete_category(category_id, company_id) {

            $.ajax({
                url: "/transactions/delete-category",
                type: "get",
                data: "category_id=" + category_id + "&_token=" + "{{ csrf_token() }}",
                async: false,
                success: function(response) {
                    if (response !== "" && response !== undefined) {
                        if (response.success === 1) {
                            toastr.success("Kategori başarıyla silindi!", "Başarılı");
                            $("#category-delete-modal").modal("hide");
                            regenerate_categories();
                            getCompany(company_id);
                            get_categories_raw();
                        }
                        else {
                            $("#category-delete-modal").modal("hide");
                            toastr.error("Bir hata meydana geldi!", "Hata")
                        }
                    }
                }
            })

        }

        function move_category(category_id, company_id) {
            let move_to_category = $('#before-delete-select').val();
            if (move_to_category !== "") {
                $.ajax({
                    url: "/transactions/move-category",
                    data: "category_id=" + category_id + "&company_id=" + company_id + "&move_to_category=" +
                        move_to_category + "&_token=" + "{{ csrf_token() }}",
                    type: "get",
                    async: false,
                    success: async function(response) {
                        if (response !== "" && response !== undefined) {
                            if (response.success === 1) {
                                toastr.success("Kategori başarıyla taşındı.", "Başarılı");
                                await new Promise(r => setTimeout(r, 500));
                                delete_category(category_id, company_id);
                                $("#category-delete-modal").modal("hide");
                                regenerate_categories();
                            }
                            else {
                                toastr.error("Taşıma işlemi başarısız oldu!");
                                $("#category-delete-modal").modal("hide");
                            }
                        }
                    }
                });
            }
            else {
                $('#before-delete-select').addClass("error-border");
            }
        }

        function form_validate() {
            let amount = $("#amount");
            let name = $("#name");
            let date = $('#date');
            if (amount.val() === "") {
                amount.addClass("error-border");
                return false;
            }
            if (name.val() === "") {
                name.addClass("error-border");
                return false;
            }
            if (date.val() === "") {
                date.addClass("error-border");
                return false;
            }

            return true;
        }

        function regenerate_categories() {
            let company_id = $(".show-category-modal").data("company-id");
            $.ajax({
                url: "/transactions/get-categories/" + company_id,
                type: "get",
                success: function(response) {
                    let transactionTypeOneCount = 0;
                    let transactionTypeTwoCount = 0;

                    for (let i = 0; i < response.length; i++) {
                        if (response[i].transaction_type === 1) {
                            transactionTypeOneCount++;
                        }
                        if (response[i].transaction_type === 2) {
                            transactionTypeTwoCount++;
                        }
                    }

                    if (response !== undefined && response !== "") {
                        let table_header = "<thead><tr>\n" +
                            "                             <th>Kategori İsmi</th>\n" +
                            "                             <th>Düzenle</th>\n" +
                            "                             <th>Sil</th>\n" +
                            "                         </tr></thead>";
                        let income_html = table_header;
                        let expense_html = table_header;
                        $.each(response, function(index, value) {

                            if (value.transaction_type === 1) {

                                income_html += "<tr>" +
                                    "<td class='category-name-field w-60'>" + value.name + "</td>" +
                                    "<td class='update-category-field w-20'><i class='fa fa-edit update-category text-info' data-category-id='" +
                                    value.id + "' ></i></td>" +
                                    "<td class='w-20'><i class='fa fa-remove delete-category text-danger' data-category-id='" +
                                    value.id + "' data-transaction-type='1'></i></td></tr>";

                            }
                            else if (value.transaction_type === 2) {

                                expense_html += "<tr>" +
                                    "<td class='category-name-field w-60'>" + value.name + "</td>" +
                                    "<td class='update-category-field w-20'><i class='fa fa-edit update-category text-info' data-category-id='" +
                                    value.id + "' ></i></td>" +
                                    "<td class='w-20'><i class='fa fa-remove delete-category text-danger' data-category-id='" +
                                    value.id + "' data-transaction-type='2'></i></td></tr>";

                            }

                        });

                        income_html += "<tr>" +
                            "<td colspan='2'><input type='text' class='form-control privateValidateControl form-control-sm' id='add-income-category' placeholder='Kategori ismi'></td>" +
                            "<td><button class='btn btn-sm btn-success' id='add-income-category-btn'><i class='fa fa-plus'></i></button></td>" +
                            "</tr>";
                        expense_html += "<tr>" +
                            "<td colspan='2'><input type='text' class='form-control privateValidateControl form-control-sm' id='add-expense-category' placeholder='Kategori ismi'></td>" +
                            "<td><button class='btn btn-sm btn-success' id='add-expense-category-btn'><i class='fa fa-plus'></i></button></td>" +
                            "</tr>";

                        $("#income-category-table").html(income_html);
                        $("#expense-category-table").html(expense_html);
                        if(transactionTypeOneCount == 0){
                            $("#income-category-table thead").remove();
                        }
                        if(transactionTypeTwoCount == 0){
                            $("#expense-category-table thead").remove();
                        }
                    }
                }
            });
        }

        function add_category(company_id, transaction_type, category_name) {

            $.ajax({
                url: "/transactions/add-category",
                type: "get",
                data: "company_id=" + company_id + "&transaction_type=" + transaction_type + "&category_name=" +
                    category_name + "&_token=" + "{{ csrf_token() }}",
                success: function(response) {
                    if (response !== "" && response !== undefined) {
                        if (response.success === 1) {
                            toastr.success("Kategori başarıyla eklendi.", "Başarılı");
                            regenerate_categories();
                            get_categories_raw();
                        }
                        else {
                            toastr.error(response.message, "Hata");
                        }
                    }
                }
            });
        }

        function update_category(category_id, category_name) {

            $.ajax({
                url: "/transactions/update-category",
                type: "get",
                data: "category_id=" + category_id + "&category_name=" + category_name + "&_token=" +
                    "{{ csrf_token() }}",
                async: false,
                success: function(response) {
                    if (response !== undefined && response !== "") {
                        if (response.success === 1) {
                            toastr.success("Kategori başarıyla güncellendi.", "Başarılı");
                            regenerate_categories();
                            get_categories_raw();
                        }
                        else {
                            toastr.error(response.message, "Hata");
                        }
                    }
                }
            });

        }



        $(document).ready(function() {


            createDatatable(1); // table initial page load process

            // add transsaction modal show
            $(document).on("click", ".show-transaction-modal", function() {
                $('#transactionModal').modal("show");
                $("#transactionModalTitle").html("İşlem Ekle");
                $('#transactionForm')[0].reset();
                $('#action-button').data("action", "add");
                $('#category').html(""); // clear category
                $('#expense').trigger("click"); // default options set
            });
            $(document).on("click", ".show-category-modal", function() {
                regenerate_categories();
                $('#category-modal').modal("show");

            });



            // add or update transaction button according to data-action={"update" and "add"}
            $(document).on("click", "#action-button", function() {

                if (form_validate()) {

                    let data = $("#transactionForm").serialize();
                    let url;
                    let action = $('#action-button').data("action");
                    let toast_message;
                    if (action === "update") {
                        let data_id = $('#action-button').data("data-id");
                        url = "/transactions/update/" + data_id;
                        toast_message = "İşlem başarıyla güncellendi.";
                    }
                    if (action === "add") {
                        url = "/transactions/add";
                        toast_message = "İşlem başarıyla eklendi.";
                    }
                    $.ajax({
                        url: url,
                        type: "post",
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            if (response !== "" && response !== undefined) {
                                if (response.success === 1) {
                                    toastr.success(toast_message, "Başarılı");
                                    getCompany($('#company-id').val());

                                }
                                else {
                                    toastr.error(response.message, "Hata");
                                }
                            }
                            else {
                                toastr.error(response.message, "Hata");
                            }
                        }
                    });
                }
            });

            //####### Category Listeners
            //set to expenses category into the category selectbox
            $(document).on("click", "#expense", function() {
                let html = "";
                let company_id = $('#company-id').val();
                $.each(expense_category, function(index, value) {

                    if (company_id === value.company_id)
                        html += "<option value=" + value.category_id + ">" + value.category_name +
                        "</option>";
                });
                $('#category').html("");
                $('#category').append(html);
            });

            //set to incomes category into the category selectbox
            $(document).on("click", "#income", function() {
                let html = "";
                let company_id = $('#company-id').val();
                $.each(income_category, function(index, value) {

                    if (company_id === value.company_id)
                        html += "<option value=" + value.category_id + ">" + value.category_name +
                        "</option>";
                });

                $('#category').html("");
                $('#category').append(html);
            });

            $(document).on("click", "#add-income-category-btn", function() {
                let company_id = $('.show-category-modal').data("company-id");
                let category_name = $('#add-income-category').val();
                if (category_name !== "") {
                    add_category(company_id, 1, category_name);
                }
                else {
                    $('#add-income-category').addClass("error-border");
                }

            });

            $(document).on("click", "#add-expense-category-btn", function() {
                let company_id = $('.show-category-modal').data("company-id");
                let category_name = $('#add-expense-category').val();
                if (category_name !== "") {
                    add_category(company_id, 2, category_name);
                }
                else {
                    $('#add-expense-category').addClass("error-border");
                }

            });

            $(document).on("click", ".update-category", function() {
                let category_id = $(this).data("category-id");
                let old_value = $(this).closest("tr").find(".category-name-field").text();
                $(this).closest("tr").find(".category-name-field")
                    .html(
                        "<input type='text' class='form-control form-control-sm update-category-input privateValidateControl'  value='" +
                        old_value + "'>");
                $(this).closest("tr").find(".update-category-field")
                    .html(
                        "<i class='fa fa-check text-success update-category-send-btn' data-category-id='" +
                        category_id + "'></i>");



            });

            $(document).on("click", ".update-category-send-btn", function() {
                let category_id = $(this).data("category-id");
                let category_input = $(this).closest("tr").find(".update-category-input");
                let category_type = $(this).closest("tr").find(".update-category-input");
                let category_name = category_input.val();
                if (category_name !== "") {
                    update_category(category_id, category_name);
                }
                else {
                    category_input.addClass("error-border");
                }

            });

            $(document).on("click", ".delete-category", function() {

                let category_id = $(this).data("category-id");
                let company_id = $('.show-category-modal').data("company-id");
                let transaction_type = $(this).data("transaction-type"); // for get move to selectbox option
                $.ajax({
                    url: "/transactions/get-transaction-count/" + category_id,
                    type: "get",
                    success: function(response) {
                        if (response !== "" && response !== undefined) {
                            $('#category-delete-modal').modal("show");
                            if (response.transaction_count > 0) {

                                $('.category-move-box').show();
                                $('.category-relax-box').hide();
                                $('#accept-delete-category').html("Taşı ve Sil");

                                let categories = get_categories(company_id);
                                let move_categories = "<option value=''></option>";

                                $.each(categories, function(index, value) {

                                    if (category_id !== value.id && value
                                        .transaction_type === transaction_type) {
                                        move_categories += "<option value='" + value
                                            .id + "'>" + value.name + "</option>";
                                    }
                                    if (category_id === value.id) {
                                        $('#delete-category-request').html(
                                            "<option value='" + value.id + "'>" +
                                            value.name + "</option>");
                                    }
                                });
                                $('#before-delete-select').html(move_categories);
                                $('#before-delete-select').data("request", 1);

                            }
                            else {
                                $('.category-move-box').hide();
                                $('.category-relax-box').show();
                                $('#before-delete-select').html("");
                                $('#accept-delete-category').html("Sil");
                                $('#before-delete-select').data("request", 0);


                            }
                            $('#accept-delete-category').data("category-id", category_id);
                        }
                    }
                });
            });

            $(document).on("click", "#accept-delete-category", function() {
                let request = $('#before-delete-select').data("request");
                let company_id = $('.show-category-modal').data("company-id");
                let category_id = $('#accept-delete-category').data("category-id");
                if (request === 1) {
                    move_category(category_id, company_id);
                }
                else {
                    delete_category(category_id, company_id);
                }


            });

            $(document).on('keyup change', ".privateValidateControl", function() {
                $(this).removeClass('error-border');
            });


        });
    </script>
@endsection
