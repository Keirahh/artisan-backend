<?php

namespace App\Utilities;

/**
 * @param $date
 * @return array
 */

class FormatDate
{
    function formatDate($date)
    {
        $rst = [];

        $rst['source'] = $date;
        $rst['day'] = $date->format('d');
        $rst['month'] = $date->format('m');
        $rst['year'] = $date->format('Y');
        $rst['hours'] = $date->format('H');
        $rst['minutes'] = $date->format('i');

        return $rst;
    }
}
