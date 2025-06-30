@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('drop-zone/dropzone.css') }}">
    <style>
        .border-bottom {
            border-bottom: 1px solid #ebecf1;
            padding-bottom: 7px;
            margin-bottom: 7px;
        }

        input[type="text"] {
            border: 1px solid #D5D5D5;
        }

        input[type="select"] {
            border: 1px solid #D5D5D5;
        }

        input[type="number"] {
            border: 1px solid #D5D5D5;
        }

        input[type="password"] {
            border: 1px solid #D5D5D5;
        }

        .default-cursor {
            cursor: default !important;
        }
        .paymentRow span{
            font-size: 12px;
            font-weight: normal;
        }

    </style>

@endsection
@section('page-header')
    <!--Page header-->
    <!-- <div class="page-header">
                                                                                                                                        <div class="page-leftheader">
                                                                                                                                            <h4 class="page-title mb-0">Add User</h4>
                                                                                                                                            <ol class="breadcrumb">
                                                                                                                                                <li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>Users</a></li>
                                                                                                                                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Add User</a></li>
                                                                                                                                            </ol>
                                                                                                                                        </div>
                                                                                                                                    </div> --><br>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                            </button>
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">@if ($page_type == 'update'){{ ucfirst(trans('words.update_contracts')) }} getucon GmbH  @else{{ ucfirst(trans('words.add_contracts')) }} getucon GmbH @endif</h3>
                    <div>
                        <a href="{{ url('/contracts/'.$owner_company) }}" class="btn btn-sm btn-info"><i class="fa fa-backward mr-1"></i>
                            {{ ucfirst(trans('words.back')) }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="@if ($page_type == 'update'){{ url('/update-contract/'.$owner_company) }}@else{{ url('/add-contract/'.$owner_company) }}@endif" id="contract" method="post">
                        @csrf
                        @if ($page_type == 'update')
                            <input type="hidden" value="{{ $contract->id }}" name="id">
                            <input type="hidden" value="0" name="save_and_close" id="save_and_close">
                        @endif
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_customer')) }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select name="organization" id="organization"
                                                class="form-control custom-select select2" required>
                                            <option value="">{{ ucfirst(trans('words.contract_customer')) }}</option>
                                            @if ($page_type == 'update')
                                                @if ($contract->oid)
                                                    <option value="{{ $contract->oid }}" selected="selected">
                                                        {{ $contract->org_name }}
                                                    </option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_service')) }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="">{{ ucfirst(trans('words.contract_service')) }}</option>
                                            <option value="1"
                                                {{ $page_type == 'update' ? ($contract->type == 1 ? 'selected' : '') : '' }}>
                                                DataCenter
                                            </option>
                                            <option value="2"
                                                {{ $page_type == 'update' ? ($contract->type == 2 ? 'selected' : '') : '' }}>
                                                Support-Service-Maintance</option>
                                            <option value="5"
                                                {{ $page_type == 'update' ? ($contract->type == 5 ? 'selected' : '') : '' }}>
                                                Leasing-Firewall</option>
                                            <option value="3"
                                                {{ $page_type == 'update' ? ($contract->type == 3 ? 'selected' : '') : '' }}>
                                                Non-Service</option>
                                            <option value="4"
                                                {{ $page_type == 'update' ? ($contract->type == 4 ? 'selected' : '') : '' }}>
                                                Web
                                                Contract</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if($owner_company=='getucon-de')
                                @if($page_type == 'update')
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_id')) }}
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-md-9">
                                                <input type="text" name="contractId" id="contractId" class="form-control"
                                                       placeholder="{{ ucfirst(trans('words.contract_id')) }} "
                                                       value="{{ $contract->contractId }}" required disabled>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_id')) }}
                                            <span class="text-danger">*</span> </label>
                                        <div class="col-md-9">
                                            <input type="text" name="contractId" id="contractId" class="form-control"
                                                   placeholder="{{ ucfirst(trans('words.contract_id')) }} "
                                                   value="{{ $page_type == 'update' ? $contract->contractId : '' }}" required>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <div style="display: none" class="col-lg-6 col-md-6 usedContractId">
                                @if ($page_type == 'update')
                                    <span class="badge badge-danger">This Contract ID is in use!</span>
                                    <input type="text" value="1" id="contractIdStatus" name="contractIdStatus"
                                           style="opacity: 0; z-index: -1;width: 1px;" required>
                                @else
                                    <span class="badge badge-danger">This Contract ID is in use!</span>
                                    <input type="text" value="{{ $owner_company == 'getucon-de' ? 1 : '' }}" id="contractIdStatus" name="contractIdStatus"
                                           style="opacity: 0; z-index: -1;width: 1px;" required>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_start')) }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="date" id="contract_start" name="contract_start" class="form-control"
                                               placeholder="{{ ucfirst(trans('words.contract_start')) }}"
                                               value="{{ $page_type == 'update' ? $contract->start : '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="contract-end-row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_end')) }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="date" min="{{ $page_type == 'update' ? $contract->start : '' }}"
                                               id="contract-end" name="contract_end" class="form-control"
                                               placeholder="{{ ucfirst(trans('words.contract_end')) }}"
                                               value="{{ $page_type == 'update' ? $contract->end : '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($page_type == 'update')
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-3 form-label my-auto">Contract Status<span
                                                class="text-danger"></span></label>
                                        <div class="col-md-3 pr-0">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-check pl-0">
                                                        <input type="checkbox" id="terminated" name="terminated"
                                                            {{ $contract->terminated_date != null ? 'checked=true' : '' }}>
                                                        <label class="form-check-label">
                                                            <span style="color:red;font-weight: bold;">Terminated</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <input type="checkbox" id="upgraded" name="upgraded"
                                                        {{ $contract->upgraded_date != null ? 'checked=true' : '' }}>
                                                    <label class="form-check-label">
                                                        <span style="color:green;font-weight: bold;">Upgraded</span>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6 pl-0" id="terminated_date_field"
                                             style="{{ $contract->terminated_date === null ? 'display: none;' : '' }}">
                                            <input type="date" id="terminated_date" name="terminated_date"
                                                   class="form-control"
                                                   value="{{ $contract->terminated_date !== null ? \Carbon\Carbon::parse($contract->terminated_date)->toDateString() : '' }}">
                                        </div>
                                        <div class="col-md-6 pl-0" id="upgraded_date_field"
                                             style="{{ $contract->upgraded_date === null ? 'display: none;' : '' }}">
                                            <input type="date" id="upgraded_date" name="upgraded_date"
                                                   class="form-control"
                                                   value="{{ $contract->upgraded_date !== null ? \Carbon\Carbon::parse($contract->upgraded_date)->toDateString() : '' }}">
                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row" id="payment-cycle-row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_payment_cycle')) }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select name="paymentCycle" id="paymentCycle" class="form-control" required>
                                            <option value="">{{ ucfirst(trans('words.contract_payment_cycle')) }}
                                            </option>
                                            <option value="1"
                                                {{ $page_type == 'update' ? ($contract->paymentCycle == 1 ? 'selected' : '') : '' }}>
                                                {{ ucfirst(trans('words.contract_cycle_month')) }}</option>
                                            <option value="2"
                                                {{ $page_type == 'update' ? ($contract->paymentCycle == 2 ? 'selected' : '') : '' }}>
                                                {{ ucfirst(trans('words.contract_cycle_3_month')) }}</option>
                                            <option value="3"
                                                {{ $page_type == 'update' ? ($contract->paymentCycle == 3 ? 'selected' : '') : '' }}>
                                                {{ ucfirst(trans('words.contract_cycle_six_month')) }}</option>
                                            <option value="4"
                                                {{ $page_type == 'update' ? ($contract->paymentCycle == 4 ? 'selected' : '') : '' }}>
                                                {{ ucfirst(trans('words.contract_cycle_yearly')) }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_company')) }}
                                        (or brand) <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select name="cid" id="cid" class="form-control" required>

                                            <option value="">{{ ucfirst(trans('words.contract_company')) }}</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}"
                                                    {{ $page_type == 'update' ? ($contract->cid == $company->id ? 'selected' : '') : '' }}>
                                                    {{$company->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label
                                        class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_personel')) }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select id="personel" name="personel" class="form-control" required>
                                            @if ($page_type == 'update')
                                                @if ($contract->personel)
                                                    <option value="{{ $contract->personel }}" selected="selected">
                                                        {{ $contract->first_name }} {{ $contract->surname }}
                                                    </option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-3 form-label my-auto">Internal Information </label>
                                    <div class="col-md-9">
                                        <textarea name="description" id="description" class="form-control"
                                                  rows="2">{{ $page_type == 'update' ? $contract->description : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group row border-bottom">
                                    <label class="col-md-3 form-label my-auto">Reaction Time</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select name="reaction_time" class="form-control">
                                                    <option value="">Select hour</option>
                                                    <option value="0"
                                                        {{ $page_type == 'update' ? ($contract->reaction_time === 0 ? 'selected' : '') : '' }}>
                                                        No</option>
                                                    <option value="4"
                                                        {{ $page_type == 'update' ? ($contract->reaction_time === 4 ? 'selected' : '') : '' }}>
                                                        4h</option>
                                                    <option value="8"
                                                        {{ $page_type == 'update' ? ($contract->reaction_time === 8 ? 'selected' : '') : '' }}>
                                                        8h</option>
                                                    <option value="24"
                                                        {{ $page_type == 'update' ? ($contract->reaction_time === 24 ? 'selected' : '') : '' }}>
                                                        24h</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label pt-2 text-center">Monthly inclusive hours</label>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="inclusive_hours" class="form-control">
                                                    <option value="">Select hour</option>
                                                    @for ($i = 0; $i <= 10; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $page_type == 'update' ? ($contract->inclusive_hours === $i ? 'selected' : '') : '' }}>
                                                            {{ $i }}h</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group row border-bottom">
                                    <div class="col-md-3 col-lg-3">
                                        <label class="form-label">{{ ucfirst(trans('words.monthly_fee_contract')) }}
                                            <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input type="text" name="price" id="price" class="form-control"
                                                       data-type="currency"
                                                       value="{{ $page_type == 'update' ? number_format($contract->price, 2, ',', '.') : '' }}"
                                                       required>
                                            </div>

                                            <label
                                                class="col-md-3 form-label my-auto text-center">{{ ucfirst(trans('words.contract_discount')) }}
                                            </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" data-type="currency4"
                                                       name="priceDiscount" id="priceDiscount"
                                                       value="{{ $page_type == 'update' ? number_format($contract->priceDiscount, 4, ',', '.') : '' }}">
                                            </div>
                                            <label class="col-md-3 form-label my-auto text-center">Final </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" data-type="currency"
                                                       name="priceResult" id="priceResult">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-hidden-section"  style="{{$page_type=="update"?($contract->type == 5?"display:none;":""):""}}">

                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group row border-bottom">
                                        <div class="col-md-3 col-lg-3">
                                            <label class="form-label">{{ ucfirst(trans('words.hourly_rate')) }} <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 col lg-9">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="hourprice"
                                                           value="{{ $page_type == 'update' ? number_format($contract->hourprice, 2, ',', '.') : '' }}"
                                                           id="hourprice" required>
                                                </div>
                                                <label
                                                    class="col-md-3 form-label my-auto text-center">{{ ucfirst(trans('words.contract_discount')) }}
                                                </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency4"
                                                           name="hourPriceDiscount"
                                                           value="{{ $page_type == 'update' ? number_format($contract->hourPriceDiscount, 4, ',', '.') : '' }}"
                                                           id="hourPriceDiscount">
                                                </div>
                                                <label class="col-md-3 form-label my-auto text-center">Final </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="hourPriceResult" id="hourPriceResult">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group row border-bottom">
                                        <div class="col-md-3 col-lg-3">
                                            <label class="form-label">{{ ucfirst(trans('words.15_minute_rate')) }} <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 col-lg-9">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="fiftyteenprice" id="fiftyteenprice">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group row border-bottom">
                                        <div class="col-md-3 col-lg-3">
                                            <label class="form-label">{{ ucfirst(trans('words.driving_costs')) }} <br>
                                                (0-25 KM)<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 col lg-9">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="transportprice_1" id="transportprice_1"
                                                           value="{{ $page_type == 'update' ? number_format($contract->transportPrice1, 2, ',', '.') : '' }}"
                                                           required>
                                                </div>
                                                <label
                                                    class="col-md-3 form-label my-auto text-center">{{ ucfirst(trans('words.contract_discount')) }}
                                                </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency4"
                                                           name="transportpriceDiscount_1"
                                                           value="{{ $page_type == 'update' ? number_format($contract->transportPriceDiscount1, 4, ',', '.') : '' }}"
                                                           id="transportpriceDiscount_1">
                                                </div>
                                                <label class="col-md-3 form-label my-auto text-center">Final </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="transportResult_1" id="transportResult_1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group row border-bottom">
                                        <div class="col-md-3 col-lg-3">
                                            <label class="form-label">{{ ucfirst(trans('words.driving_costs')) }}
                                                <br>(25-60 KM)<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 col lg-9">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="transportprice_2"
                                                           value="{{ $page_type == 'update' ? number_format($contract->transportPrice2, 2, ',', '.') : '' }}"
                                                           id="transportprice_2" required>
                                                </div>
                                                <label
                                                    class="col-md-3 form-label my-auto text-center">{{ ucfirst(trans('words.contract_discount')) }}
                                                </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control " data-type="currency4"
                                                           name="transportpriceDiscount_2"
                                                           value="{{ $page_type == 'update' ? number_format($contract->transportPriceDiscount2, 4, ',', '.') : '' }}"
                                                           id="transportpriceDiscount_2">
                                                </div>
                                                <label class="col-md-3 form-label my-auto text-center">Final </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="transportResult_2" id="transportResult_2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group row border-bottom">
                                        <div class="col-md-3 col-lg-3">
                                            <label class="form-label">{{ ucfirst(trans('words.driving_costs')) }} <br>
                                                (60-100 KM)<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 col lg-9">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="transportprice_3"
                                                           value="{{ $page_type == 'update' ? number_format($contract->transportPrice3, 2, ',', '.') : '' }}"
                                                           id="transportprice_3" required>
                                                </div>
                                                <label
                                                    class="col-md-3 form-label my-auto text-center">{{ ucfirst(trans('words.contract_discount')) }}
                                                </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control " data-type="currency4"
                                                           name="transportpriceDiscount_3"
                                                           value="{{ $page_type == 'update' ? number_format($contract->transportPriceDiscount3, 4, ',', '.') : '' }}"
                                                           id="transportpriceDiscount_3">
                                                </div>
                                                <label class="col-md-3 form-label my-auto text-center">Final </label>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" data-type="currency"
                                                           name="transportResult_3" id="transportResult_3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($page_type == 'update')
                            <div class="row">
                                <div class="col-md-6 col-lg-6 border-bottom">
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3 d-flex align-items-center">
                                            <label class="form-label">Dienstleistungskosten
                                                <x-infobox
                                                    info="Regelzeit und außerhalb. (Werte werden in Prozent angezeigt.)" />
                                            </label>
                                        </div>
                                        <div class="col-md-9 col-lg-9">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="default-cursor"></th>
                                                    <th scope="col" class="default-cursor">Zwischen 08:00 - 18:00
                                                        <x-infobox info="Uhr Regelsatz" />
                                                    </th>
                                                    <th scope="col" class="default-cursor">Außerhalb zzgl.</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th scope="row" class="default-cursor">Montag - Freitag </th>
                                                    <td class="default-cursor">&nbsp;</td>
                                                    <td><input type="number" min="0" max="1000" name="notwh_weekday"
                                                               class="form-control form-control-sm"
                                                               value="{{ $contract->notwh_weekday ?? '50' }}"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="default-cursor">Samstag </th>
                                                    <td><input type="number" min="0" max="1000" name="wh_saturday"
                                                               class="form-control form-control-sm"
                                                               value="{{ $contract->wh_saturday ?? '50' }}"></td>
                                                    <td><input type="number" min="0" max="1000" name="notwh_saturday"
                                                               class="form-control form-control-sm"
                                                               value="{{ $contract->notwh_saturday ?? '100' }}"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="default-cursor">Sonntag und gesetzl.
                                                        feiertage </th>
                                                    <td><input type="number" min="0" max="1000" name="wh_sunday_holiday"
                                                               class="form-control form-control-sm"
                                                               value="{{ $contract->wh_sunday_holiday ?? '100' }}"></td>
                                                    <td><input type="number" min="0" max="1000"
                                                               name="notwh_sunday_holiday"
                                                               class="form-control form-control-sm"
                                                               value="{{ $contract->notwh_sunday_holiday ?? '150' }}">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        {{-- reee --}}
                        <div id="payment-container">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 border-bottom">
                                    <label class="text-center form-label">
                                        {{ ucfirst(trans('words.received_payments')) }}
                                        <x-infobox info="Changes will take effect after save." />
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-1 form-label my-auto text-center">POS </label>
                                        <label
                                            class="col-md-2 form-label my-auto text-center">{{ ucfirst(trans('words.contract_payment_text')) }}
                                        </label>
                                        <label class="col-md-3 form-label my-auto text-center">Date </label>
                                        <label
                                            class="col-md-2 form-label my-auto text-center">{{ ucfirst(trans('words.contract_payment_method')) }}
                                        </label>
                                        <label class="col-md-3 form-label my-auto text-center">Notes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group row border-bottom">
                                        <label class="col-md-1 form-label my-auto text-center">
                                            #
                                        </label>
                                        <label class="col-md-2 form-label my-auto">
                                            <input class="form-control" data-type="currency" id="paymentPrice" type="text"
                                                   min="1" step="any">
                                        </label>
                                        <label class="col-md-3 form-label my-auto ">
                                            <input class="form-control" id="paymentDate" type="date"></label>
                                        <label class="col-md-2 form-label my-auto ">
                                            <select class="form-control" id="paymentMethod">
                                                <option value="">
                                                    {{ ucfirst(trans('words.contract_payment_method')) }}
                                                </option>
                                                <option value="1">
                                                    {{ ucfirst(trans('words.contract_payment_method_1')) }}
                                                </option>
                                                <option value="2">
                                                    {{ ucfirst(trans('words.contract_payment_method_2')) }}
                                                </option>
                                            </select>
                                        </label>
                                        <label class="col-md-3 form-label my-auto">
                                            <input class="form-control" id="paymentNote" type="text">
                                        </label>
                                        <label class="col-md-1 form-label my-auto">
                                            <button type="button" onclick="addPaymentRow()"
                                                    class="btn btn-sm btn-success float-right">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="paymentRows">
                                @if ($page_type == 'update')
                                    @php
                                        $paymentCount = 1;
                                        $bankTotal = 0;
                                        $autoTotal = 0;
                                        $total = 0;
                                    @endphp
                                    @foreach ($payments as $payment)

                                        <div class="row paymentRow" data-id="{{ $paymentCount }}">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group row border-bottom">
                                                    <label class="col-md-1 form-label my-auto text-center paymentIndex">
                                                        #{{ $paymentCount }}
                                                    </label>
                                                    <label class="col-md-2 form-label my-auto paymentPriceRow">
                                                        <span>{{ number_format($payment->price, 2, ',', '.') }}</span>
                                                        <input class="form-control d-none" data-type="currency"
                                                               name="paymentprice[]" type="text" min="1" step="any"
                                                               value="{{ number_format($payment->price, 2, ',', '.') }}"
                                                               required>
                                                    </label>
                                                    <div class="col-md-3 form-label my-auto paymentDateRow">
                                                        <span data-value={{ $payment->date }}>
                                                            {{ date_format(date_create($payment->date), 'd.m.Y') }}
                                                        </span>
                                                        <input class="form-control d-none" name="paymentdate[]" type="date"
                                                               value="{{ $payment->date }}" required></div>
                                                    <label class="col-md-2 form-label my-auto paymentMethodRow ">
                                                        <span>
                                                            @if ($payment->type == 1)
                                                                {{ ucfirst(trans('words.contract_payment_method_1')) }}
                                                            @else
                                                                {{ ucfirst(trans('words.contract_payment_method_2')) }}
                                                            @endif
                                                        </span>
                                                        <select class="form-control d-none" name="paymentmethod[]" required>
                                                            <option value="">
                                                                {{ ucfirst(trans('words.contract_payment_method')) }}
                                                            </option>
                                                            <option value="1" @if ($payment->type == 1) selected="selected" @endif>
                                                                {{ ucfirst(trans('words.contract_payment_method_1')) }}
                                                            </option>
                                                            <option value="2" @if ($payment->type == 2) selected="selected" @endif>
                                                                {{ ucfirst(trans('words.contract_payment_method_2')) }}
                                                            </option>
                                                        </select>
                                                    </label>
                                                    <label class="col-md-3 form-label my-auto paymentNotesRow">
                                                        <span>{{ $payment->note }}</span>
                                                        <input class="form-control d-none" name="paymentNotes[]" type="text"
                                                               value="{{ $page_type == 'update' ? $payment->note ?? '' : '' }}">
                                                    </label>
                                                    <label class="col-md-1 form-label row px-0">
                                                        <button type="button" class="btn btn-sm col-6 text-info edit-btn"
                                                                onclick="toggleEditRow({{ $paymentCount }})">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </button>
                                                        <button type="button" data-toggle="modal"
                                                                data-target="#deletePaymentModal"
                                                                class="btn btn-sm col-6 text-danger"
                                                                onclick="passDeleteID({{ $paymentCount }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $paymentCount++;
                                            if ($payment->type == 1) {
                                                $autoTotal += $payment->price;
                                            } elseif ($payment->type == 2) {
                                                $bankTotal += $payment->price;
                                            }
                                            $total += $payment->price;
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 row">
                                    <div class="col-2"><b>TOTAL:</b></div>
                                    <div class="col-10 row">
                                        <div class="col-4 text-center border-left bank-total">
                                            {{ ucfirst(trans('words.contract_payment_method_2')) }}:
                                            {{ number_format($bankTotal ?? 0, 2, ',', '.') }}
                                        </div>
                                        <div class="col-4 text-center border-left auto-total">
                                            {{ ucfirst(trans('words.contract_payment_method_1')) }}:
                                            {{ number_format($autoTotal ?? 0, 2, ',', '.') }}
                                        </div>
                                        <div class="col-4 text-center border-left all-total">
                                            All: {{ number_format($total ?? 0, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" id="paymentRowCount"
                                   value="{{ $page_type == 'update' ? count($payments) : '1' }}">


                            {{-- reee end --}}
                            <br>

                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group row border-bottom">
                                        <label
                                            class="col-md-3 form-label my-auto">{{ ucfirst(trans('words.contract_payment_status')) }}
                                            <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="contract_payment_status" id="contract_payment_status"
                                                    class="form-control" required>
                                                <option value="" selected>
                                                    Select Payment Status</option>
                                                <option value="1"
                                                    {{ $page_type == 'update' ? ($contract->status == 1 ? 'selected' : '') : '' }}>
                                                    {{ ucfirst(trans('words.contact_payment_status_1')) }}</option>
                                                <option value="2"
                                                    {{ $page_type == 'update' ? ($contract->status == 2 ? 'selected' : '') : '' }}>
                                                    {{ ucfirst(trans('words.contact_payment_status_2')) }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($page_type == 'update')
                            @if ($attachments)
                                <?php $attachment_count = count($attachments); ?>
                                <div class="row border {{ count($attachments) == 0 ? 'd-none' : '' }}"
                                     id="attachments">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-wrap" id="" style="width:100%;">
                                                <thead>
                                                <tr>
                                                    <th class="w-5">{{ ucfirst(trans('words.id')) }}
                                                    </th>
                                                    <th class="w-20">
                                                        {{ ucfirst(trans('words.file_name')) }}</th>
                                                    <th class="w-10">Extension</th>
                                                    <th class="w-15">
                                                        {{ ucfirst(trans('words.file_size')) }} (MB)</th>
                                                    <th class="w-20">
                                                        {{ ucfirst(trans('words.uploaded_from')) }}</th>
                                                    <th class="w-20">
                                                        {{ ucfirst(trans('words.uploaded_date')) }}</th>
                                                    <th class="w-10">{{ ucfirst(trans('words.action')) }}
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($attachments as $attachment)
                                                    <tr id="attach{{ $attachment->id }}" class="default-cursor">
                                                        <td class="px-1">{{ $attachment->id }}</td>
                                                        <td class="px-1"><a
                                                                href="{{route("uploads",[$attachment->attachment])}}"
                                                                class="link text-primary"
                                                                target="_blank">{{ substr($attachment->attachment, 0, 20) }}</a>
                                                        </td>
                                                        <td class="px-1">
                                                            {{ substr($attachment->attachment, strrpos($attachment->attachment, '.') + 1) }}
                                                        </td>
                                                        <td class="px-1">
                                                            {{ round($attachment->size * 0.000001, 2) }} MB</td>
                                                        <td class="px-1">{{ $attachment->first_name }}
                                                            {{ $attachment->surname }}</td>
                                                        <td class="px-1">
                                                            {{ \Carbon\Carbon::parse($attachment->created_at)->format('d.m.Y [H:i:s]') }}
                                                        </td>
                                                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4)
                                                            <td class="px-1 text-center"><i
                                                                    class="btn btn-sm btn-danger deleteAttachment fa fa-trash"
                                                                    data-id="{{ $attachment->id }}"></i>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div id="attachmentResponse">
                        </div>

                        <div class="form-label pt-1">
                            {{ ucfirst(trans('words.add_attachment')) }}

                            <span style="color:red">(max. 5 Files | max. File size 10 MB)</span>
                        </div>
                        <input type="submit" id="submitHidden" style="opacity:0;z-index:-1;">
                    </form>
                    <div class="row">
                        <div class="col-md-6">
                            <form class="dropzone" id="contractAttachments"> @csrf</form>
                        </div>
                    </div>
                    <div class="row" id="buttonRow">
                        <div class="col-lg-6 col-md-6 text-right">
                            <a class="btn btn-danger mt-4 mb-0"
                               href="/contracts/{{$owner_company}}">{{ ucfirst(trans('words.cancel')) }}</a>
                            <button type="submit" onclick="submitForm('save')"
                                    class="btn btn-success ml-1 mt-4 mb-0">{{ ucfirst(trans('words.save')) }}</button>
                            @if ($page_type == 'update')
                                <button type="submit" onclick="submitForm('save-close')"
                                        class="btn btn-outline-success ml-1 mt-4 mb-0"> {{ ucfirst(trans('words.save')) }}
                                    &
                                    Close</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div><!-- end app-content-->
    </div>

@endsection
@section('js')
    <!--INTERNAL Select2 js -->
    <script src="{{ asset('drop-zone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.inputmask.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/numberFormat.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-number-format.js') }}"></script>

    <script>
        $(document).ready(function() {



            $('input,select,textarea').change(function(event) {
                if (event.target.checkValidity()) {

                    $(this).css('border', '1px solid #D5D5D5');
                }
            });
            $("#submitHidden").click(function() {

                if ($("form")[0].checkValidity()) {
                    $('input[required]:invalid').css('border', '1px solid red');
                    $('select[required]:invalid').css('border', '1px solid red');
                    $('textarea[required]:invalid').css('border', '1px solid red');
                    console.log($('input[required]:invalid'));
                }

            });

            $('#organization').select2({
                ajax: {
                    url: '/getOrganizationsRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                },
                containerCssClass: function(e) {
                    return $(e).attr('required') ? 'required' : '';
                }
            });

            $('#personel').select2({
                ajax: {
                    url: '/getPersonnelRawData',
                    processResults: function(data, page) {
                        return {
                            results: data
                        };
                    }
                },
                containerCssClass: function(e) {
                    return $(e).attr('required') ? 'required' : '';
                }
            });
            $("#role").select2();

            function precisionPrice(price) {

                return Math.round(price * 100) / 100;
            }

            function precisionPrice3(price) {
                return Math.round(price * 1000) / 1000;
            }


            $("#type").change(function() {
                if ($(this).val() === '1') {
                    $('#contractId').inputmask('integer', {
                        mask: 'DC-99999',
                        allowMinus: false,
                        rightAlign: false
                    });
                }
                if ($(this).val() === '2') {
                    $('#contractId').inputmask('integer', {
                        mask: 'SYS-99999',
                        allowMinus: false,
                        rightAlign: false
                    });
                }
                if ($(this).val() === '3') {
                    $('#contractId').inputmask('integer', {
                        mask: 'NS-99999',
                        allowMinus: false,
                        rightAlign: false
                    });
                    $("#contract-end-row").hide();
                    $("#payment-cycle-row").hide();

                    $("#contract-end-row").find("input[required]").prop("required",false);
                    $("#payment-cycle-row").find("select[required]").prop("required",false);

                    $("#payment-container").hide();
                    $("#payment-container").find("select[required]").prop("required",false);
                }
                else{
                    $("#contract-end-row").show();
                    $("#payment-cycle-row").show();

                    $("#contract-end-row").find("input[required]").prop("required",true);
                    $("#payment-cycle-row").find("select[required]").prop("required",true);

                    $("#payment-container").show();
                    $("#payment-container").find("select[required]").prop("required",true);
                }

                if ($(this).val() === '4') {
                    $('#contractId').inputmask('integer', {
                        mask: 'WS-99999',
                        allowMinus: false,
                        rightAlign: false
                    });
                }
                if ($(this).val() === '5') {
                    $('#contractId').inputmask('integer', {
                        mask: 'FW-99999',
                        allowMinus: false,
                        rightAlign: false
                    });
                    //if leasing firewall selected, not necessary this fields
                    $("#dynamic-hidden-section").hide();

                    $("#dynamic-hidden-section").find("input[required]").prop("required",false);
                }else{
                    $("#dynamic-hidden-section").show();

                    $("#dynamic-hidden-section").find("input[required]").prop("required",true);
                }
            });


            $("#hourprice,#hourPriceDiscount").change(function() {
                if ($("#hourprice").val() !== "") {
                    let price = parseFloat($('#hourprice').val().replace(".", "").replace(",", "."));
                    let priceDiscount = parseFloat($("#hourPriceDiscount").val().replace(".", "").replace(
                        ",", "."));
                    if(isNaN(priceDiscount))
                        priceDiscount = 0;
                    let calc = precisionPrice(price - (price / 100) * priceDiscount);
                    $('#hourPriceResult').val(numberFormat(calc, 2, ',', '.'));
                    $("#fiftyteenprice").val(numberFormat((calc / 4), 2, ',', '.'));
                }
            });

            $("#hourprice").keyup(function() {
                if ($(this).val() === "") {
                    $("#fiftyteenprice").prop("disabled", false);
                    $("#hourPriceResult,#hourPriceDiscount").prop("readonly", true);
                } else {
                    $("#fiftyteenprice").prop("disabled", true);
                    $("#hourPriceResult,#hourPriceDiscount").prop("readonly", false);
                }
            });

            $("#fiftyteenprice").change(function() {
                let rate = parseFloat($(this).val().replace(".", "").replace(",", "."));
                $("#hourPriceResult").val(numberFormat(4 * rate, 2, ",", "."));
                $("#hourprice").val(numberFormat(4 * rate, 2, ",", "."));
                $("#hourprice").trigger("keyup");
                $("#hourPriceDiscount").val("0,0000");
                $("#hourprice").trigger("change");
                //let final_price = parseFloat($('#hourPriceResult').val().replace(".", "").replace(",", "."));
                //let discount    = parseFloat($('#hourPriceDiscount').val().replace(".", "").replace(",", "."));
                //let price       = (100*final_price)/(100-discount);

            });

            $("#hourPriceResult").change(function() {
                var price = parseFloat($('#hourprice').val().replace(".", "").replace(",", "."));
                if (isNaN(price)) {
                    $('#hourprice').val($(this).val());
                    var price = parseFloat($(this).val().replace(".", "").replace(",", "."));
                }
                var result = parseFloat($(this).val().replace(".", "").replace(",", "."));
                var calc = ((price - result) / price) * 100;
                $("#hourPriceDiscount").val(numberFormat(calc, 4, ",", "."));
                $("#fiftyteenprice").val(numberFormat((result / 4), 2, ',', '.'));
            });


            $('#price,#priceDiscount').change(function() {
                let price = parseFloat($('#price').val().replace(".", "").replace(",", "."));
                let priceDiscount = parseFloat($("#priceDiscount").val().replace(".", "").replace(",",
                    "."));
                if(isNaN(priceDiscount))
                    priceDiscount = 0;
                let calc = precisionPrice(price - (price / 100) * priceDiscount);
                $('#priceResult').val(numberFormat(calc, 2, ',', '.'));

            });

            $("#priceResult").change(function() {
                var price = parseFloat($('#price').val().replace(".", "").replace(",", "."));
                if (isNaN(price)) {
                    $('#price').val($(this).val());
                    var price = parseFloat($(this).val().replace(".", "").replace(",", "."));
                }
                var result = parseFloat($(this).val().replace(".", "").replace(",", "."));
                var calc = ((price - result) / price) * 100;
                $("#priceDiscount").val(numberFormat(calc, 4, ",", "."));
            });

            $("input[id ^= 'transportprice'],input[id ^= 'transportpriceDiscount']").change(function() {
                var id = $(this).attr('id').split("_")[1];
                var price = parseFloat($('#transportprice_' + id).val().replace(".", "").replace(",", "."));
                var priceDiscount = parseFloat($("#transportpriceDiscount_" + id).val().replace(".", "")
                    .replace(",", "."));
                if(isNaN(priceDiscount))
                    priceDiscount = 0;
                var calc = precisionPrice(price - (price / 100) * priceDiscount);
                $('#transportResult_' + id).val(numberFormat(calc, 2, ',', '.'));
            });


            $("input[id ^= 'transportResult']").change(function() {
                var id = $(this).attr('id').split("_")[1];
                var price = parseFloat($('#transportprice_' + id).val().replace(".", "").replace(",", "."));
                if (isNaN(price)) {
                    $('#transportprice_' + id).val($(this).val());
                    var price = parseFloat($(this).val().replace(".", "").replace(",", "."));
                }
                var result = parseFloat($(this).val().replace(".", "").replace(",", "."));
                var calc = ((price - result) / price) * 100;
                $("#transportpriceDiscount_" + id).val(numberFormat(calc, 4, ",", "."));
            });

            @if($owner_company!='getucon-de')
            $("#contractId").change(function() {
                let organization = $('#organization').val();
                $.ajax({
                    url: '/quest-contract/' + $(this).val() + "?organization_id=" + organization,
                    type: "get",
                    dataType: 'json',
                }).done(function(data) {
                    if (data.status === 1) {
                        $('.usedContractId').show();
                        $('#contractIdStatus').val('');
                    } else {
                        $('.usedContractId').hide();
                        $('#contractIdStatus').val(1);
                    }
                });
            });
            @endif

            $("#contract_start").on("change", function() {

                let start_date = new Date($(this).val()).toISOString().slice(0, 10);
                $("#contract-end").attr("min", start_date);
            });

            $(`#paymentPrice, #paymentDate, #paymentMethod, .paymentPriceRow .form-control,
                .paymentDateRow .form-control, .paymentMethodRow .form-control`)
                .on("keyup change", function(e) {
                    if ($(e.target).val() == "") {
                        $(e.target).addClass("border-danger")
                    } else {
                        $(e.target).removeClass("border-danger")
                    }
                })

            @if ($page_type == 'update')
            /*DOCUMENT READY BEGIN*/
            var hourly_rate = parseFloat($('#hourprice').val().replace(".", "").replace(",", "."));
            var hourly_discount = parseFloat($("#hourPriceDiscount").val().replace(".", "").replace(",", "."));
            var hourly_final = precisionPrice(hourly_rate - (hourly_rate * (hourly_discount / 100)));


            $('#hourPriceResult').val(numberFormat(hourly_final, 2, ',', '.'));

            var transport_price_1 = parseFloat($('#transportprice_1').val().replace(".", "").replace(",", "."))
            var transport_discount_1 = parseFloat($('#transportpriceDiscount_1').val().replace(".", "").replace(",", "."));
            var transport_final_1 = precisionPrice(transport_price_1 - precisionPrice((transport_price_1 * (transport_discount_1
                / 100))));

            $('#transportResult_1').val(numberFormat(transport_final_1, 2, ',', '.'));

            var transport_price_2 = parseFloat($('#transportprice_2').val().replace(".", "").replace(",", "."))
            var transport_discount_2 = parseFloat($('#transportpriceDiscount_2').val().replace(".", "").replace(",", "."));

            var transport_final_2 = precisionPrice(transport_price_2 - (transport_price_2 * (transport_discount_2 / 100)));

            $('#transportResult_2').val(numberFormat(transport_final_2, 2, ',', '.'));

            var transport_price_3 = parseFloat($('#transportprice_3').val().replace(".", "").replace(",", "."))

            var transport_discount_3 = parseFloat($('#transportpriceDiscount_3').val().replace(".", "").replace(",", "."));

            var transport_final_3 = precisionPrice(transport_price_3 - (transport_price_3 * (transport_discount_3 / 100)) );

            $('#transportResult_3').val(numberFormat(transport_final_3, 2, ',', '.'));

            $('#fiftyteenprice').val(numberFormat(hourly_final / 4, 2, ",", "."));


            var price = parseFloat($('#price').val().replace(".", "").replace(",", "."))

            var priceDiscount = parseFloat($('#priceDiscount').val().replace(".", "").replace(",", "."));

            var priceResult = precisionPrice(price - (price * (priceDiscount / 100)) );

            $('#priceResult').val(numberFormat(priceResult,2,",","."));

            /*DOCUMENT READY END*/

            $(document).on('click', '.deleteAttachment', function (e) {
                let id = $(this).data('id');
                confirmModal('Attachment will be delete!',"Are you sure?","Delete","Close","#0275d8","#d9534f").then(function() {
                    $.ajax({
                        type: "GET",
                        url: '/contract/delete-attachment/' + id,
                        success: function (response) {
                            if (response.status === 1) {

                                location.reload();
                            } else {

                                location.reload();
                            }
                        }
                    });
                });
            });


            @if ($contract->terminated_date == null)
            $('#terminated').prop("checked",false);
            @else
            $('#terminated').prop("checked",true);
            $("#upgraded").prop("disabled",true);
            $("#terminated_date").prop("required",true);
            @endif
            @if ($contract->upgraded_date == null)
            $("#upgraded").prop("checked",false);
            @else
            $("#upgraded").prop("checked",true);
            $("#terminated").prop("disabled",true);
            $("#upgraded_date").prop("required",true);
            @endif
            $(document).on("change","#terminated",function (){

                if($(this).is(":checked")){
                    $('#terminated_date_field').show(50);
                    $("#terminated_date").prop("required",true);
                    $('#upgraded').prop("disabled",true);
                }
                else{
                    $('#terminated_date_field').hide(50);
                    $("#terminated_date").prop("required",false);
                    $('#upgraded').prop("disabled",false);

                }

            });
            $(document).on("change","#upgraded",function (){

                if($(this).is(":checked")){
                    $('#upgraded_date_field').show(50);
                    $("#upgraded_date").prop("required",true);
                    $('#terminated').prop("disabled",true);
                }
                else{
                    $('#upgraded_date_field').hide(50);
                    $("#upgraded_date").prop("required",false);
                    $('#terminated').prop("disabled",false);

                }

            });

            $('.save_and_close').on("click",function (){
                console.log("save close");
                $('#save_and_close').val(1);
            });
            $('.save_not_close').on("click",function (){
                console.log("save not close");
                $('#save_and_close').val(0);
            });

            $("#type").trigger("change");

            @endif //update end


        });

        // Helper functions
        function convertDateFormat(date) {
            date = new Date(date)

            let day = date.getDate()
            day = day > 9 ? day : "0" + day

            let month = date.getMonth() + 1;
            month = month > 9 ? month : "0" + month

            let year = date.getFullYear()
            formattedDate = `${day}.${month}.${year}`

            return formattedDate;
        }

        function convertToCurrency(value) {
            return numberFormat(value, 2, ",", ".");
        }

        function convertToFloat(value) {
            return parseFloat(value.replace(/\./g, "").replace(/\,/g, "."));
        }

        function addPaymentRow() {
            var count = parseInt($('#paymentRowCount').val());
            let index = $(".paymentRow").length + 1;
            let newCount = count + 1;

            let payment = $("#paymentPrice").val();
            let date = $("#paymentDate").val();
            let dateFormat = new Date(date)
            dateFormat = convertDateFormat(dateFormat);

            let method = $("#paymentMethod").val();
            let note = $("#paymentNote").val();

            if (payment == "") {
                $("#paymentPrice").addClass("border-danger");
            }
            if (date == "") {
                $("#paymentDate").addClass("border-danger");
            }
            if (method == "") {
                $("#paymentMethod").addClass("border-danger");
            }
            if (method == "" || payment == "" || date == "") {
                return;
            }
            $("#paymentPrice, #paymentNote, #paymentMethod, #paymentDate").val(
                "") // Add Row dan sonra inputları temizliyoruz

            $("#paymentMethod, #paymentDate, #paymentPrice").removeClass("border-danger");

            $('#paymentRowCount').val(newCount);
            var html = `
                <div class="row paymentRow" data-id="${newCount}">
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group row border-bottom">
                            <label class="col-md-1 form-label my-auto text-center paymentIndex">
                                #${ index }
                            </label>
                            <label class="col-md-2 form-label my-auto paymentPriceRow">
                                <span>${payment}</span>
                                <input class="form-control d-none" data-type="currency"
                                    name="paymentprice[]" type="text" min="1" step="any"
                                    value="${payment}"
                                    required >
                            </label>
                            <div class="col-md-3 form-label my-auto paymentDateRow">
                                <span>${ dateFormat}</span>
                                <input class="form-control d-none" name="paymentdate[]" type="date"
                                    value="${ date }" required ></div>
                            <label class="col-md-2 form-label my-auto paymentMethodRow ">
                                <span>
                                    ${method == "1" ? "Automatic Withdrawal" : "Bank Transfer"}
                                </span>
                                <select class="form-control d-none" name="paymentmethod[]" required >
                                    <option value="">
                                        {{ ucfirst(trans('words.contract_payment_method')) }}
            </option>
            <option value="1" ${method == 1 ? "selected" : ""}>
                                        {{ ucfirst(trans('words.contract_payment_method_1')) }}
            </option>
            <option value="2" ${method == 2 ? "selected" : ""}>
                                        {{ ucfirst(trans('words.contract_payment_method_2')) }}
            </option>
        </select>
    </label>
    <label class="col-md-3 form-label my-auto paymentNotesRow">
        <span>${note}</span>
                                <input class="form-control d-none" name="paymentNotes[]" type="text"
                                    value="${note}" >
                            </label>
                            <label class="col-md-1 form-label row px-0">
                                <button type="button" class="btn btn-sm col-6 text-info edit-btn"
                                    onclick="toggleEditRow(${ newCount })">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                                <button type="button" data-toggle="modal" data-target="#deletePaymentModal"
                                    class="btn btn-sm col-6 text-danger" onclick="passDeleteID(${newCount})">
                                    <i class="fa fa-trash" ></i>
                                </button>
                            </label>
                        </div>
                    </div>
                </div>`;

            $('.paymentRows').append(html);

            calculateTotals()

            $(`.paymentPriceRow .form-control, .paymentDateRow .form-control, .paymentMethodRow .form-control`)
                .on("keyup change", function(e) {
                    if ($(e.target).val() == "") {
                        $(e.target).addClass("border-danger")
                    } else {
                        $(e.target).removeClass("border-danger")
                    }
                })

        }

        function calculateTotals() {
            let bankTotal = 0;
            let autoTotal = 0;

            $(".paymentRow").each(function() {
                let price = $(this).find(".paymentPriceRow input").val();
                price = convertToFloat(price)
                let method = $(this).find(".paymentMethodRow select").val()

                if (method == 1) {
                    autoTotal += price;
                } else if (method == 2) {
                    bankTotal += price;
                }
            })
            let total = bankTotal + autoTotal;
            total = convertToCurrency(total);
            bankTotal = convertToCurrency(bankTotal);
            autoTotal = convertToCurrency(autoTotal);

            $(".bank-total").html("Bank Transfer: " + bankTotal)
            $(".auto-total").html("Automatic Withdrawal: " + autoTotal)
            $(".all-total").html("All: " + total)
        }

        function passDeleteID(id) {
            confirmModal('Received Payment will be deleted',"Are you sure?","Delete","Cancel","#0275d8","#d9534f").then(function() {
                $('.paymentRow[data-id=' + id + ']').remove();
                $(".paymentRow").each(function(index) {
                    $(this).find(".paymentIndex").html(`#${index + 1}`);
                });
                calculateTotals();
            });
        }


        function toggleEditRow(id) {
            let row = $('.paymentRow[data-id=' + id + ']');

            let price = row.find(".paymentPriceRow")
            let date = row.find(".paymentDateRow")
            let method = row.find(".paymentMethodRow")
            let note = row.find(".paymentNotesRow")

            if (price.find(".form-control").val() == "") {
                price.find(".form-control").addClass("border-danger");
            }
            if (date.find(".form-control").val() == "") {
                date.find(".form-control").addClass("border-danger");
            }
            if (method.find(".form-control").val() == "") {
                method.find(".form-control").addClass("border-danger");
            }
            if (method.find(".form-control").val() == "" || price.find(".form-control").val() == "" || date.find(
                ".form-control").val() == "") {
                return;
            }

            row.find(".form-control").toggleClass("d-none");


            row.find("span").toggleClass("d-none");
            row.find(".edit-btn i").toggleClass("fa-pencil-square-o");
            row.find(".edit-btn i").toggleClass("fa-check");
            row.find(".edit-btn").toggleClass("text-info");
            row.find(".edit-btn").toggleClass("text-success");

            let dateFormat = new Date(date.find(".form-control").val())
            dateFormat = convertDateFormat(dateFormat);
            date.find("span").html(dateFormat);

            price.find("span").html(price.find(".form-control").val());
            method.find("span").html(method.find(".form-control option:selected").text());
            note.find("span").html(note.find(".form-control").val());

            calculateTotals()
        }

        Dropzone.autoDiscover = false;
        $('#contractAttachments').dropzone({
            maxFiles: 5,
            parallelUploads: 10,
            uploadMultiple: true,
            addRemoveLinks: true,
            maxFilesize: 10,
            timeout: 180000000,
            acceptedFiles: 'image/jpeg,image/png,image/jpg,.pdf,.csv,.ppt,.pptx,.doc,.docx,.xlsx,.xlsm,.xltx,.xlsb,.zip',
            url: '/attachFiles',
            success: function(file, response) {
                if (response.error) {
                    toastr.error(response.error, 'Error');
                    $('#buttonRow').show();
                } else {
                    $.each(response.data, function(key, data) {
                        $('#attachmentResponse').append(
                            '<input type="hidden" name="ticketAttachments[' + data.size +
                            ']" value="' + data.link + '"/>');
                    });
                    toastr.success(response.success, 'Success');
                    $("#buttonRow").show();
                }
            },
            init: function() {
                this.on("sending", function() {
                    $('#buttonRow').hide();
                });
            }
        });

        function submitForm(type) {

            @if ($page_type == 'update')
            if(type === "save"){
                $('#save_and_close').val(0);
            }
            if(type==="save-close"){
                $('#save_and_close').val(1);
            }
            $('#contract').find('[type="submit"]').trigger('click');
            @else
            $('#contract').find('[type="submit"]').trigger('click');
            @endif
        }
    </script>



@endsection
