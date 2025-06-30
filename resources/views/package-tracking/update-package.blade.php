@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('text-editor/trumbowyg.min.css') }}">
    <link rel="stylesheet" href="{{ asset('drop-zone/dropzone.css') }}">
    <style>
        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 40px;
        }

        .trumbowyg-editor p img {
            width: 400px !important;
        }

        .border-bottom {
            border-bottom: 2px solid #ebecf1;
            padding-bottom: 5px;
            margin-bottom: 5px;
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

        #ticket_id::-webkit-outer-spin-button,
        #ticket_id::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #ticket_id[type=number] {
            -moz-appearance: textfield;
        }

    </style>
@endsection
@section('page-header')
@endsection
@section('content')
    <!-- Row -->
    <div class="row" style="margin-top: 20px;">
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
                <div class="card-header">
                    <h3 class="card-title">{{ ucfirst(trans('words.package')) }} {{ ucfirst(trans('words.update')) }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <form action="{{ route('package-tracking.update.post') }}" method="POST">
                                @csrf
                                <input type="hidden" name="track_id" value="{{ $track->id }}">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.shop')) }}<span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-md-10">
                                                <input type="text" name="shop" class="form-control" placeholder="Shop"
                                                    value="{{ $track->shop }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.cargo_company')) }}</label>
                                            <div class="col-md-10">
                                                <input type="text" name="cargo_company" class="form-control"
                                                    value="{{ $track->cargo_company }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.tracking_id')) }}
                                            </label>
                                            <div class="col-md-10">
                                                <input type="text" name="tracking_id" class="form-control"
                                                    placeholder="Tracking ID" value="{{ $track->tracking_id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label class="col-md-2 form-label my-auto">Ticket
                                            </label>
                                            <div class="col-md-2 d-flex align-items-center">
                                                @if ($track->ticket_id)
                                                    <a target="_blank" class="text-primary"
                                                        href="{{ url('/update-ticket/' . $track->ticket_id) }}">
                                                        <h5 style="font-size: .9rem" class="m-0">#{{ $track->ticket_id }}</h5>
                                                    </a>
                                                    <input type="hidden" name="ticket_id"
                                                        value="{{ $track->ticket_id }}">
                                                @else
                                                    <input type="number" id="ticket_id" oninput="getTicketInfo(event)"
                                                        name="ticket_id" class="form-control" placeholder="Ticket ID">
                                                @endif
                                            </div>
                                            <div class="col-8">
                                                <input type="text" id="ticket_name" class="form-control"
                                                    placeholder="Ticket Name" value="{{ $ticket_name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($organization)
                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label for="organization" class="col-md-2 form-label my-auto">Organization<span class="text-danger">*</span></label>
                                            <div class="col-md-10">
                                                <input class="form-control" id="organization" type="text" value="{{ $organization }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.order_date')) }}<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-10">
                                                <input class="form-control" type="date" name="order_date"
                                                    value="{{ $track->order_date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.expected_delivery_date')) }}</label>
                                            <div class="col-md-10">
                                                <input class="form-control" type="date" name="expected_delivery_date"
                                                    value="{{ $track->expected_delivery_date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.status')) }}
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-10">
                                                <select name="status" class="form-control">
                                                    @foreach ($statusses as $status)
                                                        <option value="{{ $status->id }}"
                                                            @if ($status->id == $track->status) selected @endif>
                                                            {{ $status->status_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <div class="form-group row border-bottom">
                                            <label
                                                class="col-md-2 form-label my-auto">{{ ucfirst(trans('words.express')) }}
                                            </label>
                                            <div class="col-md-10">

                                                <div class="custom-controls-stacked">
                                                    <div style="display: flex;">

                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="express"
                                                                value="0" @if ($track->express == 0) checked @endif>
                                                            <span class="custom-control-label">No</span>
                                                        </label>
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="express"
                                                                value="1" @if ($track->express == 1) checked @endif>
                                                            <span class="custom-control-label">Yes</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">

                                    <div class="col-lg-8 col-md-8">

                                        <div class="form-group row ">
                                            <div class="col-lg-2 col-md-2">
                                                <label
                                                    class="form-label my-auto">{{ ucfirst(trans('words.additional_info')) }}</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea id="description" name="description"
                                                    class="form-control">{{ $track->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <button type="submit"
                                            class="btn btn-success mt-4 mb-0 float-right">{{ trans('words.save') }}</button>
                                        <a href="{{ url('/package-tracking') }}"
                                            class="btn btn-danger mt-4 mb-0 mr-4 float-right">{{ trans('words.cancel') }}</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="d-flex justify-content-end  ">
                                <div class="flex-column w-100">
                                    <div class="card-body ">
                                        <div class="latest-timeline scrollbar3" id="scrollbar3">
                                            <ul class="timeline mb-0" >
                                                @if($track->created_at)
                                                    <li class="mt-0">
                                                        <div class="d-flex"><span
                                                                class="time-data">Order Created From </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($track->created_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from <span
                                                                class="text-info">{{$user != null ? $user->first_name: "-"}} {{$user ? $user->surname: "-"}}</span>
                                                        </p>
                                                    </li>
                                                @else
                                                    <li class="mt-0">
                                                        <div class="d-flex">
                                                            <span class="time-data">Order Created From </span>
                                                            <span class="ml-auto text-muted fs-11">-</span>
                                                        </div>
                                                        <p class="text-muted fs-13">Created from
                                                            <span class="text-info">-</span>
                                                        </p>
                                                    </li>
                                                @endif
                                                @if($track->updated_at)

                                                    <li class="mb-1 updated_log">
                                                        <div class="d-flex"><span class="time-data">Last Updated </span><span
                                                                class="ml-auto text-muted fs-11">{{\Carbon\Carbon::parse($track->updated_at)->format("d.m.Y H:i:s")}}</span>
                                                        </div>
                                                        <p class="text-muted fs-13 mb-1">Last updated from <span
                                                                class="text-info">{{$updated_user != null ? $updated_user->first_name: "-"}} {{$updated_user != null ? $updated_user->surname: "-"}}</span>
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
                                        </div>
                                    </div>

                                </div>
                            </div>
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
    <script src="{{ asset('drop-zone/dropzone.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('text-editor/trumbowyg.min.js') }}"></script>
    <script>
        function getTicketInfo(e) {
            let value = e.target.value;
            let id = validateTicketId(e.target)
            if (id) {
                $(e.target).prop("readonly", true)
                $.ajax({
                    url: `/getTicket/${id}`,
                    type: 'GET',
                    data: '_token=' + $('meta[name="csrf-token"]').attr('content')
                }).done(function(data) {
                    $(e.target).prop("readonly", false)
                    $("#ticket_name").val(data.name);
                }).catch(function(err) {
                    $(e.target).prop("readonly", false)
                });
            }
        }

        function validateTicketId(target) {
            let id = target.value;
            id = id.replace(".", "")
            id = id.replace(",", "")
            id = id.replace("e", "")
            if (id && id.length >= 4) {
                id = id.slice(0, 4)
                $(target).val(id);

                return id;
            }
            $(target).val(id);
            $("#ticket_name").val("");
            return false;
        }

        $('#description').trumbowyg({
            autogrow: true,
            removeformatPasted: true,
            btns:[
                ['viewHTML'],
                ['formatting'],
                ['strong', 'em', 'del'],
                ['link'],
                ['insertImage'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['fullscreen']
            ]
        });
    </script>
@endsection
