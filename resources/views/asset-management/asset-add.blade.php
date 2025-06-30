@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endsection


@section('page-header')
@endsection


@section('content')
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                {{-- Form Validation Errors --}}
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">New Asset {{ $company->name }}</h3>
                    <div>
                        <a href="{{ url('/assets/' . $company->route_name) }}" class="btn btn-info">
                            <i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>

                {{-- Card Body & Form --}}
                <div class="card-body">
                    <form id="assetForm" action="{{ route('assets.add') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="owner_company_id" value="{{ $company->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                {{-- Organization --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Organization
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="org_id" id="org_id" required>
                                            <option value="" disabled selected>Select Organization</option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->org_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Asset --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Asset
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="asset_type_id" id="asset_type_id" required>
                                            <option value="" selected disabled>Select Asset</option>
                                            @foreach ($asset_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Model --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Model
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="model" id="model" type="text"
                                            placeholder="Model" required>
                                    </div>
                                </div>

                                {{-- Model --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Quantity
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="qty" id="qty" type="number"
                                            placeholder="Quantity" required>
                                    </div>
                                </div>

                                {{-- Ordered Throug Us --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="form-label col-sm-2 my-auto">Ordered Through Us</label>
                                    <div class="col-sm-10">
                                        <label class="custom-switch">
                                            <input type="checkbox" id="order_by_us" name="order_by_us"
                                                class="custom-switch-input" onchange="toggleCollapse(event)" />
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description"></span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Ordered Throus Us Inputs --}}
                                <div class="collapse" id="order_through_us_collapse">

                                    {{-- Date of Sale --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Date of Sale
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="date" name="sale_date" id="sale_date">
                                        </div>
                                    </div>

                                    {{-- Invoice No --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Invoice No
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="invoice_no" id="invoice_no" type="text"
                                                placeholder="Invoice No">
                                        </div>
                                    </div>

                                    {{-- Pos No --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Pos No
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="pos_no" id="pos_no"
                                                placeholder="Pos No">
                                        </div>
                                    </div>

                                    {{-- Company --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Company
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="company_id" id="company_id">
                                                <option value="" selected disabled>Select Company</option>
                                                @foreach ($companies as $comp)
                                                    <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Warranty --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Warranty
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="warranty" id="warranty">
                                                <option value="" selected disabled>Select Warranty</option>
                                                <option value="12">12 months</option>
                                                <option value="24">24 months</option>
                                                <option value="36">36 months</option>
                                                <option value="48">48 months</option>
                                                <option value="60">60 months</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                {{-- Partner Invoice PDF --}}
                                <div class="form-group row pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Partner Invoice</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="partner_pdf" name="partner_pdf"
                                                onchange="changeFileLabel(event)">
                                            <label class="custom-file-label" for="partner_pdf" data-browse="Browse">
                                                Partner Invoice</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Buttons --}}
                                <input type="hidden" name="save_close" id="save_close" value="0">

                                <div class="row d-flex justify-content-end">
                                    <a class="btn btn-danger mr-3" href="{{ url('/assets/' . $company->route_name) }}">Cancel</a>
                                    <button class="btn btn-success mr-3" type="sumbit">Save</button>
                                    <button class="btn btn-outline-success mr-3" type="button" onclick="saveForm(1)">Save &
                                        Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    </div>
    </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>

    <script>
        function changeFileLabel(e) {
            let fileName = e.target.files[0].name;
            let label = $(e.target).parent().find(".custom-file-label");
            label.html(fileName);
        }

        function saveForm(closePage) {
            let form = $("#assetForm");

            if (form[0].checkValidity()) {
                $("#save_close").val(closePage);
                form.submit();
            }
            form[0].reportValidity();
        }

        function toggleCollapse(e) {
            let checked = $(e.target).is(":checked");
            let collapse = $("#order_through_us_collapse")
            let collapseInputs = collapse.find(".form-control")

            if (checked) {
                collapse.collapse("show");
                collapseInputs.prop("required", true);
                collapseInputs.prop("disabled", false);
            } else {
                collapseInputs.prop("required", false);
                collapseInputs.prop("disabled", true);
                collapse.collapse("hide");
            }
        }

        $(document).ready(function() {
            $("#org_id").select2();
            $("#asset_type_id").select2();
            $("#order_by_us").prop("checked", false);

            $("#order_by_us").prop("checked", false);
            let collapse = $("#order_through_us_collapse")
            let collapseInputs = collapse.find(".form-control")
            collapseInputs.prop("disabled", true);
        });
    </script>
@endsection
