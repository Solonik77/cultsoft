<?php

/**
 * V_UTF8::str_split
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _str_split ($str, $split_length = 1)
{
    $split_length = (int) $split_length;
    if (V_UTF8::is_ascii($str)) {
        return str_split($str, $split_length);
    }
    if ($split_length < 1) {
        return false;
    }
    if (V_UTF8::strlen($str) <= $split_length) {
        return array($str);
    }
    preg_match_all('/.{' . $split_length . '}|[^\x00]{1,' . $split_length . '}$/us', $str, $matches);
    return $matches[0];
}
