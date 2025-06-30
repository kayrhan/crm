@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('text-editor/trumbowyg.min.css') }}">
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
                    <h3 class="card-title">{{ ucfirst(trans('words.update_offer')) }}</h3>
                    <div>
                        <a href="{{ url('/offers') }}" class="btn btn-info"><i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formSection" action="{{ route('offers.update.post') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input id="offer_id" name="offer_id" type="hidden" value="{{ $offer->id }}">

                            {{-- LEFT PART --}}
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.customer')) }}
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <select id="organization" name="org_id" class="form-control" disabled>
                                                    <option value="{{ $offer->org_id }}" selected="selected">
                                                        {{ App\Organization::where('id', $offer->org_id)->value('org_name') }}
                                                    </option>

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
                                                <select id="company_id" name="company_id" class="form-control" disabled>
                                                    <option value="" disabled>Select a company</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}"
                                                            {{ $company->id == $offer->company_id ? 'selected' : '' }}>
                                                            {{ $company->name }}</option>
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
                                                <input name="offer_no" class="form-control" placeholder="Offer Number"
                                                    value="{{ $offer->offer_no }}" disabled>
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
                                                <input id="offer_amount" name="offer_amount" class="form-control"
                                                    data-type="currency" placeholder="Offer Amount"
                                                    value="{{ $offer->offer_amount }}" disabled>
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
                                                    value="{{ $offer->offer_date }}" placeholder="Offer Date" disabled>
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
                                            <label class="col-md-3 form-label my-auto">Additional Info</label>
                                            <div class="col-md-9">
                                                <textarea id="description" name="description" class="form-control"
                                                    rows="8">{{ $offer->additional_info }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            {{-- RIGHT PART --}}
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <table class="table text-center " id="offerDataMainTable">
                                            <thead>
                                                <tr style="cursor: default;">
                                                    <th class="wd-5 px-0" scope="col">Pos</th>
                                                    <th class="w-15 px-0" scope="col">Link</th>
                                                    <th class="w-15 px-0" scope="col">Amount</th>
                                                    <th class="w-20 px-0" scope="col">Buying</th>
                                                    <th clasS="w-20 px-0" scope="col">Selling</th>
                                                    <th clasS="w-20 px-0" scope="col">Profit</th>
                                                    <th clasS="wd-5 px-0" scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="offerDataTable">
                                                @php
                                                    $total = count($offer_datas);
                                                @endphp
                                                @foreach ($offer_datas as $offer_data)

                                                    <tr class="h-4" style="cursor: default;">
                                                        <th scope="row" class="indexValue px-0">#{{ $loop->index + 1 }}
                                                        </th>
                                                        <td class="px-0">
                                                            @if (isset($offer_data->link))
                                                                <a class="text-primary" href="{{ $offer_data->link }}"
                                                                    target="_blank">{!! parse_url($offer_data->link)['host'] ?? "<span class='text-danger'>Invalid url</span>" !!}</a>
                                                            @endif
                                                        </td>
                                                        <td class="px-0">{{ $offer_data->amount }}
                                                            <input type="hidden" class="offer-amount"
                                                                value="{{ $offer_data->amount }}">
                                                        </td>
                                                        <td class="px-0">
                                                            {{ number_format($offer_data->buying_price, 2, ',', '.') }}
                                                            <input type="hidden" class="offer-buy"
                                                                value="{{ number_format($offer_data->buying_price, 2, ',', '.') }}">
                                                        </td>
                                                        <td class="px-0">
                                                            {{ number_format($offer_data->selling_price, 2, ',', '.') }}
                                                            <input type="hidden" class="offer-sell"
                                                                value="{{ number_format($offer_data->selling_price, 2, ',', '.') }}">
                                                        </td>
                                                        <td class="px-0">
                                                            @php
                                                                $profit = $offer_data->selling_price - $offer_data->buying_price;
                                                                $profit *= $offer_data->amount;
                                                            @endphp

                                                            {{ number_format($profit, 2, ',', '.') }}
                                                            <input type="hidden" class="profit"
                                                                value="{{ number_format($profit, 2, ',', '.') }}">
                                                        </td>
                                                        <td class="px-0">
                                                            <div>
                                                                <a data-id="{{ $offer_data->id }}"
                                                                    data-pos="{{ $loop->index + 1 }}"
                                                                    class="offerDataUpdate text-info" role="button"><i
                                                                        class="fa fa-pencil"></i></a>
                                                                <a data-id="{{ $offer_data->id }}"
                                                                    class="offerDataDelete text-danger" role="button"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            {{-- Offer Data Form --}}
                                            <tfoot id="posSection" style="cursor:default;">
                                                <tr>
                                                    <th class="px-0" scope="row">#{{ $total + 1 }}</th>
                                                    <td class="px-1">
                                                        <input id="linkDefault" name="links[]" class="form-control"
                                                            placeholder="Link">
                                                    </td>
                                                    <td class="px-1">
                                                        <input type="number" id="amountDefault" name="amounts[]"
                                                            class="form-control offer-amount" value="1" placeholder="Amount"
                                                            autocomplete="off">
                                                    </td>
                                                    <td class="px-1">
                                                        <input id="buyingDefault" name="buyingPrice[]"
                                                            class="form-control offer-buy" data-type="currency"
                                                            placeholder="Buying" autocomplete="off">
                                                    </td>
                                                    <td class="px-1">
                                                        <input id="sellingDefault" name="sellingPrice[]"
                                                            class="form-control offer-sell" data-type="currency"
                                                            placeholder="Selling" autocomplete="off">
                                                    </td>
                                                    <td class="px-1">
                                                        <input id="profitDefault" class="form-control profit"
                                                            data-type="currency" placeholder="Profit" disabled>
                                                    </td>
                                                    <td class="px-1">
                                                        <button type="button" id="addButton"
                                                            class="btn btn-sm btn-success"><i
                                                                class="fa fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <table class="table text-center">
                                            <tbody>
                                                <tr style="cursor: default">
                                                    <td class="w-35 text-left pl-4">Total:</td>
                                                    <td class="w-20 px-0" id="totalBuy">
                                                        {{ number_format($total_buy, 2, ',', '.') }}</td>
                                                    <td class="w-20 px-0" id="totalSell">
                                                        {{ number_format($total_sell, 2, ',', '.') }}</td>
                                                    <td class="w-20 px-0" id="totalProfit">
                                                        {{ number_format($total_profit, 2, ',', '.') }}</td>
                                                    <td class="wd-5 px-0 text-white">Action</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <x-attachment-list type="Offer" ownerid="{{$offer->id}}" buttonclass="submitButton" />
                                <x-attachment type="Offer" buttonclass="submitButton" formid="formSection" maxsize="25" maxfiles="4" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <button type="button" class="btn btn-success mt-4 mb-0 float-right submitButton" id="submitButton">{{ trans('words.save') }}</button>
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

    {{-- Offer Update Modal --}}
    <div class="modal fade " id="offerDataUpdateModal" tabindex="-1" role="dialog"
        aria-labelledby="offerDataUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="offerDataUpdateModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <input type="text" id="updateLink" class="form-control mr-1" placeholder="Link">
                        <input type="number" id="updateAmount" class="form-control mr-1" placeholder="Amount">
                        <input type="text" id="updateBuyingPrice" class="form-control mr-1" data-type="currency"
                            placeholder="Buying">
                        <input type="text" id="updateSellingPrice" class="form-control mr-1" data-type="currency"
                            placeholder="Selling">
                        <input type="text" id="updateProfit" class="form-control" data-type="currency"
                            placeholder="Profit" disabled>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="updateSendButton" type="button" class="btn btn-success">Save changes</button>
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
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script src="{{ URL::asset('assets/js/numberFormat.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-number-format.js') }}"></script>

    <script>
        // Helper convertion functions
        function convertToCurrency(value){
            return numberFormat(value, 2, ",", ".");
        }
        function convertToFloat(value){
            return parseFloat(value.replace(/\./g, "").replace(/\,/g, "."));
        }

        function resetPositions(constantIndex) {
            $.each($('#posSection tr'), function(i, value) {
                i = i + constantIndex;
                $(value).find("th").html("#" + i);
            });
        }

        function getTotalPrices(classname) {

            let totalPrice = 0;
            $.each($(`#offerDataMainTable .${classname}`), function(index, element) {
                let amount = 1;
                let price = $(element).val();

                if (classname != "profit") {
                    let parent = $(element).parent().parent();
                    amount = parent.find(".offer-amount").val();
                }
                if (price !== "") {
                    price = convertToFloat(price);
                    totalPrice += price * amount;
                }
            });

            return convertToCurrency(totalPrice)
        }

        function setTotalProfit() {
            let totalProfit = getTotalPrices("profit");
            let totalBuy = getTotalPrices("offer-buy");
            let totalSell = getTotalPrices("offer-sell");

            $('#totalProfit').html(totalProfit);
            $('#totalBuy').html(totalBuy);
            $('#totalSell').html(totalSell);
        }

        function regenerateOfferData(response) {
            let html = "";
            $.each(response, function(index, value) {
                let hostname;
                let href = $('<a>', {
                    href: value.link
                });
                hostname = href.prop("hostname");
                if (hostname === "") {
                    hostname = "<span class='text-danger'>Invalid url</span>"
                }

                html +=
                    `<tr class="h-4" style="cursor: default;">
                        <th scope="row" class="px-0"> #${index + 1} </th>
                        <td>
                            <a class="text-primary" href="${value.link}" target="_blank"> ${hostname} </a>
                        </td>
                        <td class="px-0">
                            ${value.amount}
                            <input type="hidden" class="offer-amount" value="${value.amount}">
                        </td>
                        <td class="px-0">
                            ${convertToCurrency(value.buying_price)}
                            <input type="hidden" class="offer-buy" value="${convertToCurrency(value.buying_price)}">
                        </td>
                        <td class="px-0">
                            ${convertToCurrency(value.selling_price)}
                            <input type="hidden" class="offer-sell" value="${convertToCurrency(value.selling_price)}">
                        </td>
                        <td class="px-0">
                            ${convertToCurrency((value.selling_price - value.buying_price) * value.amount)}
                            <input type="hidden" class="profit" value="${convertToCurrency((value.selling_price - value.buying_price) * value.amount) }">
                        </td>

                        <td class="px-0">
                            <div>
                                <a data-id="${value.id}" data-pos="${index + 1}"
                                    class="offerDataUpdate text-info" role="button">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a data-id="${value.id}" class="offerDataDelete text-danger" role="button">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>`;

            });

            $('#offerDataTable').html(html);
        }

        $(document).ready(function() {
            $('#company').val({{ $offer->company_id }});
            $('#status').val({{ $offer->status }});
            $('#linkDefault').val("");
            $('#buyingDefault').val("");
            $('#sellingDefault').val("");
            $('#profitDefault').val("");

            @if (session()->get('success') == 1)
                toastr.success("Offer successfully updated!", "Success!");
            @elseif(session()->get('success') == -1)
                toastr.error("An error thrown.Update is unsuccess!", "Error!");
            @endif



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

                        parent.find(".profit").val(convertToCurrency(profit));
                    } else {
                        parent.find(".profit").val("");
                    }
                    $('.profit').trigger("change");
                    setTotalProfit();
                });

            let index = {{ $total + 2 }};
            let constantIndex = {{ $total + 1 }};

            $(document).on("click", "#addButton", function() {
                let html =
                    `<tr>
                        <th scope="row" class="indexValue px-0"> #${index} </th>
                        <td class="px-1">
                            <input name="links[]" class="form-control" placeholder="Link">
                        </td>
                        <td class="px-1">
                            <input name="amounts[]" class="form-control offer-amount"
                                type='number' placeholder="Amount" autocomplete="off" value="1">
                        </td>
                        <td class="px-1">
                            <input name="buyingPrice[]" class="form-control offer-buy"
                                data-type='currency' placeholder="Buying" autocomplete="off">
                        </td>
                        <td class="px-1">
                            <input name="sellingPrice[]" class="form-control offer-sell"
                                data-type='currency' placeholder="Selling" autocomplete="off">
                        </td>
                        <td class="px-1">
                            <input class="form-control profit"
                                data-type='currency' placeholder="Profit" disabled>
                        </td>
                        <td class="px-1">
                            <button type="button" class="btn btn-sm btn-danger removeButton">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>`;
                $('#posSection').append(html);
                index++;
            });

            $(document).on("click", ".removeButton", function() {
                $(this).closest("tr").remove();
                index--;
                resetPositions(constantIndex);
                setTotalProfit();
            });

            // Offer Delete
            $(document).on("click", ".offerDataDelete", function() {
                let offer_data_id = $(this).data("id");
                let offer_id = $('#offer_id').val();
                confirmModal('This section will be delete!',"Are you sure?","Delete","Close","#0275d8","#d9534f").then(function() {
                    toggleLoader(true);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/offer/offerDataDelete",
                        data: {
                            offer_data_id: offer_data_id,
                            offer_id: offer_id,
                        },
                        type: "post",
                        dataType: "json",
                        success: function(response) {
                            if (response !== "" || response !== undefined) {
                                toggleLoader(false);

                                regenerateOfferData(response);
                                constantIndex--;
                                index--;
                                resetPositions(constantIndex);
                                setTotalProfit();
                                toastr.success("Delete Successfull", "Success!");

                            }
                        }
                    });
                });

            });


            // Offer Update
            $(document).on("click", ".offerDataUpdate", function() {
                let indexValue = $(this).data("pos");
                $('#offerDataUpdateModalLabel').html("Update #" + indexValue);
                let id = $(this).data("id");
                $("#offerDataUpdateModal").modal("show");
                $("#updateSendButton").data("id", id);
                $.ajax({
                    url: "/offer/getOfferData/" + id,
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        if (response !== undefined && response !== "") {
                            let buyingPrice = convertToCurrency(response.buying_price);
                            let sellingPrice = convertToCurrency(response.selling_price);
                            let amount = response.amount;

                            let profit = (response.selling_price - response.buying_price) * amount;
                            profit = convertToCurrency(profit);

                            $('#updateLink').val(response.link);
                            $('#updateAmount').val(amount);
                            $('#updateBuyingPrice').val(buyingPrice);
                            $('#updateSellingPrice').val(sellingPrice);
                            $("#updateProfit").val(profit)
                        } else {
                            toastr.error("An error thrown!", "Error");
                        }
                    }
                });
            });

            $("#updateAmount, #updateSellingPrice, #updateBuyingPrice").on("keyup change", function() {
                let sellingPrice = $("#updateSellingPrice").val();
                let buyingPrice = $("#updateBuyingPrice").val();
                let amount = $("#updateAmount").val();

                if (sellingPrice !== "NaN") {
                    sellingPrice = convertToFloat(sellingPrice);
                    buyingPrice = convertToFloat(buyingPrice)

                    let profit = (sellingPrice - buyingPrice) * amount;
                    profit = convertToCurrency(profit);
                    $("#updateProfit").val(profit);
                    $(this).removeClass("error-border");
                }
            })

            function updateDataValidate() {
                let updateBuyingPrice = $('#updateBuyingPrice');
                let updateSellingPrice = $('#updateSellingPrice');
                if (updateBuyingPrice.val() === "" || updateSellingPrice.val() === "") {
                    if (updateBuyingPrice.val() === "") {
                        updateBuyingPrice.addClass("error-border");
                        return false;
                    }
                    if (updateSellingPrice.val() === "") {
                        updateSellingPrice.addClass("error-border");
                        return false;
                    }
                } else {
                    return true;
                }
            }
            $('#updateSendButton').on("click", function() {
                toggleLoader(true);
                let id = $('#updateSendButton').data("id")
                let buyingPrice = $('#updateBuyingPrice').val();
                let sellingPrice = $('#updateSellingPrice').val();
                let link = $('#updateLink').val();
                let amount = $("#updateAmount").val();
                let offerId = $('#offer_id').val();
                if (updateDataValidate()) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/offer/updateOfferData",
                        type: "post",
                        data: {
                            data_id: id,
                            offer_id: offerId,
                            buying_price: buyingPrice,
                            selling_price: sellingPrice,
                            link: link,
                            amount: amount
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response !== undefined && response !== "") {
                                regenerateOfferData(response);
                                setTotalProfit();
                                toggleLoader(false);
                                toastr.success("Position updated successfully!", "Success");
                            }
                            else {
                                toggleLoader(false);
                                toastr.error("An error thrown!", "Error");
                            }
                        }
                    });

                    $("#offerDataUpdateModal").modal("hide");
                }

            });

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

            $('#submitButton').on("click", function() {


                if (validateOfferDatas()) {
                    $('#formSection').submit();
                }
            });


            $(window).on("resize", function() {
                var organization_parents = $('#select2-organization-container').parents();
                $(organization_parents[2]).css("width", $(organization_parents[3]).width());
            });


        });
    </script>
@endsection
