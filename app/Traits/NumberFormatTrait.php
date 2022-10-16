<?php

namespace App\Traits;

trait NumberFormatTrait
{
    public function formatDecimal($number, $decimal)
    {
        return $number/ 10**$decimal;
    }
}
