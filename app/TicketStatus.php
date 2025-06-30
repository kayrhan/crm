<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model {
    protected $table = "tickets_status";

    function getTicketStatusNameAttribute() {
        $status = Status::query()->where("id", $this->status)->first();
        return $status->name;
    }

    function getStatusUserNameAttribute() {
        $user = User::query()->where("id", $this->add_by)->first();

        if($user) {
            return $user->first_name . " " . $user->surname;
        }
        else {
            return "-";
        }
    }

    public function getUser() {
        $user = User::query()->where("id", $this->add_by)->first();

        if($user) {
            return ($user->first_name ?? "-") . " " . ($user->surname ?? "-");
        }
        else {
            return "-";
        }
    }

    protected $appends = ["TicketStatusName", "StatusUserName"];
}