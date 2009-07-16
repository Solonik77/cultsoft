<?php
/**
* Security helper class.
*
* $Id: security.php 3769 2008-12-15 00:48:56Z zombor $
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007-2008 Kohana Team
* @license http://kohanaphp.com/license.html
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class V_Helper_Security {
    /**
    * Sanitize a string with the xss_clean method.
    *
    * @param string $ string to sanitize
    * @return string
    */
    public static function xss_clean($str)
    {
        return App_Input::instance()->xss_clean ($str);
    }

    /**
    * Remove image tags from a string.
    *
    * @param string $ string to sanitize
    * @return string
    */
    public static function strip_image_tags($str)
    {
        return preg_replace ('#<img\s.*?(?:src\s*=\s*["\']?([^"\'<>\s]*)["\']?[^>]*)?>#is', '$1', $str);
    }

    /**
    * Remove PHP tags from a string.
    *
    * @param string $ string to sanitize
    * @return string
    */
    public static function encode_php_tags($str)
    {
        return str_replace (array ('<?', '?>'), array ('&lt;?', '?&gt;'), $str);
    }
} // End security
