<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketEffortLog extends Model {
    use SoftDeletes;
    protected $table = "ticket_effort_logs";
}