<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller {
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function login(Request $request) {
        $validator = Validator::make($request->post(), [
            "email" => ["required", "regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix"],
            "password" => "required"
        ],
            [
            "email.required" => "Email is required!",
            "email.regex" => "Please enter a valid e-mail address!",
            "password.required" => "Password is required!",
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $active_user = User::query()->where("email", $request->email)->where("in_use", 1)->first();

        if(!$active_user) {
            return redirect("/")->withErrors(["inactive" => "There is no user with such email."]);
        }

        $this->validateLogin($request);

        if(method_exists($this, "hasTooManyLoginAttempts") && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if($this->attemptLogin($request)) {
            $active_user->last_login = Carbon::now();
            $active_user->save();

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function loginIndex() {
        if(Auth::check()) {
            return redirect("/tickets");
        }
        else {
            if(isset(request()->getSession()->get("url")["intended"])) {
                $url = request()->getSession()->get("url")["intended"];
                $update_ticket = Str::contains($url, "update-ticket");
                $path = parse_url($url);

                $tickets = Str::contains($url,"tickets?due_date_personnel");
                if($update_ticket) {
                    session(["update-ticket" => $path["path"]]);
                }

                if($tickets){
                    if(isset($path["query"]))
                        session(["tickets"=>$path["path"]."?".$path["query"]]);
                }
            }

            return view("auth.login");
        }
    }

    public function logout(Request $request) {
        if(Auth::check()) {
            Auth::logout();
            return redirect("/");
        }
    }

    protected function authenticated(Request $request, $user) {
        return redirect("/tickets");
    }
}