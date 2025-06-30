@extends("layouts.master")
@section("css")
<link href="{{ asset("assets/plugins/datatable/css/dataTables.bootstrap4.min.css") }}" rel="stylesheet"/>
<link href="{{ asset("assets/plugins/datatable/css/buttons.bootstrap4.min.css") }}" rel="stylesheet">
<link href="{{ asset("assets/plugins/datatable/responsive.bootstrap4.min.css") }}" rel="stylesheet"/>
<link href="{{ asset("text-editor/trumbowyg.min.css") }}" rel="stylesheet">
<link href="{{ asset("assets/plugins/select2/select2.min.css") }}" rel="stylesheet"/>
@if($page_type == "update")
<link href="{{ asset("drop-zone/dropzone.css") }}" rel="stylesheet">
@endif
<style>
    .tagify__dropdown[role="listbox"] {
        z-index: 10000000 !important;
    }

    .select2-selection .select2-selection--single {
        width: 200px;
    }

    .error-border {
        border: 1px solid red;
    }
    .modal-xl{
        min-width: 80%!important;

    }
    #preview-pdf-section{
        height: 800px;
    }
    .trumbowyg-box, .trumbowyg-editor {
        min-height: 50px;
        padding: 7px!important;
    }
    .trumbowyg-editor {
        resize: vertical !important;
    }
    .trumbowyg-editor p {
        margin:0!important;
    }
    .trumbowyg-box{
        padding: 0!important;
    }
    .trumbow-custom-background{
        background: #fafa!important;
    }
    .glyphicon-move {
        cursor: move;
        cursor: -webkit-grabbing;
    }

    .border-bottom {
        border-bottom: 1px solid #ebecf1;
        padding-bottom: 7px;
        margin-bottom: 7px;
    }

    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection
@section("page-header")
<div class="page-header mt-0 mb-3">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"> {{ $company->full_name }}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url("/") }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="/accounting-tr/{{ $company->route_name }}/{{ $type }}">{{ $company->full_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">@if($type == "offer") Offer @elseif($type == "invoice") Invoice @endif</a></li>
        </ol>
    </div>
</div>
@endsection
@section("content")
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{$page_type == "update"?"Update ".$type_text:"New ".$type_text}}</h4>
                    <div class="text-right">
                        <div class="float-right ml-4">
                            <a href="{{url('/accounting-tr/'.$company->route_name.'/'.$type)}}" class="btn btn-info"><i class="fa fa-backward mr-1"></i>{{ucfirst(trans('words.back'))}}</a>
                        </div>
                        @if($page_type == "update")
                            <div class="float-right ml-4">
                                <a onclick="showMailLogs()" class="btn btn-success"><i class="fa fa-envelope mr-1"></i>Mail Logs</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">

                            <form class="form-horizontal" action="{{$page_type=="update"?url("/accounting-tr/update/".$company->route_name."/".$type."/".$accounting->id):url("/accounting-tr/add/".$company->route_name."/".$type)}}" method="POST" id="main-form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @if(!isset($ref_no))
                                        @include("getucon-accounting-tr.standart-left-section")
                                        @include("getucon-accounting-tr.standart-right-section")
                                    @else
                                        {{--Burda gelen verinin numarasını ve tipini alıyoruz.Veritabanında kayıt işlemini yapmak için referans olarak eklemek için--}}
                                        @if(!$isCopy)
                                            <input type="hidden" name="reference_no" value="{{$accounting->no}}" >
                                            <input type="hidden" name="reference_type" value="{{$accounting->type}}">
                                        @endif
                                        @include("getucon-accounting-tr.custom-left-section")
                                        @include("getucon-accounting-tr.custom-right-section")
                                    @endif
                                </div>

                                <input type="submit" id="hidden-submit-btn" style="opacity: 0;z-index: -1">
                                @if($page_type=="update")
                                    <input type="hidden" id="save-and-close" name="save_and_close">
                                @endif
                            </form>

                        </div>
                    </div>
                </div>
                <div class="row w-100 m-0">
                    <div class="col-lg-12 col-md-12 my-3 d-flex justify-content-end">
                        {{--Accounting tipine göre sayfayı geri yönlendirme linkini oluşuruyoruz--}}
                        <a href="/accounting-tr/{{$company->route_name}}/{{$type}}" class="btn btn-danger">Cancel</a>
                        <button type="button" data-save-and-close="0"  class="btn btn-success submit-btn ml-2">Save</button>
                        @if($page_type=="update")
                        <button type="button" data-save-and-close="1" class="btn btn-outline-success submit-btn ml-2">Save & Close</button>
                        @endif
                    </div>
                </div>
                @if($page_type == "update")
                    @if($mail_status == 1)
                        @include("getucon-accounting-tr.mail")
                    @elseif($type == "invoice" && $mail_status == 2)
                        @include('getucon-accounting-tr.invoice-reminder-section')
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if($page_type == "update")
        @isset($mail_logs)
            <div class="modal fade" id="show-mail-logs-modal" tabindex="-1" role="dialog" aria-labelledby="show-mail-logs-modal-label" aria-hidden="true">
                <div class="modal-dialog @if($mail_logs->count() > 0) modal-xl @else modal-sm @endif" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="show-mail-logs-modal-label">Mail Logs</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if($mail_logs->count() > 0)
                                <table class="table w-100" id="mail-logs-table">
                                    <thead>
                                    <tr class="default-cursor">
                                        <th scope="col">Sender</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">To</th>
                                        <th scope="col">CC</th>
                                        <th scope="col">BCC</th>
                                        <th scope="col">Additional Information</th>
                                        <th scope="col">Attachments</th>
                                        <th scope="col">Sent Time</th>
                                    </tr>
                                    </thead>
                                    @foreach($mail_logs as $mail_log)
                                        @php
                                            $user = \App\User::where('id', $mail_log->send_by)->first();
                                            $name = $user->first_name . " " . $user->surname;
                                        @endphp
                                        <tbody>
                                        <tr>
                                            <td class="default-cursor">
                                                <strong>{{$name}}</strong>
                                            </td>
                                            <td class="default-cursor">{{ucfirst($mail_log->type)}}</td>
                                            <td class="default-cursor">{{$mail_log->subject}}</td>
                                            <td class="default-cursor">{{$mail_log->email_to}}</td>
                                            <td class="default-cursor">{{$mail_log->email_cc}}</td>
                                            <td class="default-cursor">{{$mail_log->email_bcc}}</td>
                                            <td class="default-cursor">{!! $mail_log->additional_text !!}</td>
                                            <td class="justify-content-between">
                                                @foreach(explode(';', $mail_log->files) as $file)
                                                    @unless($file == "")
                                                        <a href="/uploads/{{$file}}" class="position-relative mail-log-attachment link text-primary" target="_blank" data-title="{{$file}}"><i class="fa fa-paperclip"></i></a>
                                                    @endunless
                                                @endforeach
                                            </td>
                                            <td class="default-cursor">{{\Carbon\Carbon::parse($mail_log->created_at)->format('d.m.Y [H:i:s]')}}</td>
                                        </tr>
                                        </tbody>
                                    @endforeach
                                </table>
                            @else
                                <p class="font-weight-semibold text-danger">There is no mail log found!</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
    @endif

