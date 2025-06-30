<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostBoxAttachment extends Model
{
    use SoftDeletes;
    protected $table = 'postbox_attachments';
}
