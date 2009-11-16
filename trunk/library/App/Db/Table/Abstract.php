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
    protected $_cache;
    protected $_defaultRowset;
    /**
     * Classname for row
     *
     * @var string
     */
    protected $_rowClass = 'App_Db_Table_Row';
    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = 'App_Db_Table_Rowset';

    public function __construct($config = array())
    {
        $this->_preConstruct();
        parent::__construct($config);
        $this->_cache = App_Cache::getInstance();
        $this->_postConstruct();
    }

    protected function _preConstruct()
    {
    }

    protected function _postConstruct()
    {
    }

    protected function _preInsert()
    {
    }

    protected function _postInsert()
    {
    }

    protected function _preUpdate()
    {
    }

    protected function _postUpdate()
    {
    }

    protected function _preSave()
    {
    }

    protected function _postSave()
    {
    }

    protected function _preDelete()
    {
    }

    protected function _postDelete()
    {
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
            $this->_name = App::config()->database->table_prefix . strtolower(str_replace(array('Main_Model_DbTable_' , ucfirst(strtolower($module)) . '_Model_DbTable_'), '', get_class($this)));
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

    /**
     * Fetches rows by primary key.  The argument specifies one or more primary
     * key value(s).  To find multiple rows by primary key, the argument must
     * be an array.
     *
     * This method accepts a variable number of arguments.  If the table has a
     * multi-column primary key, the number of arguments must be the same as
     * the number of columns in the primary key.  To find multiple rows in a
     * table with a multi-column primary key, each argument must be an array
     * with the same number of elements.
     *
     * The find() method always returns a Rowset object, even if only one row
     * was found.
     *
     * @param  mixed $key The value(s) of the primary keys.
     * @return Zend_Db_Table_Rowset_Abstract Row(s) matching the criteria.
     * @throws Zend_Db_Table_Exception
     */
    public function find($arguments)
    {
        $this->_defaultRowset = parent::find($arguments);
        return $this;
    }

    public function findByCondition($where = null, $order = null)
    {
        return $this->_defaultRowset = parent::fetchAll($where, $order, 1);
    }

    public function findAllByCondition($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->_defaultRowset = parent::fetchAll($where, $order, $count, $offset);
    }

    /**
     * Fetches all rows.
     *
     * Honors the Zend_Db_Adapter fetch mode.
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->_defaultRowset = parent::fetchAll($where, $order, $count, $offset);
    }

    /**
     * Fetches one row in an object of type Zend_Db_Table_Row_Abstract,
     * or returns null if no row matches the specified criteria.
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @return Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public function fetchRow($where = null, $order = null)
    {
        return $this->_defaultRowset = parent::fetchAll($where, $order, 1);
    }

    /**
     * Deletes existing rows.
     *
     * @param  array|string $where SQL WHERE clause(s).
     * @return int          The number of rows deleted.
     */
    public function delete($where = NULL)
    {
        $this->_preDelete();
        if($this->getCollection() and $where === NULL){
            foreach($this->getCollection() as $row){
                $where = $this->getAdapter()->quoteInto('id = ?', $row->getId());
                $result = parent::delete($where);
            }
        }
        else{
            $result = parent::delete($where);
        }
        $this->_postDelete();
        return $result;
    }

    /**
     * Returns an instance of a Zend_Db_Table_Select object.
     *
     * @param bool $withFromPart Whether or not to include the from part of the select based on the table
     * @return Zend_Db_Table_Select
     */
    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {
        $select = new App_Db_Table_Select($this);
        if($withFromPart == self::SELECT_WITH_FROM_PART){
            $select->from($this->info(self::NAME), Zend_Db_Table_Select::SQL_WILDCARD, $this->info(self::SCHEMA));
        }
        return $select->setIntegrityCheck(false);
    }

    /*
     * Collection of Db Table rows
     * @return Zend Db Table Rowset
     */
    public function getCollection()
    {
        if($this->_defaultRowset instanceof App_Db_Table_Rowset){
            return $this->_defaultRowset;
        }
        else{
            throw new App_Exception('Default collection rowset must be App_Db_Table_Rowset object.');
        }
    }

    public function setAttributes($array)
    {
        $row = $this->getCollection()->current();
        $result = array();
        foreach($row->getData() as $key => $value){
            if($key != 'id' and isset($array[$key])){
                $method = 'set' . Zend_Filter::filterStatic($key, 'Word_UnderscoreToCamelCase');
                $row->$method($array[$key]);
            }
        }
        return $this;
    }

    public function save()
    {
        if(count($this->_defaultRowset) > 0){
            $data = array();
            App::db()->beginTransaction();
            try{
                $this->_preSave();
                foreach($this->_defaultRowset as $class){
                    $class->save();
                }
                App::db()->commit();
                $this->_postSave();
                return true;
            }
            catch(Exception $e){
                App::db()->rollBack();
                App::log($e->getMessage(), 3);
                return false;
            }
        }
    }
}