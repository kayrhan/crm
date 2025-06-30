<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Discussion;
use App\Helpers\Helper;
use App\TicketAttachment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class AttachmentController extends Controller
{
    public function uploadFiles(Request $request)
    {
        try {
            $data = [];
            $i = 0;
            $type = $request->type;
            if ($type == 'single') {
                $file = $request->file('file');
                $fileOriginalName = $file->getClientOriginalName();
                $fileOriginalName = (str_replace(['Ğ', 'ğ', 'Ş', 'ş', 'Ü', 'ü', 'Ö', 'ö', ' ', '?', 'ä', 'å', '%', '/', '!', '.'], ['g', 'g', 's', 's', 'u', 'u', 'o', 'o', '-', '-', 'a', 'a', '-', '-', '-', '-'], $fileOriginalName));
                $extension = $file->getClientOriginalExtension();
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
                    $fileOriginalName = (str_replace(['Ğ', 'ğ', 'Ş', 'ş', 'Ü', 'ü', 'Ö', 'ö', ' ', '?', 'ä', 'å', '%', '/', '!', '.'], ['g', 'g', 's', 's', 'u', 'u', 'o', 'o', '-', '-', 'a', 'a', '-', '-', '-', '-'], $fileOriginalName));
                    $extension = $file->getClientOriginalExtension();
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
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
            return ['error' => 'Something went wrong'];
        }
    }

    public function delete(Request $req)
    {
        $id = $req->id;
        try {
            $attachment = Attachment::find($id);
            $attachment->delete();
            return response()->json("success");
        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function togglePrivate(Request $req)
    {
        $id = $req->id;
        try {
            $attachment = Attachment::find($id);
            $attachment->private = $attachment->private == 1 ? 0 : 1;
            $attachment->update();

            return response()->json($attachment->private);
        } catch (\Throwable $th) {
            return response()->json("error");
        }
    }


    public function commentAttachmentDownloadAll(Request $request){

        $discussion = Discussion::where("id",$request->discussion_id)->firstOrFail();
        $commentAttachments = TicketAttachment::where("discussion_id",$request->discussion_id)->get();



        try {
            $zip = new \ZipArchive();
            $tempFileName = "temporary-file-".time().".zip";
            $zipPath = storage_path("app/tempfiles/".$tempFileName);

            $res = $zip->open($zipPath,  ZipArchive::CREATE);

            if($res) {

                foreach ($commentAttachments as $commentAttachment) {
                    $path = storage_path('app/uploads/' . $commentAttachment->attachment);

                    if (file_exists($path)) {
                        $zip->addFile($path,$commentAttachment->attachment);
                    }
                }

                $zip->close();

            }else{

                abort(500);
            }
            $discussionDate = Carbon::parse($discussion->created_at)->format("d-m-Y-H-i");
            $returnFileName = "$discussion->ticket_id - Comment Attachments on $discussionDate.zip";

            return response()->file($zipPath,["Content-Type"=>"application/zip","Content-Disposition"=>"attachment;filename={$returnFileName}"]);
        }catch (\Exception $e){
            Helper::create_debug_log(__CLASS__,__FUNCTION__,"",1,$e->getMessage());
                abort(500);
        }

    }
    public function attachmentDownloadAll(Request $request){

        $allAttachments = TicketAttachment::where("ticket_id",$request->ticket_id)->get();

        try {
            $zip = new \ZipArchive();
            $tempFileName = "temporary-file-".time().".zip";
            $zipPath = storage_path("app/tempfiles/".$tempFileName);

            $res = $zip->open($zipPath,  ZipArchive::CREATE);

            if($res) {

                foreach ($allAttachments as $attachment) {
                    $path = storage_path('app/uploads/' . $attachment->attachment);

                    if (file_exists($path)) {
                        $zip->addFile($path,$attachment->attachment);
                    }
                }

                $zip->close();

            }else{

                abort(500);
            }

            $returnFileName = "#$request->ticket_id - Ticket Attachments.zip";

            return response()->file($zipPath,["Content-Type"=>"application/zip","Content-Disposition"=>"attachment;filename={$returnFileName}"]);
        }
        catch(\Exception $e) {
            Helper::create_debug_log(
                __CLASS__
                ,__FUNCTION__,
                "Something went wrong while trying to download all the attachments by using 'Download' button on Ticket's Attachment Section!",
                1,
                $e->getMessage() . " Line:" . $e->getLine()
            );

            abort(500);
        }
    }
}
