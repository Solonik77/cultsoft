<?php
/**
 * app_utf8::stristr
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _stristr ($str, $search)
{
    if (app_utf8::is_ascii($str) and app_utf8::is_ascii($search))
        return stristr($str, $search);
    if ($search == '')
        return $str;
    $str_lower = app_utf8::strtolower($str);
    $search_lower = app_utf8::strtolower($search);
    preg_match('/^(.*?)' . preg_quote($search, '/') . '/s', $str_lower, $matches);
    if (isset($matches[1]))
        return substr($str, strlen($matches[1]));
    return false;
}
