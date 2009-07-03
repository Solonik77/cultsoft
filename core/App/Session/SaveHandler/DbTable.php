<?php
/**
* DB Session handler.
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
/**
*
* @see Zend_Session
*/
require_once 'Zend/Session.php';
/**
*
* @see Zend_Db_Table_Abstract
*/
require_once 'Zend/Db/Table/Abstract.php';
/**
*
* @see Zend_Db_Table_Row_Abstract
*/
require_once 'Zend/Db/Table/Row/Abstract.php';
/**
*
* @see Zend_Config
*/
require_once 'Zend/Config.php';
class App_Session_SaveHandler_DbTable extends Zend_Session_SaveHandler_DbTable {
    /**
    * Constructor
    *
    * $config is an instance of Zend_Config or an array of key/value pairs containing configuration options for
    * Zend_Session_SaveHandler_DbTable and Zend_Db_Table_Abstract. These are the configuration options for
    * Zend_Session_SaveHandler_DbTable:
    *
    * primaryAssignment => (string|array) Session table primary key value assignment
    *         (optional; default: 1 => sessionId) You have to assign a value to each primary key of your session table.
    *         The value of this configuration option is either a string if you have only one primary key or an array if
    *         you have multiple primary keys. The array consists of numeric keys starting at 1 and string values. There
    *         are some values which will be replaced by session information:
    *
    *         sessionId       => The id of the current session
    *         sessionName     => The name of the current session
    *         sessionSavePath => The save path of the current session
    *
    *         NOTE: One of your assignments MUST contain 'sessionId' as value!
    *
    * modifiedColumn    => (string) Session table last modification time column
    *
    * lifetimeColumn    => (string) Session table lifetime column
    *
    * dataColumn        => (string) Session table data column
    *
    * lifetime          => (integer) Session lifetime (optional; default: ini_get('session.gc_maxlifetime'))
    *
    * overrideLifetime  => (boolean) Whether or not the lifetime of an existing session should be overridden
    *         (optional; default: false)
    *
    * @param Zend_Config $ |array $config      User-provided configuration
    * @return void
    * @throws Zend_Session_SaveHandler_Exception
    */
    public function __construct($config)
    {
        parent::__construct($config);
    }
}
