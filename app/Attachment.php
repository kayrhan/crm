<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    protected $table = 'attachments';
    public $timestamps = true;

    use SoftDeletes;

    public function getUserName()
    {
        $user = User::find($this->add_by);
        $username = "";
        if ($user) {
            $username = $user->first_name . " " . $user->surname;
        }
        return $username;
    }

    public function getDate()
    {
        $date = "";
        if ($this->created_at) {
            $date = Carbon::parse($this->created_at)->format('d.m.Y [h:i:s]');
        }
        return $date;
    }

    public function getSizeMB()
    {
        return round($this->size / 100000, 2);
    }

    public function getFileName()
    {
        return substr($this->attachment, 0, strrpos($this->attachment, '_'));
    }

    public function getFileType()
    {
        $array = explode('.', $this->attachment);
        $type = $array[count($array) - 1];
        return $type;
    }
}
