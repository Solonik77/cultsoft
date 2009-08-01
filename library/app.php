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
    // Default system localization
    protected static $locale = null;
    // Logger
    protected static $log = null;
    // Zend_Translate object
    protected static $i18n = null;
    // Base URI
    protected static $base_uri = '';
    // Website languages
    protected static $site_languages = array();

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
        app::$log->log($message, $type);
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
        Zend_Validate_Abstract::setDefaultTranslator(App::$i18n);
        Zend_Form::setDefaultTranslator(App::$i18n);
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
    * Is Platform running on Windows?
    */
    public static function isWin()
    {
        return DIRECTORY_SEPARATOR === '\\';
    }

    /**
    * Website languages
    */
    public static function siteLanguages()
    {
        $site_languages = self::$site_languages;
        if (count($site_languages) > 0) {
            return $site_languages;
        }
        $cache = App_Cache::getInstance('permCache');
        if (! $site_languages = $cache->load('website_languages')) {
            $site_languages = new System_Model_DbTable_Site_Languages();
            $site_languages = $site_languages->fetchAll()->toArray();
            if (count($site_languages) > 0) {
                $cache->save($site_languages);
            }
        }
        $data = array();
        foreach($site_languages as $key => $value) {
            $data[$value['id']] = $value;
        }
        self::$site_languages = $data;
        return self::$site_languages;
    }
}

/**
* Translator function
*/
function __($text = '', $print = false)
{
    if ($print == true) {
        echo App::translate()->_($text);
    } else {
        return App::translate()->_($text);
    }
}
