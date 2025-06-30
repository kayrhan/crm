<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportantDecision extends Model
{
    use SoftDeletes;
    protected $table = "important_decisions";
    public $timestamps = true;

    public function getAttachments()
    {
        $attachments = Attachment::where("type", "important-decisions")->where("owner_id", $this->id)->get();
        return $attachments;
    }

    public function user(){
        return $this->belongsTo(User::class, "add_by", "id");
    }

    public function getDate()
    {
        return Carbon::parse($this->created_at)->format("d.m.Y H:i:s");
    }
}
