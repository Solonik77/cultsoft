<?php

namespace Ectorus; 

defined('DOC_ROOT') or exit('No direct script access.');
/**
 * Ectorus
 * @author Dmytro Denysenko
 * @copyright  (c) 2010 Dmytro Denysenko
 */

define('DS', '/');
define('PS', PATH_SEPARATOR);
define('EXT', '.php');
define('TIMENOW', time());

// Define the absolute paths for Kohana directories
define('DOCROOT', DOC_ROOT);
define('SYSPATH', FW_PATH . 'libs' . DS . 'kohana' . DS . 'system' . DS);
define('MODPATH', APP_PATH . 'code' . DS . 'modules' . DS);
define('APPPATH', APP_PATH . 'code' . DS . 'local' . DS);
/**
 * Define the start time of the application, used for profiling.
 */
define('KOHANA_START_TIME', microtime(TRUE));

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
define('KOHANA_START_MEMORY', memory_get_usage());


/* 
 * Base include paths setup
 */
$paths = array(
         APP_PATH . 'code' . DS . 'local',
         APP_PATH . 'code' . DS . 'core',
         FW_PATH,
         '.' . DS
);
$paths = implode(PS, $paths);
set_include_path($paths);
// Cleanup
unset($paths);

require_once 'ectorus/loader.php';
require_once 'ectorus/i18n.php';
require_once 'ectorus/config.php';

/**
 * Core system static class
 */
abstract class Core
{
    protected static $_run;
    public static function run()
    {
        if (self::$_run === null) {
            
        
            // Enable classes auto-loader.
            spl_autoload_register(array('Kohana' , 'auto_load'));
            spl_autoload_register(array(__CLASS__ , 'auto_load'));
            
            // Enable auto-loader for unserialization.
            ini_set('unserialize_callback_func', 'spl_autoload_call');

            // Set default server timezone
            $ER = error_reporting(~ E_NOTICE & ~ E_STRICT & ~ E_WARNING);
            if(function_exists('date_default_timezone_set')){
                date_default_timezone_set(date_default_timezone_get());
            }
            error_reporting($ER);
            
            \Kohana::init(array(
                    'errors'     => TRUE,
                    'profile'    => ('production' === APP_ENV),
                    'caching'    => ('production' === APP_ENV),
                    'cache_dir'  => VAR_PATH . 'cache',
                    'index_file' => FRONT_CONTROLLER_FILE,
            ));
            
            $bootstrap = new Bootstrap(self::config());
            $bootstrap->setup_environment()
                 ->init_modules()
                 ->init_internationalization()
                 ->init_session()
                 ->init_routes()
                 ->init_access()
                 ->init_view()
                 ->init_application_mailer()
                 ->init_debug()
                 ->execute_request()
                 ->send_response();
            
                 self::$_run = TRUE;
                 
        }
    }
    
    public static function auto_load($class)
    {
        try {
            return Loader::instance()->load_class($class);
        } catch (Exception $e) {
           throw new \Kohana_Exception($e->getMessage());
        }
    }
    
    public static function config()
    {
       return Config::instance();
    }
    
    final private function __clone()
    {
    
    }
    
	final private function __construct()
	{
		// This is a static class
	}
}