<div class="col-md-4 col-lg-4 pl-0 pr-3 pt-3" style="max-height: 350px; overflow-y:auto; overflow-x: hidden">
    <h6>Important Decisions</h6>
    <div class="form-group">
        <textarea onkeyup="passValueToImportantForm()" onchange="passValueToImportantForm()" class="form-control"
            id="important-text" cols="30" rows="4"></textarea>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="custom-switch">
                <input type="checkbox" id="important-toggle" class="custom-switch-input">
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">Add Attachment</span>
            </label>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-sm btn-success important-dec-btn"
                onclick="submitImportantDecision()">Add
            </button>
        </div>
    </div>

    <div style="display: none" id="important-attachment">
        <x-attachment type="important-decisions" formid="important-form" buttonclass="important-dec-btn"
            showheader="false" />
    </div>

    {{-- Decisions List --}}

    <div class="row mt-3 decisions-cont">
        <div class="col-md-12">
            <a  href="#" onclick="toggleImportantHistory(event)" style="@if (count($importantDecisions) == 0) display: none; @endif">
                <h6><span class="@if (count($importantDecisions)>0)blink-text @endif">Important Decisions</span></h6>
            </a>
            <div id="important-history-cont">
                @foreach ($importantDecisions as $decision)
                    <div class="row border-bottom mb-2 important-rows d-flex" id="important-cont-{{ $decision->id }}">

                        <div class="col-md-9">
                            <div style="font-size: 0.7rem">
                                <b>[{{ $decision->getDate() }}]</b>
                                <span>{{ $decision->user->getFullName() }}</span>
                            </div>
                            <div style="font-size: 0.7rem">
                                <b>Message: </b>
                                <span class="important-text-{{ $decision->id }}">{{ $decision->text }}</span>
                            </div>
                            @foreach ($decision->getAttachments() as $index => $attachment)
                                <div>
                                    <a style="font-size: 0.6rem" target="_blank"
                                        href="{{route("uploads",[$attachment->attachment])}}">

                                        <span>Attachment {{ $index + 1 }} : </span>
                                        <span class="border-right px-1">
                                            {{ substr($attachment->getFileName(), 0, 20) }}
                                        </span>
                                        <span class="border-right px-1">
                                            {{ $attachment->getSizeMB() }}
                                        </span>
                                        <span class="px-1">
                                            {{ $attachment->getFileType() }}
                                        </span>
                                    </a>
                                </div>
                            @endforeach

                        </div>

                        <div class="col-md-3 d-flex">
                            <button type="button" class="btn btn-sm btn-white border-0" data-toggle="modal"
                                data-target="#importantUpdateModal" onclick="getImportantData({{ $decision->id }})">
                                <i class="fa fa-pencil text-info"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-white border-0" data-toggle="modal"
                                 onclick="passImportantId({{ $decision->id }})">
                                <i class="fa fa-trash text-danger"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- Update Decision Modal --}}
