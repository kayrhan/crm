<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public function owner_companies(){
        return $this->belongsToMany(Company::class, "company_owner", "company_id", "owner_id");
    }

    public function sub_companies(){
        return $this->belongsToMany(Company::class, "company_owner", "owner_id", "company_id");
    }
}
