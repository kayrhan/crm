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
                        {{ ucfirst(trans('words.offer')) }}</h3>
                    <div>
                        <a href="{{ url('/offers') }}" class="btn btn-info"><i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formSection" action="{{ route('offers.add.post') }}" method="POST">
                        @csrf
                        <div class="row">

                            {{-- LEFT PART --}}
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.customer')) }}
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <select id="organization" name="org_id" class="form-control">


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.company')) }}
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-9">
                                                <select id="company_id" name="company_id" class="form-control">
                                                    <option value="">Select a company</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}">{{ $company->name }}
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
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.offer_no')) }}
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input id="offer_no" name="offer_no" class="form-control"
                                                    placeholder="Offer Number">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.offer_amount')) }}
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-9">
                                                <input id="offer_amount" name="offer_amount" class="form-control "
                                                    data-type="currency" placeholder="Offer Amount">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.offer_date')) }}
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-9">
                                                <input id="offer_date" name="offer_date" type="date" class="form-control"
                                                    placeholder="Offer Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.status')) }}
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <select id="status" name="status" class="form-control">
                                                    @foreach ($statusses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->status }}
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
                                            <label class="col-md-3 form-label my-auto">Position
                                            </label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="number" id="positionAmount"
                                                    placeholder="Position">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">Additional Info</label>
                                            <div class="col-md-9">
                                                <textarea id="description" name="description" class="form-control"
                                                    rows="8">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- RIGHT PART --}}
                            <div class="col-lg-6 col-md-6">
                                <div class="row border-bottom">
                                    <div class="col-md-12 col-lg-12" id="posSection">
                                        <div class="form-group row">
                                            <div class="col-md-1 col-lg-1 px-0">
                                                <span class="form-label pt-2">#1</span>
                                            </div>
                                            <div class="col-md-2 col-lg-2 px-1">
                                                <input name="links[]" class="form-control" placeholder="Link"
                                                    autocomplete="off">
                                            </div>
                                            <div class="col-md-2 col-lg-2 px-1">
                                                <input name="amounts[]" class="form-control offer-amount" type="number"
                                                    autocomplete="off" placeholder="Amount">
                                            </div>
                                            <div class="col-md-2 col-lg-2 px-1">
                                                <input name="buyingPrice[]" class="form-control offer-buy"
                                                    data-type="currency" placeholder="Buying" autocomplete="off">
                                            </div>
                                            <div class="col-md-2 col-lg-2 px-1">
                                                <input name="sellingPrice[]" class="form-control offer-sell"
                                                    data-type="currency" placeholder="Selling" autocomplete="off">
                                            </div>
                                            <div class="col-md-2 col-lg-2 px-1">
                                                <input class="form-control profit" data-type="currency" placeholder="Profit"
                                                    autocomplete="off" disabled>
                                            </div>
                                            <div class="col-md-1 col-lg-1 my-auto px-1">
                                                <button type="button" id="addButton" class="btn btn-sm btn-success">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center mt-2">
                                    <div class="col-md-5 col-lg-5 text-left">
                                        Total:
                                    </div>
                                    <div class="col-md-2 col-lg-2 px-0" id="totalBuy">

                                    </div>
                                    <div class="col-md-2 col-lg-2 px-0" id="totalSell">

                                    </div>
                                    <div class="col-md-2 col-lg-2 px-0" id="totalProfit">

                                    </div>
                                    <div class="col-md-1 col-lg-1"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <x-attachment type="Offer" buttonclass="submitButton" formid="formSection" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <button type="button" id="submitButton"
                                            class="btn btn-success mt-4 mb-0 float-right submitButton">{{ trans('words.save') }}</button>
                                        <a href="{{ url('/offers') }}"
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
@endsection
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{ asset('drop-zone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script src="{{ URL::asset('assets/js/numberFormat.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-number-format.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.inputmask.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            /*DEFAULT */
            $('#positionAmount').val(1);
            $("#posSection input[name='links[]']").val("");
            $("#posSection input[name='buyingPrice[]']").val("");
            $("#posSection input[name='sellingPrice[]']").val("");

            $(".profit").val("");
            /*END DEFAULT */
            $("#organization").select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                },
                allowClear: true,
                placeholder: 'Select an organization'

            });


            $("#company_id").change(function() {
                if ($(this).val() === '1') {
                    $('#offer_no').inputmask('integer', {
                        mask: 'AG-9999999',
                        allowMinus: false,
                        rightAlign: false
                    });
                } else if ($(this).val() === '') {
                    $('#offer_no').inputmask("remove");
                } else {
                    $('#offer_no').inputmask('integer', {
                        mask: 'AG-99999999',
                        allowMinus: false,
                        rightAlign: false
                    });
                }
            });

            // Helper convertion functions
            function convertToCurrency(value) {
                return numberFormat(value, 2, ",", ".");
            }

            function convertToFloat(value) {
                return parseFloat(value.replace(/\./g, "").replace(/\,/g, "."));
            }

            var index = 2;

            let links = [];
            let buying = [];
            let selling = [];
            let profits = [];
            let amounts = [];

            function backupData() {
                links = [];
                buying = [];
                selling = [];
                profits = [];
                amounts = [];
                $.each($("#posSection input[name='links[]']"), function(index, element) {
                    links.push($(element).val());
                });
                $.each($("#posSection .offer-amount"), function(index, element) {
                    amounts.push($(element).val());
                });
                $.each($("#posSection input[name='buyingPrice[]']"), function(index, element) {
                    buying.push($(element).val());
                });
                $.each($("#posSection input[name='sellingPrice[]']"), function(index, element) {
                    selling.push($(element).val());
                });
                $.each($("#posSection .profit"), function(index, element) {
                    profits.push($(element).val());
                });
            }

            function restoreData() {
                $.each($("#posSection input[name='links[]']"), function(index, element) {
                    $(element).val(links[index]);
                });
                $.each($("#posSection .offer-amount"), function(index, element) {
                    $(element).val(amounts[index]);
                });
                $.each($("#posSection input[name='buyingPrice[]']"), function(index, element) {
                    $(element).val(buying[index]);
                });
                $.each($("#posSection input[name='sellingPrice[]']"), function(index, element) {
                    $(element).val(selling[index]);
                });
                $.each($("#posSection .profit"), function(index, element) {
                    $(element).val(profits[index]);
                });
            }

            $(document).on("change", "#positionAmount", function() {
                backupData();
                let amount = $(this).val();

                let default_html =
                    `<div class="form-group row">
                        <div class="col-md-1 col-lg-1 px-0">
                            <span class="form-label pt-2">#1</span>
                        </div>
                            <div class="col-md-2 col-lg-2 px-1">
                            <input name="links[]" class="form-control" placeholder="Link" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input name="amounts[]" class="form-control offer-amount" 
                                type='number' placeholder="Amount" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input name="buyingPrice[]" class="form-control offer-buy" 
                                data-type='currency' placeholder="Buying" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input name="sellingPrice[]" class="form-control offer-sell" 
                                data-type='currency' placeholder="Selling" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input class="form-control profit" data-type='currency' 
                                placeholder="Profit" disabled>
                        </div>
                        <div class="col-md-1 col-lg-1 px-1 my-auto">
                            <button type="button" id="addButton" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>`;
                $('#posSection').html(default_html);
                index = 2;
                for (let i = 0; i < amount - 1; i++) {
                    let html =
                        `<div class="form-group row addedSection">
                            <div class="col-md-1 col-lg-1 px-0">
                                <span class="form-label indexValue pt-2"> #${index}</span>
                            </div>
                                <div class="col-md-2 col-lg-2 px-1">
                                <input name="links[]" class="form-control" placeholder="Link" autocomplete="off">
                            </div>
                            <div class="col-md-2 col-lg-2 px-1">
                                <input name="amounts[]" class="form-control offer-amount" 
                                    type='number' placeholder="Amount" autocomplete="off">
                            </div>
                            <div class="col-md-2 col-lg-2 px-1">
                                <input name="buyingPrice[]" class="form-control offer-buy" 
                                    data-type='currency' placeholder="Buying" autocomplete="off">
                            </div>
                            <div class="col-md-2 col-lg-2 px-1">
                                <input name="sellingPrice[]" class="form-control offer-sell" 
                                    data-type='currency' placeholder="Selling" autocomplete="off">
                            </div>
                            <div class="col-md-2 col-lg-2 px-1">
                                <input class="form-control profit" data-type='currency' placeholder="Profit" disabled>
                            </div>
                            <div class="col-md-1 col-lg-1 px-1 my-auto">
                                <button data-id="${index}" type="button" 
                                    class="btn btn-sm btn-danger removeButton">
                                    <i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>`;
                    $('#posSection').append(html);
                    index++;
                }
                restoreData();
                $('.profit').trigger("change");


            });

            $(document).on("change keyup",
                `   #posSection input[name='buyingPrice[]'],
                    #posSection input[name='sellingPrice[]'], 
                    #posSection input[name='amounts[]']`,
                function() {
                    let parent = $(this).parent().parent();
                    let buyingPrice = parent.find(".offer-buy").val();
                    let sellingPrice = parent.find(".offer-sell").val();
                    let amount = parent.find(".offer-amount").val();

                    if (buyingPrice !== "" && sellingPrice !== "") {

                        let buyingPriceFloat = convertToFloat(buyingPrice);
                        let sellingPriceFloat = convertToFloat(sellingPrice);
                        let profit = (sellingPriceFloat - buyingPriceFloat) * amount;

                        parent.find(".profit").val(numberFormat(profit, 2, ",", "."));
                    } else {
                        parent.find(".profit").val("");
                    }
                    $('.profit').trigger("change");
                });

            $(document).on("change", "#posSection .profit", function() {
                let profit = getTotalPrices("profit");
                let buy = getTotalPrices("offer-buy");
                let sell = getTotalPrices("offer-sell");

                $('#totalProfit').html(profit);
                $('#totalBuy').html(buy);
                $('#totalSell').html(sell);
            });

            function getTotalPrices(classname) {
                let totalPrice = 0;

                $.each($(`#posSection .${classname}`), function(index, element) {
                    let amount = 1;
                    let price = $(element).val();

                    if (classname != "profit") {
                        let parent = $(element).parent().parent();
                        amount = parent.find(".offer-amount").val();
                    }

                    if ($(element).val() !== "") {
                        price = convertToFloat(price);
                        totalPrice += price * amount;
                    }
                });

                return convertToCurrency(totalPrice)
            }

            $(document).on("click", "#addButton", function() {
                let html =
                    `<div class="form-group row addedSection">
                        <div class="col-md-1 col-lg-1 px-0">
                            <span class="form-label pt-2 indexValue"> #${index} </span>
                        </div>
                        <div class="col-md-2 col-lg-2 px-1" >
                            <input name="links[]" class="form-control" placeholder="Link" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input name="amounts[]" class="form-control offer-amount" type='number' 
                                placeholder="Amount" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input name="buyingPrice[]" class="form-control offer-buy" data-type='currency' 
                                placeholder="Buying" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input name="sellingPrice[]" class="form-control offer-sell" data-type='currency' 
                                placeholder="Selling" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-lg-2 px-1">
                            <input class="form-control profit" data-type='currency' 
                                placeholder="Profit" disabled>
                        </div>
                        <div class="col-md-1 col-lg-1 my-auto px-1">
                            <button type="button" class="btn btn-sm btn-danger removeButton">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </div>`;
                $('#posSection').append(html);

                $('#positionAmount').val(index);
                index++;
                buyingPrice = "";
                sellingPrice = "";

            });
            $(document).on("click", ".removeButton", function() {
                $(this).closest(".addedSection").remove();

                index--;
                $.each($('.addedSection'), function(i, value) {
                    i = i + 2;
                    $(value).find('.indexValue').html("#" + i);
                });

                $('#positionAmount').val(index - 1);

                $('.profit').trigger("change");
            });
            $(window).on("resize", function() {
                var organization_parents = $('#select2-organization-container').parents();
                $(organization_parents[2]).css("width", $(organization_parents[3]).width());
            });

            var oneTimeGlobal = 0;

            function validate(elementIds, oneTime) {
                let isTrue = true;
                oneTimeGlobal = 1;
                $.each(elementIds, function(index, value) {
                    if (value.indexOf("select2") === -1) {
                        let element = $("#" + value);
                        if (element.val() === "") {
                            element.addClass("error-border");
                            isTrue = false;

                        }
                        if (oneTime !== 1) {
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
                        let element = $('#' + value.replace("select2", ""));
                        if (element.val() === null) {
                            element.parent().addClass("error-border");
                            isTrue = false;

                        }
                        if (oneTime !== 1) {
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
                return isTrue;
            }

            function validateOfferDatas() {
                let isTrue = [];
                $.each($("input[name='links[]']"), function(index, element) {
                    if ($(element).val() !== "") {
                        let buying = $(element).parent().parent().find("input[name='buyingPrice[]']");
                        let selling = $(element).parent().parent().find("input[name='sellingPrice[]']");
                        if (buying.val() === "" || selling.val() === "") {
                            if (buying.val() === "") {
                                buying.addClass("error-border");
                                isTrue.push(false);
                            }
                            if (selling.val() === "") {
                                selling.addClass("error-border");
                                isTrue.push(false);
                            }
                        }
                    } else {
                        isTrue.push(true);
                    }
                });
                if (isTrue.indexOf(false) === -1) {
                    return true;
                } else {
                    return false;
                }
            }

            var isOfferNumberUnique = true;
            $('#offer_no').on("change", function() {
                let offer_no = $(this).val();
                if (offer_no !== "") {
                    $.ajax({
                        url: "/offer/isOfferNumberUnique/" + offer_no,
                        type: "get",
                        dataType: "json",
                        success: function(response) {
                            if (response !== "" || response !== undefined) {
                                if (response.isUnique) {
                                    isOfferNumberUnique = true;
                                    $('#offer_no').prev().remove();
                                } else {
                                    isOfferNumberUnique = false;
                                    $('#offer_no').before(
                                        "<span class='badge badge-danger'>Offer number is already exist!</span>"
                                    );
                                }
                            }
                        }
                    });
                } else {
                    $('#offer_no').prev().remove();
                }
            });

            $("#submitButton").on("click", function() {
                let isTrue = validate([
                    "organizationselect2",
                    "company_id",
                    "offer_no",
                    "offer_amount",
                    "offer_date",
                    "status"
                ], oneTimeGlobal);
                if (isTrue && isOfferNumberUnique && validateOfferDatas()) {
                    $('#formSection').submit();
                }
            });
        });
    </script>
@endsection
