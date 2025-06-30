<div class="col-lg-4 col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Organization</label>
                                    <div class="col-md-9">
                                        @if(!$isCopy)
                                        <select type="text" class="form-control" name="organization"
                                                required readonly>
                                           <option value="{{$accounting->customer_id}}" selected>{{$org_name}}</option>
                                        </select>
                                        @else
                                            <select type="text" class="form-control" id="organization" name="organization"
                                                    required>
                                            </select>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Bearbeiter</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="editor"
                                               value="{{$accounting->editor}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Company</label>
                                    <div class="col-md-9">

                                        <select class="form-control" id="company_id" name="company_id" required @if(!$isCopy)readonly="" style="pointer-events: none" @elseif($type == "invoice") readonly="" style="pointer-events: none" @endif>
                                             @if(!($type=="invoice"))
                                                 @if(!$isCopy)
                                                    <option value="{{$accounting->company_id}}" selected>{{$company_name}}</option>
                                                 @else
                                                     @foreach($companies as $company)
                                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                                     @endforeach
                                                 @endif
                                             @else
                                                <option value="2" selected>getucon GmbH</option>
                                             @endif
                                        </select>
                                    </div>
                                </div>
    @if($type == "invoice")
    <div class="form-group row">
        <label for="repeat-date" class="col-md-3 form-label">Repeat</label>
        <div class="col-md-9">
            <input type="date" class="form-control"  id="repeat-date" name="repeat_date">
        </div>
    </div>
        <div class="form-group row repeat-div" style="display: none">
            <label for="repeat-date" class="col-md-3 form-label">Repeat Reminder</label>
            <div class="col-md-9">
                <select class="form-control" name="repeat_reminder" id="repeat-reminder">
                    <option value="0">Select Day</option>
                    <option value="3">3</option>
                    <option value="7">7</option>
                    <option value="14">14</option>
                    <option value="21">21</option>
                    <option value="28">28</option>
                </select>
            </div>
        </div>
    @endif
                                <div class="form-group row">
                                    <label class="col-md-3 form-label d-flex align-items-center">Gesamt Netto</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" data-type="currency" id="total-amount"
                                               value="{{number_format($accounting->amount,2,",",".")}}"
                                               disabled>
                                    </div>
                                    <label class="col-md-3 form-label">zzgl. USt. <x-infobox info="%19"/></label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" data-type="currency" id="with-kdv"
                                               value="{{number_format($accounting->total_amount, 2, ",", ".")}}"
                                               disabled>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Subject</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="title">{{$accounting->title}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Fußnote</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" type="text" rows="5" cols="100" name="footnote">{{$accounting->footnote}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Internal Info</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" type="text" rows="5" name="internal_info">{!! $isCopy?"":$accounting->internal_info !!}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Datum</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="date" name="date" id="date" min="{{ date("Y-m-d") }}" max="{{ date("Y-m-d") }}" value="{{ date("Y-m-d") }}" required readonly="readonly">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-label">Lieferdatum</label>
                                    <div class="col-md-9">
                                        @if(!$isCopy)
                                            <input class="form-control" type="date" name="delivery_date"
                                               value="{{$accounting->delivery_date}}" required>
                                        @else
                                            <input class="form-control" type="date" name="delivery_date"
                                                   value="" required>
                                        @endif
                                    </div>
                                </div>
                                {{--Eğer Invoice faturası ise deadline tarihi verisini alıyoruz--}}
                                @if($type=="invoice" || $type == "proforma")
                                    <div class="form-group row">
                                        <label class="col-md-3 form-label">Zahlungsziel</label>
                                        <div class="col-md-3">
                                            @if(!$isCopy)
                                            <select class="form-control standard-form-input" name="deadline_day" id="deadline-day" required>
                                                <option value="">Select Day</option>
                                                <option value="1" {{$accounting->deadline_day==1?"selected":""}}>Vorkasse</option>
                                                <option value="7" {{$accounting->deadline_day==7?"selected":""}}>7</option>
                                                <option value="14" {{$accounting->deadline_day==14?"selected":""}}>14</option>
                                                <option value="21" {{$accounting->deadline_day==21?"selected":""}}>21</option>
                                                <option value="30" {{$accounting->deadline_day==30?"selected":""}}>30</option>
                                                <option value="45" {{$accounting->deadline_day==45?"selected":""}}>45</option>
                                                <option value="60" {{$accounting->deadline_day==60?"selected":""}}>60</option>
                                                <option value="75" {{$accounting->deadline_day==75?"selected":""}}>75</option>
                                                <option value="90" {{$accounting->deadline_day==90?"selected":""}}>90</option>
                                                <option value="120" {{$accounting->deadline_day==120?"selected":""}}>120</option>
                                            </select>
                                            @else
                                                <select class="form-control standard-form-input" name="deadline_day" id="deadline-day" required>
                                                    <option value="">Select Day</option>
                                                    <option value="1">Vorkasse</option>
                                                    <option value="7">7</option>
                                                    <option value="14">14</option>
                                                    <option value="21">21</option>
                                                    <option value="30">30</option>
                                                    <option value="45">45</option>
                                                    <option value="60">60</option>
                                                    <option value="75">75</option>
                                                    <option value="90">90</option>
                                                    <option value="120">30</option>

                                                </select>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if(!$isCopy)
                                            <input class="form-control standard-form-input" type="date" name="deadline" id="deadline" value="{{$accounting->deadline}}" required>
                                            @else
                                            <input class="form-control standard-form-input" type="date" name="deadline" id="deadline" value="" required>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                 <div class="row">
                                    <div class="col-md-12">
                                        <span class="badge badge-warning" id="ticket-warning" style="display: none;">There is not ticket have the ticket ID!</span>
                                    </div>
                                </div>
    <input type="hidden" name="ticket_count" id="ticket-count" value="@if(!$isCopy && $accounting->ticket_id) {{$ticket_id_count+1}} @endif">
    <div id="ticket-ids">
        <div class="row mt-2">
            <label class="col-md-3 form-label d-flex align-items-center">Ticket (ref.)</label>

            <div class="col-md-2 d-flex align-items-center" >
                <input class="form-control" type="text" placeholder="ID" name="ticket_id" id="ticket-id" value="{{$isCopy?"":$accounting->ticket_id}}">
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <span class="form-label" data-toggle="tooltip" data-placement="top" id="ticket-name"></span>
            </div>



        @if((!$isCopy && $ticket_id_count<9) || $isCopy)
                <div class="col-md-1 d-flex align-items-center">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <a class="btn btn-success btn-sm add-ticket-id-row-btn" onclick="addTicketId()" @if((!$isCopy && $ticket_id_count==9) || $isCopy || !$accounting->ticket_id) style="display: none;" @endif><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if(!$isCopy && isset($tickets))
            @foreach($tickets as $key=>$ticket)
                <div class="row mt-2">
                    <label class="col-md-3 form-label d-flex align-items-center">Ticket (ref.)</label>

                    <div class="col-md-2 d-flex align-items-center" >
                        <input class="form-control ticket-id" data-row-id="{{$key+2}}" type="text" placeholder="ID" name="ticket_id{{$key+2}}" id="ticket-id{{$key+2}}" value="{{$isCopy?"":$ticket["id"]}}">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <span class="form-label" data-toggle="tooltip" data-original-title="{{strlen($ticket["ticket_name"])>36?$ticket["ticket_name"]:""}}" data-placement="top" id="ticket-name">{{strlen($ticket["ticket_name"])>36?substr($ticket["ticket_name"],0,36)."...":$ticket["ticket_name"]}}</span>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <a class="btn btn-danger btn-sm delete-ticket-id-btn" data-row-id="{{$key+2}}"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>

