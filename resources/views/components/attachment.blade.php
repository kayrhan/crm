<link rel="stylesheet" href="{{ asset('drop-zone/dropzone.css') }}">

<div>
    @if($showheader == "true")
    <div class="form-label" style="padding-top: 10px;">
        {{ $header }}
        <span style="color:red">
            (max. {{$maxfiles}} Files | max. File size {{ $maxsize }} MB | Accepted File Types <x-infobox info={{$acceptedTypes}}/>)
        </span>
    </div>
    @endif

    <div class="row">
        <div class="col-12 clsbox-1" runat="server">
            <div class="dropzone clsbox" id="attachments-{{ $type }}"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('drop-zone/dropzone.js') }}"></script>

<script>
    function addDropzone() {

        let type = "{{ $type }}";
        let maxsize = "{{ $maxsize }}";
        let maxfiles = "{{ $maxfiles }}";

        Dropzone.autoDiscover = false;
        $('div#attachments-' + type).dropzone({
            maxFiles: maxfiles,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            parallelUploads: 1,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: maxsize,
            timeout: 180000000,
            acceptedFiles: "{{$acceptedTypes}}",
            url: '/attachment/uploadFiles',
            success: function(file, response) {
                let submitBtn = $(".{{ $buttonclass }}");
                let ownerForm = $("#{{ $formid }}");
                let isPrivate = "{{ $private }}";

                if (response.error) {
                    toastr.error(response.error, 'Error');
                    submitBtn.show();
                } else {
                    $.each(response.data, function(key, data) {
                        $(file.previewTemplate).append(
                            '<span style="display: none;" class="server_file">' + data
                            .link +
                            '</span>');
                        if (isPrivate == "true") {
                            $(file.previewTemplate).append(
                                `<div class="custom-control custom-switch w-50 ml-5 pl-5">
                            <input id="privateSwitch-${data.size}" class="custom-control-input"
                                onChange="changePrivateValue('${data.size}')" type="checkbox">
                            <label class="custom-control-label text-primary" for="privateSwitch-${data.size}">
                                <b>PRIVATE</b>
                            </label>
                        </div>`);
                        }
                        ownerForm.append(
                            `<input class="attachments-${type}" type="hidden" name="attachments[${data.size}][link]"
                            value="${data.link}"/>
                        <input class="attachments-${type}" type="hidden" name="attachments[${data.size}][type]"
                            value="${type}"/>
                        <input class="attachments-${type}" type="hidden" name="attachments[${data.size}][isPrivate]"
                            id="isPrivate-${data.size}" value="off">`);
                    });
                    toastr.success(response.success, 'Success');
                    submitBtn.show();

                }
            },
            init: function() {
                this.on("removedfile", function(file) {
                    let ownerForm = $("#{{ $formid }}");

                    var server_file = $(file.previewTemplate).children('.server_file')
                        .text();
                    ownerForm.find(".attachments-" + type).remove();
                });
                this.on("maxfilesexceeded", function (file) {
                    if (this.files.length >= maxfiles) {
                        toastr.error(`Maximum ${maxfiles} can be uploaded!`);
                    }
                    this.removeFile(this.files[maxfiles]);

                });
                this.on("sending",function (){
                   $("#sendmail-button").hide();
                });
                this.on("sending", function() {
                    let submitBtn = $(".{{ $buttonclass }}");

                    submitBtn.hide();
                });
            }
        });

        const changePrivateValue = (id) => {
            value = $(`#isPrivate-${id}`).val();
            value = value == "off" ? "on" : "off"
            $(`#isPrivate-${id}`).val(value);
        }
    }

    addDropzone();
</script>
