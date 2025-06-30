<div class="row w-100 m-0 pl-1 py-1">
    <div class="col-md-12 col-lg-12">
        <div class="row pt-1">
            <div class="m-0">
                <div class="form-group m-0">
                    <label class="custom-switch">
                        <input type="checkbox" id="show-reminder-section" class="custom-switch-input" autocomplete="off" @if($current_reminder) checked @endif>
                        <span class="custom-switch-indicator"></span>
                    </label>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                @if($current_reminder)
                Update Reminder
                @else
                Send Invoice & Set Reminder <x-infobox info="Customer's email and PDF of the invoice will be added automatically. CC and BCC sections are applied to both 'Reminder' and 'Invoice Mail'."/>
                @endif
            </div>
        </div>
        <div class="row" id="reminder-section" @if(!$current_reminder) style="display:none;" @endif>
            <div class="card-body">
                <form action="{{ url('/accounting-tr/set-reminder') }}" method="POST" id="set_reminder_form">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="customer_email" class="col-md-3 form-label my-auto">Customer Email</label>
                                        <div class="col-md-9">
                                            <input type="text" id="customer_email" name="customer_email" class="form-control" value="{{$customer->accounting_to?:$customer->email}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="cc" class="col-md-3 form-label my-auto">Additional CC</label>
                                        <div class="col-md-9">
                                            @if($current_reminder)
                                            <x-tag-and-search-input name="cc" values="{{ $current_reminder->cc }}"/>
                                            @else
                                            <x-tag-and-search-input name="cc" values="{{ $customer->accounting_cc }}"/>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="bcc" class="col-md-3 form-label my-auto">Additional BCC</label>
                                        <div class="col-md-9">
                                            @if($current_reminder)
                                            <x-tag-and-search-input name="bcc" values="{{ $current_reminder->bcc }}"/>
                                            @else
                                            <x-tag-and-search-input name="bcc" values="{{ $customer->accounting_bcc }}"/>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="subject" class="col-md-3 form-label my-auto">Invoice Mail Subject</label>
                                        <div class="col-md-9">
                                            <input type="text" id="subject" name="subject" class="form-control" @if($current_reminder) value="{{$current_reminder->subject}}" readonly @endif>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="invoice_mail_text" class="col-md-3 form-label my-auto">Invoice Mail's Text</label>
                                        <div class="col-md-9">
                                            <textarea id="invoice_mail_text" name="invoice_mail_text" class="form-control" rows="1" @if($current_reminder) data-readonly-status="1" @endif>
                                                @if($current_reminder){{$current_reminder->mail_text_0}}@endif
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="first_reminder_text" class="col-md-3 form-label my-auto">1. Reminder's Mail Text</label>
                                        <div class="col-md-9">
                                            <textarea id="first_reminder_text" name="first_reminder_text" class="form-control" rows="1" @if($current_reminder && (isset($current_reminder->post_mail_1))) data-readonly-status="1" @endif>
                                                @if($current_reminder){{$current_reminder->mail_text_1}}@endif
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="second_reminder_text" class="col-md-3 form-label my-auto">2. Reminder's Mail Text</label>
                                        <div class="col-md-9">
                                            <textarea id="second_reminder_text" name="second_reminder_text" class="form-control" rows="1" @if($current_reminder && (isset($current_reminder->post_mail_2))) data-readonly-status="1" @endif>
                                                @if($current_reminder){{$current_reminder->mail_text_2}}@endif
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="third_reminder_text" class="col-md-3 form-label my-auto">3. Reminder's Mail Text</label>
                                        <div class="col-md-9">
                                            <textarea id="third_reminder_text" name="third_reminder_text" class="form-control" rows="1" @if($current_reminder && (isset($current_reminder->post_mail_3))) data-readonly-status="1" @endif>
                                                @if($current_reminder){{$current_reminder->mail_text_3}}@endif
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($current_reminder)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom" style="border: 1px solid #ebecf1 !important; padding-top: 12px;">
                                            <label class="col-md-3 form-label my-auto">Reminder's Status</label>
                                            <div class="col-md-9">
                                                <table class="table table-bordered reminder-status-table text-center mb-1">
                                                    <tbody>
                                                    @php
                                                        $user = App\User::query()->find($current_reminder->add_by);
                                                    @endphp
                                                    <tr>
                                                        <td class="font-weight-bold text-left w-50">Set By</td>
                                                        <td class="text-left w-50">{{$user->first_name . " " . $user->surname}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-left w-50">Creation Date & Time</td>
                                                        <td class="text-left w-50">{{\Carbon\Carbon::parse($current_reminder->created_at)->format('d.m.Y H:i:s')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-left w-50">1. Reminder</td>
                                                        <td class="text-left w-50">{{$current_reminder->post_mail_1 ? \Carbon\Carbon::parse($current_reminder->post_mail_1)->format('d.m.Y') : "Not Sent Yet"}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-left w-50">2. Reminder</td>
                                                        <td class="text-left w-50">{{$current_reminder->post_mail_2 ? \Carbon\Carbon::parse($current_reminder->post_mail_2)->format('d.m.Y') : "Not Sent Yet"}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-left w-50">3. Reminder</td>
                                                        <td class="text-left w-50">{{$current_reminder->post_mail_3 ? \Carbon\Carbon::parse($current_reminder->post_mail_3)->format('d.m.Y') : "Not Sent Yet"}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold text-left w-50">Status</td>
                                                        @if($current_reminder->status == 1)
                                                            <td class="text-left w-50">Running</td>
                                                        @elseif($current_reminder->status == 2)
                                                            <td class="text-left w-50">Not Running</td>
                                                        @else
                                                            <td class="text-left w-50">Not Running (Blacklisted)</td>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="reminder_day" class="col-md-3 form-label my-auto">Reminder's Deadline</label>
                                        <div class="col-md-3">
                                            <select id="reminder_day" name="reminder_day" class="form-control standard-form-input">
                                                <option value="1" @if($current_reminder) @if($current_reminder->day == 1) selected="selected" @endif @else @if($accounting->deadline_day === 1) selected="selected" @endif @endif>1 Day</option>
                                                <option value="7" @if($current_reminder) @if($current_reminder->day == 7) selected="selected" @endif @else @if($accounting->deadline_day === 7) selected="selected" @endif @endif>7 Days</option>
                                                <option value="14" @if($current_reminder) @if($current_reminder->day == 14) selected="selected" @endif @else @if($accounting->deadline_day === 14) selected="selected" @endif @endif>14 Days</option>
                                                <option value="21" @if($current_reminder) @if($current_reminder->day == 21) selected="selected" @endif @else @if($accounting->deadline_day === 21) selected="selected" @endif @endif>21 Days</option>
                                                <option value="30" @if($current_reminder) @if($current_reminder->day == 30) selected="selected" @endif @else @if($accounting->deadline_day === 30) selected="selected" @endif @endif>30 Days</option>
                                                <option value="45" @if($current_reminder) @if($current_reminder->day == 45) selected="selected" @endif @else @if($accounting->deadline_day === 45) selected="selected" @endif @endif>45 Days</option>
                                                <option value="60" @if($current_reminder) @if($current_reminder->day == 60) selected="selected" @endif @else @if($accounting->deadline_day === 60) selected="selected" @endif @endif>60 Days</option>
                                                <option value="75" @if($current_reminder) @if($current_reminder->day == 75) selected="selected" @endif @else @if($accounting->deadline_day === 75) selected="selected" @endif @endif>75 Days</option>
                                                <option value="90" @if($current_reminder) @if($current_reminder->day == 90) selected="selected" @endif @else @if($accounting->deadline_day === 90) selected="selected" @endif @endif>90 Days</option>
                                                <option value="120" @if($current_reminder) @if($current_reminder->day == 120) selected="selected" @endif @else @if($accounting->deadline_day === 120) selected="selected" @endif @endif>120 Days</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" id="reminder_deadline" name="reminder_deadline" class="form-control standard-form-input" @if($current_reminder) value="{{$current_reminder->deadline}}" @else value="{{ $accounting->deadline }}" @endif required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label for="reminder_status" class="col-md-3 form-label my-auto">Reminder Status</label>
                                        <div class="col-md-9">
                                            <select id="reminder_status" name="reminder_status" class="form-control" required>
                                                <option value="1" @if($current_reminder) @if($current_reminder->status == 1) selected="selected" @endif @endif>
                                                    Enabled
                                                </option>
                                                <option value="2" @if($current_reminder) @if($current_reminder->status == 2 || $current_reminder->status == 3) selected="selected" @endif @endif>
                                                    Disabled
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($current_reminder)
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-3 form-label my-auto">Blacklist Status</label>
                                        <div class="col-md-3">
                                            @if($current_reminder->status == 3)
                                            <input type="text" class="form-control text-danger font-weight-bold" value="YES" disabled>
                                            @else
                                            <input type="text" class="form-control text-success font-weight-bold" value="NO" disabled>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-3 form-label my-auto">Receive Payment</label>
                                        <div class="col-md-3">
                                            <input type="text" name="received_payments" id="receive_payment" class="form-control standard-form-input" data-type="currency" placeholder="Enter a numeric value." autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" name="payment_date" id="payment_date" class="form-control standard-form-input" max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" value="{{\Carbon\Carbon::now()->format("Y-m-d")}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom">
                                            <label for="reminder_status" class="col-md-3 form-label my-auto">Gesamtbetrag</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{ number_format($accounting->total_amount, 2, ',', '.') }} €" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-3 form-label my-auto">Unpaid Payment</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-danger" id="result" data-type="currency" disabled>
                                            <input type="hidden" id="payment_status" name="payment_status">
                                            <input type="hidden" id="total_payment" value="{{$total_payments}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @isset($payment_logs)
                            @if(count($payment_logs) > 0)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group row border-bottom" style="border: 1px solid #ebecf1 !important; padding-top: 12px;">
                                            <label class="col-md-3 form-label my-auto">Payment History</label>
                                            <div class="col-md-9 switch-history @if(count($payment_logs) > 5) hide-history @endif">
                                                <table class="payment-logging-table text-center">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left">Payment</th>
                                                            <th class="text-left">Date</th>
                                                            <th class="text-left">Added By</th>
                                                            <th class="text-left">Log Date & Time</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($payment_logs as $log)
                                                            @php
                                                                $user = App\User::query()->find($log->add_by);
                                                                $fullname = $user->first_name . " " . $user->surname;
                                                            @endphp
                                                            <tr>
                                                                <td class="text-left">{{number_format($log->payment_amount, 2, ',', '.')}} €</td>
                                                                <td class="text-left">{{\Carbon\Carbon::parse($log->payment_date)->format("d.m.Y")}}</td>
                                                                <td class="text-left">{{$fullname}}</td>
                                                                <td class="text-left">[{{\Carbon\Carbon::parse($log->created_at)->format("d.m.Y H:i:s")}}]</td>
                                                                <td><a onclick="deletePayment({{$log->id}})"><i class="fa fa-trash text-danger"></i></a></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if(count($payment_logs) > 5)
                                            <div class="col-md-3"></div>
                                            <div class="col-md-9">
                                                <div class="d-flex justify-content-end py-2">
                                                    <a class="history-switcher font-weight-semibold text-primary pointer-cursor"
                                                       onclick="collapsePaymentHistory()"
                                                       style="text-decoration: underline;">
                                                        Show More
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endisset
                            @isset($deadline_logs)
                            @if(count($deadline_logs) > 0)
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group row border-bottom" style="border: 1px solid #ebecf1 !important; padding-top: 12px;">
                                                    <label class="col-md-3 form-label my-auto">
                                                        Deadline Logs
                                                        <x-infobox info="If a reminder email is sent after the deadline, it is kept as a log."/>
                                                    </label>
                                                    <div class="col-md-9 switch-deadline @if(count($deadline_logs) > 5) hide-history @endif">
                                                        <table class="deadline-logs-table text-center">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-left">New Deadline</th>
                                                                <th class="text-left">Changed By</th>
                                                                <th class="text-left">Log Date & Time</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($deadline_logs as $log)
                                                                @if($log->changed_deadline)
                                                                    @php
                                                                        $user = App\User::query()->find($log->changed_by);
                                                                        $fullname = $user->first_name . " " . $user->surname;
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-left">{{\Carbon\Carbon::parse($log->changed_deadline)->format("d.m.Y")}}</td>
                                                                        <td class="text-left">{{$fullname}}</td>
                                                                        <td class="text-left">[{{\Carbon\Carbon::parse($log->created_at)->format("d.m.Y H:i:s")}}]</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @if(count($deadline_logs) > 5)
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-9">
                                                            <div class="d-flex justify-content-end py-2">
                                                                <a class="deadline-switcher font-weight-semibold text-primary pointer-cursor"
                                                                   onclick="collapseDeadlineLogs()"
                                                                   style="text-decoration: underline;">
                                                                    Show More
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1 border-bottom" style="border: 1px solid #ebecf1 !important; padding-top: 12px;">
                                            <label class="col-md-3 form-label align-self-center">Old Outgoing Mail Status</label>
                                            <div class="col-lg-9 col-md-9 switch-old-mails @if(count($deadline_logs) > 5) hide-history @endif">
                                                <table class="outgoing-mail-logs-table text-center">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-left">Reminder No</th>
                                                        <th class="text-left">Old Mail Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($deadline_logs as $deadline_log)
                                                        <tr>
                                                            <td class="text-left">{{$loop->index + 1}}. Reminder</td>
                                                            <td class="text-left">{{Carbon\Carbon::parse($deadline_log->old_mail_date)->format("d.m.Y")}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if(count($deadline_logs) > 5)
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
                                                    <div class="d-flex justify-content-end py-2">
                                                        <a class="old-mail-switcher font-weight-semibold text-primary pointer-cursor"
                                                           onclick="collapseOldMailLogs()"
                                                           style="text-decoration: underline;">
                                                            Show More
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                            @endif
                            @endisset
                        </div>
                    </div>
                    @if($attachments)
                    <div class="row border m-0 p-0 w-100-1 @if(count($attachments) == 0) d-none @endif">
                        <div class="table-responsive">
                            <table class="table table-bordered text-wrap w-100 text-center">
                                <thead>
                                    <tr>
                                        <th class="w-5 default-cursor">ID</th>
                                        <th class="w-30 default-cursor">File</th>
                                        <th class="w-10 default-cursor">File Size</th>
                                        <th class="w-30 default-cursor">Uploaded By</th>
                                        <th class="w-15 default-cursor">Upload Time</th>
                                        <th class="w-10 default-cursor">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($attachments as $attachment)
                                    @php
                                        $user = \App\User::query()->find($attachment->add_by);
                                        $name = $user->first_name . " " . $user->surname;
                                    @endphp
                                    <tr>
                                        <td class="default-cursor">{{$attachment->id}}</td>
                                        <td class="default-cursor">{{$attachment->attachment}}</td>
                                        <td class="default-cursor">{{round($attachment->size * 0.000001, 2)}} MB</td>
                                        <td class="default-cursor">{{$name}}</td>
                                        <td class="default-cursor">{{\Carbon\Carbon::parse($attachment->created_at)->format('d.m.Y H:i:s')}}</td>
                                        @if(in_array(auth()->user()->role_id, [1, 2, 3, 4]))
                                        <td class="justify-content-between">
                                            <a onclick="deleteReminderAttachment({{$attachment->id}})" class="btn btn-danger" target="_blank"><i class="fa fa-trash"></i></a>
                                            <a href="{{route("uploads",[$attachment->attachment])}}" class="btn btn-primary" target="_blank"><i class="fa fa-eye"></i></a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @unless(count($attachments) == 5)
                    <div id="reminder-attachment-response"></div>
                    <div class="form-label pt-2">
                        Add Attachment <span class="text-danger">(Maximum {{5 - count($attachments)}} Files | Maximum File Size: 10 MB)</span>
                    </div>
                    @endunless

                    <input type="hidden" id="reference-company" value="{{$company->route_name}}" name="ref_company" autocomplete="off">
                    <input type="hidden" id="invoice_date" name="invoice_date" autocomplete="off">
                    <input type="hidden" id="invoice-number" name="invoice_number" autocomplete="off">
                    <input type="hidden" id="cid" name="cid" autocomplete="off">
                    <input type="hidden" id="oid" name="oid" autocomplete="off">
                    <input type="hidden" id="invoice_reminder_id" name="invoice_reminder_id" @if($current_reminder) value="{{$current_reminder->id}}" @endif>
                    <input type="submit" id="form_submit_button" style="display: none;">
                </form>

                @isset($attachments)
                @unless(count($attachments) == 5)
                <div class="row">
                    <div class="col-md-12">
                        <form class="dropzone" id="reminder-attachment-dropzone">
                        @csrf
                        </form>
                    </div>
                </div>
                @endunless
                @endisset

                <div class="row">
                    <div class="col-lg-12 col-md-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm mt-4 mb-0" onclick="formSubmit()">@if($current_reminder) Save Reminder @else Send Invoice & Set Reminder @endif</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

