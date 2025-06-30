<script>
    function confirmModal(message,header,confirmBtn,cancelBtn,confirmBtnColor,cancelBtnColor,modalWidth) {
        let modal = $(`
        <div class="modal fade custom-alert-modal" tabindex="-1" role="dialog" data-backdrop="static" >
            <div class="modal-dialog" role="document" >
                <div class="modal-content" style="${modalWidth ? `width: ${modalWidth}px` : ''}">
                  ${header ?`<div class="modal-header justify-content-center">
                                  <span class="modal-title " style="font-size: 1.5em" id="staticBackdropLabel">${header}</span>
                            </div>` : ""  }
                    <div class="modal-body text-center" style="font-size: 16px !important;">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        ${confirmBtn ? `<button type="button" class="btn confirm-modal-btn" style="background-color: ${cancelBtnColor ? cancelBtnColor : "#0275d8" }; ; color: white">${confirmBtn}</button>`:"" }
                        ${cancelBtn ? `<button type="button" class="btn "  style="background-color: ${confirmBtnColor ? confirmBtnColor : "#d9534f" }; color: white"  data-dismiss="modal">${cancelBtn}</button>`:"" }
                    </div>
                </div>
            </div>
        </div>
    `);

        $('body').append(modal);

        let deferred = $.Deferred();
        modal.find('.confirm-modal-btn').click(function() {
            modal.modal('hide');
            deferred.resolve(true);
        });

        modal.modal('show');
        modal.on('hidden.bs.modal', function() {
            modal.remove();

            if (typeof confirmModal.result !== 'undefined') {
                deferred.resolve(confirmModal.result);
                delete confirmModal.result;
            } else {
                deferred.reject();
            }
        });

        return deferred.promise();
    }
</script>