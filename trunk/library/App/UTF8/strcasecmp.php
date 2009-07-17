<?php

/**
* App_Utf8::strcasecmp
*

* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _strcasecmp($str1, $str2)
{
    if (App_Utf8::is_ascii($str1) and App_Utf8::is_ascii($str2))
        return strcasecmp($str1, $str2);
    $str1 = App_Utf8::strtolower($str1);
    $str2 = App_Utf8::strtolower($str2);
    return strcmp($str1, $str2);
}
