<?php
/**
 * V_UTF8::rtrim
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _rtrim ($str, $charlist = null)
{
    if ($charlist === null)
        return rtrim($str);
    if (V_UTF8::is_ascii($charlist))
        return rtrim($str, $charlist);
    $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);
    return preg_replace('/[' . $charlist . ']++$/uD', '', $str);
}
