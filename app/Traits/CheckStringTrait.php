<?php

namespace App\Traits;

trait CheckStringTrait
{
    public function compareLowerString($str1, $str2)
    {
        return strtolower($str1) === strtolower($str2);
    }
}
