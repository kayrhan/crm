		<!-- Back to top -->
		<a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

		<!-- Jquery js-->
		<script src="{{URL::asset('assets/js/jquery-3.5.1.min.js')}}"></script>

		<!-- Bootstrap4 js-->
		<script src="{{URL::asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

		<!-- Circle-progress js-->
		<script src="{{URL::asset('assets/js/circle-progress.min.js')}}"></script>

		<!--Sidemenu js-->
		<script src="{{URL::asset('assets/plugins/sidemenu/sidemenu.js')}}"></script>

        <!-- SWEET ALERT -->
        <script src="{{URL::asset('assets/js/custom-sweet-alert.js')}}"></script>

		@yield('js')
		<!-- Simplebar JS -->
		<script src="{{URL::asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
		<!-- Custom js-->
		<script src="{{URL::asset('assets/js/custom.js')}}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="{{URL::asset('assets/js/numberFormat.js')}}"></script>
        <script src="{{URL::asset('assets/js/custom-number-format.js')}}"></script>
        <script src="{{URL::asset('assets/js/loader.js')}}"></script>

        <script src="{{URL::asset('assets/plugins/jquery-validation-engine/js/jquery.validationEngine.js')}}" type="text/javascript" charset="utf-8"></script>
        <script src="{{URL::asset('assets/plugins/jquery-validation-engine/js/languages/jquery.validationEngine-en.js')}}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ asset('assets/js/update-ticket.js') }}"></script>
        @stack("tagify")
        @stack("search-input")
        @if(auth()->user()->role_id == 1)
            <script src="{{ asset('assets/js/global-price.js') }}"></script>
        @endif
        <x-alert-modal/>