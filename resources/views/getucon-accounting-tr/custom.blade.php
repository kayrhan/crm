
@if($type=="offer")
        {{--Eğer offer ın referasn olduğu proforma varsa linkini bırakıyoruz yoksa yeni proforma oluşturma imkanı veriyoruz--}}
        @if($accounting->proforma_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Invoice</label>
                <div class="col-md-9">
                    <a class="link text-primary" target="_blank" href="/accounting-tr/update/{{$company->route_name}}/invoice/{{$accounting->reference_proforma->id}}">{{$accounting->reference_proforma->no}}</a>
                </div>
            </div>

        @endif
        @if(!$accounting->proforma_no)
            <div class="row mt-2">
                <label class="col-md-3 form-label">Invoice</label>
                <div class="col-md-9">
                    <a class="btn btn-sm btn-warning" target="_blank" href="/accounting-tr/add/{{$company->route_name}}/invoice?ref_no={{$accounting->no}}">Create Invoice</a>
                </div>
            </div>
        @endif



@elseif($type == "invoice")
        {{--Eğer proformanın referasn olduğu invoice varsa linkini bırakıyoruz yoksa yeni invoice oluşturma imkanı veriyoruz--}}
@if($accounting->reference_offer)
<div class="row mt-2">
    <label class="col-md-3 form-label">Reference Offer</label>
    <div class="col-md-9">
        <a class="link text-primary" target="_blank" href="/accounting-tr/update/{{$company->route_name}}/offer/{{$accounting->reference_offer->id}}">{{$accounting->reference_offer->no}}</a>
    </div>
</div>
@endif
@if($accounting->is_cancel === "Yes")
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
        <p>{!! $accounting->storno->reason !!}</p>
    </div>
</div>
@else
<div class="row mt-2">
    <label class="col-md-3 form-label">Cancel Invoice</label>
    <div class="col-md-9">
        <a class="btn btn-sm btn-danger" id="cancel-invoice">Cancel Invoice</a>
    </div>
</div>
@endif
@endif

<div class="row mt-2">
    <label class="col-md-3 form-label">Copy</label>
    <div class="col-md-9">
        <a class="btn btn-sm btn-green" target="_blank" href="/accounting-tr/add/{{$company->route_name}}/{{$accounting->type}}?ref_no={{$accounting->no}}&copy=1">New Copy</a>
    </div>
</div>
