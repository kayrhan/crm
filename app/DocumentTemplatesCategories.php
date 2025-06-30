<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTemplatesCategories extends Model {
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "document_templates_categories";
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}