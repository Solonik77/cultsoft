<?php

/**
* App_Utf8::rtrim
*
* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _rtrim($str, $charlist = null)
{
    if ($charlist === null)
        return rtrim($str);
    if (App_Utf8::is_ascii($charlist))
        return rtrim($str, $charlist);
    $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);
    return preg_replace('/[' . $charlist . ']++$/uD', '', $str);
}