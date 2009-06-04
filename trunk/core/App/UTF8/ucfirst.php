<?php
/**
 * app_utf8::ucfirst
 *
 * @package Core
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _ucfirst ($str)
{
    if (app_utf8::is_ascii($str))
        return ucfirst($str);
    preg_match('/^(.?)(.*)$/us', $str, $matches);
    return app_utf8::strtoupper($matches[1]) . $matches[2];
}
