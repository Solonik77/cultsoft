<?php

/**
* V_UTF8::substr_replace
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _substr_replace($str, $replacement, $offset, $length = null)
{
    if (V_UTF8::is_ascii ($str))
        return ($length === null) ? substr_replace ($str, $replacement, $offset) : substr_replace ($str, $replacement, $offset, $length);
    $length = ($length === null) ? V_UTF8::strlen ($str) : (int) $length;
    preg_match_all ('/./us', $str, $str_array);
    preg_match_all ('/./us', $replacement, $replacement_array);
    array_splice ($str_array [0], $offset, $length, $replacement_array [0]);
    return implode ('', $str_array [0]);
}
