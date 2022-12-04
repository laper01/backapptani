<?php

namespace App\Helpers;

class StringHelper
{

    public static function splitTime($datetime)
    {
        return explode(' ', $datetime)[1];
    }

    public static function splitDate($datetime)
    {
        return explode(' ', $datetime)[0];
    }

    public static function formatValueDate($datetime)
    {
        $strArr = explode('-', StringHelper::splitDate($datetime));
        return "$strArr[2]/$strArr[1]/$strArr[0]";
    }

    public static function formatValueDateOnly($date)
    {
        $strArr = explode('-', $date);
        return "$strArr[2]/$strArr[1]/$strArr[0]";
    }
}
