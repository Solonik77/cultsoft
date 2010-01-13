<?php

defined('DOC_ROOT') or exit('No direct script access.');
require_once 'libs/kohana/system/classes/kohana/core.php';

/**
 * Ectorus
 * @author Dmytro Denysenko
 * @copyright (c) 2010 Dmytro Denysenko
 */
class Kohana extends Kohana_Core
{
    
    public static function get_config()
    {
        return self::$config;
    }
    
    public static function set_config(Kohana_Config $config)
    {
        self::$config = $config;
    }
    
    public static function get_cache_directory()
    {
           return self::$cache_dir;
    }
        
    public static function set_cache_directory($directory)
    {
        if (is_dir($directory) and is_writeable($directory)) {
            Kohana::$cache_dir = realpath($directory);
            return TRUE;
        }
        return FALSE;
    }
    
    public static function set_base_url($url)
    {
        Kohana::$base_url = $url;
    }
    
    public static function logger()
    {
        return self::$log;
    }
}
