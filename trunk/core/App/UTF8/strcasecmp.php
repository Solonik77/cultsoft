<?php

/**
* app_utf8::strcasecmp
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _strcasecmp($str1, $str2)
{
    if (app_utf8::is_ascii ($str1) and app_utf8::is_ascii ($str2))
        return strcasecmp ($str1, $str2);
    $str1 = app_utf8::strtolower ($str1);
    $str2 = app_utf8::strtolower ($str2);
    return strcmp ($str1, $str2);
}
