<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function addAttachments($attachments, $owner_id){
        if ($attachments) {
            foreach ($attachments as $key => $file) {
                $attachment = new Attachment();
                $attachment->owner_id = $owner_id;
                $attachment->attachment = $file["link"];
                $attachment->type = $file["type"];
                $attachment->size = $key;
                if (in_array(auth()->user()->role_id, [5, 6, 8])) {
                    $attachment->private = false;
                }else {
                    $attachment->private = $file["isPrivate"] == "on";
                }
                $attachment->add_by = auth()->id();
                $attachment->add_ip = request()->ip();
                // dd($attachment);
                $attachment->save();
            }
        } //attachmet saving
    }
}
