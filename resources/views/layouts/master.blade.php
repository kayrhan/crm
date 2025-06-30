<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta content="getucon CRM" name="description">
	<meta content="getucon Software Development Team" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	@include('layouts.head')
</head>
<body class="app sidebar-mini">
	<div id="global-loader">
		<img src="{{URL::asset('assets/images/svgs/loader.svg')}}" alt="loader">
        <p id="createTicketMessage" style="position: absolute;right: 0;bottom: 0;top: 50%;left: 0;margin: 0 auto;text-align: center; display: none">Ticket creating and e-mail sending...</p>
	</div>
	<div class="page">
		<div class="page-main">
			@include('layouts.aside-menu')
			<div class="app-content main-content">
				<div class="side-app">
					@include('layouts.header')
					@yield('page-header')
					@yield('content')
				</div>
            </div>
        </div>
    </div>
    <div class="sweet-overlay" tabindex="-1" style="display: none;"></div>
    <div class="sweet-alert hideSweetAlert accounting-sweet-alert-layout" data-custom-class="" data-has-cancel-button="true" data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop" data-timer="null" style="display: none; margin:0!important; opacity: -0.02; left:220px!important;padding: 25px;">
        <div class="row">
            <div class="col-md-6 pb-4">
                <div class="card h-100 m-0 accounting-sweet-alert-layout">
                    <div class="card-body text-left">
                        <h4 class="accounting-sweet-alert-title">{{ trans('words.accounting') }}</h4>
                        <ul class="popup-menu-links">
                            <li>
                                <a href="{{ url('/transactions') }}" target="_blank">Transactions</a>
                            </li>
                            <li>
                                <a href="{{ url('/bills') }}" target="_blank">Change Ticket's Status (List)</a>
                            </li>
                            <li>
                                <a href="{{ url('/reports') }}" target="_blank">Reports (Export PDF/XLS)</a>
                            </li>
                            <li>
                                <a href="{{ url('/hospitality-receipt') }}" target="_blank">Bewirtungsbeleg</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if (in_array(auth()->id(), [5, 9, 81, 82, 86, 119, 126, 158, 161, 162, 199, 201, 202]))
                <div class="col-md-6 pb-4">
                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                        <div class="card-body text-left">
                            <h4 class="accounting-sweet-alert-title">getucon GmbH</h4>
                            <ul class="popup-menu-links">
                                <li>
                                    <a href="{{ url('/getucon/accounting/offer') }}" target="_blank">Offer</a>
                                </li>
                                <li>
                                    <a href="{{ url('/getucon/accounting/proforma') }}" target="_blank">Proforma</a>
                                </li>
                                <li>
                                    <a href="{{ url('/getucon/accounting/invoice') }}" target="_blank">Invoice</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if (in_array(auth()->id(), [5, 86, 119, 158, 161, 199, 201, 202]))
                <div class="col-md-4 pb-4">
                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                        <div class="card-body text-left">
                            <h4 class="accounting-sweet-alert-title">getucon Ltd. (TR)</h4>
                            <ul class="popup-menu-links">
                                <li>
                                    <a href="{{ url('/accounting-tr/getucon-tr/offer') }}" target="_blank">Offer</a>
                                </li>
                                <li>
                                    <a href="{{ url('/accounting-tr/getucon-tr/invoice') }}" target="_blank">Invoice</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pb-4">
                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                        <div class="card-body text-left">
                            <h4 class="accounting-sweet-alert-title">Guler Consulting Ltd. (TR)</h4>
                            <ul class="popup-menu-links">
                                <li>
                                    <a href="{{ url('/accounting-tr/guler-consulting/offer') }}" target="_blank">Offer</a>
                                </li>
                                <li>
                                    <a href="{{ url('/accounting-tr/guler-consulting/invoice') }}" target="_blank">Invoice</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pb-4">
                    <div class="card h-100 m-0 accounting-sweet-alert-layout">
                        <div class="card-body text-left">
                            <h4 class="accounting-sweet-alert-title">MediaKit Production A.Ş.</h4>
                            <ul class="popup-menu-links">
                                <li>
                                    <a href="{{ url('/accounting-tr/media-kit/offer') }}" target="_blank">Offer</a>
                                </li>
                                <li>
                                    <a href="{{ url('/accounting-tr/media-kit/invoice') }}" target="_blank">Invoice</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('layouts.footer')
    @include('layouts.footer-scripts')
</body>
</html>
