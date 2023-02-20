<?php

namespace App\Helper;

use Carbon\Carbon;

class DateHelper
{
    /**
     * @param string $filenameWithoutExtension
     *
     * @return Carbon|false
     */
    public static function createDateTime(string $filenameWithoutExtension)
    {
        $dateTimeString = substr($filenameWithoutExtension, -13, 8) . substr($filenameWithoutExtension, -4, 4);

        return Carbon::createFromFormat(
            'YmdHi',
            $dateTimeString
        );
    }
}