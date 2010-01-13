<?php

namespace Ectorus;

defined('DOC_ROOT') or exit('No direct script access.');

/**
 * Ectorus
 * @author Dmytro Denysenko
 * @copyright (c) 2010 Dmytro Denysenko
 */

class Loader
{
    	// Singleton static instance
	protected static $_instance;
	
    /**
     * Get the singleton instance of Ectorus_Loader.
     *
     * @return  Ectorus_Loader
     */
    public static function instance()
    {
        if (self::$_instance === NULL) {
            // Create a new instance
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function load_class ($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        // autodiscover the path from the class name
        $file = str_replace('_', DS, $class) . '.php';
        $this->_file_security_check($file);
        
        // Base include paths
        $paths = explode(PS, get_include_path());
        foreach ($paths as $path) {
            $path = rtrim($path) . DS;
            if (is_file($path . $file)) {
                return include $file;
            } elseif (is_file($path . 'libs' . DS . $file)) {
                return include $path . 'libs' . $file;
            } elseif (is_file(strtolower($path . $file))) {
                return include $path . $file;
            }
        }

        if (! class_exists($class, false) && ! interface_exists($class, false)) {
            throw new Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }
	
    final private function __construct()
    {        
    }
    
    final private function __clone()
    {}
    
    /**
     * Ensure that filename does not contain exploits
     *
     * @param  string $filename
     * @return void
     * @throws Exception
     */
    protected function _file_security_check($filename)
    {
        /**
         * Security check
         */
        if (preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $filename)) {
            throw new Exception('Security check: Illegal character in filename');
        }
    }
}