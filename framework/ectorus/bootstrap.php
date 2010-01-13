<?php

namespace Ectorus;

defined('DOC_ROOT') or exit('No direct script access.');

/**
* Ectorus
*
* @author Dmytro Denysenko
* @copyright (c) 2010 Dmytro Denysenko
*/
class Bootstrap {
    private $_config;

    /**
    * Constructor
    *
    * @var Ectorus_Config object
    */
    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    /**
    * Setup system environment by parameters from config
    *
    * @return $this
    */
    public function setup_environment()
    {
        // Set error reporting level
        ini_set('log_errors', true);
        // Attach the file write to logging. Multiple writers are supported.
        if ('production' != APP_ENV) {
            ini_set('display_errors', true);
            error_reporting(E_ALL | E_STRICT);
        } else {
            ini_set('display_errors', false);
            error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
        }

        umask(0);
        // Convert all global variables to UTF-8
        $_GET = \utf8::clean($_GET);
        $_POST = \utf8::clean($_POST);
        $_COOKIE = \utf8::clean($_COOKIE);
        $_SERVER = \utf8::clean($_SERVER);

        \Kohana::set_base_url($this->_config->get('core.base_url'));

        return $this;
    }

    /**
    * Kohana modules initialization
    *
    * @return $this
    */
    public function init_modules()
    {
        $modules_list = $this->_config->get('core.modules');
        \Kohana::modules($modules_list);
        return $this;
    }

    /**
    * Locale and internationalization setup
    *
    * @return $this
    */
    public function init_internationalization()
    {
        // Disable notices and "strict" errors
        $ER = error_reporting(~ E_NOTICE &~ E_STRICT);
        $i18n = I18n::instance();

        if (!$this->_config->get('core.is_installed')) {
            $i18n->set_locale('en_US');
        } else {
            $i18n->set_locale($locale);
        }

        /**
        * Set the default time zone.
        *
        * @see http://docs.kohanaphp.com/about.configuration
        * @see http://php.net/timezones
        */
        if (function_exists('date_default_timezone_set')) {
            $timezone = $this->_config->get('locale.timezone');
            // Set default timezone, due to increased validation of date settings
            // which cause massive amounts of E_NOTICEs to be generated in PHP 5.2+
            $i18n->set_timezone(empty($timezone) ? date_default_timezone_get() : $timezone);
        }
        // Restore error reporting
        error_reporting($ER);

        return $this;
    }

    /**
    * PHP session initialization and starting
    *
    * @return $this
    */
    public function init_session()
    {
        \Session::instance($this->_config->get('core.session_handler'));
        return $this;
    }
}
