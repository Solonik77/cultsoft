<?php
/**
* Controls headers that effect client caching of pages
*
* 
$Id: expires.php 4272 2009-04-25 21:47:26Z zombor 
$
*
* @package Core
* @author Kohana Team
* @copyright (c) 2007-2008 Kohana Team
* @license http://kohanaphp.com/license.html
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class V_Helper_Expires {
    /**
    * Sets the amount of time before a page expires
    *
    * @param integer 
$ Seconds before the page expires
    * @return boolean
    */
    public static function set(
$seconds = 60)
    {
        if (V_Helper_Expires::check_headers()) {
            
$now = 
$expires = time();
            // Set the expiration timestamp
            
$expires += 
$seconds;
            // Send headers
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s T',                   
$now));
            header('Expires: ' . gmdate('D, d M Y H:i:s T',                   
$expires));
            header('Cache-Control: max-age=' . 
$seconds);
            return 
$expires;
        }
        return false;
    }

    /**
    * Checks to see if a page should be updated or send Not Modified status
    *
    * @param integer 
$ Seconds added to the modified time received to calculate what should be sent
    * @return bool FALSE when the request needs to be updated
    */
    public static function check(
$seconds = 60)
    {
        if (! empty(
$_SERVER['HTTP_IF_MODIFIED_SINCE']) and V_Helper_Expires::check_headers()) {
            if ((
$strpos = strpos(
$_SERVER['HTTP_IF_MODIFIED_SINCE'],';')) !== false) {
                // IE6 and perhaps other IE versions send length too, compensate here
                
$mod_time = substr(
$_SERVER['HTTP_IF_MODIFIED_SINCE'],                   0,                   
$strpos);
            } else {
                
$mod_time = 
$_SERVER['HTTP_IF_MODIFIED_SINCE'];
            }
            
$mod_time = strtotime(
$mod_time);
            
$mod_time_diff = 
$mod_time + 
$seconds - time();
            if (
$mod_time_diff > 0) {
                // Re-send headers
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s T',
$mod_time));
                header('Expires: ' . gmdate('D, d M Y H:i:s T',time() + 
$mod_time_diff));
                header('Cache-Control: max-age=' . 
$mod_time_diff);
                header('Status: 304 Not Modified',                   true,                   304);
                // Exit to prevent other output
                exit();
            }
        }
        return false;
    }

    /**
    * Check headers already created to not step on download or Img_lib's feet
    *
    * @return boolean
    */
    public static function check_headers()
    {
        foreach(headers_list() as 
$header) {
            if ((session_cache_limiter() == '' and stripos(
$header,    'Last-Modified:') === 0) or stripos(
$header, 'Expires:') === 0) {
                return false;
            }
        }
        return true;
    }
} // End expires
