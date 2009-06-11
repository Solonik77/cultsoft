<?php
/**
 * App_Db_Table
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 * @category Zend
 * @package Zend_Db
 * @subpackage Table
 */
/**
 * Zend_Db_Table
 */
require_once 'Zend/Db/Table.php';
abstract class App_Db_Table extends Zend_Db_Table_Abstract
{

    /**
     * Initialize table and schema names.
     *
     * If the table name is not set in the class definition,
     * use the class name itself as the table name.
     *
     * A schema name provided with the table name (e.g., "schema.table") overrides
     * any existing value for $this->_schema.
     *
     * @return void
     */
    protected function _setupTableName ()
    {
        if (! $this->_name) {
            $this->_name = App::config()->database->table_prefix . strtolower(str_replace(array('Model_DbTable_' , 'Site_' , 'Admin_'), '', get_class($this)));
        } else 
            if (strpos($this->_name, '.')) {
                list ($this->_schema, $this->_name) = explode('.', $this->_name);
                $this->_name = App::config()->database->table_prefix . $this->_name;
            }
        parent::_setupTableName();
    }

    /**
     * Initialize object
     *
     * @return void
     */
    public function init ()
    {
        parent::init();
    }
}