@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('text-editor/trumbowyg.min.css') }}">
    <link rel="stylesheet" href="{{ asset('drop-zone/dropzone.css') }}">
    <style>
        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 40px;
        }

        .trumbowyg-editor p img {
            width: 400px !important;
        }

        .border-bottom {
            border-bottom: 2px solid #ebecf1;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

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

        .error-border {
            border: 1px solid #ff0000 !important;
        }

    </style>
@endsection
@section('page-header')

@endsection
@section('content')
    <!-- Row -->
    <div class="row" style="margin-top: 20px;">
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
                    <h3 class="card-title">{{ ucfirst(trans('words.create')) }} {{ ucfirst(trans('words.new')) }}
                        Stock</h3>
                    <div>
                        <a href="{{ url('/stocks') }}" class="btn btn-info"><i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formSection" action="{{ route('stocks.add') }}" method="POST">
                        @csrf
                        <div class="row">

                            {{-- LEFT PART --}}
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Stock ID
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="{{$stock_id}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Office
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <select name="office" id="office" class="form-control">
                                                    <option value="">Select an Office</option>
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">
                                                Brand
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="brand" id="brand" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">
                                                Product Name / Model
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="model" id="model" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Product Description(for Offer)
                                            </label>
                                            <div class="col-md-9">
                                                <textarea id="desc_offer" name="desc_offer" class="form-control"
                                                    rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Product Description (for Original)
                                            </label>
                                            <div class="col-md-9">
                                                <textarea id="desc_original" name="desc_original" class="form-control"
                                                    rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Stock
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" name="stock" id="stock" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Link to Manufacturer
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="stock_link" id="stock_link" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Product Brochures
                                            </label>
                                            <div class="col-md-9">

                                                <x-attachment type="Stock_Brochure" buttonclass="submitButton"
                                                    formid="formSection" maxsize="15" maxfiles="3" header=""
                                                    acceptedTypes=".pdf" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-12">
                                        <div class="form-group row border-bottom">

                                            <label class="col-md-3 form-label my-auto">Product Pictures
                                            </label>
                                            <div class="col-md-9">

                                                <x-attachment type="Stock_Picture" buttonclass="submitButton"
                                                    formid="formSection" maxsize="15" maxfiles="5" header=""
                                                    acceptedTypes="image/jpeg,image/png,image/jpg" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <button type="submit" id="submitButton"
                                            class="btn btn-success mt-4 mb-0 float-right submitButton">{{ trans('words.save') }}</button>
                                        <a href="{{ url('/stocks') }}"
                                            class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{ trans('words.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div><!-- end app-content-->
    </div>

    <script>
        let oneTime = true;

        function validate(elementIds, firstTime) {
            let isTrue = true;
            $.each(elementIds, function(index, value) {
                if (value.indexOf("select2") === -1) {
                    let element = $("#" + value);
                    if (element.val() === "") {
                        element.addClass("error-border");
                        isTrue = false;

                    }
                    if (oneTime) {
                        element.on("keyup change", function() {
                            if ($(this).val() === "") {
                                element.addClass("error-border");
                            } else {
                                element.removeClass("error-border");
                            }
                        });
                    }
                } else {
                    //for select2 component
                    let element = $('#' + value);
                    if (element.val() === null) {
                        element.parent().addClass("error-border");
                        isTrue = false;
                    }
                    if (oneTime) {
                        element.on("keyup change", function() {
                            if (element.val() === null) {
                                element.parent().addClass("error-border");
                            } else {
                                element.parent().removeClass("error-border");
                            }
                        })
                    }

                }
            });
            oneTime = false;

            return isTrue;
        }

        $("#submitButton").on("click", function(e) {
            e.preventDefault();
            let isValid = validate([
                "office",
                "brand",
                "model",
                "stock"
            ], oneTime);
            if (isValid) {
                $('#formSection').submit();
            }
        });
    </script>
@endsection
