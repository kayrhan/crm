<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function array_differ($value,$array){

        if (in_array($value, $array) && !is_array($value)) {
            $array = array_diff($array, [$value]);
        }
        return $array;
    }
}
