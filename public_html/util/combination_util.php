<?php

function getAllCombinations($arr)
{
    $result = array();

    sort($arr);

    for ($i = 0; $i < pow(2, count($arr)); ++$i) {
        $bin_str = sprintf('%0'.count($arr).'d', decbin($i));
        $str = '';

        for ($j = 0; $j < strlen($bin_str); ++$j) {
            if ('1' == $bin_str[$j]) {
                $str .= $arr[$j];
            }
        }

        if (strlen($str) > 0) {
            $result[] = $str;
        }
    }

    return array_unique($result);
}
