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
        <title>getucon CRM - Reset Password</title>
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
                    <form class="login-form password-reset-form" action="{{ route("forgot.password.update") }}" method="POST" autocomplete="off" novalidate>
                        @csrf
                        <div class="input-field">
                            <img class="left-icon" src="{{ asset("images/icons/second-login-icon.png") }}" alt="New Password Icon">
                            <img class="right-icon" id="show-new-password" src="{{ asset("images/icons/third-login-icon.png") }}" alt="Show New Password Icon" onclick="switchPasswordType(0, this.id)">
                            <img class="right-icon d-none" id="hide-new-password" src="{{ asset("images/icons/fourth-login-icon.png") }}" alt="Hide New Password Confirm Icon" onclick="switchPasswordType(0, this.id)">
                            <input type="password" id="new-password" name="new_password" placeholder="Enter your new password." autocomplete="off">
                            <small class="login-error mt-2 d-none">E-Mail address is required!</small>
                        </div>

                        <div class="input-field">
                            <img class="left-icon" src="{{ asset("images/icons/second-login-icon.png") }}" alt="New Password Confirm Icon">
                            <img class="right-icon" id="show-new-password-confirm" src="{{ asset("images/icons/third-login-icon.png") }}" alt="Show New Password Confirm Icon" onclick="switchPasswordType(1, this.id)">
                            <img class="right-icon d-none" id="hide-new-password-confirm" src="{{ asset("images/icons/fourth-login-icon.png") }}" alt="Hide New Password Confirm Icon" onclick="switchPasswordType(1, this.id)">
                            <input type="password" id="new-password-confirm" name="new_password_confirm" placeholder="Confirm your new password." autocomplete="off">
                            <small class="login-error mt-2 d-none">New password's confirmation is required!</small>
                        </div>

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="button" onclick="resetPassword()">Reset</button>
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
                    setTimeout(function() {
                        $("#modal-successful").modal("hide");
                    }, 3000);
                @endif
            });

            function switchPasswordType(operation, id) {
                if(operation === 0) {
                    let password = document.getElementById("new-password");

                    if(id === "show-new-password") {
                        $("#show-new-password").addClass("d-none");
                        $("#hide-new-password").removeClass("d-none");
                        password.style.fontSize = "14px";
                        password.type = "text";
                    }
                    else {
                        $("#show-new-password").removeClass("d-none");
                        $("#hide-new-password").addClass("d-none");
                        password.style.fontSize = "12px";
                        password.type = "password";
                    }
                }
                else {
                    let password = document.getElementById("new-password-confirm");

                    if(id === "show-new-password-confirm") {
                        $("#show-new-password-confirm").addClass("d-none");
                        $("#hide-new-password-confirm").removeClass("d-none");
                        password.style.fontSize = "14px";
                        password.type = "text";
                    }
                    else {
                        $("#show-new-password-confirm").removeClass("d-none");
                        $("#hide-new-password-confirm").addClass("d-none");
                        password.style.fontSize = "12px";
                        password.type = "password";
                    }
                }
            }

            function removeValidation() {
                $("input").on("input", function() {
                    $(this).removeClass('input-validation');
                    $(this).siblings('.login-error').addClass('d-none');
                });
            }

            function resetPassword() {

                let isValidated = true;

                $('.input-field input').each(function () {

                    if($(this).val() == "" || $(this).val() == null) {

                        $(this).addClass('input-validation');
                        $(this).siblings('.login-error').removeClass('d-none');
                        isValidated = false;

                    }

                });

                let password = $('input[name="new_password"]');
                let confirmPassword = $('input[name="new_password_confirm"]');
                const validatePassword = (password) => {
                    return password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/);
                };

                if(!validatePassword(password.val()) && password.val() != '') {
                    password.addClass('input-validation');
                    password.siblings('.login-error').removeClass('d-none');
                    password.siblings('.login-error').text("Password must be at least 8 characters and must contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character!");
                    isValidated = false;
                }
                else if(!validatePassword(password.val()) && password.val() == '') {
                    password.siblings('.login-error').text("New Password is required!");
                    isValidated = false;
                }

                if(password.val() != confirmPassword.val() && confirmPassword.val() != '') {
                    confirmPassword.addClass('input-validation');
                    confirmPassword.siblings('.login-error').removeClass('d-none');
                    confirmPassword.siblings('.login-error').text("Passwords do not match!");
                    isValidated = false;
                }
                else if(password.val() != confirmPassword.val() && confirmPassword.val() == '') {
                    confirmPassword.addClass('input-validation');
                    confirmPassword.siblings('.login-error').removeClass('d-none');
                    confirmPassword.siblings('.login-error').text("New password confirmation is required!");
                    isValidated = false;
                }

                if(isValidated) {
                    $(".password-reset-form").submit();
                }
            }

        </script>
    </body>
</html>