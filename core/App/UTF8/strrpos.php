<?php

/**
* app_utf8::strrpos
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _strrpos($str, $search, $offset = 0)
{
    $offset = (int) $offset;
    if (SERVER_UTF8)
        return mb_strrpos ($str, $search, $offset);
    if (app_utf8::is_ascii ($str) and app_utf8::is_ascii ($search))
        return strrpos ($str, $search, $offset);
    if ($offset == 0) {
        $array = explode ($search, $str, - 1);
        return isset ($array [0]) ? app_utf8::strlen (implode ($search, $array)) : false;
    }
    $str = app_utf8::substr ($str, $offset);
    $pos = app_utf8::strrpos ($str, $search);
    return ($pos === false) ? false : $pos + $offset;
}
