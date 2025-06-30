<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
<script src="{{ URL::asset('assets/js/tui-calendar.js') }}"></script>
<script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chance/1.0.13/chance.min.js"></script>
<script src="{{ URL::asset('assets/js/calendars.js') }}"></script>
<script src="{{ URL::asset('assets/js/schedules.js') }}"></script>
{{-- TUI TimePicker for create Schedule --}}
<script>
    const TimePicker = tui.TimePicker;

    let startPicker = new TimePicker("#start-picker", {
        initialHour: 12,
        initialMinute: 0,
        minuteStep: 15,
        inputType: 'selectbox',
        showMeridiem: false
    });
    let endPicker = new TimePicker("#end-picker", {
        initialHour: 13,
        initialMinute: 0,
        minuteStep: 15,
        inputType: 'selectbox',
        showMeridiem: false
    });

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
    })

    endPicker.on("change", (e) => {
        let end = $('#enddate').val()
        let end1 = $('#enddate1').val()

        let newEnd = convertTimePicker(end, e.hour, e.minute, TIMEZONE, 3);
        let newEnd1 = convertTimePicker(end1, e.hour, e.minute, TIMEZONE, 1);

        $('#enddate').val(newEnd)
        $('#enddate1').val(newEnd1)

    })

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

        return newDate;
    }
    /*
        I need to set new Times when modal opens, I did that change on
        - calendarapp::421,
        - inside of saveNewSchedule
    */
    // END of TUI Timepicker
</script>
<script type="text/javascript">
    let timezoneCalc = new Date();
    timezoneCalc = timezoneCalc.getTimezoneOffset();
    timezoneCalc = timezoneCalc / 60 * -1;

    const TIMEZONE = timezoneCalc;

    $(document).ready( function() {
        $('#calendar').css("min-height", "1000px");
    });

    // ticket to calendar
    function ticketToCalendar(){
        $('.ticketUsers').val('').trigger('change');

        @if(\Illuminate\Support\Facades\Auth::user()->role_id!=1)
            $('.ticketmodalusers').hide();
            $('#calendar_user_id').val('{{\Illuminate\Support\Facades\Auth::user()->id}}');

            $('.selectUserShow').removeClass('d-none');
            showPersonnelCalendar();
        @else
            $('#ticketCalendarModal').modal('show');
        @endif
    }

    function showPersonnelCalendar() {
        var startDate = moment(cal.getDateRangeStart().getTime()).format('YYYY-MM-DD') + 'T00:00';
        var endDate = moment(cal.getDateRangeEnd().getTime()).format('YYYY-MM-DD') + 'T23:59';

        $('#currentStartDate').val(startDate);
        $('#currentEndDate').val(endDate);
        var id = $('#calendar_user_id').val();

        $.ajax({
            url: '/calendar/getdata/' + id + '/' + $('#currentStartDate').val() + '/' + $(
                '#currentEndDate').val() + '/0/0/' + TIMEZONE,
            type: 'GET',
            data: '_token=' + $('meta[name="csrf-token"]').attr('content')
        }).done(function(data) {
            var dataImport = JSON.parse(data);
            setSchedules(dataImport);

        });

        $('#ticketCalendarModal').modal('hide');
        $('#ticketCalendarContentModal').modal('show');

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

    $(document).ready(function (){
        $('.ticketUsers').select2({
            ajax: {
                url: '/getPersonnelRawData',
                dropdownParent: $('#ticketCalendarModal'),
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('.ticketUsers').on('change', function () {
            var userId = this.value;

            if(userId>0){
                $('.selectUserShow').removeClass('d-none');
                $('#calendar_user_id').val(userId);
                showPersonnelCalendar();
            } else {
                $('.selectUserShow').addClass('d-none');
            }

        });
    });

    function addTicketToCalendar(){
        toggleLoader(true);
        var form = $('#mainFormCalendar').serialize();
        $.ajax({
            url: "/tickettocalendar",
            type: "post",
            data: form,
            dataType: "json",
            success: function (response) {
                toggleLoader(false);
                $('.selectUserShow').addClass('d-none');
                $('#ticketCalendarModal').modal('hide');

                $('#message').val('');
                $('#answer').val('');
                $("#status option").attr("selected", false);
                $("#user option").attr("selected", false);
                $('#startdate').val('');
                $('#enddate').val('');
                toastr.success('The new job has been successfully created.', 'Success!');
                setTimeout( function() {
                    window.location.reload();
                }, 1000);
            },
            error: function (response){
                toggleLoader(false);
                toastr.error('An error thrown!', 'Error!');
            }
        });

    }

</script>
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
        zIndex: 10001,
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

</script>
<script src="{{ URL::asset('assets/js/calendarappticket.js?v') . rand() }}"></script>
