		<!-- Title -->
		<title>{{ "getucon CRM - ". Request::path() }}</title>

        <link rel="icon" href="{{URL::asset('assets/images/brand/fav-icon.png')}}" type="image/x-icon"/>
		<!--Bootstrap css -->
		<link href="{{URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

		<!-- Style css -->
        <link href="{{URL::asset('assets/css/accounting.css')}}" rel="stylesheet">
		<link href="{{URL::asset('assets/css/style.css?v=1')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/css/skin-modes.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

		<!-- Animate css -->
		<link href="{{URL::asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!--Sidemenu css -->
       <link href="{{URL::asset('assets/css/sidemenu.css')}}" rel="stylesheet">

		<!---Icons css-->
		<link href="{{URL::asset('assets/css/icons.css')}}" rel="stylesheet" />
		@yield('css')

		<!-- Simplebar css -->
		<link rel="stylesheet" href="{{URL::asset('assets/plugins/simplebar/css/simplebar.css')}}">

	    <!-- Color Skin css -->
		<link id="theme" href="{{URL::asset('assets/colors/color1.css')}}" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <link href="{{URL::asset('assets/plugins/datatable/custom.datatable.row.css')}}" rel="stylesheet"/>

        <!-- SWEET ALERT -->
        <link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet">
        <link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{URL::asset('assets/plugins/jquery-validation-engine/css/validationEngine.jquery.css')}}" type="text/css"/>

		{{-- Tippy Development --}}
		{{-- All of theese scripts should be handled with laravel mix --}}
        <script src="{{asset('assets/plugins/tippy/popper.min.js')}}"></script>
        <script src="{{asset('assets/plugins/tippy/tippy-bundle.umd.min.js')}}"></script>

