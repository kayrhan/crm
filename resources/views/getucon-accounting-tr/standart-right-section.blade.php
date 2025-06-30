<div class="col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-1 pl-0">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <label class="form-label">Pos</label>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <label class="form-label">Quantity</label>
        </div>
        <div class="col-md-1">
            <label class="form-label">Type</label>
        </div>
        <div class="col-md-5">
            <label class="form-label">Description</label>
        </div>
        <div class="col-md-1 pr-0">
            <label class="form-label">Unit Price</label>
        </div>
        <div class="col-md-1 pr-0">
            <label class="form-label">Discount</label>
        </div>
        <div class="col-md-2 pl-0 pr-0">
            <label class="form-label">Total Price</label>
        </div>
    </div>
    <div id="table-input-context">
        @if($page_type == "update")
        @foreach($accounting_datas as $accounting_data)
        <div class="row mb-3 draggable-row">
            <div class="col-md-1 pl-0">
                <div class="form-group row">
                    <div class="col-md-3 position-btn" data-already="1">
                        <span class="glyphicon glyphicon-move mt-2"></span>
                    </div>
                    <div class="col-md-9 pr-0">
                        <input class="form-control privateValidateControl accounting-item-position" type="number" name="items[{{ $accounting_data->pos }}][position]" value="{{ $accounting_data->pos }}" readonly required>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl accounting-item-quantity" type="number" name="items[{{ $accounting_data->pos }}][quantity]" value="{{ $accounting_data->quantity }}" step="0.25" required>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl" type="text" name="items[{{ $accounting_data->pos }}][type]" value="{{ $accounting_data->quantity_type }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group row">
                    <div class="col-md-12">
                        <textarea class="form-control privateValidateControl accounting-item-description" type="text" name="items[{{ $accounting_data->pos }}][description]" style="color:#000;" rows="5" required> {{ $accounting_data->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl accounting-item-unit-price" type="text" data-type="currency" name="items[{{ $accounting_data->pos }}][unit_price]" value="{{ number_format($accounting_data->unit_price, 2, ",", ".") }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control accounting-item-discount" data-type="currency" type="text" name="items[{{ $accounting_data->pos }}][discount]" value="{{ number_format($accounting_data->discount, 2, ",", ".") }}">
                    </div>
                </div>
            </div>
            <div class="col-md-1 pl-0 pr-0">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl accounting-item-total-price" type="text" data-type="currency" name="items[{{ $accounting_data->pos }}][total_price]" value="{{ number_format($accounting_data->total_price, 2, ",", ".") }}" readonly required>
                    </div>
                </div>
            </div>
            @if($loop->first)
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn btn-success btn-sm add-row-btn" onclick="$.add_row()"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn btn-danger btn-sm delete-btn">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            <input type="hidden" class="accounting-item-id" name="items[{{ $accounting_data->pos }}][item_id]" value="{{ $accounting_data->id }}">
        </div>
        @endforeach
        @else
        <div class="row" id="not-last">
            <div class="col-md-1 pl-0">
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3 position-btn">
                                <span class="glyphicon glyphicon-move mt-2"></span>
                            </div>
                            <div class="col-md-9 pr-0">
                                <input class="form-control privateValidateControl accounting-item-position" type="number" name="items[1][position]" required readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl accounting-item-quantity" type="number" name="items[1][quantity]" step="0.25" value="" required>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl" type="text" name="items[1][type]" required>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group row">
                    <div class="col-md-12">
                        <textarea class="form-control privateValidateControl" type="text" rows="5" name="items[1][description]" required></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl accounting-item-unit-price" type="text" data-type="currency" name="items[1][unit_price]" required>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control accounting-item-discount" data-type="currency" type="text" value="" name="items[1][discount]">
                    </div>
                </div>
            </div>
            <div class="col-md-1 pl-0 pr-0">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control privateValidateControl accounting-item-total-price" type="text" data-type="currency" name="items[1][total_price]" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group row">
                    <div class="col-md-12">
                        <a class="btn btn-success btn-sm add-row-btn" onclick="$.add_row()"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>