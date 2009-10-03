<?php
/**
 * App_Db_Table
 *
 * @author Denysenko Dmytro


 * @category Zend
 * @package Zend_Db
 * @subpackage Abstract
 */
abstract class App_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    private $_cache;

    public function __construct($config = array())
    {
        $config['rowClass'] = 'App_Db_Table_Row';
        $config['rowsetClass'] = 'App_Db_Table_Rowset';
        parent::__construct($config);
        $this->_cache = App_Cache::getInstance();
    }

    /**
     * Initialize table and schema names.
     *
     * If the table name is not set in the class definition,
     * use the class name itself as the table name.
     *
     * A schema name provided with the table name(e.g., "schema.table") overrides
     * any existing value for $this->_schema.
     *
     * @return void
     */
    protected function _setupTableName()
    {
        $request = App::front()->getRequest();
        $module = ($request) ? $request->getModuleName() : 'Main_';
        if(! $this->_name){
            $this->_name = App::config()->database->table_prefix . strtolower(str_replace(array('Main_DbTable_' , ucfirst(strtolower($module)) . '_DbTable_'), '', get_class($this)));
        }
        else 
            if(strpos($this->_name, '.')){
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
    public function init()
    {
        parent::init();
    }
}