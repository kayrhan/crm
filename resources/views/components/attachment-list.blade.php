@if ($count > 0)

    <div>
        <div class="row pt-2">
            <div class="col-12">
                <div class="form-group border">
                    <div class="form-label">{{ $header }}
                        ({{ $count }})
                    </div>
                    <label class="custom-switch">
                        <input type="checkbox" id="attachmentToggle-{{ $ownerid }}-{{$type}}" name="attachmentToggle"
                            class="custom-switch-input" {{ $count > 0 ? 'checked' : '' }}>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="table-responsive border" id="attachments-{{ $ownerid }}-{{$type}}">
            <table class="table table-bordered text-wrap w-100">
                <thead>
                    <tr>
                        <th class="text-center" colspan="7">{{ $header }}</th>
                    </tr>
                    <tr>
                        <th style="width:5%">{{ ucfirst(trans('words.id')) }}</th>
                        <th style="width:20%">{{ ucfirst(trans('words.file_name')) }}</th>
                        <th style="width:10%;">Extension</th>
                        <th style="width:10%">{{ ucfirst(trans('words.file_size')) }}</th>
                        <th style="width:20%">{{ ucfirst(trans('words.uploaded_from')) }}</th>
                        <th style="width:20%">{{ ucfirst(trans('words.uploaded_date')) }}</th>
                        @if (auth()->user()->role_id != 7)
                            <th style="width:25%">{{ ucfirst(trans('words.action')) }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attachments as $attachment)
                        <tr id="attachment-row-{{ $attachment->id }}"
                            class="{{ $attachment->private == 1 ? 'text-white bg-primary' : '' }}">
                            <td>
                                {{ $attachment->id }}
                            </td>
                            <td>
                                <a href="{{route("uploads",[$attachment->attachment])}}"
                                    class="link {{ $attachment->private == 1 ? 'text-white' : 'text-primary' }}"
                                    target="_blank">{{ substr($attachment->attachment, 0, 20) }}</a>
                            </td>
                            <td>
                                {{ substr($attachment->attachment, strrpos($attachment->attachment, '.') + 1) }}
                            </td>
                            <td>
                                {{ round($attachment->size * 0.000001, 2) }} MB</td>
                            <td>
                                {{ $attachment->getUserName() }}
                            </td>
                            <td>
                                {{ $attachment->getDate() }}</td>
                            @if (auth()->user()->role_id != 7)
                                <td class="bg-white">
                                    <div class="text-center">
                                        <i data-toggle="modal"
                                            class="btn btn-sm btn-danger fa fa-trash delete-{{ $ownerid }}-{{$type}}"
                                            data-id="{{ $attachment->id }}">
                                        </i>
                                        @if ($private == 'true')

                                            <i class="btn btn-sm btn-primary private-att privateToggle-{{ $ownerid }}-{{$type}} fa
                                        {{ $attachment->private == 1 ? 'fa-lock' : 'fa-unlock' }}"
                                                data-is-private="{{ $attachment->private == 1 ? '1' : '0' }}"
                                                data-id="{{ $attachment->id }}">
                                            </i>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function addList() {

            const ownerid = "{{ $ownerid }}"
            const type = "{{$type}}"


            $(`#attachmentToggle-${ownerid}-${type}`).on('change', function() {
                let isAttachment = $(`#attachmentToggle-${ownerid}-${type}`).prop("checked");
                console.log(isAttachment);
                if (isAttachment) {
                    $(`#attachments-${ownerid}-${type}`).css('display', 'block');
                } else {
                    $(`#attachments-${ownerid}-${type}`).css('display', 'none');
                }
            });

            $(`.delete-${ownerid}-${type}`).on("click", function(e) {
                let id = $(e.target).data("id");
                confirmModal('Are you sure you want to delete this attachment?',"Delete Attachment","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/attachment/delete",
                        data: {
                            id: id,
                        },
                        type: "post",
                        dataType: "json",
                        success: function(response) {
                            if (response == "success") {
                                $("#attachment-row-" + id).css("display", "none");
                                toastr.success("Delete is successfull", 'Success');
                            } else {
                                toastr.error("Something went wrong", 'Error');
                            }
                        }
                    });
                });
            })


            $(`.privateToggle-${ownerid}-${type}`).on("click", function(e) {
                let toggleBtn = $(e.target);
                let id = toggleBtn.data("id");
                let attachmentRow = $("#attachment-row-" + id);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/attachment/togglePrivate",
                    data: {
                        id: id,
                    },
                    type: "post",
                    dataType: "json",
                    success: function(isPrivate) {
                        if (isPrivate != "error") {
                            let message = "Attachment set " + (isPrivate == 1) ? "private" :
                            "unprivate";
                            toastr.success(message, 'Success');

                            toggleBtn.toggleClass("fa-lock")
                            toggleBtn.toggleClass("fa-unlock")

                            attachmentRow.toggleClass("bg-primary");
                            attachmentRow.toggleClass("text-white");
                            attachmentRow.find("a.link").toggleClass("text-primary");
                            attachmentRow.find("a.link").toggleClass("text-white");
                        }
                        else {
                            toastr.error("Something went wrong", 'Error');
                        }
                    }
                });
            })
        }

        addList();
    </script>
@endif
