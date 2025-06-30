@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/cicons.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/calendar/calendar.css') }}">
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
    .custom-alert-modal{
        background-color: rgba(0, 0, 0, 0.35);
    }
    .tui-full-calendar-time-date-schedule-block .tui-full-calendar-time-schedule-content strong{
        display: none;
    }
    .custom-readonly {
        pointer-events: none !important;
        background-color: #e9ecef !important;
        touch-action: none !important;
    }
    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Job Calendar </h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fe fe-layout mr-2 fs-14"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Job Calendar</a></li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Calendar and Summary Tabs --}}
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @if($isAdmin)
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab"
                        aria-controls="pills-summary" aria-selected="true">
                        <h4 class="m-0 px-2 py-1">Summary</h4>
                    </a>
                </li>
                @endif
                <li class="nav-item" role="presentation">
                    <a onclick="showPersonnelCalendar({{$isAdmin ? 119 : auth()->id()}})" class="nav-link" id="pills-calendar-tab"
                        data-toggle="pill" href="#pills-calendar" role="tab" aria-controls="pills-calendar"
                        aria-selected="false">
                        <h4 class="m-0 px-2 py-1">Calendar</h4>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                {{-- Summary --}}
                @if($isAdmin)
                <div class="tab-pane fade show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title" style="width: max-content">Summary</div>
                        </div>

                        <div class="card-body">
                            @include('calendar.summary')
                        </div>
                    </div>
                </div>
                @endif
                {{-- Calendar --}}
                <div class="tab-pane fade @if(!$isAdmin) show active @endif" id="pills-calendar" role="tabpanel"
                    aria-labelledby="pills-calendar-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title" style="width: max-content">Job Calendar</div>
                        </div>
                        @include('calendar.oldForm')

                        <div class="card-body calendarContent" id="calendarContent" style="@if ($isAdmin) display: none @endif">
                            {{-- Calendar Left part --}}
                            <div id="lnb" class="p-0">
                                {{-- calendarın kendi sıralaması d-none yaptım direk --}}
                                <div id="lnb-calendars" class="lnb-calendars d-none">
                                    <div>
                                        <div class="lnb-calendars-item">
                                            <label>
                                                <input class="tui-full-calendar-checkbox-square" type="checkbox" value="all" checked>
                                                <span></span>
                                                <strong>View all</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="calendarList" class="lnb-calendars-d1"></div>
                                </div>
                                {{-- Burada personelleri listeliycez --}}
                                <div class="w-100 h-100 p-4 py-5">
                                    <h3 class="text-center border-bottom pb-3">Users</h3>
                                    <div class="btn-group-vertical w-100">
                                        @foreach ($users as $user)
                                            <a onclick="showPersonnelCalendar({{ $user->id }})"
                                                class="btn btn-sm btn-light my-1 text-left rounded user-btns"
                                                id="user-btn-{{ $user->id }}">
                                                {{ $user->text }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- Calendar right part --}}
                            <div id="right">
                                <div id="menu">
                                    <span class="dropdown">
                                        <button style="display: none" id="dropdownMenu-calendarType"
                                            class="btn btn-default btn-sm" type="button" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="true">
                                            <i id="calendarTypeIcon" class="calendar-icon ic_view_month" style="margin-right: 4px;"></i>
                                            <span id="calendarTypeName">Dropdown</span>&nbsp;
                                            <i class="calendar-icon tui-full-calendar-dropdown-arrow"></i>
                                        </button>
                                        <ul style="display: none" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu-calendarType">
                                            <li role="presentation">
                                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-daily">
                                                    <i class="calendar-icon ic_view_day"></i>
                                                    Daily
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weekly">
                                                    <i class="calendar-icon ic_view_week"></i>
                                                    Weekly
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-monthly">
                                                    <i class="calendar-icon ic_view_month"></i>
                                                    Month
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weeks2">
                                                    <i class="calendar-icon ic_view_week"></i>
                                                    2 weeks
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a class="dropdown-menu-title" role="menuitem" data-action="toggle-weeks3">
                                                    <i class="calendar-icon ic_view_week"></i>
                                                    3 weeks
                                                </a>
                                            </li>
                                            <li role="presentation" class="dropdown-divider"></li>
                                            <li role="presentation">
                                                <a role="menuitem" data-action="toggle-workweek">
                                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-workweek" checked>
                                                    <span class="checkbox-title"></span>
                                                    Show weekends
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a role="menuitem" data-action="toggle-start-day-1">
                                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-start-day-1">
                                                    <span class="checkbox-title"></span>
                                                    Start Week on Monday
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a role="menuitem" data-action="toggle-narrow-weekend">
                                                    <input type="checkbox" class="tui-full-calendar-checkbox-square" value="toggle-narrow-weekend">
                                                    <span class="checkbox-title"></span>
                                                    Narrower than weekdays
                                                </a>
                                            </li>
                                        </ul>
                                    </span>
                                    <span id="menu-navi">
                                        <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">
                                            Today
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">
                                            <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">
                                            <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i>
                                        </button>
                                    </span>
                                    <span id="renderRange" class="render-range"></span>
                                </div>
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Add Event Modal --}}
    <div aria-hidden="true" class="modal main-modal-calendar-schedule" id="modalSetSchedule" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create New Job</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="copyPanel"
                    style="display: none; flex-direction: row; justify-content: right; margin-top: 5px">

                    <button id="" type="button" onclick="copyCalendar('left')" class="btn btn-sm btn-success mr-4"
                        style="font-size: 12px;"><i class="fa fa-long-arrow-left"></i> Copy Left
                    </button>
                    <button id="" type="button" onclick="copyCalendar('up')" class="btn btn-sm btn-warning mr-4"
                        style="font-size: 12px;">Copy Up <i class="fa fa-long-arrow-up"></i>
                    </button>
                    <button id="" type="button" onclick="copyCalendar('down')" class="btn btn-sm btn-info mr-4"
                        style="font-size: 12px;">Copy Down <i class="fa fa-long-arrow-down"></i>
                    </button>
                    <button id="" type="button" onclick="copyCalendar('right')" class="btn btn-sm btn-primary mr-4"
                        style="font-size: 12px;">Copy Right <i class="fa fa-long-arrow-right"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/calendar" id="mainFormCalendar" method="post" name="mainFormCalendar">
                        @csrf
                        <input type="hidden" name="guid" id="guid">
                        <div class="form-group">
                            <label class="tx-13 mg-b-5 tx-gray-600">
                                Status
                                <span class="text-danger">*</span>
                            </label>
                            <div class="row row-xs">
                                <div class="col-12">
                                    <select name="status" id="status" class="form-control" style="width: 100%" required>
                                        <option value="1">Open</option>
                                        <option value="2">In Progress</option>
                                        <option value="3">Done</option>
                                        <option value="4">Delay</option>
                                        <option value="5">Absent</option>
                                    </select>
                                </div><!-- col-7 -->
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 ticket-id-cont">
                                <label for="ticket-id">Ticket ID</label>
                                <input type="number" min="1" max="9999" name="ticket_id" id="ticket-id" placeholder="4444" class="form-control">
                                <div class="text-primary pt-2">
                                    <a class="link text-primary" target="_blank" id="ticket-link" href=""></a>
                                    <a class="d-none" id="link-href" href="{{ url('/update-ticket/') }}"></a>
                                </div>
                            </div>
                            <div class="form-group col-md-10 subject-cont">
                                <label class="tx-13 mg-b-5 tx-gray-600">
                                    Subject
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-control" placeholder="#4444 | Ticket Name" type="text" name="subject" id="subject" required>
                            </div>
                        </div>

                        <div class="form-row time-pickers">
                            <div class="form-group col-md-6">
                                <label for="start-picker">Start Time</label>
                                <div id="start-picker"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="start-picker">End Time</label>
                                <div id="end-picker"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="tx-13 mg-b-5 tx-gray-600">
                                Category
                                <span class="text-danger">*</span>
                            </label>
                            <div class="row row-xs">
                                <div class="col-12">
                                    <select name="category" id="category" class="form-control" style="width: 100%" required>
                                        <option value=""></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div><!-- col-7 -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="tx-13 mg-b-5 tx-gray-600">
                                {{ ucfirst(trans('words.contract_customer')) }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="row row-xs">
                                <div class="col-12">
                                    <select name="organization" id="organization" class="form-control custom-select select2" style="width: 100%" required>
                                            <option value="">{{ ucfirst(trans('words.contract_customer')) }}</option>
                                    </select>
                                </div><!-- col-7 -->
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Message" type="text" name="message" id="message" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Answer" type="text" name="answer" id="answer" rows="3"></textarea>
                        </div>

                        {{-- hidden date inputs --}}
                        <input id="startdate" name="start" type="hidden">
                        <input id="enddate" name="end" type="hidden">
                        <input id="startdate1" name="start1" type="hidden" />
                        <input id="enddate1" name="end1" type="hidden">
                        {{-- hidden date inputs END --}}


                        <input type="hidden" name="dataid" id="dataid" value="">
                        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->id() }}">

                        <div class="d-flex mg-t-15 mg-lg-t-30 w-100">
                            <button class="btn btn-primary mr-4" type="submit">
                                Save
                            </button>
                            <a class="btn btn-light mr-4" data-dismiss="modal" href="">
                                Cancel
                            </a>
                            <button id="removeButton" type="button" onclick="deleteCalendar()" class="btn btn-danger mr-4">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="currentStartDate">
    <input type="hidden" id="currentEndDate">

    @if($search == null)
        <input type="hidden" id="is-from-ticket" value="0">
    @else
        <input type="hidden" id="is-from-ticket" value="1">
        @if($calendar != null)
            <input type="hidden" id="calendar-set-date" value="{{ $calendar->start }}">
        @endif
    @endif


