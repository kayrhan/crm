@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('text-editor/trumbowyg.min.css') }}">
<style>
    .trumbowyg-box, .trumbowyg-editor {
        min-height: 120px;
    }

    .trumbowyg-editor p img {
        width: 360px !important;
    }
</style>
@endsection
@section('page-header')
@endsection
@section('content')
<div class="row mt-4">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header  d-flex justify-content-between">
                <h3 class="card-title">New Receipt</h3>
                <a class="btn btn-info" href="{{ url('/hospitality-receipt') }}"><i class="fa fa-backward mr-1"></i>Back</a>
            </div>
            <div class="card-body">
                <form id="hospitality-create-form" action="{{ url('/hospitality-receipt/store') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="reason" class="col-md-2 form-label my-auto">Anlass der Bewirtung<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea id="reason" name="reason" class="form-control validate[required] maxSize[255]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="place-of-stay" class="col-md-2 form-label my-auto">ORT der Bewirtung<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea id="place-of-stay" name="place_of_stay" class="form-control validate[required] maxSize[500]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label for="address" class="col-md-2 form-label my-auto">ORT<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea id="address" name="address" class="form-control validate[required] maxSize[255]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="host" class="col-md-2 form-label my-auto">Bewirtende Person<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="text" id="host" name="host" class="form-control validate[required]" placeholder="Hosting Person" maxlength="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="visitor-section">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-2 form-label my-auto">Bewirtete Personen<span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" name="visitor[]" class="form-control validate[required]" placeholder="Visitors" maxlength="100">
                                        </div>
                                        <div class="col-md-1">
                                            <a class="btn btn-sm btn-success p-2" onclick="addVisitor()"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="date" class="col-md-2 form-label my-auto">Datum<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="date" class="form-control validate[required]" name="date" id="date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="day" class="col-md-2 form-label my-auto">Tag der Bewirtung<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control validate[required] min[1] max[100000]" name="day" id="day" placeholder="Visiting Day">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label for="currency" class="col-md-2 form-label my-auto">Zahlung<span class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input type="text" id="receipt-amount" name="receipt_amount" class="form-control validate[required]" data-type="currency" placeholder="Höhe der Aufwendungen">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" id="tip" name="tip" class="form-control" data-type="currency" placeholder="Trinkgeld">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" id="total-amount" name="total_amount" class="form-control" data-type="currency" placeholder="Gesamtbetrag" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="currency" id="currency" style="max-height: 33px;" required>
                                                <option value="TRY">TRY ₺</option>
                                                <option value="EUR" selected>EUR €</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 d-flex align-items-end justify-content-end">
                            <div>
                                <button type="submit" class="btn btn-success mt-4 mb-0 float-right">Save</button>
                                <a href="{{ url('/hospitality-receipt') }}" class="btn btn-danger mt-4 mb-0 mr-4 float-right">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('text-editor/trumbowyg.min.js') }}"></script>
<script src="{{ asset('assets/js/numberFormat.js') }}"></script>
<script>
    const textarea_variables = {
        autogrow: false,
        resetCss: false,
        removeformatPasted: true,
        semantic: false,
        btns: [
            ['viewHTML'],
            ['fullscreen']
        ],
        tagsToKeep: [
            'p',
            'br'
        ],
    };

    $('textarea').trumbowyg(textarea_variables).on('tbwblur', function() {
        let text = $(this).parent().find('.trumbowyg-editor').html();
        $(this).val(text).trigger('change');
    });

    function addVisitor() {
        let last = $('#visitor-section :input').last();
        let status = 1;

        if($(last).val() === "") {
            status = 0;
            $(last).addClass('standard-input-field-error-border');
        }

        if(status === 0) {
            return false;
        }
        else {
            $(last).removeClass('standard-input-field-error-border');
            let html =  `<div class="col-lg-12 col-md-12">
                            <div class="form-group row border-bottom">
                                <label class="col-md-2 form-label my-auto">Bewirtete Personen<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="visitor[] validate[required]" class="form-control" placeholder="Visitors" maxlength="100">
                                </div>
                                <div class="col-md-1">
                                    <a class="btn btn-sm btn-success p-2" onclick="addVisitor(this)"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>`

            let visitor_section = document.getElementById('visitor-section');
            let remove = $(visitor_section).children();

            $.each(remove, function(index, value) {
               let element = $(value).find('a');
                $(element).removeClass("btn-success").addClass("btn-danger");
                $(element).attr("onclick", "removeVisitor(this)");
                $(element.children()[0]).addClass("fa-minus").removeClass("fa-plus");
            });

            $('#visitor-section').append(html);
        }
    }

    function removeVisitor(element) {
        $(element).parent().parent().remove();
    }

    function calculateTotalAmount() {
        let receipt_amount = $('#receipt-amount').val();
        let tip = $('#tip').val();

        if(tip === "") {
            tip = "0,00"
        }

        let total_amount = parseFloat(stripePrice(receipt_amount)) + parseFloat(stripePrice(tip));

        $('#total-amount').val(numberFormat(total_amount, 2, ",", "."));
    }

    function stripePrice(number) {
        return number.replace(".", "").replace(",", ".");
    }

    $(document).ready(function() {
        $(document).on('keyup change', '#receipt-amount, #tip', function() {
            calculateTotalAmount();
        });

        $(document).on('keyup change', 'input[name="visitor[]"]', function() {
            $(this).removeClass('standard-input-field-error-border');
        });

        $('#hospitality-create-form').on('submit', function() {
            var validate = $('#hospitality-create-form').validationEngine('validate', {
                promptPosition: "bottomRight",
                scroll: false
            });
            let last = $('#visitor-section :input').last();
            let status = 1;

            if($(last).val() === "") {
                status = 0;
                $(last).addClass('standard-input-field-error-border');
            }

            if(!validate || status === 0) {
                return false;
            }
        });
    })
</script>
@endsection