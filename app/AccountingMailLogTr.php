<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingMailLogTr extends Model
{
    protected $table = "accounting_mail_logs_tr";

    public function getUser(){
        return $this->hasOne("App\User","id","send_by");
    }

}
