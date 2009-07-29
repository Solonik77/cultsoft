<?php

/**
 * App_Utf8::trim
 *
 * @author Kohana Team
 * @copyright (c) 2007 Kohana Team
 * @copyright (c) 2005 Harry Fuecks
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _trim($str, $charlist = null)
{
	if ($charlist === null)
	return trim($str);
	return App_Utf8::ltrim(App_Utf8::rtrim($str, $charlist), $charlist);
}
