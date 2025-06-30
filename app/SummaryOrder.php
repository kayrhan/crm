<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SummaryOrder extends Model
{
    protected $table = "summary_orders";
    public $timestamps = false;

    public function getUsername()
    {
        $user = User::where("id",$this->user_id)->first();

        $username = ($user->first_name??"") . " " . ($user->surname??"");
        return $username;
    }
}
