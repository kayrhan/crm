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
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Update Receipt</h3>
                <div class="text-right">
                    <div class="float-right ml-4">
                        <a class="btn btn-info" href="{{ url('/hospitality-receipt') }}"><i class="fa fa-backward mr-1"></i>Back</a>
                    </div>
                    <div class="float-right ml-4">
                        <a class="btn btn-success" href="{{ '/uploads/' . $hospitality->file_name }}" target="_blank"><i class="fa fa-file-pdf-o mr-1"></i>PDF</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="hospitality-update-form" action="{{ url('/hospitality-receipt/update') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="reason" class="col-md-2 form-label my-auto">Anlass der Bewirtung<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea id="reason" name="reason" class="form-control validate[required] maxSize[255]">{{$hospitality->reason}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="place-of-stay" class="col-md-2 form-label my-auto">ORT der Bewirtung<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea id="place-of-stay" name="place_of_stay" class="form-control validate[required] maxSize[500]">{{$hospitality->place_of_stay}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label for="address" class="col-md-2 form-label my-auto">ORT<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea id="address" name="address" class="form-control validate[required] maxSize[255]">{{$hospitality->address}}</textarea>
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
                                            <input type="text" id="host" name="host" class="form-control validate[required]" placeholder="Hosting Person" value="{{$hospitality->host}}" maxlength="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="visitor-section">

                                @foreach($hospitality->hospitalityVisitors as $visitor)
                                @if($loop->first)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto">Bewirtete Personen<span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="visitor[{{$visitor->id}}]" class="form-control validate[required]" placeholder="Visitors" value="{{$visitor->visitor}}" maxlength="100">
                                            </div>
                                            <div class="col-md-1">
                                                <a class="btn btn-sm @if($loop->count == 1) btn-success @else btn-danger @endif p-2" @if($loop->count == 1) onclick="addVisitor()" @else onclick="removeCurrentVisitor({{$visitor->id}})" @endif><i class="fa @if($loop->count == 1) fa-plus @else fa-minus @endif"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto">Bewirtete Personen<span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="visitor[{{$visitor->id}}]" class="form-control validate[required]" placeholder="Visitors" value="{{$visitor->visitor}}" maxlength="100">
                                            </div>
                                            <div class="col-md-1">
                                                <a class="btn btn-sm @if($loop->last) btn-success @else btn-danger @endif p-2" @if($loop->last) onclick="addVisitor()" @else onclick="removeCurrentVisitor({{$visitor->id}})" @endif><i class="fa @if($loop->last) fa-plus @else fa-minus @endif"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="date" class="col-md-2 form-label my-auto">Datum<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="date" class="form-control validate[required]" name="date" id="date" value="{{$hospitality->date}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row standard-input-field-border">
                                        <label for="day" class="col-md-2 form-label my-auto">Tag der Bewirtung<span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control validate[required] min[1] max[100000]" name="day" id="day" placeholder="Visiting Day" value="{{$hospitality->day}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label for="currency" class="col-md-2 form-label my-auto">Zahlung<span class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input type="text" id="receipt-amount" name="receipt_amount" class="form-control validate[required]" data-type="currency" placeholder="Höhe der Aufwendungen" value="{{ number_format($hospitality->receipt_amount, 2, ',', '.') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" id="tip" name="tip" class="form-control" data-type="currency" placeholder="Trinkgeld" value="{{ number_format($hospitality->tip, 2, ',', '.') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" id="total-amount" name="total_amount" class="form-control" data-type="currency" placeholder="Gesamtbetrag" value="{{ number_format($hospitality->total_amount, 2, ',', '.') }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="currency" id="currency" style="max-height: 33px;" required>
                                                <option value="TRY" @if($hospitality->currency == "TRY") selected @endif>TRY ₺</option>
                                                <option value="EUR" @if($hospitality->currency == "EUR") selected @endif>EUR €</option>
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
                                <input type="hidden" name="id" value="{{ $hospitality->id }}">
                                <button type="button" id="update-receipt-button" class="btn btn-success mt-4 mb-0 float-right">Save</button>
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
                let html = `<div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-2 form-label my-auto">Bewirtete Personen<span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" name="new_visitor[]" class="form-control validate[required]" placeholder="Visitors" maxlength="100">
                                        </div>
                                        <div class="col-md-1">
                                            <a class="btn btn-sm btn-success p-2" onclick="addVisitor()"><i class="fa fa-plus"></i></a>
                                        </div>
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

        function removeCurrentVisitor(ID) {
            confirmModal('This user has registered! Are you sure that you want to delete the visitor? <br> <span class=" d-block mt-0 ml-5 text-left" >PDF will be updated!</span>',"Remove Reference","Delete","Close","#0275d8","#d9534f",600).then(function() {
                    $.ajax({
                        url:'/hospitality-receipt/delete-visitor',
                        type: 'POST',
                        data: {
                            id: ID,
                            _token: "{{csrf_token()}}",
                        },
                    }).done(function() {
                        location.reload();
                        toastr.success("Visitor deleted successfully!", "Success");
                    }).error(function() {
                        toastr.error("Visitor deletion has failed!", "Error");
                    });
            });
        }

        $(document).ready(function() {
            $(document).on('keyup change', '#receipt-amount, #tip', function() {
                calculateTotalAmount();
            });

            $('#update-receipt-button').on('click', function() {
                var validate = $('#hospitality-update-form').validationEngine('validate', {
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
                }else{
                    confirmModal('PDF will be updated!',"Are you sure?","Update","Close").then(function() {
                        $('#hospitality-update-form').submit();
                    });
                }
            });
        })
    </script>
@endsection