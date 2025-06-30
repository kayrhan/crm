<style>
    .summary-calendars .tui-full-calendar-time-schedule {
        color: rgba(0, 0, 0, 0) !important;
    }

    .summary-calendars {
        height: 400px;
        border: 1px solid #8e8e8e !important;
        overflow: hidden;
    }

    #summarySort .summary-calendar-cont {

        cursor: pointer;
    }

    .blue-background-class {
        background: lightblue;
    }

</style>
{{-- Tippy Theme css --}}
<link rel="stylesheet" href="{{asset('assets/plugins/tippy/translucent.css')}}">

{{-- Summary Calendars Container --}}
<div class="row" id="calendars-cont">
    <h4 class="text-warning pl-3">
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
        You can change the order by drag and drop
    </h4>
    <input type="hidden" name="user_ids" id="user_ids" value="{{ $summaryUsers->pluck('user_id') }}">

    <ul class="row" id="summarySort">
        @foreach ($summaryUsers as $summaryUser)
            <li data-id="{{ $summaryUser->id }}" class="col-lg-3 col-md-4 col-sm-6 col-xs-12 summary-calendar-cont">
                <div class="summary-calendars border border-dark rounded p-4 m-1"
                    id="summary-calendar-{{ $summaryUser->user_id }}">
                    <div class="row">
                        <div class="col-3 d-flex aling-items-center">
                            <i class="fa fa-arrows" aria-hidden="true"></i>
                        </div>
                        <div class="col-9 text-right">
                            <a class="btn btn-sm btn-primary"
                                href="{{url('/calendar/'. $summaryUser->user_id)}}">Go To Calendar</a>
                        </div>
                        <div class="col-12 mt-3">
                            <h4 class="text-center">{{ $summaryUser->getUsername() }}</h4>
                        </div>
                    </div>

                </div>
            </li>
        @endforeach
    </ul>

</div>

{{-- js part is done in calendarSummary.js --}}


<script src="{{asset('assets/plugins/sortable/Sortable.min.js')}}"></script>

<script>
    // Tippy Script
    tippy.delegate("#calendars-cont", {
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
        placement: "auto-end"
    })

    // SortableJs Script
    let sortDiv = document.getElementById("summarySort");
    let sortable = new Sortable(sortDiv, {
        animation: 150,
        ghostClass: 'blue-background-class',
        onUpdate: function(event) {

            sortable.option("disabled", true);
            let newOrderIds = sortable.toArray();
            toggleLoader(true);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/summaryOrder/updateOrder",
                type: "post",
                data: {
                    new_order_ids: newOrderIds,
                },
            }).done(function(data) {
                if (data == "success") {
                    toggleLoader(false);
                    toastr.success("Order updated successfully!", "Success");
                }
                else {
                    toggleLoader(false);
                    toastr.error("An error accured", "Error");
                }
                sortable.option("disabled", false);
            });
        }
    });
</script>
