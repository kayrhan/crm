<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalPartnerAttachments extends Model
{
    use SoftDeletes;
    protected $table = "external_partner_attachments";


}
