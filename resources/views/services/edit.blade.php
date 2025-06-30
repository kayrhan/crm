@extends("layouts.master")
@section("css")
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("text-editor/trumbowyg.min.css") }}">
    <link rel="stylesheet" href="{{ asset("drop-zone/dropzone.css") }}">
    <style>
        .trumbowyg-box {
            min-height: 80px !important;
            width: 100% !important;
        }

        .trumbowyg-editor {
            min-height: 170px !important;
            max-height: 450px !important;
            resize: vertical !important;
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

        thead, tbody tr td, tbody tr td:last-child {
            pointer-events: none !important;
        }

        tbody tr td:last-child button, tbody tr td:last-child a {
            pointer-events: auto !important;
        }
    </style>
@endsection
@section("content")
    <div class="row mt-3">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Update Service</h3>
                    <div>
                        <a href="{{ url("/services") }}" class="btn btn-info">
                            <i class="fa fa-backward mr-1"></i>{{ ucfirst(trans("words.back")) }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formSection" method="POST" action="{{ url("/services/" . $service->id) }}">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto" for="title">Title</label>
                                            <div class="col-md-10">
                                                <input id="title" type="text" name="title" class="form-control privateValidate" placeholder="Title" value="{{ $service->title }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto" for="access-link">Access Link</label>
                                            <div class="col-md-10">
                                                <input id="access-link" type="url" name="access-link" class="form-control privateValidate" placeholder="Access Link" value="{{ $service->access_link }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label for="description" class="col-md-2 form-label my-auto">Description</label>
                                            <div class="col-md-10">
                                                <textarea id="description" name="description" class="form-control privateValidate">{{ $service->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(count($service_attachments) > 0)
                                <div class="row">
                                    <div class="table-responsive border">
                                        <table class="table table-bordered text-wrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="w-10">Order</th>
                                                    <th class="w-30">Name</th>
                                                    <th class="w-10">Size</th>
                                                    <th class="w-30">Upload Date</th>
                                                    <th class="w-20">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($service_attachments as $attachment)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ substr($attachment->file_name, 0, 20) }}</td>
                                                    <td>{{ round($attachment->size * 0.000001, 2) }} MB</td>
                                                    <td>{{ \Carbon\Carbon::parse($attachment->created_at)->format("d.m.Y H:i:s") }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-around align-items-center">
                                                            <button class="btn btn-sm btn-danger delete-service-attachment" data-id="{{ $attachment->id }}" type="button"><i class="fa fa-trash"></i></button>
                                                            <a href="{{ route("uploads", [$attachment->file_name]) }}" target="_blank" class="btn btn-sm btn-azure"><i class="fa fa-eye"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto" for="provider">Provider
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input id="provider" type="text" name="provider" class="form-control privateValidate" placeholder="Provider" value="{{ $service->provider }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label for="organization" class="col-md-2 form-label my-auto">Organization
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <select name="organization" id="organization" class="form-control privateValidate">
                                                    @foreach($organizations as $organization)
                                                    <option value="{{ $organization->id }}" @if($service->organization_id == $organization->id) selected="selected" @endif>{{ $organization->org_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label for="service-type" class="col-md-2 form-label my-auto">Service Type
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <select id="service-type" name="service-type" class="form-control privateValidate">
                                                    @foreach($service_types as $service_type)
                                                    <option value="{{ $service_type->id }}" @if($service->service_type == $service_type->id) selected="selected" @endif>{{ $service_type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto" for="service-amount">Service Amount
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-8">
                                                <input id="service-amount" name="service-amount" class="form-control privateValidate" data-type="currency" placeholder="Service Amount" value="{{ number_format($service->service_amount, 2, ",", ".") }}">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" name="currency" id="currency" style="max-height: 33px;" required>
                                                    <option value="TRY" @if($service->currency == "TRY") selected @endif>TRY ₺</option>
                                                    <option value="EUR" @if($service->currency == "EUR") selected @endif>EUR €</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto" for="beginning-date">Beginning Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input id="beginning-date" name="beginning-date" type="date" class="form-control privateValidate" value="{{ $service->beginning_date }}" required="required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto" for="expiring-date">Expiring Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input id="expiring-date" name="expiring-date" type="date" class="form-control privateValidate" value="{{ $service->expiring_date }}" required="required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="attachment-response"></div>
                    </form>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <label class="form-label">Attachments <span class="text-danger">(Maxium 5 Files & 10 MB)</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <form class="dropzone" id="service-attachments">
                            @csrf
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <a href="{{ url("/services") }}" class="btn btn-danger mt-4 mb-0 float-right">{{ trans("words.cancel") }}</a>
                                    <button type="button" id="submitButton" class="btn btn-success mt-4 mb-0 float-right mr-4">{{ trans("words.save") }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ asset("drop-zone/dropzone.js") }}"></script>
<script src="{{ asset("assets/plugins/select2/select2.full.min.js") }}"></script>
<script src="{{ asset("assets/js/select2.js") }}"></script>
<script src="{{ asset("assets/js/jquery.priceformat.min.js") }}"></script>
<script src="{{ asset("assets/js/jquery.inputmask.min.js") }}"></script>
<script src="{{ asset("assets/js/numberFormat.js") }}"></script>
<script src="{{ asset("assets/js/custom-number-format.js") }}"></script>
<script src="{{ asset("text-editor/trumbowyg.min.js") }}"></script>
<script>
    Dropzone.autoDiscover = false;

    function validate() {
        let provider = $("#provider");
        let service_type = $("#service-type");
        let service_amount = $("#service-amount");
        let date = $("#beginning-date");
        let expired_date = $("#expiring-date");
        let currency = $("#currency");

        if(provider.val() === ""){
            provider.addClass("error-border");
            return false;
        }
        if(service_type.val() === ""){
            service_type.addClass("error-border");
            return false;
        }

        if(service_amount.val() === ""){
            service_amount.addClass("error-border");
            return false;
        }
        if(date.val() === ""){
            date.addClass("error-border");
            return false;
        }

        if(expired_date.val() === ""){
            expired_date.addClass("error-border");
            return false;
        }

        if(currency.val() === "") {
            currency.addClass("error-border");
            return false;
        }

        return true;
    }

    $(document).ready(function () {
        $('#description').trumbowyg({
            autogrow: true,
            removeformatPasted: true,
            btns:[
                ['viewHTML'],
                ['formatting'],
                ['strong', 'em', 'del'],
                ['link'],
                ['insertImage'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['fullscreen']
            ]
        });

        $("#service-attachments").dropzone({
            maxFiles: 5,
            parallelUploads: 10,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 10,
            timeout: 180000000,
            acceptedFiles: "image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.webm,.zip,.rar,.msg,.7z,.tar",
            url: "/attachFiles",
            success: function (file, response) {
                if(response.error) {
                    toastr.error(response.error, "Error");
                }
                else {
                    $.each(response.data, function (key, data) {
                        $('#attachment-response').append('<input type="hidden" name="service-attachments[' + data.size + ']" value="' + data.link + '"/>');
                    });

                    toastr.success(response.success, "Success");
                }
            }
        });

        $("#submitButton").on("click",function(){
            if(validate()) {
                $('#formSection').submit();
            }
        });

        $('.privateValidate').on("keyup change",function() {
            $(this).removeClass("error-border");
        });

        $(".delete-service-attachment").on("click", function() {
            let attachment_id = $(this).data("id");
            confirmModal("The attachment will be deleted!", "Are you sure?", "Delete", "Close", "#0275d8", "#d9534f").then(function() {
                $.ajax({
                    url: "/services/delete/attachments/" + attachment_id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response !== undefined && response !== "") {
                            if(response.success === 1) {
                                toastr.success("The attachment deleted successfully!", "Success");
                                location.reload();
                            }
                            else {
                                toastr.error("Something went wrong!", "Error");
                            }
                        }
                        else {
                            toastr.error("Something went wrong!", "Error");
                        }
                    }
                });
            });
        });
    });
</script>
@endsection