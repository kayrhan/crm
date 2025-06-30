<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function organization()
    {
        return $this->belongsTo(Organization::class, "org_id");
    }

    public function asset_type(){
        return $this->belongsTo(AssetType::class, "asset_type_id");
    }

    public function company(){
        return $this->belongsTo(Company::class, "company_id");
    }

    public function owner_company(){
        return $this->belongsTo(Company::class, "owner_company_id");
    }
}
