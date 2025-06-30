<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketExternalPartner extends Model
{
    use SoftDeletes;
    protected $table = "ticket_external_partners";
}
