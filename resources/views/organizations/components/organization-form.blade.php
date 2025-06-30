<form method="POST" enctype="multipart/form-data" id="organization-form"
    action="{{ $page_type == 'update'? route('organizations.update', ['organization' => $organization->id]): route('organizations.store') }}">
    @if ($page_type == 'update')
        @method("PATCH")
    @endif
    @csrf

    <div class="row">
        <div class=" row col-md-8">
            {{-- Organization Owner Name --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">Organization Owner Name
                        <span class="text-danger">*</span>
                    </label>
                    <div class="form-row">
                        <div class="col-6">
                            <input type="text" name="owner_firstname" class="form-control" placeholder="First Name"
                                   value="{{ $page_type == 'update' ? $organization->owner_firstname : old('owner_firstname') }}"
                                   required>
                        </div>
                        <div class="col-6">
                            <input type="text" name="owner_lastname" class="form-control" placeholder="Last Name"
                                   value="{{ $page_type == 'update' ? $organization->owner_lastname : old('owner_lastname') }}"
                                   required>
                        </div>
                        <div class="input-group">
                        </div>
                    </div>
                </div>
            </div>
            {{-- Organization Name --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">Organization Name
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Organization Name"
                               value="{{ $page_type == 'update' ? $organization->org_name : old('name') }}" required>
                    </div>
                </div>
            </div>
            {{-- Organization --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">Assigned Company
                        <span class="text-danger">*</span>
                    </label>
                    <select id="organization" name="organization" class="form-control" required>
                        <option selected disabled>{{ ucfirst(trans('words.select')) }}
                        <option value="8" {{ $page_type == 'update'? ($organization->personnel_org == 8? 'selected': ''): (old('organization') == 8? 'selected': '') }}>
                            getucon Management & Technology
                        </option>
                        <option value="3"{{ $page_type == 'update'? ($organization->personnel_org == 3? 'selected': ''): (old('organization') == 3? 'selected': '') }}>
                            getucon GmbH
                        </option>
                    </select>
                </div>
            </div>

            {{-- Personnel Select --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="form-label"> Assigned Personnel {{-- ucfirst(trans('words.personnel')) --}}
                        <span class="text-danger">*</span>
                    </label>
                    <select id="users" name="user" class="form-control col-md-12" required>
                        @if ($page_type == 'update')
                            @if ($organization->personnel_id)
                                <option value="{{ $organization->personnel_id }}" selected>
                                    {{ $organization->personnel ? $organization->personnel->getFullName() : '' }}
                                </option>
                            @endif
                        @elseif(old('user'))
                            <option value="{{ old('user') }}" selected>
                                {{ App\User::find(old('user'))->getFullName() }}
                            </option>
                        @endif
                    </select>
                </div>
            </div>
            {{-- Email --}}
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-label">E-Mail
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                               value="{{ $page_type == 'update' ? $organization->email : old('email') }}" required>
                    </div>
                </div>
            </div>
            {{-- GSM --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label for="gsm" class="form-label">{{ ucfirst(trans('words.gsm')) }}</label>
                    <div class="input-group">
                        <input class="form-control" id="gsm" name="gsm" type="text" placeholder="GSM" value="{{ $page_type == 'update' ? $organization->gsm : old('gsm') }}">
                    </div>
                </div>
            </div>
            {{-- Email Invoice --}}
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-label">E-Mail for Invoices & Reminder (To)</label>
                    <div class="input-group">
                        @if($page_type === "update")
                        <x-tag-and-search-input name="to" values="{{ $organization->accounting_to }}" maxTags="3"/>
                        @else
                        <x-tag-and-search-input name="to" maxTags="3"/>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-label">E-Mail for Invoices & Reminder (CC)</label>
                    <div class="input-group">
                        @if($page_type === "update")
                        <x-tag-and-search-input name="cc" values="{{ $organization->accounting_cc }}" maxTags="3"/>
                        @else
                        <x-tag-and-search-input name="cc" maxTags="3"/>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-label">E-Mail for Invoices & Reminder (BCC)</label>
                    <div class="input-group">
                        @if($page_type === "update")
                        <x-tag-and-search-input name="bcc" values="{{ $organization->accounting_bcc }}" maxTags="3"/>
                        @else
                        <x-tag-and-search-input name="bcc" maxTags="3"/>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Phone --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ ucfirst(trans('words.phone')) }}</label>
                    <div class="input-group">
                        <input type="number" name="phone" class="form-control" placeholder="Phone"
                               value="{{ $page_type == 'update' ? $organization->phone_no : old('phone') }}">
                    </div>
                </div>
            </div>
            {{-- Adress --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="form-label">{{ ucfirst(trans('words.address')) }}</label>
                    <div class="input-group">
                        <input type="text" name="address" class="form-control" placeholder="Address"
                               value="{{ $page_type == 'update' ? $organization->address : old('address') }}">
                    </div>
                </div>
            </div>
            {{-- City, ZipCode, Client Type --}}
            <div class="col-lg-6 col-md-6">
                <div class="form-row">
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ ucfirst(trans('words.city')) }}</label>
                            <div class="input-group">
                                <input type="text" name="city" class="form-control" placeholder="City"
                                       value="{{ $page_type == 'update' ? $organization->city : old('city') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ ucfirst(trans('words.zip_code')) }}</label>
                            <div class="input-group">
                                <input type="number" name="zip_code" class="form-control" placeholder="Zip Code"
                                       value="{{ $page_type == 'update' ? $organization->zip_code : old('zip_code') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ ucfirst(trans('words.rating')) }} </label>
                            <div class="input-group">
                                <select class="form-control" name="rating" id="rating">
                                    <option value="3"
                                        {{ $page_type == 'update'? ($organization->rating == 3? 'selected': ''): (old('rating') == 3? 'selected': '') }}>
                                        Good Client</option>
                                    <option value="2"
                                        {{ $page_type == 'update'? ($organization->rating == 2? 'selected': ''): (old('rating') == 2? 'selected': '') }}>
                                        Normal Client</option>
                                    <option value="1"
                                        {{ $page_type == 'update'? ($organization->rating == 1? 'selected': ''): (old('rating') == 1? 'selected': '') }}>
                                        Blacklist Client</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Contract --}}
            <input type="hidden" name="contracted" id="contracted"
                   value="{{ $page_type == 'update' ? $organization->is_contracted : old('contracted') }}">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <div class="form-label">{{ ucfirst(trans('words.contract')) }}</div>
                    <label class="custom-switch">
                        <input type="checkbox" id="contractToggle" name="contractToggle" class="custom-switch-input"
                            {{ $page_type == 'update'? ($organization->is_contracted == 1? 'checked': ''): (old('contracted') == 1? 'checked': '') }}>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"></span>
                    </label>
                </div>
            </div>
            <input type="hidden" id="save_close" name="save_close" value="0">

        </div>
        <div class="col-md-4" >
            <div class="d-flex justify-content-end   ">
                <div class="flex-column w-100 ">
                    <div class="card-body  ">
                        <div class="latest-timeline scrollbar3 " id="scrollbar3">
                            <ul class="timeline mb-0" style="overflow-y: scroll;max-height: 400px; {{ $page_type == 'update'? "display:block": "display:none" }} ">
                                @if( isset($organization) && $organization->created_at)
                                    <li class="mt-0">
                                        <div class="d-flex"><span
                                                class="time-data">Organization Created </span><span
                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($organization->created_at)->format("d.m.Y H:i:s")}}</span>
                                        </div>
                                        <p class="text-muted fs-13">Created from <span
                                                class="text-info">{{$organization["add_by_name"] != null ? $organization["add_by_name"]: "-"}}</span>
                                        </p>
                                    </li>
                                @else
                                    <li class="mt-0">
                                        <div class="d-flex">
                                            <span class="time-data">Organization Created </span>
                                            <span class="ml-auto text-muted fs-11">-</span>
                                        </div>
                                        <p class="text-muted fs-13">Created from
                                            <span class="text-info">-</span>
                                        </p>
                                    </li>
                                @endif
                                @if(isset($organization) && $organization->updated_at)
                                    <li class="mb-1 updated_log">
                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($organization->updated_at)->format("d.m.Y H:i:s")}}</span>
                                        </div>
                                        <p class="text-muted fs-13 mb-1">Last updated from <span
                                                class="text-info">{{$organization["update_by_name"] != null ? $organization["update_by_name"]: "-"}}</span>
                                        </p>
                                    </li>
                                @else
                                    <li class="mb-1 updated_log">
                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                class="ml-auto text-muted fs-11">-</span></div>
                                        <p class="text-muted fs-12">Last updated from <span
                                                class="text-info">-</span></p>
                                    </li>
                                @endif
                            </ul>
                            <div style="height: 100px;  {{ $page_type == 'update'? "display:none": "display:block" }} " ></div>
                            <div class="form-group row " style="margin-top: 20px; ">
                                <label class=" form-label col-md-4 ">Internal Info: </label>
                                <div class="col-md-12">
                                        <textarea name="description" id="description"  class="form-control"
                                                  rows="9" >{{ $page_type == 'update' ? $organization->description : old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class=" text-right"  >
                            <div>
                                <div class="d-flex justify-content-end" >
                                    <a href="{{ url('/organizations') }}" class="btn btn-danger mt-4 "  >Cancel</a>
                                    <button type="button" onclick="submitSave(event,0)" class="btn btn-success ml-3 mt-4 mb-0"  >Save</button>
                                    <button type="button" onclick="submitSave(event,1)" class="btn btn-outline-success mt-4 ml-3"  >Save &
                                        Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</form>

@push('organization-script')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script>
        function submitSave(e, isClose) {
            let form = $("#organization-form");

            if(form[0].checkValidity()){
                $("#save_close").val(isClose);
                form.submit();
            }else{
                form[0].reportValidity();
            }
        }
        $(document).ready(function() {
            $('#contractToggle').on('change', function() {
                let checked = $("#contractToggle").is(":checked");
                if (checked) {
                    $('#contracted').val("1"); // contracted
                } else {
                    $('#contracted').val("0"); // not contracted
                }
            });

            let oldUser = "{{ old('user') }}";
            let firstTime = false;

            $('#organization').on('change', function() {
                var orgId = this.value;
                if (firstTime) {
                    $('#users').val(oldUser ?? "0");
                }
                firstTime = true;
                oldUser = "";

                $('#users').select2({
                    ajax: {
                        url: '/getOrganizationUsersRawData/' + orgId + '?returnType=raw',
                        processResults: function(data, page) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            });

            $("#organization").trigger("change");
        });
    </script>
@endpush
