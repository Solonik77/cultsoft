<?php
/**
* BASE APPLICATION
* Provides Zend application-specific helper functions.
*
* $Id$
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
final class App {
    const CHARSET = 'UTF-8';
    // Application front controller object
    protected static $front = null;
    // Application config object
    protected static $config = null;
    // Database object
    protected static $db = null;
    // I18n Object
    protected static $i18n = null;
    // Logger
    protected static $log = null;
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
            throw new Zend_Exception('Config object is not set');
        }
    }

    /**
    * Front controller instance
    */
    public static function front()
    {
        return Zend_Controller_Front::getInstance();
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
        if ($type <= App::config()->system_log_threshold) {
            app::$log->log($message, $type);
        }
        return;
    }

    /**
    * Get system locale information
    */
    public static function i18n()
    {
        return App::$i18n;
    }

    /**
    * Set configuration
    */
    public static function addConfig(Zend_Config $object)
    {
        if (App::$config === null) {
            App::$config = $object;
        } else {
            App::$config->merge($object);
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

    /*
    * Set App Internationalization object
    */
    public static function setI18N(App_I18n $object)
    {
        if (App::$i18n === null) {
            App::$i18n = $object;
        }
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
    * Translator object
    */
    public function translate()
    {
        return App::$i18n;
    }

    /**
    * Base URL, with or without the index page.
    *
    * If protocol(and core.site_protocol) and core.site_domain are both empty,
    * then
    *
    * @param boolean $ include the index page
    * @param boolean $ non-default protocol
    * @return string
    */
    public static function baseUri($index = false, $protocol = false)
    {
        if (! empty(app::$base_uri)) {
            return app::$base_uri;
        }
        if ($protocol == false) {
            // Guess the protocol to provide full http://domain/path URL
            $base_url = ((empty($_SERVER['HTTPS']) or $_SERVER['HTTPS'] === 'off') ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'];
        } else {
            // Guess the server name if the domain starts with slash
            $base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
        }
        if ($index === true) {
            // Append the index page
            $base_url = $base_url . FRONT_CONTROLLER_FILE;
        }
        app::$base_uri = rtrim($base_url, '/') . '/';
        // Force a slash on the end of the URL
        return app::$base_uri;
    }

    /**
    * Is Platform running on CLI?
    */
    public static function isCli()
    {
        return (PHP_SAPI === 'cli');
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
function __($text = '', $print = false)
{
    if ($print == true) {
        echo App::i18n()->getTranslator()->_($text);
    } else {
        return App::i18n()->getTranslator()->_($text);
    }
}