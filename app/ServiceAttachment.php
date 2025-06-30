<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAttachment extends Model {
    use SoftDeletes;
    protected $table = "service_attachments";
}