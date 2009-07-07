<?php
/**
* BASE APPLICATION
* Provides Zend application-specific helper functions.
*
* $Id$
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
final class App {
    const CHARSET = 'UTF-8';
    // Application front controller object
    protected static $front = null;
    // Application config object
    protected static $config = null;
    // Database object
    protected static $db = null;
    // Default system localization
    protected static $locale = null;
    // Logger
    protected static $log = null;
    // Zend_Translate object
    public static $i18n = null;
    // Base URI
    protected static $base_uri = '';

    /**
    * Return application configuration
    */
    public static function config()
    {
        if (App::$config instanceof Zend_Config) {
            return App::$config;
        } else {
            throw new Zend_Exception ('Config object is not set');
        }
    }

    /**
    * Front controller instance
    */
    public static function front()
    {
        return Zend_Controller_Front::getInstance ();
    }

    /**
    * Return Database object
    */
    public static function db()
    {
        return App::$db;
    }

    /**
    * Zend logger
    */
    public static function log($message, $type = 1)
    {
        app::$log->log ($message, $type);
        return;
    }

    /**
    * Get system locale information
    */
    public static function locale()
    {
        return App::$locale;
    }

    /**
    * Set configuration
    */
    public static function setConfig(Zend_Config $object)
    {
        if (App::$config === null) {
            App::$config = $object;
        }
    }

    /**
    * Set logger
    */
    public static function setLog(Zend_Log $object)
    {
        if (App::$log === null) {
            App::$log = $object;
        }
    }

    /**
    * Set translator object
    */
    public static function setTranslate(Zend_Translate $object)
    {
        App::$i18n = $object;
        Zend_Validate_Abstract::setDefaultTranslator (App::$i18n);
        Zend_Form::setDefaultTranslator (App::$i18n);
    }

    /**
    * Set database object
    */
    public static function setDb($object)
    {
        if (App::$db === null) {
            App::$db = $object;
        }
    }

    /**
    * Set locale object
    */
    public static function setLocale($object)
    {
        if (App::$locale === null) {
            App::$locale = $object;
        }
    }

    /**
    * Base URL, with or without the index page.
    *
    * If protocol (and core.site_protocol) and core.site_domain are both empty,
    * then
    *
    * @param boolean $ include the index page
    * @param boolean $ non-default protocol
    * @return string
    */
    public static function baseUri($index = false, $protocol = false)
    {
        if (! empty (app::$base_uri)) {
            return app::$base_uri;
        }
        if ($protocol == false) {
            // Guess the protocol to provide full http://domain/path URL
            $base_url = ((empty ($_SERVER ['HTTPS']) or $_SERVER ['HTTPS'] === 'off') ? 'http' : 'https') . '://' . $_SERVER ['HTTP_HOST'];
        } else {
            // Guess the server name if the domain starts with slash
            $base_url = $protocol . '://' . $_SERVER ['HTTP_HOST'];
        }
        if ($index === true) {
            // Append the index page
            $base_url = $base_url . FRONT_CONTROLLER_FILE;
        }
        app::$base_uri = rtrim ($base_url, '/') . '/';
        // Force a slash on the end of the URL
        return app::$base_uri;
    }

    /**
    * Is Platform running on Windows?
    */
    public static function isWin()
    {
        return DIRECTORY_SEPARATOR === '\\';
    }
}

/**
* Translator function
*/
function __($text = '', $print = true)
{
    if ($print === true) {
        echo App::$i18n->_ ($text);
    } else {
        return App::$i18n->_ ($text);
    }
}
