var SummaryCalendar = tui.Calendar;

let userIDs = JSON.parse($("#user_ids").val())

let calendarsConfig = [];
function getCalendarSummary(id) {

    let summaryCalendars = new SummaryCalendar("#summary-calendar-" + id, {
        defaultView: 'week',
        taskView: false, // e.g. true, false, or ['task', 'milestone']
        scheduleView: ['time'], // e.g. true, false, or ['allday', 'time']
        useCreationPopup: false,
        useDetailPopup: false,
        toggleScheduleView: false,
        calendars: calendarsConfig,
        week: {
            hourStart: 5,
            hourEnd: 23,
            startDayOfWeek: 1
        },
        month: {
            startDayOfWeek: 1
        },
        disableClick: true,
        isReadOnly: true,
        theme: {
            "week.timegridOneHour.height": "18px",
            "week.timegridHalfHour.height": "0px",
            'week.timegridLeft.width': '0px',
            'week.dayname.height': '0px',
            'week.dayname.borderTop': 'none',
            'week.dayname.borderBottom': '1px solid #ddd',
            'week.dayname.borderLeft': 'none',
            'week.today.color': '#fff',
            'week.timegridLeft.fontSize': '0px',
            'week.timegridLeftTimezoneLabel.height': '0px',
            'week.today.backgroundColor': 'rgba(0, 98, 255, 0.1)',
            'week.timegridLeft.borderRight': 'none',
            'week.timegrid.paddingRight': '10px',
            'week.timegrid.borderRight': '1px solid #ddd',
            'week.timegridSchedule.borderRadius': '0',
            'week.timegridSchedule.paddingLeft': '0',
            'week.timegridSchedule.borderRadius': '2px',
            'week.timegridSchedule.paddingLeft': '2px',
            'week.timegridSchedule.marginRight': '0px',
        },
        template: {
            timegridDisplayPrimayTime: function (time) {
                return time.hour + ':00';
            },
            timegridDisplayTime: function (time) {
                return time.hour + ':00';
            }
        },
    });

    var org = $("#filterOrganization option:selected").val();
    var status = $("#filterStatus option:selected").val();
    $.ajax({
        url: '/calendar/getdata/' + id + '/' + $('#currentStartDate').val() + '/' + $('#currentEndDate').val() + '/' + org + '/' + status + "/" + TIMEZONE,
        type: 'GET',
        data: '_token=' + $('meta[name="csrf-token"]').attr('content')
    }).done(function (data) {
        let dataImport = JSON.parse(data);
        summaryCalendars.createSchedules(dataImport)
    });
}

userIDs.forEach(id => {
    getCalendarSummary(id);
})

