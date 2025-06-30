'use strict';

/* eslint-disable */
/* eslint-env jquery */
/* global moment, tui, chance */
/* global findCalendar, CalendarList, ScheduleList, generateSchedule */

// create
function resetCalendarForm() {
    $('#modalSetSchedule .modal-title').text('Create New Job');
    $('#subject').val('');
    $('#category option[value=""]').prop('selected', true);
    $('#message').val('');
    $('#answer').val('');
    $('#guid').val('');
    $("#status").val("1");
    $('#startdate').val('');
    $('#enddate').val('');
    $('#startdate1').val('');
    $('#enddate1').val('');

    $('.copyPanel').hide();
    $("#ticket-link").hide();
    $("#ticket-id").css("display", "block");
    $(".ticket-id-cont").css("display", "block")
    $(".subject-cont").removeClass("col-md-12")
    $('.time-pickers').css('display', 'flex');
    $("#ticket-id").val(null);

    $("#organization").empty();
    $('#mainFormCalendar').attr('action', '/calendar');
    $("#organization, #customer, #category, #answer, #subject, #customer").prop("disabled", false);

    $('#removeButton').hide();
    $("#message").removeClass("custom-readonly");
    $("#organization, .select2-selection--single").removeClass("custom-readonly");
    $("#status option[value='1'], #status option[value='2'], #status option[value='5']").attr("disabled",false);
    $("#status option[value='']").remove()
    $("#status").removeClass("custom-readonly");
}

// Update
function openModalCalendar(id) {
    resetCalendarForm();
    $('#dataid').val(id);
    $.ajax({
        url: '/calendar/get/' + id + '/',
        type: 'GET',
        data: '_token=' + $('meta[name="csrf-token"]').attr('content')
    }).done(function (data) {
        let show = data.show;
        data = data.data;

        $('#subject').val(data.subject);
        $('#category option[value="' + data.category + '"]').prop('selected', true);
        $('#message').val(data.message);
        $('#answer').val(data.answer);
        $('#startdate').val(data.start);
        $('#enddate').val(data.end);
        $('#startdate1').val(data.start1);
        $('#enddate1').val(data.end1);
        $('#guid').val(data.guid);
        $('#status option[value="' + data.status + '"]').prop('selected', true);
        $('#user_id').val(data.user_id);
        if(show === 0){
            if(data.status == 5){
                $("#status option[value='1'], #status option[value='2']").attr("disabled",true);
                $("#status").addClass("custom-readonly");
            }else{
                $("#message").addClass("custom-readonly");
                $("#organization, .select2-selection--single").addClass("custom-readonly");
                $("#status option[value='1'], #status option[value='2'], #status option[value='5']").attr("disabled",true);
                if(data.status != 3 && data.status != 4){
                    $("#status").prepend("<option value='' selected disabled></option>")
                }
            }

        }

        let option = new Option(data.org_name ?? "", data.organization_id ?? "");
        option.selected = true;
        $('#removeButton').show();

        $("#organization").append(option);
        $("#organization").trigger("change");
        if (data.ticket_id && data.ticket_id > 0) {
            $("#ticket-link").css("display", "block");
            $('#ticket-link').html("#" + data.ticket_id);
            $('#ticket-link').prop("href", $("#link-href").prop("href") + "/" + data.ticket_id);
            $("#ticket-id").val(data.ticket_id);
            $(".subject-cont").removeClass("col-md-12");
        }else{
            $(".ticket-id-cont").hide()
            $("#ticket-link").hide();
            $(".subject-cont").addClass("col-md-12");

        }

        $('.copyPanel').css('display', 'flex');
        $('.time-pickers').hide();
        $("#ticket-id").hide();

        $('#mainFormCalendar').attr('action', '/calendar/update');
        $('#modalSetSchedule .modal-title').text('Update Job');
        $('#modalSetSchedule').modal('show');
    });
}