<div class="modal fade" id="importantUpdateModal" tabindex="-1" role="dialog" aria-labelledby="importantUpdateModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Important Decision</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Message</label>
                    <textarea class="form-control" name="text" cols="30" rows="4" id="important-update-text"
                        onchange="validateImportantUpdate()" onkeyup="validateImportantUpdate()"></textarea>
                </div>
                <input type="hidden" id="important-update-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="submitImportantUpdate()" class="btn btn-sm btn-success">Update
                    Decision</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('#important-toggle').on("change", function() {
        var isAttachment = $(this).is(":checked");
        if (isAttachment) {
            $('#important-attachment').show(100);
        } else {
            $('#important-attachment').hide(100);
        }
    });

    function passValueToImportantForm() {
        let text = $("#important-text").val();
        $("#important-form-text").val(text);
    }

    function submitImportantDecision() {
        let text = $("#important-text").val();
        if (text == "" || text.trim() == "") {
            $("#important-text").addClass("border-danger");
            $("#important-text").on("change keyup", function() {
                if ($(this).val() == "") {
                    $("#important-text").addClass("border-danger");
                } else {
                    $("#important-text").removeClass("border-danger");
                }
            })
            return;
        }

        let data = $("#important-form").serialize();
        toggleLoader(true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/important/add",
            data: data,
            type: "post",
            dataType: "json",
            success: function(response) {
                toggleLoader(false);
                toastr.success("Important decision added.", "Success");
                addNewImportantRow(response); // Adds new row to history
                clearImportantForm(); // clears form
                $(".decisions-cont").show();
            },
            error: function(error) {
                toggleLoader(false);
                toastr.error("Something went wrong", "Error")
                $("#importantUpdateModal").modal("hide");
            }
        });
    }

    function clearImportantForm() {
        let text = $("#important-text").val("");
        $("#important-form-text").val("");

        // Clicking remove and toggle buttons manually
        let removeBtns = $('#important-attachment .dz-remove');
        let sleep = 100;
        for (let i = 0; i < removeBtns.length; i++) {
            setTimeout(() => {
                removeBtns[i].click()

            }, sleep);
            sleep += 100
        }

        if ($("#important-toggle").is(":checked")) {
            $("#important-toggle").click();
        }

    }

    function addNewImportantRow(decision) {

        let attachments = "";
        decision.attachments.forEach((att, index) => {
            attachments += `
                <div>
                    <a style="font-size: 0.6rem" target="_blank"
                        href="${att.link}">

                        <span>Attachment ${ index + 1 } : </span>
                        <span class="border-right px-1">
                            ${ att.filename }
                        </span>
                        <span class="border-right px-1">
                            ${ att.size} MB
                        </span>
                        <span class="px-1">
                            ${ att.filetype }
                        </span>
                    </a>
                </div>`
        });

        let newHtml = `
            <div class="row border-bottom mb-2 important-rows d-flex" id="important-cont-${decision.id}">
                <div class="col-md-9">
                    <div style="font-size: 0.7rem">
                        <b>${decision.date}</b>
                        <span>${decision.add_by.name}</span>
                    </div>
                    <div style="font-size: 0.7rem">
                        <b>Message: </b>
                        <span class="important-text-${ decision.id }">${ decision.text }</span>
                    </div>
                    ${attachments}
                </div>

                <div class="col-md-3 d-flex">
                    <button type="button" class="btn btn-sm btn-white border-0" data-toggle="modal"
                        data-target="#importantUpdateModal"
                        onclick="getImportantData(${ decision.id })">
                        <i class="fa fa-pencil text-info"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-white border-0" data-toggle="modal"
                        onclick="passImportantId(${ decision.id })">
                        <i class="fa fa-trash text-danger"></i>
                    </button>
                </div>
            </div>`

        $("#important-history-cont").prepend(newHtml);

    }

    function toggleImportantHistory(e) {
        e.preventDefault();
        $("#important-history-cont").toggle(100);
    }

    function getImportantData(id) {
        let text = $(`.important-text-${id}`).html();
        $("#important-update-text").val(text);
        $("#important-update-id").val(id)

    }

    function submitImportantUpdate() {
        let id = $("#important-update-id").val();
        let text = $("#important-update-text").val();
        toggleLoader(true);
        if (text == "" || text.trim() == "") {
            $("#important-update-text").addClass("border-danger");
            return;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/important/update",
            data: {
                id: id,
                text: text,
            },
            type: "post",
            dataType: "json",
            success: function(response) {
                toggleLoader(false);
                toastr.success("Important decision is updated.", "Success");
                $(`.important-text-${id}`).html(text);
                $("#importantUpdateModal").modal("hide");
            },
            error: function(error) {
                toggleLoader(false);
                toastr.error("Something went wrong!", "Error")
                $("#importantUpdateModal").modal("hide");
            }
        });
    }

    function passImportantId(id) {
        confirmModal('Important Decision will be deleted!',"Are you sure?","Delete","Cancel","#0275d8","#d9534f").then(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/important/delete",
                data: {
                    id: id,
                },
                type: "post",
                dataType: "json",
                success: function(response) {
                    toggleLoader(false);
                    toastr.success("Important decision deleted", "Success");
                    $("#important-cont-" + id).addClass("d-none");
                    $("#important-cont-" + id).removeClass("d-flex");
                    checkImportantHistory();
                },
                error: function(error) {
                    toggleLoader(false);
                    toastr.error("Something went wrong", "Error")
                }
            });
        })

    }

    function checkImportantHistory() {
        let importantRows = $("#important-history-cont .important-rows.d-flex")
        console.log(importantRows.length);
        if (importantRows.length > 0) {
            $(".decisions-cont").show()
        } else {
            $(".decisions-cont").hide()
        }
    }


    function validateImportantUpdate() {
        let text = $("#important-update-text");
        if (text.val() == "" || text.val().trim() == "") {
            text.addClass("border-danger")
        } else {
            text.removeClass("border-danger")
        }
    }
</script>

{{-- There is a form inside the update-ticket blade,
    attachment data and text data being send from this form
    #important-form --}}
