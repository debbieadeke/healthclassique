<?php

if (! function_exists('get_week_of_this_month')) {
    function get_week_of_this_month()
    {
        $currentDate = date('Y-m-d');
        list($year, $month, $day) = explode('-', $currentDate);
        $firstDay = date('Y-m-01', strtotime($currentDate));
        return ceil(($day + date('w', strtotime($firstDay))) / 7);
    }
}

if (! function_exists('get_month_and_year')) {
    function get_month_and_year($start)
    {
        if (is_array($start)) {
            return [$start[0], $start[1]];
        } else {
            $pieces = explode("-", $start);
            if (count($pieces) >= 2) { // Ensure $pieces has at least 2 elements
                return [$pieces[0], $pieces[1]];
            } else {
                // Handle the case where $start is not in the expected format
                // You might want to return an error message or handle it differently
                return false;
            }
        }
    }
}




