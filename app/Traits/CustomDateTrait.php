<?php

namespace App\Traits;

use Carbon\Carbon;

trait CustomDateTrait
{
    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * parse a datetime or date/time string to a certain format.
     *
     * @param String $value
     * @param String $format
     * @param String|null $defaultVal
     *
     * @return string|null $rtn
     */
    public static function formatStrDateTime(string $value, string $format, ?string $defaultVal)
    {
        $rtn = $defaultVal;

        if (!empty($value)) {
            $rtn = Carbon::parse($value)->format($format);
        }

        return $rtn;
    }

    /**
     * combine date and time strings and parse to a certain format.
     *
     * @param String $date
     * @param String $time
     * @param String $format
     * @param String|null $defaultVal
     *
     * @return string|null $rtn
     */
    public static function formatStrDateAndTime(?string $date, ?string $time, string $format, ?string $defaultVal)
    {
        $rtn = $defaultVal;
        if (!empty($date) || !empty($time)) {
            $dateTime = $date . str_pad($time, 6, '0');
            $rtn = Carbon::parse($dateTime)->format($format);
        }

        return $rtn;
    }
}
