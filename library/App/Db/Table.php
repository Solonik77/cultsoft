<?php
/**
 * App Db Table
 *
 * @author Denysenko Dmytro
 * @category Zend
 * @package Zend_Db
 * @subpackage Abstract
 */
abstract class App_Db_Table extends App_Db_Table_Abstract
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public static function collectionFactory($className)
    {
        try
        {
            if(! class_exists($className)){
                require_once 'Zend/Loader.php';
                Zend_Loader::loadClass($className);
            }
            return new App_Db_Table_Rowset(array('table' => new $className, 'stored' => true));
        } catch(Exception $e)
        {
            throw new App_Exception($e->getMessage());
        }
    }
}