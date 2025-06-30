<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function index($code){
        return view('errors.index')->with('code', $code);
    }
}
