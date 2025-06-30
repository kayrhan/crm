@extends('layouts.master')
@section('css')
    <!--INTERNAL Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">
                {{ ucfirst(trans('words.add_organization')) }}
            </h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">
                        <i class="fe fe-file-text mr-2 fs-14"></i>
                        {{ ucfirst(trans('words.organizations')) }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">
                        {{ ucfirst(trans('words.add_organization')) }} </a>
                </li>
            </ol>
        </div>
    </div>
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
                    <h3 class="card-title">{{ ucfirst(trans('words.organization')) }}
                        {{ ucfirst(trans('words.information')) }}</h3>
                    <div>
                        <a href="{{ url('/organizations/') }}" class="btn btn-info">
                            <i class="fa fa-backward mr-1"></i>{{ ucfirst(trans('words.back')) }}</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('organizations.components.organization-form', ["page_type" => "add"])
                </div>
            </div>
        </div>
    </div>
    </div><!-- end app-content-->
    </div>
@endsection
@section('js')
    @stack('organization-script')
@endsection
