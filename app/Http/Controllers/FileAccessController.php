<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FileAccessController extends Controller
{
    public function access_control($file){


        $path = 'uploads/'.$file;

        $mime_type = File::mimeType(Storage::path($path));

        return response()->file(Storage::path($path),["Content-Type"=>$mime_type,"Content-Disposition"=>"inline;filename={$file}"]);

    }
}
