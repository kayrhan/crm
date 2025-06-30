<div class="col-lg-4 col-md-12">
    <div class="form-group row">
        <label for="organization" class="col-md-3 form-label">Organization</label>
        <div class="col-md-9">
            <select type="text" class="form-control" id="organization" name="organization" required {{$page_type=="update"?"disabled":""}}>
                @if($page_type == "update")
                    <option value="{{$accounting->customer_id}}">{{$accounting->org_name}}</option>
                @endif
                @if(isset(request()->ticket_id))
                    <option value="{{$customer_id}}">{{$org_name}}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="editor" class="col-md-3 form-label">Editor</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="editor" name="editor" value="@if($page_type=="update"){{$accounting->editor}}@elseif(isset(request()->ticket_id)){{auth()->user()->first_name . " " . auth()->user()->surname}}@endif" required>
        </div>
    </div>
    @if($page_type=="update")
    <div class="form-group row">
        <label for="accounting-no" class="col-md-3 form-label">@if($type=="offer")Offer @elseif($type=="invoice") Invoice @endif No</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="accounting-no" value="{{$accounting->no}}" disabled>
        </div>
    </div>
    <input type="hidden" name="accounting_id" value="{{$accounting->id}}">
    @endif
    <div class="form-group row">
        <label for="company_id" class="col-md-3 form-label">Company</label>
        <div class="col-md-9">
            <select id="company_id" class="form-control" name="company_id" required {{$page_type=="update"?"disabled":""}} @if($type=="invoice")style="pointer-events: none;" readonly="readonly" @endif>
            @if($page_type=="update")
                @foreach($companies as $comp)
                    <option value="{{$comp->id}}" {{$comp->id==$accounting->company_id?"selected":""}}>{{$comp->name}}</option>
                @endforeach
            @else
                @foreach($companies as $comp)
                        <option value="{{$comp->id}}" {{$page_type=="update"?($comp->id==$accounting->company_id?"selected":""):""}}>{{$comp->name}}</option>
                @endforeach
            @endif
            </select>
        </div>
    </div>
    @if($type == "invoice")
    <div class="form-group row">
        <label for="repeat-date" class="col-md-3 form-label">Repeat</label>
        <div class="col-md-9">
            <input type="date" class="form-control" id="repeat-date" name="repeat_date" value="{{$page_type=="update"?$accounting->repeat_date:""}}">
        </div>
    </div>
        <div class="form-group row repeat-div" @if($page_type != "update" || ($page_type == "update" && !$accounting->repeat_date)) style="display: none"@endif>
            <label for="repeat-date" class="col-md-3 form-label">Repeat Reminder</label>
            <div class="col-md-9">
                <select class="form-control standard-form-input" name="repeat_reminder" id="repeat-reminder">
                    <option value="0">Select Day</option>
                    <option value="3"  {{$page_type=="update"?($accounting->repeat_reminder==1?"selected":""):""}}>3</option>
                    <option value="7"  {{$page_type=="update"?($accounting->repeat_reminder==7?"selected":""):""}}>7</option>
                    <option value="14" {{$page_type=="update"?($accounting->repeat_reminder==14?"selected":""):""}}>14</option>
                    <option value="21" {{$page_type=="update"?($accounting->repeat_reminder==21?"selected":""):""}}>21</option>
                    <option value="28" {{$page_type=="update"?($accounting->repeat_reminder==28?"selected":""):""}}>28</option>
                </select>
            </div>
        </div>
    @endif
    <div class="form-group row">
        <label for="kdv" class="col-md-3 form-label">TAX</label>
        <div class="col-md-3">
            <select class="form-control" id="kdv" name="kdv" required>
                <option value="0,00" {{$page_type=="update" ? ($accounting->kdv==0 ? "selected" : "") : ""}}>0</option>
                @if($page_type == "update")
                <option value="18" {{$accounting->kdv==18 ? "selected" : ""}}>18</option>
                @endif
                <option value="20" {{$page_type=="update" ? ($accounting->kdv==20 ? "selected" : "") : ""}}>20</option>
            </select>
        </div>
        <label for="total-amount" class="col-md-3 form-label d-flex align-items-center">Total Amount + TAX</label>
        <div class="col-md-3">
            <input type="text" class="form-control" data-type="currency" id="total-amount" value="{{$page_type=="update"?number_format($accounting->total_amount, 2, ",", "."):""}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-md-3 form-label">Subject</label>
        <div class="col-md-9">
            <textarea class="form-control" rows="5" id="title" name="title">{{$page_type=="update"?$accounting->title:""}}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="footnote" class="col-md-3 form-label">Footnote</label>
        <div class="col-md-9">
            <textarea class="form-control" type="text" rows="5" id="footnote" name="footnote">{{$page_type=="update"?$accounting->footnote:""}}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="internal-info" class="col-md-3 form-label">Internal Info</label>
        <div class="col-md-9">
            <textarea class="form-control" type="text" rows="5" id="internal-info" name="internal_info">{{$page_type=="update"?$accounting->internal_info:""}}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="date" class="col-md-3 form-label">Date</label>
        <div class="col-md-9">
            <input class="form-control" type="date" name="date" id="date" min="{{ date("Y-m-d") }}" max="{{ date("Y-m-d") }}" value="{{ $page_type=="update" ? $accounting->date : date("Y-m-d") }}" required readonly="readonly">
        </div>
    </div>
    <div class="form-group row">
        <label for="delivery-date" class="col-md-3 form-label">Delivery Date</label>
        <div class="col-md-9">
            <input type="date" id="delivery-date" class="form-control" name="delivery_date" value="{{$page_type=="update"?$accounting->delivery_date:""}}" required>
        </div>
    </div>
    @if($type=="invoice")
    <div class="form-group row">
        <label for="deadline-day" class="col-md-3 form-label">Deadline Date</label>
        <div class="col-md-3">
            <select class="form-control standard-form-input" name="deadline_day" id="deadline-day" required>
                <option value="">Select Day</option>
                <option value="1"  {{$page_type=="update"?($accounting->deadline_day==1?"selected":""):""}}>Vorkasse</option>
                <option value="7"  {{$page_type=="update"?($accounting->deadline_day==7?"selected":""):""}}>7</option>
                <option value="14" {{$page_type=="update"?($accounting->deadline_day==14?"selected":""):""}}>14</option>
                <option value="21" {{$page_type=="update"?($accounting->deadline_day==21?"selected":""):""}}>21</option>
                <option value="30" {{$page_type=="update"?($accounting->deadline_day==30?"selected":""):""}}>30</option>
            </select>
        </div>
        <div class="col-md-6">
            <input class="form-control standard-form-input" type="date" name="deadline" id="deadline" value="{{$page_type=="update"?$accounting->deadline:""}}" required>
        </div>
    </div>
    @endif
    @if($page_type == "update")
    <div class="row">
        <label class="col-md-3 form-label">PDF</label>
        <div class="col-md-9">
            <a class="btn btn-primary btn-sm" target="_blank" href="{{route("uploads",[$accounting->filename])}}">{{$accounting->filename}}</a>
        </div>
    </div>
        @include("getucon-accounting-tr.custom")
    @endif
    <div class="row">
        <div class="col-md-12">
            <span class="badge badge-warning" id="ticket-warning" style="display: none;">There is not ticket have the ticket ID!</span>
        </div>
    </div>
    <input type="hidden" name="ticket_count" id="ticket-count" value="">
    <div id="ticket-ids">
        <div class="row mt-2">
            <label class="col-md-3 form-label d-flex align-items-center">Ticket (ref.)</label>

            <div class="col-md-2 d-flex align-items-center" >
                @if($page_type == "update" && $accounting->ticket_id!=null)
                    <a class="link text-primary form-label" target="_blank" id="ticket-name" href="/update-ticket/{{$accounting->ticket_id}}">#{{$accounting->ticket_id}}</a>
                @else

                    <input class="form-control" type="text" placeholder="ID" name="ticket_id" id="ticket-id" value="@if(request()->ticket_id){{$ticket_id}}@endif">
                @endif

            </div>
            @if($page_type == "update" && $accounting->ticket_id!=null)
                <div class="@if(count($tickets)<9) col-md-5 @else col-md-6 @endif d-flex align-items-center">
                    <span class="form-label" data-toggle="tooltip" data-original-title="{{strlen($ticket_name)>36?$ticket_name:""}}" data-placement="top" id="ticket-name">{{strlen($ticket_name)>36?substr($ticket_name,0,36)."...":$ticket_name}}</span>
                </div>
            @else
                <div class="col-md-6 d-flex align-items-center">
                    <span class="form-label" data-toggle="tooltip" data-placement="top" id="ticket-name"></span>
                </div>
            @endif

            @if($page_type == "update" && $accounting->ticket_id!=null)
                @if(count($tickets)==0)
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <a class="btn btn-danger btn-sm delete-main-ticket"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <a class="btn btn-primary btn-sm update-ticket" style="padding-right: 5px;"><i class="fa fa-edit"></i></a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        @if($page_type != "update" || count($tickets)<9)
                <div class="col-md-1 d-flex align-items-center">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <a class="btn btn-success btn-sm add-ticket-id-row-btn" onclick="addTicketId()" @if(($page_type == "update" && $accounting->ticket_id==null) || $page_type != "update") style="display: none;" @endif><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if($page_type == "update" && $accounting->ticket_id!=null)
            @foreach($tickets as $key=>$ticket)
                <div class="row mt-2">
                    <label class="col-md-3 form-label d-flex align-items-center">Ticket (ref.)</label>

                    <div class="col-md-2 d-flex align-items-center" >
                        <a class="link text-primary form-label ticket-id{{$key}}" data-ticket-id="{{$ticket["id"]}}" target="_blank" id="ticket-name" href="/update-ticket/{{$ticket["id"]}}">#{{$ticket["id"]}}</a>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <span class="form-label" data-toggle="tooltip" data-original-title="{{strlen($ticket["ticket_name"])>36?$ticket["ticket_name"]:""}}" data-placement="top" id="ticket-name">{{strlen($ticket["ticket_name"])>36?substr($ticket["ticket_name"],0,36)."...":$ticket["ticket_name"]}}</span>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <a class="btn btn-danger btn-sm delete-ticket" data-row-id="{{$key}}"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if($page_type === "update" && $type === "invoice")
        @if($tickets_status_ok == 1)
            <div class="row mt-2">
        <label class="col-md-3 form-label">Change Ticket Status</label>
        <div class="col-md-9">
            <a class="btn btn-sm btn-azure text-white" target="_blank" onclick="changeTicketStatus()">Set Ticket as Invoiced</a>
        </div>
    </div>
    @endif
    @endif
    @if($page_type == "update" && $type == "invoice")
    <div id="gta-section">
        @for($i = 0; $i < count($gta["numbers"]); $i++)
        @if((isset($gta["numbers"][$i]) && !empty($gta["numbers"][$i]))  && (isset($gta["amounts"][$i]) && !empty($gta["amounts"][$i])) && (isset($gta["dates"][$i]) && !empty($gta["dates"][$i])))
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group row border-bottom">
                    <label class="col-md-3 form-label my-auto">#{{$i+1}} Official Invoice</label>
                    <div class="col-md-3">
                        <input type="text" name="official_invoice_number[]" readonly class="form-control official-invoice-number validate[funcCall[validateOfficialInvoice]]" value="{{$gta["numbers"][$i]}}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="gta_create_date[]" readonly class="form-control validate[required]" value="{{$gta["dates"][$i]?Carbon\Carbon::parse($gta["dates"][$i])->format("Y-m-d"):""}}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="gta_amounts[]" readonly class="form-control validate[required]" data-type="currency" value="{{$gta["amounts"][$i]?:""}}">
                    </div>
                    <div class="col-md-1 d-flex align-self-center">
                        <a class="btn btn-sm btn-danger remove-gta-button" data-id="{{$i}}"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endfor
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group row border-bottom">
                    <label class="col-md-3 form-label my-auto">Official Invoice</label>
                    <div class="col-md-3">
                        <span class="badge badge-danger badge-official" style="z-index: 9999;"></span>
                        <input type="text" name="official_invoice_number[]" class="form-control official-invoice-number validate[funcCall[validateOfficialInvoice]]" placeholder="Official Number">
                        <input type="hidden" name="existing-official-number" class="existing-official-number" value="0">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="gta_create_date[]" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="gta_amounts[]" class="form-control" data-type="currency" >
                    </div>
                    <div class="col-md-1 d-flex align-self-center">
                        <a class="btn btn-sm btn-success" onclick="$.add_gta_row()"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
