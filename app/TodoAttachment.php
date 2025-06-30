<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodoAttachment extends Model
{
    use SoftDeletes;
    protected $table = "todo_attachments";
}
