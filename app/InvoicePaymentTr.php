<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePaymentTr extends Model
{
    use SoftDeletes;
    protected $table = "invoice_payments_tr";
}
