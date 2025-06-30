<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalityVisitor extends Model {

    use SoftDeletes;
    protected $table = "hospitality_visitors";

    public function hospitality() {
        return $this->belongsTo(Hospitality::class);
    }

    public function delete() {
        $this->deleted_by = auth()->id();
        $this->save();

        return parent::delete();
    }
}