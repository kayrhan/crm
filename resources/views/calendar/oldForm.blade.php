
{{-- Calendarları göstermek için kullandığımız eski form" --}}
<div class="card-body d-none">
    <div class="row col-md-12">

        @if (auth()->user()->role_id == 1)
            <div class="form-group col-md-4">
                <label class="tx-13 mg-b-5 tx-gray-600"><strong>Personel</strong></label>
                <div class="row row-xs">
                    <div class="col-12">
                        <select id="personel" name="personel" class="form-control" required>
                            <option>Select</option>
                        </select>
                    </div><!-- col-7 -->
                </div>
            </div>
        @endif
        <div class="form-group col-md-4">
            <label
                class="tx-13 mg-b-5 tx-gray-600"><strong>{{ ucfirst(trans('words.contract_customer')) }}</strong></label>
            <div class="row row-xs">
                <div class="col-12">
                    <select name="filterOrganization" id="filterOrganization"
                        class="form-control custom-select select2 " style="width: 100%" required>
                        <option value="0">All</option>
                    </select>
                </div><!-- col-7 -->
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="tx-13 mg-b-5 tx-gray-600"><strong>Status</strong></label>
            <div class="row row-xs">
                <div class="col-12">
                    <select name="filterStatus" id="filterStatus" class="form-control">
                        <option value="0">All</option>
                        <option value="1">Open</option>
                        <option value="2">In Progress</option>
                        <option value="3">Done</option>
                        <option value="4">Delay</option>
                    </select>
                </div><!-- col-7 -->
            </div>
        </div>
    </div>
</div>