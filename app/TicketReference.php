<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReference extends Model {
    protected $guarded = [];
    use SoftDeletes;

    public function delete() {
        $this->deleted_by = auth()->id(); // Silen kişinin kaydını alıyoruz.
        $this->save();
        return parent::delete();
    }
}