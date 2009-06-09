<?php

/**
 * V_UTF8::strspn
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _strspn ($str, $mask, $offset = null, $length = null)
{
    if ($str == '' or $mask == '')
        return 0;
    if (V_UTF8::is_ascii($str) and V_UTF8::is_ascii($mask))
        return ($offset === null) ? strspn($str, $mask) : (($length === null) ? strspn($str, $mask, $offset) : strspn($str, $mask, $offset, $length));
    if ($offset !== null or $length !== null) {
        $str = V_UTF8::substr($str, $offset, $length);
    }
    // Escape these characters:  - [ ] . : \ ^ /
    // The . and : are escaped to prevent possible warnings about POSIX regex elements
    $mask = preg_replace('#[-[\].:\\\\^/]#', '\\\\$0', $mask);
    preg_match('/^[^' . $mask . ']+/u', $str, $matches);
    return isset($matches[0]) ? V_UTF8::strlen($matches[0]) : 0;
}