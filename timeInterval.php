<?php

function getTimeInterval (DateTime $from, DateTime $to = null): array
{
    $from = $from->format('Y-m-d H:i:s');
    if ($to) {
        $to = $to->format('Y-m-d H:i:s');
    } else {
        $to = date("Y-m-d H:i:s");
    }

    if (strtotime($from) > strtotime($to)) {
        return ['error' => 'The end date must be greater than the start date'];
    }

    $from_unix = strtotime($from);
    $to_unix = strtotime($to);

    if ($from_unix - $to_unix > 2682000 ) {
        return ['error' => 'The maximum time range for which you can get a statement is 31 days and 1 hour'];
    }

    return [$from_unix, $to_unix];
}