@if($type == "invoice" && $page_type == "update")
<div class="modal fade" id="cancel-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-invoice-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancel-invoice-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        Invoice will be cancelled! This action has not a rollback!
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="cancel-reason" class="form-label my-auto">Reason<span class="text-danger pl-1">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control privateValidateControl" id="cancel-reason"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">To Emails for Storno Email</label>
                            </div>
                            <div class="col-md-9">
                                <x-tag-and-search-input name="to_for_storno" values="{{ $customer->accounting_to }}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">CC Emails for Storno Email</label>
                            </div>
                            <div class="col-md-9">
                                <x-tag-and-search-input name="cc_for_storno" values="{{ $customer->accounting_cc }}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">BCC Emails for Storno Email</label>
                            </div>
                            <div class="col-md-9">
                                <x-tag-and-search-input name="bcc_for_storno" values="{{ $customer->accounting_bcc }}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button type="button" id="cancel-invoice-accept-btn" class="btn btn-danger">Cancel Invoice!</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update-ticket-modal" tabindex="-1" role="dialog" aria-labelledby="update-ticket-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="display: flex;justify-content: center;">
        <div class="modal-content" style="width: 60% !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="update-ticket-modal-label">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="badge badge-warning" id="ticket-warning" style="display: none;">There is not ticket have the ticket ID!</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ticket (ref.)</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="text" placeholder="ID" name="new_ticket_id" id="new-ticket-id" value="">
                            </div>
                            <div class="col-md-4">
                                <span class="form-label" data-toggle="tooltip" data-placement="top" id="new-ticket-name"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="new-ticket-btn" onclick="updateTicket()" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif

<input type="hidden" name="in_use" id="in-use">
@endsection
@section("js")
<script src="{{ asset("assets/plugins/datatable/js/jquery.dataTables.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/dataTables.bootstrap4.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/buttons.bootstrap4.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/jszip.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/pdfmake.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/vfs_fonts.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/buttons.html5.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/buttons.print.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/js/buttons.colVis.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/dataTables.responsive.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatable/responsive.bootstrap4.min.js") }}"></script>
<script src="{{ asset("assets/js/datatables.js") }}"></script>
<script src="{{ asset("assets/plugins/select2/select2.full.min.js") }}"></script>
<script src="{{ asset("assets/js/select2.js") }}"></script>
<script src="{{ asset("assets/js/jquery.inputmask.min.js") }}"></script>
<script src="{{ asset("assets/js/numberFormat.js") }}"></script>
<script src="{{ asset("assets/js/custom-number-format.js") }}"></script>
<script src="{{ asset("text-editor/trumbowyg.js?v=123448756") }}"></script>
<script src="{{ asset('text-editor/trumbowyg.allowtagsfrompaste.min.js?v=123448756')}}"></script>
<script src="{{ asset("assets/sortable/Sortable.min.js") }}"></script>
@if($page_type == "update")
<script src="{{ asset("assets/js/jquery.inputmask.min.js") }}"></script>
<script src="{{ asset("drop-zone/dropzone.js")}}"></script>
@endif
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$.changeFileLabel = function(e) {
    let fileName = e.target.files[0].name;
    let label = $(e.target).parent().find(".custom-file-label");
    label.html(fileName);
}

@if($company->route_name == "getucon-tr")
const official_invoice_mask = "GTA9999999999999";
@elseif($company->route_name == "guler-consulting")
const official_invoice_mask = "IHA9999999999999";
@elseif($company->route_name === "media-kit")
const official_invoice_mask = "MDK9999999999999";
@endif

let isAllDisabled = 0;
let oneTimeVariable = 0;
const trumbowConf = {
    autogrow: false,
    resetCss:false,
    // removeformatPasted: true,
    semantic:false,
    btns:[
        ['viewHTML'],
        ['fullscreen']
    ],
    plugins: {
        allowTagsFromPaste: {
            allowedTags: [ 'p', 'br']
        }
    },
    tagsToKeep: ['p', 'br'],
};

function clear_textarea_val(str){
    return str.replace(" ", "").replaceAll(/<\/?[^>]+>/gi, "");
}

let add_row_var = 0;

function calculateTotalAmount() {
    let total_amount = 0;

    $.each($(".accounting-item-total-price"), function(key, element) {
        if($(element).val() !== "") {
            let price = parseFloat(clear_number($(element).val()));
            total_amount += price;
        }
    });

    let with_kdv = total_amount + (total_amount * parseFloat(parseInt($('#kdv').val()) / 100));
    $("#total-amount").val(numberFormat(with_kdv, 2, ",", "."));
}

$.add_row = function() {
    let last_row =  $("#table-input-context").children().last();
    let inputs = last_row.find("input").not(".accounting-item-discount,textarea");
    let textarea = last_row.find("textarea");
    let a = 1;

    $.each(inputs,function (index,element) {
        if($(element).val()==="") {
            a = 0;
            $(element).addClass("error-border");
        }
    });

    if(clear_textarea_val(textarea.val())===""){
        textarea.parent().addClass("error-border");
        a = 0;
    }

    if(a === 0) {
        return false;
    }
    else {
        let position_number = ($("#table-input-context").children().length) + 1;
        let html = `<div class="col-md-1 pl-0">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3 position-btn">
                                        <span class="glyphicon glyphicon-move mt-2"></span>
                                    </div>
                                    <div class="col-md-9 pr-0">
                                        <input class="form-control privateValidateControl accounting-item-position" type="number" name="items[${position_number}][position]" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input class="form-control privateValidateControl accounting-item-quantity" type="number" name="items[${position_number}][quantity]" step="0.25">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input class="form-control privateValidateControl accounting-item-type" type="text" name="items[${position_number}][type]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea class="form-control privateValidateControl accounting-item-description" id="newTextArea${add_row_var}" type="text" rows="5" name="items[${position_number}][description]"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input class="form-control privateValidateControl accounting-item-unit-price" type="text" data-type="currency" name="items[${position_number}][unit_price]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input class="form-control accounting-item-discount" data-type="currency" type="text" name="items[${position_number}][discount]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 pl-0 pr-0">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input class="form-control privateValidateControl accounting-item-total-price" type="text" data-type="currency" name="items[${position_number}][total_price]" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <a class="btn btn-danger btn-sm delete-btn">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>`;

        let result = document.createElement("div");
        result.classList.add("row");
        result.classList.add("border-top");
        result.classList.add("pt-3");
        result.innerHTML = html;
        let table_input = document.getElementById("table-input-context");
        table_input.appendChild(result)
        let created = document.getElementById("newTextArea"+add_row_var);

        $(created).trumbowyg(trumbowConf).on("tbwfocus",function (){
            $(this).parent().removeClass("error-border");
        });


        add_row_var = add_row_var + 1;

        $(table_input).children().last().find(".position-btn").html("");
        $(table_input).children().last().removeClass("draggable-row").addClass("not-last");
        generate_position();
    }
}

