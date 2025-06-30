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
        <title>getucon CRM - Login</title>
    </head>
    <body>
        <main class="container-fluid">
            <div class="welcome">
                <a class="header" href="/">
                    <img src="{{ asset("images/crmgetucon-light.png") }}" alt="getucon">
                </a>
                <div class="body">
                    <h1>Welcome to CRM.getucon.de (AI)</h1>
                    <hr class="w-86">
                    <p class="mt-2">Customized CRM/ERP solutions for your company. Technological progress for maximum performance.</p>
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
                            <i class="fa fa-map-marker" style="padding: 3px"></i>
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
                        Copyright © {{ \Carbon\Carbon::now()->format("Y") }} getucon GmbH - Management and Technology Consultancy
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
                    <form class="login-form" method="POST" action="{{ route("login") }}" autocomplete="off" novalidate>
                        @csrf
                        <div class="input-field">
                            <img class="left-icon" src="{{ asset("images/icons/first-login-icon.png") }}" alt="User Icon">
                            <input class="@error("email") input-validation @enderror" type="text" id="email" name="email" placeholder="E-Mail Address" autocomplete="off" value="{{ old("email") }}">
                            @error("email")
                            <small class="login-error mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-field mb-0">
                            <img class="left-icon" src="{{ asset("images/icons/second-login-icon.png") }}" alt="Password Icon">
                            <img class="right-icon" id="show-password" src="{{ asset("images/icons/third-login-icon.png") }}" alt="Show Password Icon" onclick="switchPasswordType(this.id)">
                            <img class="right-icon d-none" id="hide-password" src="{{ asset("images/icons/fourth-login-icon.png") }}" alt="Hide Password Icon" onclick="switchPasswordType(this.id)">
                            <input class="@error("password") input-validation @enderror" type="password" id="password" name="password" placeholder="Password" autocomplete="off" value="{{ old("password") }}">
                            @error("failed")
                            <small class="login-error mt-2">{{ $message }}</small>
                            @enderror
                            @error("password")
                            <small class="login-error mt-2">{{ $message }}</small>
                            @enderror
                            @error("inactive")
                            <small class="login-error mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-field flex-row-reverse justify-content-between align-items-center">
                            <div class="forget">
                                <a href="{{ route("forgot.password") }}">Forgot Password?</a>
                            </div>
                        </div>
                        <button type="submit">Login</button>
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
                    }, 5000);
                @elseif(session()->has("reset"))
                    $("#modal-successful").modal("show");
                    $('#modal-successful .modal-body p').text("Your password was successfully reset!");
                    setTimeout(function () {
                        $("#modal-successful").modal("hide");
                    }, 5000);
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
        </script>
    </body>
</html>