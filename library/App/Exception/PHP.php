<?php
/**
* PHP Errors Class
* $Id$
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
final class App_Exception_PHP extends App_Exception {
    /**
    * Enable application PHP error handling.
    *
    * @return void
    */
    public static function enable()
    {
        set_error_handler(array(__CLASS__, 'handle'));
    }

    /**
    * Disable application PHP error handling.
    *
    * @return void
    */
    public static function disable()
    {
        restore_error_handler();
    }

    /**
    * Create a new PHP error exception.
    *
    * @return void
    */
    public function __construct($code, $error, $file, $line, $context = null)
    {
        parent::__construct($error);
        // Set the error code, file, line, and context manually
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
    }

    /**
    * PHP error handler.
    *
    * @throws App_Exception_PHP
    * @return void
    */
    public static function handle($code, $error = 0, $file = '', $line = 0, $context = null)
    {
        if ((error_reporting() &$code) === 0) {
            // Respect error_reporting settings
            return;
        }
        // Create an exception
        $exception = new App_Exception_PHP($code, $error, $file, $line, $context);
        echo $exception;
        // Execution must halt
        exit();
    }
}
