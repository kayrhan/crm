<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospitality extends Model {

    use SoftDeletes;
    protected $table = "hospitality";

    public function hospitalityVisitors() {
        return $this->hasMany(HospitalityVisitor::class);
    }

    public function delete() {
        foreach($this->hospitalityVisitors()->get() as $visitor) { // Bu kayda ait misafirleri de siliyoruz.
            $visitor->deleted_by = auth()->id();
            $visitor->save();
            $visitor->delete();
        }

        $this->deleted_by = auth()->id(); // Silen kişinin kaydını alıyoruz.
        $this->save();
        return parent::delete();
    }
}