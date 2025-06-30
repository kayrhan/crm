<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler {
    protected $dontReport = [];
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation"
    ];

    public function render($request, Throwable $e) {
        if($e instanceof TokenMismatchException) {
            return redirect()->route('login');
        }

        if(env("APP_ENV") != "production") {
            dd($e);
        }

        $response = parent::render($request, $e);


        if(in_array($response->status(), [401, 403, 404, 419, 429, 500])) { //TODO: Burada HTTP tarafından gönderilen dönüş koduna göre işlem yapmak sağlıklı değil. Hatanın tipine bağlı olarak işlem yapmak gerekiyor.
            return response(view("errors.index", [
                "code" => $response->status()
            ]), $response->status());
        }

        return parent::render($request, $e);
    }
}