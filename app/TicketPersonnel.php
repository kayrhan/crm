<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPersonnel extends Model {
    // Bu model, ikincil olarak eklenen Assigned User'ların tablosu için kullanılır.
    use SoftDeletes;
    protected $table = 'ticket_personnels';
}
