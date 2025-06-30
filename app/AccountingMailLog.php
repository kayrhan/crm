<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingMailLog extends Model
{
    protected $table = "accounting_mail_logs";

    public function getUser(){
        return $this->hasOne("App\User","id","send_by");
    }
}
