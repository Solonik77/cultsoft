<?php
/**
 * BASE APPLICATION
 * Provides Zend application-specific helper functions.
 *
 * $Id$
 *
 * @author Denysenko Dmytro
 */
final class App
{
    const VERSION = '0.0.3';
    const CHARSET = 'UTF-8';
    // Application front controller object
    protected static $front = null;
    // Application config object
    protected static $config = null;
    // Database object
    protected static $db = null;
    // I18n Object
    public static $i18n = NULL;
    // Logger
    protected static $log = null;
    // Base URI
    protected static $base_uri = '';
    // System info
    protected static $_systemInfo = '';

    final private function __construct() {}
    final private function __clone() {}

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
        if (App::$log instanceof Zend_Log AND is_writeable(VAR_PATH . 'logs')) {
            if (App::$config instanceof Zend_Config and $type <= App::config()->system_log_threshold) {
                App::$log->log($message, $type);
            } else if(defined('INSTALLER_RUN') AND $type <= 4) {
                App::$log->log($message, $type);
            }
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
    /**
     * Set App Internationalization object
     */
    public static function setI18N(App_I18n $object)
    {
        if (App::$i18n === NULL) {
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
    public static function getVersion ()
    {
        return self::VERSION;
    }

    public static function systemInfo()
    {
        if(!self::$_systemInfo){
            self::$_systemInfo = new Main_Model_SystemInfo;
        }
        return self::$_systemInfo;
    }
}
/**
 * Translator function
 */
function __ ($text = '', $locale = null)
{
    return App::i18n()->getTranslator()->_($text, $locale);
}
