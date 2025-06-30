<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    function getStatusColorAttribute()
    {
        $color = ['background' => '', 'border' => '', 'text-color' => '#FFFFFF'];

        if ($this->id == 1) //Opened
            $color = ['background' => '#7DAA50', 'border' => '#7DAA50', 'text-color' => '#FFFFFF'];

        if ($this->id == 2) //transferred
            $color = ['background' => '#E0E0E0', 'border' => '#E0E0E0', 'text-color' => '#1A1630'];

        if ($this->id == 3) //In progress
            $color = ['background' => '#6B6B6B', 'border' => '#6B6B6B', 'text-color' => '#FFFFFF'];

        if ($this->id == 4) //Answered
            $color = ['background' => '#BA3129', 'border' => '#BA3129', 'text-color' => '#FFFFFF'];

        if ($this->id == 5) //Query
            $color = ['background' => '#AF2823', 'border' => '#AF2823', 'text-color' => '#FFFFFF'];

        if ($this->id == 6) //Done
            $color = ['background' => '#3991CE', 'border' => '#3991CE', 'text-color' => '#FFFFFF'];

        if ($this->id == 7) //Invoiced
            $color = ['background' => '#17679B', 'border' => '#17679B', 'text-color' => '#FFFFFF'];

        if ($this->id == 8) //On hold
            $color = ['background' => '#E05E02', 'border' => '#E05E02', 'text-color' => '#FFFFFF'];

        if ($this->id == 9) //Closed
            $color = ['background' => '#7966AD', 'border' => '#7966AD', 'text-color' => '#FFFFFF'];

        return $color;
    }
    protected $appends = ['StatusColor'];
}
