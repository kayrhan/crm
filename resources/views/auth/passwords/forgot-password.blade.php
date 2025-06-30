<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta content="getucon CRM" name="description">
    <meta content="getucon Software Development Team" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset("assets/images/brand/fav-icon.png") }}" rel="icon" type="image/x-icon">
    <link href="{{ asset("assets/plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/style.css") }}" rel="stylesheet">
    <title>getucon CRM - Forgot Password</title>
</head>
<body>
<main class="container-fluid">
    <div class="welcome">
        <a class="header" href="/">
            <img src="{{ asset("images/crmgetucon-light.png") }}" alt="getucon">
        </a>
        <div class="body">
            <h1>Welcome to getucon CRM!</h1>
            <hr>
            <p class="mt-2">Customised CRM/ERP solutions for your company. Technological progress for maximum performance.</p>
            <ul>
                <li class="mb-1">
                    <i class="fa fa-phone"></i>
                    <span>+49 (0) 69-34866710</span>
                </li>
                <li class="mb-1">
                    <i class="fa fa-envelope"></i>
                    <span>info@getucon.de</span>
                </li>
                <li>
                    <i class="fa fa-map-marker"></i>
                    <span>Taunusanlage 8 | 60329 Frankfurt am Main</span>
                </li>
            </ul>
        </div>
        <div class="footer">
            <ul class="links">
                <li class="mr-1">
                    <a href="https://getucon.de" target="_blank">www.getucon.de</a>
                    <span class="link-line">|</span>
                </li>
                <li class="mr-1">
                    <a href="https://getudc.de" target="_blank">www.getudc.de</a>
                    <span class="link-line">|</span>
                </li>
                <li class="mr-1">
                    <a href="https://getusys.de" target="_blank">www.getusys.de</a>
                    <span class="link-line">|</span>
                </li>
                <li class="mr-1">
                    <a href="https://getusoft.de" target="_blank">www.getusoft.de</a>
                    <span class="link-line">|</span>
                </li>
                <li class="mr-1">
                    <a href="https://getumedia.de" target="_blank">www.getumedia.de</a>
                </li>
            </ul>
            <div class="copyright">
                Copyright © 2023 getucon GmbH - Management and Technology Consultancy
            </div>
        </div>
    </div>
    <div class="operation">
        <div class="language">
            <button class="language-btn" type="button" aria-expanded="false">English</button>
        </div>
        <div class="login">
            <div class="logo">
                <img src="{{ asset("images/crmgetucon-dark.png") }}" alt="getucon GmbH">
            </div>
            <form class="login-form forget-form" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="input-field">
                    <img class="left-icon" src="{{ asset("images/icons/first-login-icon.png") }}" alt="User Icon">
                    <input type="email" id="email" name="email" placeholder="Email" autocomplete="off" value="{{ old('email') }}">
                    <small class="login-error mt-2 d-none"></small>
                </div>
                <a class="go-back" href="{{ route("login") }}">
                    <i class="fa fa-chevron-left mr-1"></i>Back
                </a>
                <button type="button" onclick="sendResetLink()">Reset Password</button>
            </form>
            <div class="default-browser">
                Download link for <a href="https://www.google.com/intl/en/chrome/" target="_blank">Google Chrome</a>
            </div>
        </div>
    </div>
</main>

<div class="modal effect-slide-in-bottom" id="modal-successful">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Successful!</h4>
                <p></p>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset("assets/js/jquery-3.5.1.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/plugins/bootstrap/js/bootstrap.bundle.min.js") }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        removeValidation();

        @if(session()->has("successful"))
        $('#modal-successful').modal('show');
        $('#modal-successful .modal-body p').text("Password reset link has been successfully sent to your E-Mail Address!");
        setTimeout(function() {
            $('#modal-successful').modal("hide");
        }, 3000);
        @elseif(session()->has("reset"))
        $("#modal-successful").modal("show");
        $('#modal-successful .modal-body p').text("Your password was successfully reset!");
        setTimeout(function () {
            $("#modal-successful").modal("hide");
        }, 3000);
        @endif

    });

    function switchPasswordType(id) {
        let password = document.getElementById("password");

        if(id === "show-password") {
            $("#show-password").addClass("d-none");
            $("#hide-password").removeClass("d-none");
            password.style.fontSize = "14px";
            password.type = "text";
        }
        else {
            $('#show-password').removeClass('d-none');
            $('#hide-password').addClass('d-none');
            password.style.fontSize = "10px";
            password.type = "password";
        }
    }

    function removeValidation() {
        $("input").on("input", function() {
            $(this).removeClass('input-validation');
            $(this).siblings('.login-error').addClass('d-none');
        });
    }

    // Send Reset Link
    function sendResetLink() {
        let isValidated = true;
        let email = $('input[name="email"]');

        const validateEmail = (email) => {

            return email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g);

        };

        if (email.val() == "" || email.val() == null) {

            email.addClass('input-validation');
            $(".login-error").removeClass('d-none');
            $(".login-error").text("{{ __('auth.email_required') }}");

            isValidated = false;

        }
        else {
            if(!validateEmail(email.val())) {
                email.addClass('input-validation');
                email.siblings('.login-error').removeClass('d-none');
                email.siblings('.login-error').text('{{ __("auth.email_regex") }}');
                isValidated = false;
            }
            else {

                isValidated = true;

            }
        }

        if(isValidated) {
            $(".forget-form").submit();
        }

    }

</script>
</body>
</html>