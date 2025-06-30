<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller {
    public function index() {
        return view("auth.passwords.forgot-password");
    }

   public function sendMail(Request $request) {
        $request->validate([
            "email" => "required|email",
        ]);

        if($request->email) {
            $isUser = User::query()->where("email", $request->email)->first();

            if($isUser) {
                $password_reset = PasswordReset::query()->where("email", $request->email)->first();
                $token = md5(uniqid(time()));

                if($password_reset) {
                    $password_reset->token = $token;
                    $password_reset->ip = request()->ip();
                    $password_reset->save();
                }
                else {
                     $new_password_reset =new PasswordReset;
                     $new_password_reset->email = $request->email;
                     $new_password_reset->token = $token;
                     $new_password_reset->ip = request()->ip();
                     $new_password_reset->save();
                }

                $data = [
                    "token" => $token,
                    "email" => $request->email
                ];

                try {
                    Mail::mailer(env("MAIL_GETUCON_MAILER"))->send("emails.forgot-password-email", $data, function($message) use($request) {
                        $message->from(env("MAIL_GETUCON_PASSWORD_RESET_FROM"), "CRM getucon Forgot Password");
                        $message->to($request->email)->subject("Reset Password");
                    });

                    return redirect("/")->with("successful", "Successful!");
                }
                catch(Exception $exception) {
                    return redirect()->route("forgot.password")->with("fail_mail", 1);
                }
            }
            else {
                return redirect()->route("forgot.password")->with("notUser", 1);
            }
        }
   }

   public function getResetPassword($token, $email) {
        $email = base64_decode($email);
        $password_reset = PasswordReset::query()->where("email", $email)->where("token", $token)->first();

        if($password_reset) {
            if($password_reset->token!="0") {
                $current_time = Carbon::now()->timestamp;
                $updated_time = Carbon::parse($password_reset->updated_at)->timestamp;
                $expired_time = 60 * 60 * 12; // 12 Hours

                if($updated_time > $current_time - $expired_time) {
                    $email = base64_encode($email);
                    return view("auth.passwords.reset-password", compact("email", "token"));
                }
                else {
                    return redirect()->route("forgot.password")->with("again", 1);
                }
            }
            else {
                return redirect()->route("forgot.password")->with("invalid_token",1);
            }
        }
        else {
           return redirect("/");
        }
   }

   public function updatePassword(Request $request) {
        try {
            $email = base64_decode($request->email);
            $user = User::query()->where("email", $email)->first();
            $password_reset = PasswordReset::query()->where("email", $email)->where("token", $request->token)->first();

            if($user && $password_reset) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                $password_reset->token = "0";
                $password_reset->save();

                return redirect("/")->with("reset", 1);
            }
            else {
                return redirect("/");
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update a password by using Forget Password! ",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
   }
}