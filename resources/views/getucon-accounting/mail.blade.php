<div class="row w-100 m-0 pl-1 py-1">
    <div class="col-md-12 col-lg-12">
        <div class="row pt-1">
                    <div class="m-0">
                        <div class="form-group m-0">
                            <label class="custom-switch">
                                <input id="show-send-mail" type="checkbox" name="custom-switch-checkbox1" class="custom-switch-input" autocomplete="off">
                                <span class="custom-switch-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        @if($accounting->type=="offer")
                            Send Offer Mail
                            <x-infobox info="PDF of the offer and customer's email will be added automatically. Also, If the offer has a ticket reference, a comment will be created in the ticket." />
                        @elseif($accounting->type=="proforma")
                            Send Proforma Mail
                            <x-infobox info="PDF of the proforma and customer's email will be added automatically. If there is a offer that references this proforma, its PDF also will be added." />
                        @elseif($accounting->type == "invoice")
                            Send Storno Mail
                            <x-infobox info="PDF of the storno and customer's email will be added automatically." />
                        @endif
                    </div>
                </div>

        <input type="hidden"  id="mail-accounting-type" value="{{$type}}">
        <input type="hidden"  id="mail-accounting-no" value="{{$accounting->no}}">

        <div class="row" id="send-email-section" style="display: none;">
            <div class="col-md-6 col-lg-6">
                <div class="form-group row mt-1 mb-1">
                    <label style="color: #494444;" class="col-md-1 form-label my-auto pr-0"><small>Subject:</small></label>
                    <div class="col-md-7 p-0">
                        <span id="email-subject-badge" class="badge badge-danger" style="display:none;">This field required!</span>
                        <input id="mail-subject" class="form-control form-control-sm" value="">
                    </div>
                </div>
                <div class="form-group row mt-1 mb-1">
                    <label style="color: #494444;" class="col-md-1 form-label my-auto">
                        <small>To:</small>
                    </label>
                    <div class="col-md-7 p-0">
                        <span id="email-to-badge" class="badge badge-danger" style="display:none;">This field required!</span>
                        <x-tag-and-search-input name="email_to"/>
                    </div>
                </div>

                <div class="form-group row mt-1 mb-1">
                    <label style="color: #494444;" class="col-md-1 form-label my-auto">
                        <small>Cc:</small>
                    </label>
                    <div class="col-md-7 p-0">
                         <x-tag-and-search-input name="email_cc"/>
                    </div>
                </div>
                <div class="form-group row mt-1 mb-1">
                    <label style="color: #494444;" class="col-md-1 form-label my-auto">
                        <small>Bcc:</small>
                    </label>
                    <div class="col-md-7 p-0">
                         <x-tag-and-search-input name="email_bcc"/>
                    </div>
                </div>
                <div class="row">
                    <label style="color: #494444; " class="col-md-8 form-label my-auto"><small>Additional Information:</small>
                    </label>
                </div>
                <div class="form-group row mt-1 mb-1">
                    <div class="col-md-8 my-auto">
                        <textarea id="additional-text" name="additional_text" style="height: 100px!important;"></textarea>
                    </div>
                </div>
                <div class="row pt-1">
                    <div class="col-lg-12 col-md-12">
                        <small>Attach File</small>
                    </div>
                </div>
                <div class="row " id="dropzone-area">
                    <div class="col-lg-8 col-md-8 pr-0">
                        <form class="dropzone" id="accounting-mail-attachment"> @csrf
                            <div id="accounting-mail-attachment-response">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-8 p-0 text-right">
                        <button type="button" class="btn btn-primary btn-sm mt-1 mb-0 p-1" id="send-button">
                            Send <i id="btn-loader" style="display: none;" class="fa fa-circle-o-notch fa-spin"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