$.remove_row = function (self){
    $(self).parents()[3].remove();
    calculateTotalAmount();
    generate_position();
}

function addTicketId() {
    let totalIds = $("#ticket-ids").children().length
    if(totalIds<=9){
        if(totalIds == 9){
            $(".add-ticket-id-row-btn").hide();
        }
        let position_number = totalIds + 1;
        let html = `
                     <div class="row mt-2" id="deleted-row${position_number}">
                        <label class="col-md-3 form-label d-flex align-items-center">Ticket (ref.)</label>
                        <div class="col-md-2 d-flex align-items-center" >
                            <input class="form-control ticket-id" data-row-id="${position_number}" type="text" placeholder="ID" name="ticket_id${position_number}" id="ticket-id${position_number}" value="">
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <span class="form-label" data-toggle="tooltip" data-placement="top" id="ticket-name${position_number}"></span>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <a class="btn btn-danger btn-sm delete-ticket-id-btn" data-row-id="${position_number}"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                     </div>
`
        $("#ticket-count").val(position_number);
        $("#ticket-ids").append(html)
        $(".add-ticket-id-row-btn").hide();
    }else{
        $(".add-ticket-id-row-btn").hide();
    }

}
@if($page_type === "update" && $type === "invoice")
function updateTicket() {
    let ticket_id = $("#new-ticket-id").val();
    $.ajax({
        url:'/accounting-tr/update-ticket',
        type: 'POST',
        data: {
            ticket_id: ticket_id,
            accounting_id: {{ $accounting->id }},
            _token: "{{csrf_token()}}",
        },
    }).done(function(response) {
        if(response.message= "success"){
            location.reload();
            toastr.success("Ticket ID updated successfully!", "Success");
        }else{
            toastr.error("Ticket ID update has failed!", "Error");
        }

    }).error(function() {
        toastr.error("Ticket ID update has failed!", "Error");
        toggleLoader(false);
    });
}
@endif
function clear_number(number) {
    return number.replace(".", "").replace(",", ".");
}

function generate_position() {
    $.each($(".accounting-item-position"), function(index, element) {
        $(element).val(index + 1);
    });
}

function getFileSize(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("HEAD", url, false);
    xhr.onreadystatechange = function() {
        if(this.readyState === this.DONE) {
            callback(parseInt(xhr.getResponseHeader("Content-Length")));
        }
    };
    xhr.send();
}

@if($page_type === "update" && $type === "invoice")
function changeTicketStatus() {
    confirmModal("Are you sure that you want to change ticket's status to \"Invoiced\"?", "Change Ticket Status", "Change", "Close").then(function() {
        toggleLoader(true);
        $.ajax({
            url: "/accounting-tr/change-ticket-status",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: {{ $accounting->id }}
            },
            success: function(response) {
                if(response !== "" && response !== undefined) {
                    if(response.status === "Success") {
                        location.reload();
                        toastr.success(response.message, response.status);
                    }
                    else {
                        toggleLoader(false);
                        toastr.error(response.message, response.status);
                    }
                }
                else {
                    toggleLoader(false);
                    toastr.error("Something went wrong while trying to change ticket status!", "Error");
                }
            }
        });
    });
}
@endif

@if($page_type == "update")
function showMailLogs() {
    $('#show-mail-logs-modal').modal('show');
}

Dropzone.autoDiscover = false;
$('#accounting-mail-attachment').dropzone({
    maxFiles: 5,
    parallelUploads: 1,
    uploadMultiple: true,
    addRemoveLinks: true,
    maxFilesize: 10,
    timeout: 180000000,
    acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.mp4,.xlsx,.xlsm,.xltx,.xlsb,.webm,.zip,.rar,.msg,.7z,.tar',
    url: '/attachFiles',
    success: function (file, response) {
        if(response.error) {
            toastr.error(response.error, 'Error');
        }
        else {
            $.each(response.data, function (key, data) {
                $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                $('#accounting-mail-attachment-response').append('<input type="hidden" name="mail_attacment[' + data.size + ']" value="' + data.link + '"/>');
            });
            toastr.success(response.success, 'Success');

        }
    },
    init: function () {
        this.on("removedfile", function (file) {
            var server_file = $(file.previewTemplate).children('.server_file').text();
            if(file.processing === true) {
                $("#accounting-mail-attachment-response input[value='" + server_file + "']").remove();//yüklenen dosyayı kaldırma
            }
            else {
                $("#accounting-mail-attachment-response input[value='" + file.name + "']").remove();// yüklenmeden (sunucudan sahte olarak getirilen) dosyayı kaldırma
            }
        });
    }
});

@if($type == "invoice")

function deletePayment(paymentID) {
    confirmModal('Are you sure that you want to delete this payment?',"Delete Payment","Delete","Close").then(function() {
        toggleLoader(true);

        $.ajax({
            url: '/accounting-tr/delete-payment',
            type: 'POST',
            dataType: 'JSON',
            data: 'payment_id=' + paymentID + '&_token={{csrf_token()}}',
        }).done(function() {
            location.reload();
            toastr.success("Payment deleted successfully!", "Success");
        }).error(function() {
            toggleLoader(false);
            toastr.error("Payment deletion has failed!", "Error");
        });
    });
}

$.add_gta_row = function() {
    let html = `<div class="row appended-row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group row border-bottom">
                        <label class="col-md-3 form-label my-auto"> Official Invoice</label>
                        <div class="col-md-3">
                            <span class="badge badge-danger badge-official" style="z-index: 9999;"></span>
                            <input type="text" name="official_invoice_number[]" class="form-control official-invoice-number validate[funcCall[validateOfficialInvoice]]" value="">
                            <input type="hidden" name="existing-official-number" class="existing-official-number" value="0">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="gta_create_date[]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="gta_amounts[]" class="form-control" data-type="currency">
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-sm btn-danger" onclick="$.remove_gta_row(this)"><i class="fa fa-minus"></i></a>
                       </div>
                    </div>
                </div>
            </div>`

    $("#gta-section").append(html);

    $('.official-invoice-number').inputmask('integer', {
        mask: official_invoice_mask,
        allowMinus: false,
        rightAlign: false
    });
}

$.remove_gta_row = function(element) {
    $(element).parent().parent().remove();
}

function validateOfficialInvoice(field) {
    @if($company->route_name == "getucon-tr")
    var is_valid = /GTA(.*[0-9]{13}$)/.test($(field).val());
    var example_format = "GTA123456891234";
    @elseif($company->route_name == "guler-consulting")
    var is_valid = /IHA(.*[0-9]{13}$)/.test($(field).val());
    var example_format = "IHA123456891234";
    @else
    var is_valid = /MDK(.*[0-9]{13}$)/.test($(field).val());
    var example_format = "MDK123456891234";
    @endif
    if(!is_valid) {
        return example_format;
    }
}

