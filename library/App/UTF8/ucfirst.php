<?php

/**
* App_Utf8::ucfirst
*
* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _ucfirst($str)
{
    if (App_Utf8::is_ascii($str))
        return ucfirst($str);
    preg_match('/^(.?)(.*)$/us', $str, $matches);
    return App_Utf8::strtoupper($matches[1]) . $matches[2];
}
