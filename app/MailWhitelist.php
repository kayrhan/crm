<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailWhitelist extends Model
{
    public $timestamps = false;
    protected $table = 'mail_whitelist';
    
}
