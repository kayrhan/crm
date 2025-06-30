<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyOwner extends Model
{
    protected $table="company_owner";
    protected $primary_key = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ["company_id", "owner_id"];
}