(function (window, Calendar) {

    var cal, resizeThrottled;
    var useCreationPopup = false;
    var useDetailPopup = false;
    var datePicker, selectedCalendar;

    cal = new Calendar('#calendar', {
        defaultView: 'week',
        taskView: false,  // e.g. true, false, or ['task', 'milestone']
        scheduleView: ['time'], // e.g. true, false, or ['allday', 'time']
        useCreationPopup: useCreationPopup,
        useDetailPopup: useDetailPopup,
        calendars: CalendarList,
        toggleScheduleView: true,
        week: {
            hourStart: 5,
            hourEnd: 23,
            startDayOfWeek: 1
        },
        month: { startDayOfWeek: 1 },
        template: {
            milestone: function (model) {
                return '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' + model.bgColor + '">' + model.title + '</span>';
            },
            allday: function (schedule) {
                return getTimeTemplate(schedule, true);
            },
            time: function (schedule) {
                return getTimeTemplate(schedule, false);
            },
            timegridDisplayPrimayTime: function (time) {
                return time.hour + ':00';
            },
            timegridDisplayTime: function (time) {
                return time.hour + ':00';
            }
        },
        // timezone: {
        //     zones: [
        //         {
        //             timezoneName: 'Turkey',
        //             displayLabel: 'Turkey',
        //             tooltip: 'Turkey'
        //         },
        //         {
        //             timezoneName: 'Europe/Berlin',
        //             displayLabel: 'Germany',
        //             tooltip: 'Germany',
        //         }
        //     ],
        // }
    });

    // event handlers
    cal.on({
        'clickMore': function (e) {
            // console.log('clickMore', e);
        },
        'clickSchedule': function (e) {
            var dataid = parseInt(e.schedule.body);
            openModalCalendar(dataid);
        },
        'clickDayname': function (date) {
            // console.log('clickDayname', date);
        },
        'beforeCreateSchedule': function (e) {
            // console.log('beforeCreateSchedule', e);
            saveNewSchedule(e);
        },
        'beforeUpdateSchedule': function (e) {
            var schedule = e.schedule;
            var changes = e.changes;
            // console.log(changes);

            // console.log('beforeUpdateSchedule', e);

            if (changes && !changes.isAllDay && schedule.category === 'allday') {
                changes.category = 'time';
            }

            var id = parseInt(e.schedule.body);

            var startDate = new Date(e.schedule.start._date).addHours(3);
            var endDate = new Date(e.schedule.end._date).addHours(3);
            var startDate1 = new Date(e.schedule.start._date).addHours(1);
            var endDate1 = new Date(e.schedule.end._date).addHours(1);


            if (schedule.category === 'allday') {
                startDate = endDate.toISOString().split('T')[0] + 'T00:00';
                endDate = endDate.toISOString().split('T')[0] + 'T23:59';
                startDate1 = endDate1.toISOString().split('T')[0] + 'T00:00';
                endDate1 = endDate1.toISOString().split('T')[0] + 'T23:59';
            } else {
                startDate = startDate.toISOString().split(':00.000Z')[0];
                endDate = endDate.toISOString().split(':00.000Z')[0];
                startDate1 = startDate1.toISOString().split(':00.000Z')[0];
                endDate1 = endDate1.toISOString().split(':00.000Z')[0];
            }

            if (changes.end) {
                endDate = new Date(changes.end._date).addHours(3);
                endDate = endDate.toISOString().split(':00.000Z')[0];
                endDate1 = new Date(changes.end._date).addHours(1);
                endDate1 = endDate1.toISOString().split(':00.000Z')[0];
            }

            if (changes.start) {
                startDate = new Date(changes.start._date).addHours(3);
                startDate = startDate.toISOString().split(':00.000Z')[0];
                startDate1 = new Date(changes.start._date).addHours(1);
                startDate1 = startDate1.toISOString().split(':00.000Z')[0];
            }

            $.ajax({
                url: '/calendar/updatedate',
                type: 'POST',
                data: 'id=' + id + '&start=' + startDate + '&end=' + endDate + '&start1=' + startDate1 + '&end1=' + endDate1 + '&_token=' + $('meta[name="csrf-token"]').attr('content')
            });
            cal.updateSchedule(schedule.id, schedule.calendarId, changes);
            refreshScheduleVisibility();
        },
        'beforeDeleteSchedule': function (e) {
            // console.log('beforeDeleteSchedule', e);
            cal.deleteSchedule(e.schedule.id, e.schedule.calendarId);
        },
        'afterRenderSchedule': function (e) {
            var schedule = e.schedule;
            // var element = cal.getElement(schedule.id, schedule.calendarId);
            // console.log('afterRenderSchedule', element);
        },
        'clickTimezonesCollapseBtn': function (timezonesCollapsed) {
            // console.log('timezonesCollapsed', timezonesCollapsed);

            if (timezonesCollapsed) {
                cal.setTheme({
                    'week.daygridLeft.width': '77px',
                    'week.timegridLeft.width': '77px'
                });
            } else {
                cal.setTheme({
                    'week.daygridLeft.width': '60px',
                    'week.timegridLeft.width': '60px'
                });
            }

            return true;
        }
    });

    /**
     * Get time template for time and all-day
     * @param {Schedule} schedule - schedule
     * @param {boolean} isAllDay - isAllDay or hasMultiDates
     * @returns {string}
     */

    function getTimeTemplate(schedule, isAllDay) {
        var html = [];
        var start = moment(schedule.start.toUTCString());
        var end = moment(schedule.end.toUTCString());
        if (!isAllDay) {
            html.push('<strong>' + start.format('HH:mm') + ' - ' + end.format('HH:mm') + '</strong> ');
        }
        if (schedule.isPrivate) {
            html.push('<span class="calendar-font-icon ic-lock-b"></span>');
            html.push(' Private');
        } else {
            if (schedule.isReadOnly) {
                html.push('<span class="calendar-font-icon ic-readonly-b"></span>');
            } else if (schedule.recurrenceRule) {
                html.push('<span class="calendar-font-icon ic-repeat-b"></span>');
            } else if (schedule.attendees.length) {
                html.push('<span class="calendar-font-icon ic-user-b"></span>');
            } else if (schedule.location) {
                html.push('<span class="calendar-font-icon ic-location-b"></span>');
            }
            html.push(' ' + schedule.title);
        }

        return html.join('');
    }

    /**
     * A listener for click the menu
     * @param {Event} e - click event
     */

    function onClickMenu(e) {
        var target = $(e.target).closest('a[role="menuitem"]')[0];
        var action = getDataAction(target);
        var options = cal.getOptions();
        var viewName = '';

        // console.log(target);
        // console.log(action);
        switch (action) {
            case 'toggle-daily':
                viewName = 'day';
                break;
            case 'toggle-weekly':
                viewName = 'week';
                break;
            case 'toggle-monthly':
                options.month.visibleWeeksCount = 0;
                viewName = 'month';
                break;
            case 'toggle-weeks2':
                options.month.visibleWeeksCount = 2;
                viewName = 'month';
                break;
            case 'toggle-weeks3':
                options.month.visibleWeeksCount = 3;
                viewName = 'month';
                break;
            case 'toggle-narrow-weekend':
                options.month.narrowWeekend = !options.month.narrowWeekend;
                options.week.narrowWeekend = !options.week.narrowWeekend;
                viewName = cal.getViewName();

                target.querySelector('input').checked = options.month.narrowWeekend;
                break;
            case 'toggle-start-day-1':
                options.month.startDayOfWeek = options.month.startDayOfWeek ? 0 : 1;
                options.week.startDayOfWeek = options.week.startDayOfWeek ? 0 : 1;
                viewName = cal.getViewName();

                target.querySelector('input').checked = options.month.startDayOfWeek;
                break;
            case 'toggle-workweek':
                options.month.workweek = !options.month.workweek;
                options.week.workweek = !options.week.workweek;
                viewName = cal.getViewName();

                target.querySelector('input').checked = !options.month.workweek;
                break;
            default:
                break;
        }

        cal.setOptions(options, true);
        cal.changeView(viewName, true);

        setDropdownCalendarType();
        setRenderRangeText();
        setSchedules();
    }

    function onClickNavi(e) {
        var action = getDataAction(e.target);

        switch (action) {
            case 'move-prev':
                cal.prev();
                break;
            case 'move-next':
                cal.next();
                break;
            case 'move-today':
                cal.today();
                break;
            default:
                return;
        }

        setRenderRangeText();
        setSchedules();
    }

    function onNewSchedule() {
        var title = $('#new-schedule-title').val();
        var location = $('#new-schedule-location').val();
        var isAllDay = document.getElementById('new-schedule-allday').checked;
        var start = datePicker.getStartDate();
        var end = datePicker.getEndDate();
        var calendar = selectedCalendar ? selectedCalendar : CalendarList[0];

        if (!title) {
            return;
        }

        cal.createSchedules([{
            id: String(chance.guid()),
            calendarId: calendar.id,
            title: title,
            isAllDay: isAllDay,
            start: start,
            end: end,
            category: isAllDay ? 'allday' : 'time',
            dueDateClass: '',
            color: calendar.color,
            bgColor: calendar.bgColor,
            dragBgColor: calendar.bgColor,
            borderColor: calendar.borderColor,
            raw: {
                location: location
            },
            state: 'Busy'
        }]);


        $('#modal-new-schedule').modal('hide');
    }

    function onChangeNewScheduleCalendar(e) {
        var target = $(e.target).closest('a[role="menuitem"]')[0];
        var calendarId = getDataAction(target);
        changeNewScheduleCalendar(calendarId);
    }

    function changeNewScheduleCalendar(calendarId) {
        var calendarNameElement = document.getElementById('calendarName');
        var calendar = findCalendar(calendarId);
        var html = [];

        html.push('<span class="calendar-bar" style="background-color: ' + calendar.bgColor + '; border-color:' + calendar.borderColor + ';"></span>');
        html.push('<span class="calendar-name">' + calendar.name + '</span>');

        calendarNameElement.innerHTML = html.join('');

        selectedCalendar = calendar;
    }

    function createNewSchedule(event) {
        var start = event.start ? new Date(event.start.getTime()) : new Date();
        var end = event.end ? new Date(event.end.getTime()) : moment().add(1, 'hours').toDate();

        if (useCreationPopup) {
            cal.openCreationPopup({
                start: start,
                end: end
            });
        }
    }

    function pad2(n) { return n < 10 ? '0' + n : n }

    Date.prototype.addHours = function (h) {
        this.setHours(this.getHours() + h);
        return this;
    }

    function saveNewSchedule(scheduleData) {
        resetCalendarForm();
        var guid = chance.guid();

        let startDate = new Date(scheduleData.start._date).addHours(3);
        let endDate = new Date(scheduleData.end._date).addHours(3);
        let startDate1 = new Date(scheduleData.start._date).addHours(1);
        let endDate1 = new Date(scheduleData.end._date).addHours(1);

        $("#guid").val(guid);
        if (scheduleData.isAllDay === true) {
            $('#startdate').val(startDate.toISOString().split('T23:59:59.000Z')[0] + 'T00:00');
            $('#enddate').val(endDate.toISOString().split('T23:59:59.000Z')[0] + 'T23:59');
            $('#startdate1').val(startDate1.toISOString().split('T23:59:59.000Z')[0] + 'T00:00');
            $('#enddate1').val(endDate1.toISOString().split('T23:59:59.000Z')[0] + 'T23:59');
        } else {
            $('#startdate').val(startDate.toISOString().split(':00.000Z')[0]);
            $('#enddate').val(endDate.toISOString().split(':00.000Z')[0]);
            $('#startdate1').val(startDate1.toISOString().split(':00.000Z')[0]);
            $('#enddate1').val(endDate1.toISOString().split(':00.000Z')[0]);
        }

        // Setting TUI TimePicker initial according to selected time

        let startDateInitial = new Date(scheduleData.start._date);
        let endDateInitial = new Date(scheduleData.end._date);

        let newStartInitial = {
            hour: TIMEZONE == 1 ? startDateInitial.getHours() : startDateInitial.getHours(),
            minute: startDateInitial.getMinutes(),
        }
        let newEndInitial = {
            hour: TIMEZONE == 1 ? endDateInitial.getHours() : endDateInitial.getHours(),
            minute: endDateInitial.getMinutes(),
        }

        startPicker.setTime(newStartInitial.hour, newStartInitial.minute);
        endPicker.setTime(newEndInitial.hour, newEndInitial.minute);

        $('#modalSetSchedule').modal('show');
    }

    function onChangeCalendars(e) {
        var calendarId = e.target.value;
        var checked = e.target.checked;
        var viewAll = document.querySelector('.lnb-calendars-item input');
        var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));
        var allCheckedCalendars = true;

        if (calendarId === 'all') {
            allCheckedCalendars = checked;

            calendarElements.forEach(function (input) {
                var span = input.parentNode;
                input.checked = checked;
                span.style.backgroundColor = checked ? span.style.borderColor : 'transparent';
            });

            CalendarList.forEach(function (calendar) {
                calendar.checked = checked;
            });
        } else {
            findCalendar(calendarId).checked = checked;

            allCheckedCalendars = calendarElements.every(function (input) {
                return input.checked;
            });

            if (allCheckedCalendars) {
                viewAll.checked = true;
            } else {
                viewAll.checked = false;
            }
        }

        refreshScheduleVisibility();
    }

    function setDropdownCalendarType() {
        var calendarTypeName = document.getElementById('calendarTypeName');
        var calendarTypeIcon = document.getElementById('calendarTypeIcon');
        var options = cal.getOptions();
        var type = cal.getViewName();
        var iconClassName;

        if (type === 'day') {
            type = 'Daily';
            iconClassName = 'calendar-icon ic_view_day';
        } else if (type === 'week') {
            type = 'Weekly';
            iconClassName = 'calendar-icon ic_view_week';
        } else if (options.month.visibleWeeksCount === 2) {
            type = '2 weeks';
            iconClassName = 'calendar-icon ic_view_week';
        } else if (options.month.visibleWeeksCount === 3) {
            type = '3 weeks';
            iconClassName = 'calendar-icon ic_view_week';
        } else {
            type = 'Monthly';
            iconClassName = 'calendar-icon ic_view_month';
        }

        calendarTypeName.innerHTML = type;
        calendarTypeIcon.className = iconClassName;
    }

    function currentCalendarDate(format) {
        var currentDate = moment([cal.getDate().getFullYear(), cal.getDate().getMonth(), cal.getDate().getDate()]);

        return currentDate.format(format);
    }

    function setRenderRangeText() {

        let isFromTicket;
        $('#is-from-ticket').val() == 0 ? isFromTicket = false : isFromTicket = true;

        var renderRange = document.getElementById('renderRange');
        var options = cal.getOptions();
        var viewName = cal.getViewName();

        var html = [];
        if (viewName === 'day') {
            html.push(currentCalendarDate('DD.MM.YYYY'));
        } else if (viewName === 'month' &&
            (!options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
            html.push(currentCalendarDate('YYYY.MM'));
        } else {
            if(!isFromTicket) {
                html.push(moment(cal.getDateRangeStart().getTime()).format('DD.MM.YYYY'));
                html.push(' ~ ');
                html.push(moment(cal.getDateRangeEnd().getTime()).format(' DD.MM.YYYY'));
            }
            else {
                // Set calendar's date to the requested date
                let setDate = moment($('#calendar-set-date').val()).format('YYYY-MM-DD');
                cal.setDate(setDate);

                // Get the start and end dates of the requested date's week (only for first page load)
                var requestedDate = new Date($('#calendar-set-date').val());
                var dayOfWeek = requestedDate.getDay();
                var firstDay, lastDay;
                if(dayOfWeek == 0) {
                    firstDay = new Date(requestedDate);
                    firstDay.setDate(requestedDate.getDate() - dayOfWeek - 6);

                    lastDay = new Date(requestedDate);
                    lastDay.setDate(requestedDate.getDate() + dayOfWeek);
                }
                else {
                    firstDay = new Date(requestedDate);
                    firstDay.setDate(requestedDate.getDate() - dayOfWeek + 1);

                    lastDay = new Date(requestedDate);
                    lastDay.setDate(requestedDate.getDate() + (6 - dayOfWeek) + 1);
                }
                var tempStartDate = formatRangeDates(firstDay);
                var tempEndDate = formatRangeDates(lastDay);

                // Print the temporary range dates
                html.push(tempStartDate);
                html.push(' ~ ');
                html.push(tempEndDate);

                // To make it work once only
                $('#is-from-ticket').val(0);
            }
        }

        var startDate = moment(cal.getDateRangeStart().getTime()).format('YYYY-MM-DD') + 'T00:00';
        var endDate = moment(cal.getDateRangeEnd().getTime()).format('YYYY-MM-DD') + 'T23:59';

        $('#currentStartDate').val(startDate);
        $('#currentEndDate').val(endDate);

        var org = $('#filterOrganization option:selected').val();
        var status = $("#filterStatus option:selected").val();

        $.ajax({
            url: '/calendar/getdata/' + $('#user_id').val() + '/' + $('#currentStartDate').val() + '/' + $('#currentEndDate').val() + '/' + org + '/' + status + "/" + TIMEZONE,
            type: 'GET',
            data: '_token=' + $('meta[name="csrf-token"]').attr('content')
        }).done(function (data) {
            var dataImport = JSON.parse(data);
            setSchedules(dataImport);
        });
        renderRange.innerHTML = html.join('');
    }

    function formatRangeDates(date) {
        var day = ("0" + date.getDate()).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var year = date.getFullYear();
        return day + "." + month + "." + year;
    }

    function setEventListener() {
        $('#menu-navi').on('click', onClickNavi);
        $('.dropdown-menu a[role="menuitem"]').on('click', onClickMenu);
        $('#lnb-calendars').on('change', onChangeCalendars);

        $('#btn-save-schedule').on('click', onNewSchedule);
        $('#btn-new-schedule').on('click', createNewSchedule);

        $('#dropdownMenu-calendars-list').on('click', onChangeNewScheduleCalendar);

        window.addEventListener('resize', resizeThrottled);
    }

    function getDataAction(target) {
        return target.dataset ? target.dataset.action : target.getAttribute('data-action');
    }

    resizeThrottled = tui.util.throttle(function () {
        cal.render();
    }, 50);

    window.cal = cal;
    setDropdownCalendarType();
    setRenderRangeText();
    setEventListener();
})(window, tui.Calendar);

// set calendars
(function () {
    var calendarList = document.getElementById('calendarList');
    var html = [];
    CalendarList.forEach(function (calendar) {
        html.push('<div class="lnb-calendars-item"><label>' +
            '<input type="checkbox" class="tui-full-calendar-checkbox-round" value="' + calendar.id + '" checked>' +
            '<span style="border-color: ' + calendar.borderColor + '; background-color: ' + calendar.borderColor + ';"></span>' +
            '<span>' + calendar.name + '</span>' +
            '</label></div>'
        );
    });
    calendarList.innerHTML = html.join('\n');
})();
