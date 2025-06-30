<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketEffortTotal extends Model {
    protected $table = "ticket_effort_totals";
    use SoftDeletes;
}