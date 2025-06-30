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
                    <h3 class="card-title">Edit Asset {{ $company->name }}</h3>
                    <div>
                        <a href="{{ url('/assets/' . $company->route_name) }}" class="btn btn-info">
                            <i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>

                {{-- Card Body & Form --}}
                <div class="card-body">
                    <form id="assetForm" action="{{ route('assets.update', ['asset' => $asset->id]) }}" method="POST"
                        enctype="multipart/form-data">
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
                                                <option value="{{ $organization->id }}"
                                                    @if ($organization->id == $asset->org_id) selected @endif>
                                                    {{ $organization->org_name }}
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
                                                <option value="{{ $type->id }}"
                                                    @if ($type->id == $asset->asset_type_id) selected @endif>
                                                    {{ $type->name }}
                                                </option>
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
                                            placeholder="Model" value="{{ $asset->model }}" required>
                                    </div>
                                </div>

                                {{-- Model --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Quantity
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="qty" id="qty" type="number"
                                            placeholder="Quantity" value="{{ $asset->qty }}" required>
                                    </div>
                                </div>

                                {{-- Ordered Throug Us --}}
                                <div class="form-group row border-bottom pb-2 mb-2">
                                    <label class="form-label col-sm-2 my-auto">Ordered Through Us</label>
                                    <div class="col-sm-10">
                                        <label class="custom-switch">
                                            <input type="checkbox" id="order_by_us" name="order_by_us"
                                                class="custom-switch-input" onchange="toggleCollapse(event)"
                                                @if ($asset->order_by_us) checked @endif />
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description"></span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Ordered Throus Us Inputs --}}
                                <div class="collapse @if ($asset->order_by_us) show @endif"
                                    id="order_through_us_collapse">

                                    {{-- Date of Sale --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Date of Sale
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="date" name="sale_date" id="sale_date"
                                                value="{{ $asset->sale_date }}">
                                        </div>
                                    </div>

                                    {{-- Invoice No --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Invoice No
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" name="invoice_no" id="invoice_no" type="text"
                                                placeholder="Invoice No" value="{{ $asset->invoice_no }}">
                                        </div>
                                    </div>

                                    {{-- Pos No --}}
                                    <div class="form-group row border-bottom pb-2 mb-2">
                                        <label class="col-sm-2 form-label my-auto">Pos No
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="pos_no" id="pos_no"
                                                placeholder="Pos No" value="{{ $asset->pos_no }}">
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
                                                    <option value="{{ $comp->id }}"
                                                        @if ($comp->id == $asset->company_id) selected @endif>
                                                        {{ $comp->name }}
                                                    </option>
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
                                                <option value="12" @if ($asset->warranty == '12') selected @endif>
                                                    12 months</option>
                                                <option value="24" @if ($asset->warranty == '24') selected @endif>
                                                    24 months</option>
                                                <option value="36" @if ($asset->warranty == '36') selected @endif>
                                                    36 months</option>
                                                <option value="48" @if ($asset->warranty == '48') selected @endif>
                                                    48 months</option>
                                                <option value="60" @if ($asset->warranty == '60') selected @endif>
                                                    60 months</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                {{-- Partner Invoice PDF --}}
                                <div class="form-group row pb-2 mb-2">
                                    <label class="col-sm-2 form-label my-auto">Partner Invoice</label>
                                    <div class="col-sm-10">
                                        @if ($asset->partner_pdf_name)
                                            <div id="partner-pdf-link" class="d-flex justify-content-between">
                                                <a target="_blank" class="text-primary"
                                                    href="{{ asset($asset->partner_pdf_path) }}">
                                                    {{ $asset->partner_pdf_name }}
                                                </a>
                                                <a onclick="showDeletePdfModal(event,{{ $asset->id }},'partner')"
                                                    class="text-danger" href="#">Delete</a>
                                            </div>
                                        @endif
                                        <div id="partner-pdf-div"
                                            class="custom-file @if ($asset->partner_pdf_name) d-none @endif">
                                            <input type="file" class="custom-file-input" id="partner_pdf" name="partner_pdf"
                                                onchange="changeFileLabel(event)"
                                                @if ($asset->partner_pdf_name) disabled @endif>
                                            <label class="custom-file-label" for="partner_pdf" data-browse="Browse">Partner
                                                Invoice</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Buttons --}}
                                <input type="hidden" name="save_close" id="save_close" value="0">

                                <div class="row d-flex justify-content-end">
                                    <a class="btn btn-danger mr-3"
                                        href="{{ url('/assets/' . $company->route_name) }}">Cancel</a>
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
        // Pdf functions
        function showDeletePdfModal(e, asset_id, pdf_type) {
            confirmModal('File will be deleted!',"Are you sure?","Remove","Close","#0275d8","#d9534f").then(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `/assets/deleteFile/${asset_id}/${pdf_type}`,
                    type: "post",
                    dataType: "json",
                    success: function(response) {
                        if (response == "success") {
                            hidePdfDeleteModal(pdf_type);
                            toastr.success("File Deleted Successfuly!", "Success");
                        } else {
                            toastr.error("An error thrown!", "Error");
                            $("#deletePdfModal").modal("hide");
                        }
                    }
                });
            });
        }


        // Displaying File Input, removing Deleted link and hiding the modal
        function hidePdfDeleteModal(pdf_type) {
            $(`#${pdf_type}-pdf-div`).removeClass("d-none");
            $(`#${pdf_type}-pdf-div`).find("input").prop("disabled", false);
            $(`#${pdf_type}-pdf-link`).addClass("d-none");
            $(`#${pdf_type}-pdf-link`).removeClass("d-flex");
        }

        // change input file label on upload
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

        // toggle collapse order_by_us div
        function toggleCollapse(e) {
            let checked = $(e.target).is(":checked");
            let collapse = $("#order_through_us_collapse")
            let collapseInputs = collapse.find(".form-control")

            if (checked) {
                showCollapse();
            } else {
                hideCollapse();
            }
        }

        function showCollapse(){
            let collapse = $("#order_through_us_collapse")
            let collapseInputs = collapse.find(".form-control")

            collapse.collapse("show");
            collapseInputs.prop("required", true);
            collapseInputs.prop("disabled", false);
        }

        function hideCollapse(){
            let collapse = $("#order_through_us_collapse")
            let collapseInputs = collapse.find(".form-control")

            collapseInputs.prop("required", false);
            collapseInputs.prop("disabled", true);
            collapse.collapse("hide");
        }

        $(document).ready(function() {
            $("#org_id").select2();
            $("#asset_type_id").select2();

            let order_by_us = "{{ $asset->order_by_us }}"
            if (order_by_us == 1) {
                $("#order_by_us").prop("checked", true);
                showCollapse();
            } else {
                $("#order_by_us").prop("checked", false);
                hideCollapse();
            }
        });
    </script>
@endsection
