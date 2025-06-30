<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FileAttachmentController extends Controller
{
    //
    public function uploadFile(Request $request)
    {
        try {
            $data = [];
            $i = 0;
            $type = $request->type;
            if ($type == 'single') {
                $file = $request->file('file');
                $fileOriginalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileOriginalName = Helper::generateFileName($fileOriginalName, $extension);
                $size = $file->getSize();
                $fileNameToStore = $fileOriginalName . '_' . time() . '.' . $extension;
                $filename = $file->storeAs('uploads/', $fileNameToStore);
                $data['name'] = $fileOriginalName;
                $data['link'] = $fileNameToStore;


                $data['size'] = $size;
                return ['success' => 'File uploaded successfully', 'data' => $data];
            } else {
                $files = $request->file('file');
                if (!$files)
                    return ['error' => 'Please attach at least one file'];
                foreach ($files as $file) {
                    $fileOriginalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileOriginalName = Helper::generateFileName($fileOriginalName, $extension);
                    $size = $file->getSize();
                    $fileNameToStore = $fileOriginalName . '_' . time() . '.' . $extension;
                    $filename = $file->storeAs('uploads/', $fileNameToStore);
                    $data[$i]['name'] = $fileOriginalName;
                    $data[$i]['link'] = $fileNameToStore;
                    $data[$i]['size'] = $size;
                    $i++;
                }
                return ['success' => 'File uploaded successfully', 'data' => $data];
            }
        } catch (\Exception $e) {

            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to attach a file!",
                9,
                $e->getMessage() . " Line:" . $e->getLine()
            );
            return ['error' => 'Something went wrong'];
        }
    }

    public function access_control($file, $rename = null)
    {

        if (!Auth::check()) {

            abort(ResponseAlias::HTTP_FORBIDDEN);
        }

        $path = 'uploads/' . $file;
        $mime_type = File::mimeType(Storage::path($path));
        if ($rename) {
            $file = $rename;
        }
        return response()->file(Storage::path($path), ["Content-Type" => $mime_type, "Content-Disposition" => "inline;filename={$file}"]);

    }

    public function tempFiles($file, $renameBase64 = null)
    {
        if (!Auth::check()) {

            abort(ResponseAlias::HTTP_FORBIDDEN);
        }

        $path = 'tempfiles/' . $file;

        if (file_exists(Storage::path($path))) {
            $mime_type = File::mimeType(Storage::path($path));

            if ($renameBase64) {
                $file = urldecode($renameBase64);
            }
            return response()->file(Storage::path($path), ["Content-Type" => $mime_type, "Content-Disposition" => "inline;filename={$file}"]);
        } else {

            abort(404);
        }
    }


}