function validationEngineHide(elements) {
    $.each(elements,function (index,element){
        $(element).validationEngine('hide');
    });
}

function removeCurrentGTA(id) {
    $.remove_gta_row($(".remove-gta-button[data-id='" + id + "']"));
    $("#remove-gta-warning-modal").modal('hide');
}

@if($mail_status == 2)
function deleteReminderAttachment(attachment_id) {
    confirmModal('Reminder attachment will be deleted!',"Are you sure?","Delete Attachment","Close","#0275d8","#d9534f").then(function() {
        $.ajax({
            url:'/accounting-tr/delete-attachment',
            type: 'POST',
            data: {
                attachment_id: attachment_id,
                _token: "{{csrf_token()}}",
            },
        }).done(function() {
            location.reload();
            toastr.success("Attachment deleted successfully!", "Success");
        }).error(function() {
            toastr.error("Attachment deletion has failed!", "Error");
            toggleLoader(false);
        });
    });
}

function formSubmit() {
    @if(count($attachments) == 0)
    if(Dropzone.forElement('#reminder-attachment-dropzone').files.length <= 0) {
        toastr.warning("Please add an attachment!", "Warning");
        return false;
    }
    @endif

    var email = $('#customer_email').val();
    var reminder = $('#invoice_reminder_id').val();

    let ccEmails = $('#cc').val();
    let bccEmails = $('#bcc').val();

    let ccSplitEmails = ccEmails.split(";");
    let bccSplitEmails = bccEmails.split(";");

    if(!reminder) {
            confirmModal("Attention, an invoice e-mail will be sent to <br> TO:" + email +(ccSplitEmails[0]!="" ? "<br> CC:" : "") +ccSplitEmails.map((item)=>item+" ")+(bccSplitEmails[0]!="" ? "<br> BCC:" : "")+bccSplitEmails.map((item)=>item+" ") +"<br>Sent!","Are you sure?","Ok","Cancel").then(function() {
                toggleLoader(true);
                $('#invoice_date').val($('#date').val());
                $('#invoice-number').val($('#accounting-no').val());
                $('#cid').val($('#company_id').val());
                $('#oid').val($('#organization').val());
                $('#set_reminder_form').find('[type="submit"]').trigger('click');
            }).fail(function() {
                event.preventDefault();
            });
    }
    else {
        $('#invoice_date').val($('#date').val());
        $('#invoice-number').val($('#accounting-no').val());
        $('#cid').val($('#company_id').val());
        $('#oid').val($('#organization').val());
        $('#set_reminder_form').find('[type="submit"]').trigger('click');
    }
}

var dp = 1;

$('#reminder-attachment-dropzone').on('click', function() {
    dp = 1;
});

var increment = 5;
Dropzone.autoDiscover = false;
$('#reminder-attachment-dropzone').dropzone({
    maxFiles: @if(count($attachments) < 5) {{5 - count($attachments)}} @else {{'0'}} @endif,
    parallelUploads: 1,
    uploadMultiple: true,
    addRemoveLinks: true,
    maxFilesize: 10,
    timeout: 180000000,
    acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.xlsx,.xlsm,.xltx,.xlsb,.zip,.rar,.msg,.7z,.tar',
    url: '/attachFiles',
    success: function(file, response) {
        if(response.error) {
            toastr.error(response.error, 'Error');
        }
        else {
            $.each(response.data, function(key, data) {
                $(file.previewTemplate).append('<span style="display: none;" class="server_file">' + data.link + '</span>');
                $('#reminder-attachment-response').append('<input type="hidden" name="reminder_attachments[' + data.size + ']" value="' + data.link + '"/>');
            });
            let inputs = $('#reminder-attachment-response').children();
            let tmp = [];
            $.each(inputs, function(key, input) {
                let prefix = $(input).val().substring(0, 2);

                if(prefix === "RG") {
                    tmp[0] = input;
                }
                else if(prefix === "PR") {
                    tmp[1] = input;
                }
                else if(prefix === "AG") {
                    tmp[2] = input;
                }
                else {
                    tmp[increment] = input;
                    increment++;
                }
            });

            let html = "";

            $.each(tmp, function(key, input) {
                if(input !== undefined) {
                    html += input.outerHTML;
                }
            });

            $('#reminder-attachment-response').html(html);
            toastr.success(response.success, 'Success');
        }
    },
    init: function() {
        a = dp;
        this.on("maxfilesexceeded", function(file) {
            if(this.files.length >= 5) {
                if (dp === 1) {
                    toastr.error("Maximum file must be 5!");
                    dp = 0;
                }
            }
            this.removeFile(this.files[5]);

        });
        this.on("removedfile", function(file) {
            var server_file = $(file.previewTemplate).children('.server_file').text();
            if(file.processing === true) {
                $("#reminder-attachment-response input[value='" + server_file + "']").remove();
            }
            else {
                $("#reminder-attachment-response input[value='" + file.name + "']").remove();
            }
        });
    }
});
@endif
@endif
@endif

