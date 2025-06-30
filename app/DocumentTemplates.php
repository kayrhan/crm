<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTemplates extends Model
{
    protected $table = 'document_templates';
    protected $guarded = [];
    public $timestamps = true;

}
