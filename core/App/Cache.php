<?php
/**
* Provide simple sinleton interface to Zend_Cache different objects of backends.
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class App_Cache {
    protected static $instance = null;
    protected $cache = null;

    public static function getInstance($backend = 'file')
    {
        if (App_Cache::$instance == null) {
            new App_Cache ();
        }
        if (! is_string ($backend)) {
            return null;
        }
        if (App_Cache::$instance->cache->$backend instanceof Zend_Cache_Core) {
            return App_Cache::$instance->cache->$backend;
        } else {
            return null;
        }
    }

    /**
    * Constructor
    */
    public function __construct()
    {
        if (App_Cache::$instance === null) {
            $frontendOptions = array ('lifetime' => App::Config ()->cache_lifetime, 'automatic_serialization' => true);

            $backendOptions = array ('cache_dir' => App::Config ()->syspath->cache . '/');

            $this->cache->file = Zend_Cache::factory ('Core', 'File', $frontendOptions, $backendOptions);
            App_Cache::$instance = $this;
        }
    }

    public function __call($method, $args)
    {
        if ($this->cache->file instanceof Zend_Cache_Core) {
            return call_user_func_array (array ($this->cache->file, $method), $args);
        } else {
            return null;
        }
    }
}
