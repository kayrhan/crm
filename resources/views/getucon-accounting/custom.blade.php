@if($accounting->type=="invoice"  )
    @if($accounting->offer_no)
        <div class="row mt-2">
            <label class="col-md-3 form-label">Reference Offer</label>
            <div class="col-md-9">
                <a class="link text-primary" target="_blank" href="/getucon/accounting/update/offer/{{$accounting->reference_offer->id}}">{{"AG-".$accounting->reference_offer->no}}</a>
            </div>
        </div>
    @endif
    @if($accounting->proforma_no)
        <div class="row mt-2">
            <label class="col-md-3 form-label">Reference Proforma</label>
            <div class="col-md-9">
                <a class="link text-primary" target="_blank" href="/getucon/accounting/update/proforma/{{$accounting->reference_proforma->id}}">{{"PR-".$accounting->reference_proforma->no}}</a>
            </div>
        </div>
    @endif
    @if($accounting->storno)
        <div class="row mt-2">
            <label class="col-md-3 form-label">Storno PDF</label>
            <div class="col-md-9">
                <a class="btn btn-danger btn-sm" target="_blank" href="{{route("uploads",[$accounting->storno->filename])}}">{{$accounting->storno->filename}}</a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0">Storno Reason</label>
            </div>
            <div class="col-md-9">
                <p>{!!  $accounting->storno->reason!!}</p>
            </div>
        </div>
    @else
        <div class="row mt-2">
            <label class="col-md-3 form-label">Cancel Invoice</label>
            <div class="col-md-9">
                <a class="btn btn-sm btn-danger" id="cancel-invoice" data-invoice-no="{{$accounting->no}}">Cancel Invoice</a>
            </div>
        </div>
    @endif
@else
    @if($type=="offer")
        @if($accounting->proforma_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Proforma</label>
                <div class="col-md-9">
                    <a class="link text-primary" target="_blank" href="/getucon/accounting/update/proforma/{{$accounting->reference_proforma->id}}">{{"PR-".$accounting->reference_proforma->no}}</a>
                </div>
            </div>
        @endif
        @if(!$accounting->proforma_no && !$accounting->invoice_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Proforma</label>
                <div class="col-md-9">
                    <a class="btn btn-sm btn-warning" target="_blank" href="/getucon/accounting/add/proforma?ref_no={{$accounting->no}}">Create Proforma</a>
                </div>
            </div>
        @endif
        @if($accounting->invoice_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Invoice</label>
                <div class="col-md-9">
                    <a class="link text-primary" target="_blank" href="/getucon/accounting/update/invoice/{{$accounting->reference_invoice->id}}">{{"RG-".$accounting->reference_invoice->no}}</a>
                    @if($accounting->is_storno)  <x-infobox info="This invoice has been cancelled." /> @endif
                </div>
            </div>
        @endif
        @if(!$accounting->proforma_no && !$accounting->invoice_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Invoice</label>
                <div class="col-md-9">
                    <a class="btn btn-sm btn-warning" target="_blank" href="/getucon/accounting/add/invoice?ref_no={{$accounting->no}}">Create Invoice</a>
                </div>
            </div>
        @endif
    @elseif($type="proforma")
        @if($accounting->reference_offer)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Reference Offer</label>
                <div class="col-md-9">
                    <a class="link text-primary" target="_blank" href="/getucon/accounting/update/offer/{{$accounting->reference_offer->id}}">{{"AG-".$accounting->reference_offer->no}}</a>
                </div>
            </div>
        @endif
        @if($accounting->invoice_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Invoice</label>
                <div class="col-md-9">
                    <a class="link text-primary" target="_blank" href="/getucon/accounting/update/invoice/{{$accounting->reference_invoice->id}}">{{"RG-".$accounting->reference_invoice->no}}</a>
                    @if($accounting->is_storno)<x-infobox info="This invoice has been cancelled." />@endif
                </div>
            </div>
        @else
            <div class="row mt-2">
                <label class="col-md-3 form-label">Invoice</label>
                <div class="col-md-9">
                    <a class="btn btn-sm btn-warning" target="_blank" href="/getucon/accounting/add/invoice?ref_no={{$accounting->no}}">Create Invoice</a>
                </div>
            </div>
        @endif
    @endif
@endif
<div class="row mt-2">
    <label class="col-md-3 form-label">Copy</label>
    <div class="col-md-9">
        <a class="btn btn-sm btn-green" target="_blank" href="/getucon/accounting/add/{{$accounting->type}}?ref_no={{$accounting->no}}&copy=1">New Copy</a>
    </div>
</div>