$(document).ready(function() {

    $('#reminder_day').on('change', function() {
        var invoice_date = $('#date').val();
        if(invoice_date) {
            let date = new Date(invoice_date);
            date.setDate(date.getDate() + parseInt($(this).val()));

            dformat = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
            $('#reminder_deadline').val(dformat);
            $('#reminder_deadline').trigger('change');
        }
    });

    $("#repeat-date").on('change',function () {
        if($(this).val() != ''){
            $(".repeat-div").show();
        }else{
            $(".repeat-div").hide();
        }
    })


    $('.official-invoice-number').inputmask('integer', {
        mask: official_invoice_mask,
        allowMinus: false,
        rightAlign: false
    });

    $('#show-reminder-section').on('change', function() {
        if($(this).is(':checked')) {
            $('#reminder-section').show();

            $.ajax({
                url:"/accounting-tr/retrieve-invoice-details",
                type:"POST",
                async:false,
                data: {
                    accounting_no: $("#accounting-no").val(),
                    _token:"{{csrf_token()}}",
                },
                success:function(response) {
                    if(response.success === 1) {
                        if(response.ticket_status_ok == 0){
                            $('#reminder-section').hide();
                            $('#show-reminder-section').prop("checked",false);
                            Swal.fire({
                                title: 'Ticket is not in Done & Proofed status!',
                                text: 'To continue, update the ticket status to Done & Proofed.',
                                confirmButtonText: 'OK!',
                            })
                            return false;
                        }
                        $("#subject").val(response.subject);
                        let mock_files = [];
                        let dropzone = Dropzone.forElement('#reminder-attachment-dropzone');
                        let global_size;

                        $.each(response.files, function(index, file) {
                            getFileSize("/uploads/" + file, function(size) {
                                global_size = size;
                            });
                            mock_files.push({
                                name: file.substring(0, 9) + ".pdf",
                                size: global_size,
                                original_name: file,
                                type:0
                            });
                        });

                        if(response.ticket_file){

                            getFileSize("/tempfiles/" + response.ticket_file, function(size) {
                                global_size = size;
                            });
                            mock_files.push({
                                name: response.ticket_file.substring(0, 11) + ".pdf",
                                size: global_size,
                                original_name: response.ticket_file,
                                type:1
                            });
                        }

                        $.each(mock_files, function(index, file) {
                            file.status = Dropzone.SUCCESS;
                            file.accepted = true;
                            dropzone.emit("addedfile",file);
                            dropzone.emit("complete",file);
                            dropzone.files.push(file);

                            if(file.type == 1){
                                $('#reminder-attachment-response').append("<input type='hidden' name='reminder_attachments_ticket[" + file.size + "]' value='" + file.original_name + "' >");
                                $('#reminder-attachment-response').append("<input type='hidden' name='reminder_attachments[" + file.size + "]' value='" + file.original_name + "' >");
                            }else{
                                $('#reminder-attachment-response').append("<input type='hidden' name='reminder_attachments[" + file.size + "]' value='" + file.original_name + "' >");
                            }
                        });
                    }
                    else {
                        toastr.error("Something went wrong while trying to retrieve The Invoice's details!","Error!");
                    }
                }
            });

            var invoice_date = $('#date').val();
            if(invoice_date) {
                let date = new Date(invoice_date);
                date.setDate(date.getDate() + parseInt($('#reminder_day').val()));

                dformat = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
                $('#reminder_deadline').val(dformat);
                $('#reminder_deadline').trigger('change');
            }
        }
        else {
            $('#reminder-section').hide();
            Dropzone.forElement('#reminder-attachment-dropzone').removeAllFiles(true);
        }
    });

    $('textarea').trumbowyg(trumbowConf).on('tbwblur', function() {
        let text = $(this).parent().find('.trumbowyg-editor').html();
        $(this).val(text).trigger('change');
    });

    @if($type == "invoice")
    $('#no').inputmask('integer', {mask: 'PF-999999', allowMinus: false, rightAlign: false});
    @elseif($type == "offer")
    $('#no').inputmask('integer', {mask: 'OF-999999', allowMinus: false, rightAlign: false});
    @endif
        @if($errors->any())
        toastr.options.timeOut=10000;
    toastr.warning("{{$errors->first()}}","Warning!")
    @endif

    generate_position();

    function resize() {
        let app_header = $('.app-header').outerHeight();
        let window_height = window.outerHeight;
        $("card").css("height", window_height + app_header);
    }

    resize();

    $('#organization').select2({
        ajax: {
            url: '/getOrganizationsRawData',
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    $(document).on("keyup change",".privateValidateControl", function () {
        $(this).removeClass("error-border");
    });

    $('textarea').trumbowyg().on('tbwfocus', function() {
        $(this).parent().removeClass("error-border");
    });

    $(document).on("click", ".update-ticket", function() {
        $("#new-ticket-id").val("");
        $("#new-ticket-name").html("");
        $("#new-ticket-btn").prop("disabled",true);
        $("#update-ticket-modal").modal("show");
    });

    $(document).on("keyup change", ".accounting-item-quantity", function() {
        let unit_price = $($(this).parents()[3]).find(".accounting-item-unit-price").val();
        let discount = $($(this).parents()[3]).find(".accounting-item-discount").val();

        if(discount === "") {
            discount ="0,00";
        }

        let quantity = $(this).val();

        if(unit_price !== "" && quantity !== "") {
            unit_price = parseFloat(clear_number(unit_price));
            discount = parseFloat(clear_number(discount));

            let total_price = (unit_price * quantity) - ((unit_price * quantity) * (discount / 100));
            total_price = numberFormat(total_price, 2, ',', '.')
            $($(this).parents()[3]).find(".accounting-item-total-price").val(total_price);
            $($(this).parents()[3]).find(".accounting-item-total-price").trigger("change");
        }
        else {
            $($(this).parents()[3]).find(".accounting-item-total-price").val("").trigger("change");
        }
    });

    $(document).on("keyup change", ".accounting-item-unit-price,.accounting-item-discount,#kdv", function() {
        $($(this).parents()[3]).find(".accounting-item-quantity").trigger("keyup");
    });

    $(document).on("keyup change", ".accounting-item-total-price", function () {
        calculateTotalAmount();
    });

    $(document).on("click", ".delete-btn", function() {
        let row = $(this);
        confirmModal("Are you sure that you want to delete this position?", "Are you sure?", "Delete", "Close", "#0275d8", "#d9534f").then(function() {
            row.parents().eq(3).remove();
            calculateTotalAmount();
            generate_position();
        }).bind(row);
    });

    $(document).on("click", ".delete-ticket-id-btn", function() {
        let row = $(this);
        confirmModal("Are you sure that you want to delete this position?", "Are you sure?", "Delete", "Close", "#0275d8", "#d9534f").then(function() {
            row.parents().eq(3).remove();
            let deletedRowId =  row.data("row-id");
            $("#deleted-row"+deletedRowId).remove();
            $(".add-ticket-id-row-btn").show();
        });
    });

    function validate_right(elements){
        let return_value = true;

        $.each(elements,function(index,element) {
            let tag_name = element.tagName;
            let value = "sahte";

            if(tag_name === "TEXTAREA") {
                value = clear_textarea_val($(element).val());
            }
            else if(tag_name ==="INPUT") {
                value = $(element).val().replaceAll(" ","");
            }

            if(value==="") {
                if(tag_name === "TEXTAREA") {
                    $(element).parent().addClass("error-border");
                }
                else if(tag_name ==="INPUT") {
                    $(element).addClass("error-border");
                }

                return_value = false;
            }
        });

        return return_value;
    }

    $(".submit-btn").on("click", function() {
        @if($page_type == "update")
        if($(this).data('save-and-close') === 1) {
            $('#save-and-close').val(1);
        }
        else {
            $('#save-and-close').val(0);
        }
        @endif

        let in_use = $("#in-use").val();
        let official_invoice_usage = $(".existing-official-number").val();
        let validate = $("#main-form").validationEngine("validate", {
            scroll: false
        });

        if(isAllDisabled === 0) {
            let inputs2 = $("#table-input-context").children().find(":input").not(".accounting-item-discount,.accounting-item-id");

            if(!validate_right(inputs2)) {
                return false;
            }

            if(@if($page_type == "update" && $type == "invoice") official_invoice_usage == 0 && @endif in_use == 0) { // Eğer sayfa tipi "Update" ise ve tip "Proforma" ise resmi faturayı da kontrol ediyoruz.
                if(!validate) {
                    return false;
                }
            }

            $("#hidden-submit-btn").click();
        }
        else {
            if(@if($page_type == "update" && $type == "invoice") official_invoice_usage == 0 && @endif in_use == 0) { // Eğer sayfa tipi "Update" ise ve tip "Proforma" ise resmi faturayı da kontrol ediyoruz.
                if(!validate) {
                    return false;
                }
            }

            $("#hidden-submit-btn").click();
        }
    });

    @if($type=="invoice")
    $("#deadline-day").on("change",function(){
        let day = $(this).val();
        let date = $('#date').val();
        if(day !== "" && date !== ""){
            if(day == 1){
                day = 3
                $("textarea[name='footnote']").trumbowyg('html', 'Vorkasse');
            }
            let date_object = new Date(date);
            date_object.setDate(date_object.getDate() + parseInt(day));
            let  dformat = date_object.getFullYear() + '-' +
                ('0' + (date_object.getMonth() + 1)).slice(-2) + '-' +
                ('0' + date_object.getDate()).slice(-2);
            $('#deadline').val(dformat);

        }
    });
    @endif

    $("#ticket-id").on("input",function() {
        this.value =  this.value.replace(/[^0-9]/g,'');
    });

    $("#ticket-id").on("keyup change", function() {
        if($(this).val() === "") {
            $("#ticket-name").html("");
            $("#ticket-warning").hide();
            return false;
        }

        let ticket_id = $(this).val();
        $.ajax({
            url:"/get-ticket/"+ticket_id,
            type:"get",
            success:function(response){
                if(response.ticket_name) {
                    if(response.ticket_name.length>36){
                        $("#ticket-name").html(response.ticket_name.substr(0,36)+"...");
                        $("#ticket-name").attr("data-original-title",response.ticket_name);
                    }
                    else{
                        $("#ticket-name").attr("data-original-title","");
                        $("#ticket-name").html(response.ticket_name);
                    }
                    $("#ticket-warning").hide();
                    $(".add-ticket-id-row-btn").show();
                }else{
                    $("#ticket-name").html("");
                    if(ticket_id!==""){
                        $("#ticket-warning").show();
                    }
                    $(".add-ticket-id-row-btn").hide();
                }
            }
        });
    });

    $("#new-ticket-id").on("input", function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $("#new-ticket-id").on("keyup change", function() {
        if($(this).val() === "") {
            $("#new-ticket-name").html("");
            $("#new-ticket-warning").hide();
            $("#new-ticket-btn").prop("disabled",true);
            return false;
        }
        let ticket_id = $(this).val();
        $.ajax({
            url: "/getucon/accounting/get-ticket/" + ticket_id,
            type: "get",
            success: function (response) {
                if(response.ticket_name) {
                    if(response.ticket_name.length > 36) {
                        $("#new-ticket-name").html(response.ticket_name.substr(0, 36) + "...");
                        $("#new-ticket-name").attr("data-original-title", response.ticket_name);
                    }
                    else {
                        $("#new-ticket-name").attr("data-original-title", "");
                        $("#new-ticket-name").html(response.ticket_name);
                    }
                    $("#new-ticket-btn").prop("disabled",false);
                    $("#new-ticket-warning").hide();
                }
                else {
                    $("#new-ticket-btn").prop("disabled",true);
                    $("#new-ticket-name").html("");
                    if(ticket_id !== "") {
                        $("#new-ticket-warning").show();
                    }
                }
            }
        });
    });

    $(document).on("input",".ticket-id", function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $(document).on("keyup change",".ticket-id", function() {
        let rowId = $(this).data("row-id")
        if($(this).val() === "") {
            $("#ticket-name"+rowId).html("");
            $("#ticket-warning").hide();
            return false;
        }
        let ticket_id = $(this).val();

        $.ajax({
            url: "/get-ticket/" + ticket_id,
            type: "get",
            success: function (response) {
                if(response.ticket_name) {
                    if(response.ticket_name.length > 36) {
                        $("#ticket-name"+rowId).html(response.ticket_name.substr(0, 36) + "...");
                        $("#ticket-name"+rowId).attr("data-original-title", response.ticket_name);
                    }
                    else {
                        $("#ticket-name"+rowId).attr("data-original-title", "");
                        $("#ticket-name"+rowId).html(response.ticket_name);
                    }
                    $("#ticket-warning").hide();

                    if($("#ticket-ids").children().length <= 9){
                        $(".add-ticket-id-row-btn").show();
                    }
                }
                else {
                    $("#ticket-name"+rowId).html("");
                    if(ticket_id !== "") {
                        $("#ticket-warning").show();
                    }
                    $(".add-ticket-id-row-btn").hide();
                }
            }
        });
    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $("#ticket-id").trigger("change");
    });

    @if($page_type=="update") // "Page Type: Update" Start

    function disable_trumbow(element) {
        element.trumbowyg("disable");
        element.parent().find(".trumbowyg-fullscreen-button").removeAttr("disabled");
        element.parent().find('.trumbowyg-editor').css('background', '#e9e9eb');
    }

    function disableAll() {
        isAllDisabled = 1;
        $("#main-form").find(":input").not("textarea[name='internal_info'],#hidden-submit-btn,input[name='_token'],input[name='isDisabledAll'],input[name='repeat_date'],select[name='repeat_reminder'],#save-and-close,input[name='gta_create_date[]'],input[name='gta_amounts[]'],input[name='official_invoice_number[]']").prop("disabled",true);
        $(".add-row-btn").remove();
        $(".delete-btn").remove();
        disable_trumbow($("textarea[name='title']"));
        disable_trumbow($("textarea[name='footnote']"));
        $(".add-ticket-id-row-btn").remove();
        $(".delete-ticket-id-btn").remove();
        $(".delete-ticket").remove();
        $(".delete-main-ticket").remove();
        $(".update-ticket").remove();
        $.each($(".accounting-item-description"), function(index, element) {
            disable_trumbow($(element));
        });

        if(oneTimeVariable === 0) {
            $("form").append("<input type='hidden' name='isDisabledAll' value='1'>");
            oneTimeVariable = 1;
        }
    }

    const elements = document.querySelectorAll('[data-readonly-status="1"]');
    $.each(elements, function(index, element) {
        $(element).parent().find('.trumbowyg-editor').attr('contenteditable', false).css('background', '#e9e9eb');
    });

    @if($type == "invoice") // "Type: "Proforma" Start
    @if($accounting->reminder_setted != null || $accounting->storno != null || $accounting->offer_no || $accounting->is_cancel === "Yes")
    disableAll();
    @endif

    $(document).on("click", "#cancel-invoice", function() {
        $("#cancel-invoice-modal").modal("show");
    });

    $(document).on("click", "#cancel-invoice-accept-btn", function() {
        let reason = $('#cancel-reason').val();
        let text = clear_textarea_val(reason);
        let to = $("input[name='to_for_storno']").val();
        let cc = $("input[name='cc_for_storno']").val();
        let bcc = $("input[name='bcc_for_storno']").val();

        if(text === "") {
            toastr.error("Reason field can not be null!", "Error");
            return false;
        }

        if(to === "") {
            toastr.error("To section cannot be null!", "Error");
            return false;
        }

        toggleLoader(true);
        $.ajax({
            url: "/accounting-tr/cancel-invoice",
            type: "POST",
            data: {
                invoice_no: "{{ $accounting->no }}",
                _token: "{{ csrf_token() }}",
                reason: reason,
                to: to,
                cc: cc,
                bcc: bcc
            },
            success: function(response) {
                if(response !== "" || response !== undefined) {
                    location.reload();
                }
            }
        });
    });

    $('#result').val(numberFormat(parseFloat($('#total-amount').val().replace(/\./g, "").replace(/\,/g, ".")) - parseFloat($('#total_payment').val()), 2, ',', '.') + ' €');
    var total_payment = parseFloat($('#total_payment').val());
    var invoice_amount = parseFloat($('#total-amount').val().replace(/\./g, "").replace(/\,/g, "."));

    if(total_payment != invoice_amount) {
        $('#receive_payment').on('change keyup', function () {
            let received_payments = parseFloat($("#receive_payment").val().replace(/\./g, "").replace(/\,/g, "."));

            if(received_payments > precisionPrice(invoice_amount - total_payment)) {
                toastr.error('Received payment can not be greater than the invoice amount!', 'Error');
                $(this).val("");
                $('#result').val(numberFormat(invoice_amount - total_payment, 2, ',', '.') + '€');

                if(received_payments < 0) {
                    $(this).val("");
                    $('#result').val(numberFormat(invoice_amount - total_payment, 2, ',', '.') + '€');
                }

                return false;
            }
            if(received_payments > 0) {
                var calc = precisionPrice(invoice_amount - (received_payments + total_payment));
            }
            else {
                var calc = precisionPrice(invoice_amount - total_payment);
            }

            if(invoice_amount === precisionPrice(received_payments + total_payment)) {
                $("#payment_status").val(2);
            }
            else {
                $("#payment_status").val(1);
            }
            $('#result').val(numberFormat(calc, 2, ',', '.') + '€');
        });
    }
    else {
        $('#receive_payment').prop('disabled', true).val('Payment has completed!').removeData('type');
        $('#payment_date').prop('disabled', true);
        $('#payment_status').prop('disabled', true);
    }

    $(document).on('click', '.remove-gta-button', function() {
       let id=  $(this).data("id")
        confirmModal('Are you sure that you want to delete this official invoice? <br> The page need to be saved for deletion!',"Delete Official Invoice","Delete","Close","#0275d8","#d9534f").then(function() {
            removeCurrentGTA(id)
        });
    });
  @if($page_type === "update")
    $(document).on("click", ".delete-ticket", function() {
        let rowId = $(this).data("row-id");
        confirmModal("Are you sure that you want to delete this position?", "Are you sure?", "Delete", "Close", "#0275d8", "#d9534f").then(function() {
            let ticketId = $(".ticket-id"+rowId).data("ticket-id");

            $.ajax({
                url: "/accounting-tr/delete-ticket",
                type: "post",
                data:{
                    "_token":$('meta[name="csrf-token"]').attr('content'),
                    "ticket_id":ticketId,
                    "accounting_id":{{ $accounting->id }}
                }
            }).done(function (response) {
                if(response.message = "success"){
                    toastr.success("Ticket ID deleted successfully!", "Success");
                    location.reload();
                }else{
                    toastr.error("Ticket ID deletion has failed!", "Error");
                }

            }).fail(function () {
                toastr.error("Ticket ID deletion has failed!", "Error");
            });
        });
    });

    $(document).on("click", ".delete-main-ticket", function() {
        confirmModal("Are you sure that you want to delete this position?", "Are you sure?", "Delete", "Close", "#0275d8", "#d9534f").then(function() {

            $.ajax({
                url: "/accounting-tr/delete-main-ticket",
                type: "post",
                data:{
                    "_token":$('meta[name="csrf-token"]').attr('content'),
                    "accounting_id":{{ $accounting->id }}
                }
            }).done(function (response) {
                if(response.message = "success"){
                    toastr.success("Ticket ID deleted successfully!", "Success");
                    location.reload();
                }else{
                    toastr.error("Ticket ID deletion has failed!", "Error");
                }

            }).fail(function () {
                toastr.error("Ticket ID deletion has failed!", "Error");
            });
        });
    });
 @endif
    $(document).on('change keyup', '.official-invoice-number',function() {
        let value = $(this).val().replaceAll("_","");
        let element = $(this);
        $(this).parent().find('span').hide();
        if(value.length === 16) {
            $.ajax({
                url: '/accounting-tr/check-official-invoice',
                type: "POST",
                data: {
                    _token:"{{csrf_token()}}",
                    official_number: value,
                },
                success: function(response) {
                    if(response.status === 1) {
                        element.parent().find('.existing-official-number').val(1);
                        element.parent().find('.badge-official').html('This official number is already in used!').show();
                    }
                    else {
                        element.parent().find('.existing-official-number').val(0);
                        element.parent().find('.badge-official').html('').hide();
                    }
                }
            });
        }
        let gta_created_date_element = $(this).parent().parent().find("input[name='gta_create_date[]']");
        let gta_amounts_element      = $(this).parent().parent().find("input[name='gta_amounts[]']");

        if(value.length > 0 && value.length <= 16) {
            gta_created_date_element.addClass("validate[required]");
            gta_amounts_element.addClass("validate[required]");
        }
        else {
            gta_created_date_element.removeClass("validate[required]");
            gta_amounts_element.removeClass("validate[required]");
            validationEngineHide([gta_amounts_element,gta_created_date_element,$(this)])
        }
    });
    @endif // "Type: "Proforma" End

    @if($type=="offer")
    @if($mail_status == 0)
    disableAll();
    @endif
    @endif

    @if(($type == "offer" || $type == "invoice") && $mail_status == 1)
    function get_filesize(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("HEAD", url, false); // Notice "HEAD" instead of "GET",
        //  to get only the header
        xhr.onreadystatechange = function() {
            if (this.readyState === this.DONE) {
                callback(parseInt(xhr.getResponseHeader("Content-Length")));
            }
        };
        xhr.send();
    }

    $("#show-send-mail").on("change",function (){

        if($(this).is(":checked")){

            $("#send-email-section").show(100);

            $.ajax({
                url:"/accounting-tr/get-requested-accounting/{{$company->route_name}}",
                type:"POST",
                async:false,
                data:{
                    accounting_no:$("#mail-accounting-no").val(),
                    accounting_type:$("#mail-accounting-type").val(),
                    _token:"{{csrf_token()}}",
                },
                success:function (response){
                    if(response.success === 1){
                        let organization = response.organization;
                        let accounting   = response.accounting;


                        if(accounting.type === "offer") {
                            $("#mail-subject").val("Offer: "+$("#accounting-no").val()+" ");
                        }
                        else if(accounting.type === "invoice") {
                            $("#mail-subject").val("Storno: " + accounting.storno_no);
                        }

                        if(organization.accounting_to) {
                            $("#email_to").val(organization.accounting_to);//organizasyonun mailini al
                        }
                        else {
                            $("#email_to").val(organization.email);//organizasyonun mailini al
                        }

                        if(organization.accounting_cc) {
                            $("#email_cc").val(organization.accounting_cc.split(";"));
                        }

                        if(organization.accounting_bcc) {
                            $("#email_bcc").val(organization.accounting_bcc.split(";"));
                        }

                        /*Oluşturulan dosyaları sunucudan çekip dropzone a koy*/
                        let mockFiles = [];
                        let dropzone = Dropzone.forElement("#accounting-mail-attachment");
                        let globalsize;
                        $.each(response.files,function (index,file){
                            get_filesize("/uploads/"+file,function(size){
                                globalsize = size;
                            });
                            mockFiles.push({name:file,size:globalsize});
                        });
                        $.each(mockFiles,function(index,file){
                            file.status = Dropzone.SUCCESS;
                            file.accepted = true;
                            dropzone.emit("addedfile",file);
                            dropzone.emit("complete",file);
                            dropzone.files.push(file);
                            $("#accounting-mail-attachment-response").append("<input type='hidden' name='mail_attachment["+file.size+"]' value='"+file.name+"' >");
                        });
                    }
                    else {
                        toastr.error("An error thrown!","Error!");
                    }
                }
            });
        }
        else{
            $("#send-email-section").hide(100);
            Dropzone.forElement("#accounting-mail-attachment").removeAllFiles(true);
            $("#accounting-mail-attachment-response").html("");
        }
    });

    $("#email_to").on("change keyup",function (){
        $("#email-to-badge").hide();
    });

    $("#mail-subject").on("change keyup",function (){
        $("#email-subject-badge").hide();
    });

    $("#send-button").on("click",function (){
        let accounting_type = $("#mail-accounting-type").val();
        let accounting_no   = $("#mail-accounting-no").val();
        let email_to        = $("#email_to").val();
        let email_cc        = $("#email_cc").val();
        let email_bcc       = $("#email_bcc").val();
        let additional_text = $("#additional-text").val();
        let subject         = $("#mail-subject").val();
        let files           = JSON.stringify($("#accounting-mail-attachment-response :input").serializeArray());
        /*VALIDATE*/
        if(subject === ""){
            $("#email-subject-badge").show();
            return false;
        }
        if(email_to==="") {
            $("#email-to-badge").show();
            return false;
        }
        /*END VALIDATE*/


            confirmModal('Are you sure? Mail will be sent!',"Are you sure?","Ok","Cancel").then(function() {
                toggleLoader(true);
                $.ajax({
                    url: "/accounting-tr/send-mail/{{$company->route_name}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{csrf_token()}}",
                        accounting_type: accounting_type,
                        accounting_no: accounting_no,
                        email_to: email_to,
                        email_cc: email_cc,
                        email_bcc: email_bcc,
                        files: files,
                        additional_text:additional_text,
                        subject:subject,
                        ticket_id:$("#ticket-id").val(),

                    },
                    success: function (response) {
                        if (response.success === 1) {
                            location.reload();
                        }
                        else {
                            toggleLoader(false);
                            toastr.error("An error thrown!", "Error!")
                        }
                    }
                });
            })

    });
    @endif
    @endif // "Page Type: Update" End

    var el = document.getElementById('table-input-context');

    let sortable = new Sortable(el, {
        handle: '.glyphicon-move',
        //items: "#table-input-context:not(:last-child)",
        animation: 150,
        filter: ".not-last",
        draggable: ".draggable-row",
        preventOnFilter: false,
        onEnd: function (evt) {
            generate_position();
            let remove_rows = $("#table-input-context").children();
            let len = remove_rows.length;
            $.each(remove_rows, function (index, sel) {
                let element = $(sel).find("a");

                if (len - 1 === index) {
                    if (!$(sel).find(".position-btn").data("already")) {
                        $(element).parent().html("<a class=\"btn btn-success btn-sm add-row-btn\" onclick='$.add_row()'><i class=\"fa fa-plus\"></i></a>");
                        $(sel).find(".position-btn").html("");
                    }
                    else {
                        generate_position();
                    }

                    $(sel).removeClass("draggrable-row").addClass("not-last")
                }
                else {
                    if (!$(sel).find(".position-btn").data("already")) {

                        $(element).parent().html("<a class=\"btn btn-danger btn-sm remove-row-btn\" onclick='$.remove_row(this)'><i class=\"fa fa-minus\"></i></a>")
                    }
                    $(sel).find(".position-btn").html("<span class='glyphicon glyphicon-move mt-2'></span>");
                    $(sel).removeClass("not-last").addClass("draggable-row");
                }

            });

        },
    });

    if(isAllDisabled){
        $.each($("#table-input-context").children(),function (index,child){
            $(child).find(".position-btn").html("");
        });
    }

    $('#main-form').on('submit', function() {
        let cont = true;

        $.each($(".existing-official-number"),function(index,element) {
            if($(element).val() == 1) {
                cont = false;
            }
        });

        if($(".existing-official-number").val() == 1) {
            cont = false;
        }

        $('#main-form').validationEngine('validate',{
            scroll: false,
            validateNonVisibleFields:true
        });
    })

    $("#no").on("change keyup", function() { ////
        let value = $(this).val().replace("_","").replace("PF-","").replace("OF-","");

        if (value.length < 6) {
            $('#badgeText').html('The number must be at least 6 characters.');
            $("#badgeText").show();
            $('#in-use').val(1);
            return false;
        } else{
            $("#badgeText").hide();
            $('#in-use').val(0);
        }

        $.ajax({
            url: '/accounting-tr/quest-no/{{$company->route_name}}' + $(this).val(),
            type: "get",
            dataType: 'json',
        }).done(function(data) {
            if (data.status === 1) {
                $('#badgeText').html('This number is in use!');
                $("#badgeText").show();
                $('#in-use').val(1);
            }
            else {
                $('#in-use').val(0);
                $("#badgeText").hide();
            }
        });
    });

});

function collapsePaymentHistory() {
    if($('.switch-history').hasClass('hide-history')) {
        $('.switch-history').removeClass('hide-history');
        $('.history-switcher').text('Show Less');
    }
    else {
        $('.switch-history').addClass('hide-history');
        $('.history-switcher').text('Show More');
    }
}

function collapseDeadlineLogs() {
    if($('.switch-deadline').hasClass('hide-history')) {
        $('.switch-deadline').removeClass('hide-history');
        $('.deadline-switcher').text('Show Less');
    }
    else {
        $('.switch-deadline').addClass('hide-history');
        $('.deadline-switcher').text('Show More');
    }
}

function collapseOldMailLogs() {
    if($('.switch-old-mails').hasClass('hide-history')) {
        $('.switch-old-mails').removeClass('hide-history');
        $('.old-mail-switcher').text('Show Less');
    }
    else {
        $('.switch-old-mails').addClass('hide-history');
        $('.old-mail-switcher').text('Show More');
    }
}

@if(isset(request()->ticket_id))
    $(document).ready(function () {
        $("#tickte-id").trigger("change")
    })
@endif


</script>
@endsection
