<?php
function truncate($string, $limit = 50)
{
    if (strlen($string) > $limit) {
        return substr($string, 0, $limit) . '...';
    }
    return $string;
}
