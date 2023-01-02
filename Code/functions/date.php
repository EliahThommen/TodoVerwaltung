<?php

function checkDateInput($date)
{
    if (!strtotime($date)) {
        return false;
    }
    list($year, $month, $day) = explode('-', $date);
    $date = checkdate($month, $day, $year);
    return $date;
}
