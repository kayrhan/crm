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
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

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
                        Office</h3>
                    <div>
                        <a href="{{ url('/offices') }}" class="btn btn-info"><i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formSection" action="{{ route('offices.update.office') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="office_id" value="{{ $office->id }}">
                            {{-- LEFT PART --}}
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Office Name
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input id="name" type="text" name="name" class="form-control"
                                                    value="{{ $office->name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">
                                                Country
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <select id="country-select2" name="country" class="form-control">
                                                    <option value="">Select a Country</option>
                                                    @foreach ($countries as $country)
                                                        <option @if ($office->country == $country->id) selected @endif value="{{ $country->id }}">
                                                            {{ $country->name }}
                                                        </option>
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
                                                City
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <select id="city-select2" name="city" class="form-control">
                                                    <option value="">Select a City</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Description</label>
                                            <div class="col-md-9">
                                                <textarea id="description" name="description" class="form-control"
                                                    rows="3">{{ $office->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-12">
                                        <x-attachment type="Office" buttonclass="submitButton" formid="formSection" />
                                    </div>
                                </div>



                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                                <x-attachment-list type="Office" ownerid="{{ $office->id }}" />
                            </div>
                            <div class="col-lg-12 col-md-12 mt-5">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <button type="button" id="submitButton"
                                            class="btn btn-success mt-4 mb-0 float-right submitButton">{{ trans('words.save') }}</button>
                                        <a href="{{ url('/offices') }}"
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
@endsection

</div>
</div><!-- end app-content-->
</div>
@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>

    <script>
        $(document).ready(function() {

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
                        if (element.val() === null || element.val() === "") {

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
                    "name",
                    "city-select2",
                    "country-select2"
                ], oneTime);

                if (isValid) {
                    $('#formSection').submit();
                }
            });

            $("#country-select2").select2();

            const countrySelected = "{{ $office->country }}"
            const citySelected = "{{ $office->city }}"

            $.ajax({
                url: `/getCities/${countrySelected}`,
                type: "get",
                dataType: "json",
                success: function(response) {
                    $("#city-select2").empty().trigger("change")
                    $("#city-select2").select2({
                        data: response
                        
                    })
                    $("#city-select2").val(citySelected).trigger("change");
                    
                }
            });

            $("#country-select2").on("change keyup", function(e) {
                let country = $(e.target).val();

                $.ajax({
                    url: `/getCities/${country}`,
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        $("#city-select2").empty().trigger("change")
                        $("#city-select2").select2({
                            data: response
                        })
                    }
                });
            })

            validate(["name", "city-select2", "country-select2"], oneTime)

        })
    </script>
@endsection
