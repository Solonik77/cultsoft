<?php

/**
* V_UTF8::trim
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007 Kohana Team
* @copyright (c) 2005 Harry Fuecks
* @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
*/
function _trim(
$str, 
$charlist = null)
{
    if (
$charlist === null)
        return trim(
$str);
    return V_UTF8::ltrim(V_UTF8::rtrim(
$str, 
$charlist),       
$charlist);
}
