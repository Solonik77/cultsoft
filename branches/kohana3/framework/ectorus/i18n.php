<?php 

namespace Ectorus;

defined('DOC_ROOT') or exit('No direct script access.');

/**
 * Ectorus
 * @author Dmytro Denysenko
 * @copyright (c) 2010 Dmytro Denysenko
 */
class I18n
{
    // Singleton static instance
    protected static $_instance;
	protected $_locale;
    
    private function __construct()
    {}
    
    public function __clone()
    {}
    
    /**
     * Get the singleton instance of Ectorus\I18n.
     *
     * @return Ectorus\I18n
     */
    public static function instance()
    {
        if (self::$_instance === NULL) {
            // Create a new instance
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	 public function set_locale($locale)
    {
        $locale = (string) $locale . '.UTF-8';
        $this->_locale = setlocale(LC_ALL, $locale);
    }

    public function set_timezone($timezone)
    {
        date_default_timezone_set($timezone);
    }	
	
    public function get_locale()
    {
        return self::$locale;
    }
    
    public function __($string, array $values = NULL, $lang = 'en-us')
    {
        return $string;
    }
}