@endsection
@section('js')
    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js?v=2') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
    <script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
    <script src="{{ URL::asset('assets/js/tui-calendar.js') }}"></script>
    <script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chance/1.0.13/chance.min.js"></script>
    <script src="{{ URL::asset('assets/js/calendars.js') }}"></script>
    <script src="{{ URL::asset('assets/js/schedules.js') }}"></script>
    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript" class="code-js">
        let timezoneCalc = new Date();
        timezoneCalc = timezoneCalc.getTimezoneOffset();
        timezoneCalc = timezoneCalc / 60 * -1;

        const TIMEZONE = timezoneCalc;

        (function() {
            var calendar;

            @foreach ($categories as $category)
                calendar = new CalendarInfo();
                calendar.id = String({{ $category->id }});
                calendar.name = '{{ $category->name }}';
                calendar.color = '#ffffff';
                calendar.bgColor = '{{ $category->color }}';
                calendar.dragBgColor = '{{ $category->color }}';
                calendar.borderColor = '{{ $category->color }}';
                addCalendar(calendar);
            @endforeach
        })();

        $(document).ready(function() {
            $('#calendar').css("min-height", "1000px");
            formatMinute();
        });

        @if($isAdmin && $search == null)
        showPersonnelCalendar(119); // User Calendar with user id 119
        @elseif($isAdmin && $search != null)
        showPersonnelCalendar({{ $user_id }}); // from Ticket -> Calendar Tasks
        @else // if auth user is freelancer, show only own calendar
        showPersonnelCalendar({{auth()->id()}});
        @endif

        function showPersonnelCalendar(id) {
            $('#user_id').val(id);

            var org = $("#filterOrganization option:selected").val();
            var status = $("#filterStatus option:selected").val();
            $.ajax({
                url: '/calendar/getdata/' + id + '/' + $('#currentStartDate').val() + '/' + $('#currentEndDate').val() + '/' + org + '/' + status + "/" + TIMEZONE,
                type: 'GET',
                data: '_token=' + $('meta[name="csrf-token"]').attr('content')
            }).done(function(data) {
                var dataImport = JSON.parse(data);
                setSchedules(dataImport);

            });

            $(".user-btns").removeClass("btn-primary");
            $("#user-btn-" + id).addClass("btn-primary");

            $('#calendarContent').show();
        }

        @if($user_id)
            $("#pills-calendar-tab").click();
            showPersonnelCalendar({{ $user_id }});
        @endif
    </script>

    {{-- TUI TimePicker for create Schedule --}}
    <script>
        function formatMinute() {
            setTimeout(function() {
                $(".tui-timepicker-minute option[value=0]").text("00");
            }, 1);
        }
        const TimePicker = tui.TimePicker;

        let startPicker = new TimePicker("#start-picker", {
            initialHour: 12,
            initialMinute: 0,
            minuteStep: 15,
            inputType: 'selectbox',
            showMeridiem: false
        })
        let endPicker = new TimePicker("#end-picker", {
            initialHour: 13,
            initialMinute: 0,
            minuteStep: 15,
            inputType: 'selectbox',
            showMeridiem: false
        })

        let beginTime = {
            hour: 6,
            minute: 0
        }
        let endTime = {
            hour: 22,
            minute: 30
        }

        startPicker.setRange(beginTime, endTime)
        endPicker.setRange(beginTime, endTime)

        startPicker.on("change", (e) => {
            formatMinute();
            let start = $('#startdate').val()
            let start1 = $('#startdate1').val()

            let newStart = convertTimePicker(start, e.hour, e.minute, TIMEZONE, 3);
            let newStart1 = convertTimePicker(start1, e.hour, e.minute, TIMEZONE, 1);

            $('#startdate').val(newStart)
            $('#startdate1').val(newStart1)

            // Changing endPicker range and values
            // 11:00 => 11:30   h: 0  ? h  , m: 0 ? 30
            // 11:30 => 12:00   h: 30 ? h+1, m: 30 ? 0
            let newStartRange = {
                hour: e.hour,
                minute: e.minute
            }

            let newEndRange = {
                hour: 23,
                minute: 0,
            }
            endPicker.resetMinuteRange()
            endPicker.setRange(newStartRange, newEndRange);
            endPicker.setTime(
                e.minute == 45 ? e.hour + 1 : e.hour,
                e.minute == 15 && 30,
                e.minute == 30 && 45,
                e.minute == 45 && 0,
            )
        });

        endPicker.on("change", (e) => {
            formatMinute();
            let end = $('#enddate').val()
            let end1 = $('#enddate1').val()

            let newEnd = convertTimePicker(end, e.hour, e.minute, TIMEZONE, 3);
            let newEnd1 = convertTimePicker(end1, e.hour, e.minute, TIMEZONE, 1);

            $('#enddate').val(newEnd)
            $('#enddate1').val(newEnd1)

        });

        function convertTimePicker(oldDate, hour, min, timezone, type) {

            if (type == 3) { // working on start and end
                hour = timezone == 3 ? hour : hour + 2;
            } else if (type == 1) { // working on start1 and end1
                hour = timezone == 3 ? hour - 2 : hour;
            } // we get correct times from this

            let newHour = hour > 9 ? "" + hour : "0" + hour; // hh (09, 15) format
            let newMin = "";
            if(min===15){
                newMin = "15";
            }else if(min===30){
                newMin = "30";
            }else if(min===45){
                newMin = "45";
            }else{
                newMin = "00";
            }
            let newTime = newHour + ":" + newMin;
            // We got the correct time format, now create the new Date

            oldDate = oldDate.split("T");
            let newDate = oldDate[0] + "T" + newTime;
            formatMinute();

            return newDate;
        }
        /*
            I need to set new Times when modal opens, I did that change on
            - calendarapp::421,
            - inside of saveNewSchedule
        */
        // END of TUI Timepicker
    </script>

    <script>
        // Ticket ID onChnage

        $("#ticket-id").on("input", function(e) {
            let id = e.target.value;
            if (id && id.length >= 4) {
                id = id.slice(0, 4)
                $("#ticket-id").val(id);

                $.ajax({
                    url: `/getTicket/${id}`,
                    type: 'GET',
                    data: '_token=' + $('meta[name="csrf-token"]').attr('content')
                }).done(function(data) {
                    $("#subject").val(data.name);
                    $("#category").val(data.category);
                    $("#organization").val(data.org_id);
                    if ($('#organization').find("option[value='" + data.org_id + "']").length) {
                        $('#organization').val(data.org_id).trigger('change');
                    } else {
                        var newOption = new Option(data.organizationName, data.org_id, true, true);
                        $('#organization').append(newOption).trigger('change');
                    }
                    $("#organization").val(data.org_id);
                    $("#organization").trigger("change");
                });
            }
        })

        // Disabling input areas if status absent is choosen
        $("#mainFormCalendar").change(function() {
            let isAbsent = $("#status").val() == 5;
            $("#organization, #customer, #category, #answer, #subject, #customer").prop("disabled", isAbsent);
        })

            // Customer Select
        $('#organization').select2({
            ajax: {
                url: '/getOrganizationsRawData',
                processResults: function(data, page) {

                    return {
                        results: data
                    };

                }
            },
            containerCssClass: function(e) {
                return $(e).attr('required') ? 'required' : '';
            }
        });

    </script>

    {{-- Script for Tui Calendar --}}
    @if($isAdmin)
        <script src="{{ URL::asset('assets/js/calendarapp.js?v') . rand() }}"></script>
        <script src="{{ URL::asset('assets/js/calendarSummary.js?v') . rand() }}"></script>
    @elseif(auth()->user()->role_id == 7 || auth()->user()->role_id == 4 || auth()->user()->role_id == 3)
        <script src="{{ URL::asset('assets/js/calendarapp.js?v') . rand() }}"></script>
    @else
        <script src="{{ URL::asset('assets/js/calendarappuser.js?v') . rand() }}"></script>
    @endif

    <script type="text/javascript" class="code-js">
        function openCalendarModal() {
            resetCalendarForm();
            $('#modalSetSchedule').modal('show');
        }

        function deleteCalendar() {
            var id = $('#dataid').val();
            var guid = $('#guid').val();
            var calendarId = $('#category').children("option:selected").val();

            confirmModal('Are you sure you want to delete?',"Are you sure?","Ok","Cancel").then(function() {
                $.ajax({
                    url: '/calendar/delete/' + id + '/',
                    type: 'GET',
                    data: '_token=' + $('meta[name="csrf-token"]').attr('content')
                }).done(function(data) {
                    $('#modalSetSchedule').modal('hide');
                    cal.deleteSchedule(guid, calendarId);
                });
            })

        }

        function setSchedules(ScheduleList) {
            cal.clear();
            //generateSchedule(cal.getViewName(), cal.getDateRangeStart(), cal.getDateRangeEnd());

            cal.createSchedules(ScheduleList);
            refreshScheduleVisibility();
        }

        function refreshScheduleVisibility() {
            var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));

            CalendarList.forEach(function(calendar) {
                cal.toggleSchedules(calendar.id, !calendar.checked, false);
            });

            cal.render(true);

            calendarElements.forEach(function(input) {
                var span = input.nextElementSibling;
                span.style.backgroundColor = input.checked ? span.style.borderColor : 'transparent';
            });
        }

        $('#mainFormCalendar').submit(function(event) {

            if (!this.checkValidity()) {
                event.preventDefault();
            } else {

                event.preventDefault();

                $.ajax({
                    url: $('#mainFormCalendar').attr('action'),
                    type: 'POST',
                    data: $('#mainFormCalendar').serialize()
                }).done(function(data) {

                    var org = $('#filterOrganization option:selected').val();
                    var status = $("#filterStatus option:selected").val();
                    $.ajax({
                        url: '/calendar/getdata/' + $('#user_id').val() + '/' + $(
                                '#currentStartDate').val() +
                            '/' + $('#currentEndDate').val() + '/' + org + '/' + status + "/" +
                            TIMEZONE,
                        type: 'GET',
                        data: '_token=' + $('meta[name="csrf-token"]').attr('content')
                    }).done(function(data) {
                        var dataImport = JSON.parse(data);
                        setSchedules(dataImport);

                        $('#modalSetSchedule').modal('hide');
                    });
                });

            }
        });

        function copyCalendar(side) {

            $.ajax({
                url: '/calendar/copy/' + $('#dataid').val() + '/' + side,
                type: 'GET'
            }).done(function(data) { // Returns status success

                var org = $('#filterOrganization option:selected').val();
                var status = $("#filterStatus option:selected").val();
                $.ajax({
                    url: '/calendar/getdata/' + $('#user_id').val() + '/' + $('#currentStartDate').val() +
                        '/' + $(
                            '#currentEndDate').val() + '/' + org + '/' + status + "/" + TIMEZONE,
                    type: 'GET',
                    data: '_token=' + $('meta[name="csrf-token"]').attr('content')
                }).done(function(data) {

                    var dataImport = JSON.parse(data);
                    setSchedules(dataImport);

                    $('#modalSetSchedule').modal('hide');
                });
            });

        }
    </script>

    {{-- Tippy --}}
    <script>
        tippy.delegate("#calendar", {
            target: ".tui-full-calendar-time-date-schedule-block",
            content(reference) {
                let cont = reference.cloneNode(true);
                let eventDom = cont.getElementsByClassName("tui-full-calendar-time-schedule-content")[0];
                if (eventDom) {
                    eventDom.style.height = "250px"
                    eventDom.style.width = "200px"
                    eventDom.style.padding = "10px"
                }
                return cont.innerHTML;

            },
            allowHTML: true,
            theme: "translucent",
            multiple: true,
            placement: "auto-end",
            trigger: "mouseenter",
            onShow(instance) {
                let dragDiv = $(".tui-full-calendar-time-date-schedule-block-dragging-dim");
                if (dragDiv.length > 0) { // if dragDiv exists
                    return false;
                }
                tippy.hideAll({
                    duration: 0
                })
            },
        })
        // tui-full-calendar-time-date-schedule-block-dragging-dim


        // Open the requested calendar task's modal
        @if($isAdmin && $search != null && $calendar != null)
        openModalCalendar({{ $search }});
        @endif

    </script>
@endsection
