<?php

namespace Ectorus;

defined('DOC_ROOT') or exit('No direct script access.');

/**
* Ectorus
*
* @author Dmytro Denysenko
* @copyright (c) 2010 Dmytro Denysenko
*/
class Config {
    // Singleton static instance
    protected static $_instance;
    protected $_config;

    /**
    * Enforce singleton constructor
    */
    final private function __construct()
    {
        \Kohana::set_config(\Kohana_Config::instance());
        $this->_config = \Kohana::get_config();
        // Attach a file readers to config. Multiple readers are supported.
        if (file_exists(APP_PATH . 'etc' . DS . 'local.php')) {
            $this->attach_reader(new Config\Reader_Database_Decorator_Cache(new Config\Reader_Database()));
            $this->attach_reader(new Config\Reader_File());
        } else {
            $this->attach_reader(new Config\Reader_Installer());
        }
        $this->attach_reader(new \Kohana_Config_File());
    }

    final private function __clone()
    {
    }

    public function get_config()
    {
        return $this->_config;
    }

    public function attach_reader(\Kohana_Config_Reader $reader)
    {
        if ($this->get_config()) {
            $this->get_config()->attach($reader);
        }
        return $this;
    }

    public function get($group)
    {
        return \Kohana::config($group);
    }

    /**
    * Get the singleton instance of Ectorus\Config.
    *
    * @return Ectorus_Config
    */
    public static function instance()
    {
        if (self::$_instance === null) {
            // Create a new instance
            self::$_instance = new Config();
        }
        return self::$_instance;
    }
}
