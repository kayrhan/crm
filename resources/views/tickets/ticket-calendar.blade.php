{{-- Add Calendar Modal --}}
<div aria-hidden="true" class="modal main-modal-calendar-schedule" id="ticketCalendarModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create New Job</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="mainFormCalendar" method="post" name="mainFormCalendar">
                    @csrf

                    <input type="hidden" name="guid" id="guid">

                    <div class="form-group ticketmodalusers">
                        <label class="tx-13 mg-b-5 tx-gray-600">
                            User
                            <span class="text-danger">*</span>
                        </label>
                        <div class="row row-xs">
                            <div class="col-12">
                                <select name="user" class="form-control ticketUsers" style="width: 100%">
                                    <option value="">Wahlen</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="selectUserShow d-none">

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
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" placeholder="Message" type="text" name="message" id="message" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Answer" type="text" name="answer" id="answer" rows="3"></textarea>
                        </div>

                        <div class="d-flex mg-t-15 mg-lg-t-30 w-100">
                            <button class="btn btn-primary mr-4" type="button" onclick="addTicketToCalendar()">
                                Save
                            </button>
                            <button class="btn btn-warning mr-4" type="button" onclick="showPersonnelCalendar()">
                                Open Calendar
                            </button>
                            <a class="btn btn-light mr-4" data-dismiss="modal" href="">
                                Cancel
                            </a>
                        </div>

                    </div>

                    <input type="hidden" name="calendar_user_id" id="calendar_user_id">

                    <input id="startdate" name="start" type="hidden">
                    <input id="enddate" name="end" type="hidden">
                    <input id="startdate1" name="start1" type="hidden"/>
                    <input id="enddate1" name="end1" type="hidden">

                    <input type="hidden" id="currentStartDate">
                    <input type="hidden" id="currentEndDate">
                    <input type="hidden" name="ticket_to_calender_id" id="ticket_to_calender_id" value="{{$ticket->id}}">

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Calendar Times Modal --}}
<div aria-hidden="true" class="modal main-modal-calendar-schedule" id="ticketCalendarContentModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="width: 80% !important;max-width: 80% !important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Select Date</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body calendarContent d-block" id="calendarContent" >
                    {{-- Calendar right part --}}
                    <div id="right" style="width: 100% !important;">
                        <div id="menu">
                            <span class="dropdown">
                                <button style="display: none" id="dropdownMenu-calendarType"
                                        class="btn btn-default btn-sm " type="button" data-toggle="dropdown"
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
