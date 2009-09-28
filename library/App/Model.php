<?php
abstract class App_Model
{
    protected $_table;
    protected $_dbRow;
    protected $_columns = array();
    protected $_attributes = array();

    public function __construct()
    {
        if($this->_table){
            $this->_table = new $this->_table();
            $this->getMetaData();
        }
        else{
            throw new App_Exception('Could not set table name for application model ' . __CLASS__);
        }
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function setAttributes($array)
    {
        $result = array();
        foreach($this->_columns as $key => $value){
            if(isset($array[$key])){
                $result[$key] = $array[$key];
            }
        }
        $this->_attributes = $result;
        return $this;
    }

    public function setAttribute($attribute, $value)
    {
        if(array_key_exists($attribute, $this->_columns)){
            $this->_attributes[$attribute] = $value;
        }
        return $this;
    }

    public function validateAttribute($name)
    {
        if($this->$name !== false){
            return strtolower($name);
        }
        else{
            return false;
        }
    }

    public function getMetaData()
    {
        if(count($this->_columns) == 0){
            $info = $this->_table->info();
            $cols = $info['cols'];
            foreach($cols as $col){
                $this->_columns[$col] = $info['metadata'][$col]['DEFAULT'];
            }
        }
        return $this;
    }

    public function getDbTable()
    {
        return $this->_table;
    }

    public function getTable()
    {
        return $this->getDbTable();
    }

    public function setDbTable($dbTable)
    {
        if(is_string($dbTable)){
            $dbTable = new $dbTable();
        }
        if(! $dbTable instanceof App_Db_Table_Abstract){
            throw new App_Exception("Invalid table data gateway provided");
        }
        $this->_table = $dbTable;
        return $this;
    }

    public function save()
    {
        if(count($this->_attributes) > 0){
            $data = array();
            foreach($this->_attributes as $attribute => $value){
                $data[$attribute] = $value;
            }
            try{
                if(NULL === ($id = $this->getId())){
                    unset($data['id']);
                    $this->getDbTable()->insert($data);
                    $this->setId(App::db()->lastInsertId());
                }
                else{
                    $this->getDbTable()->update($data, array('id = ?' => $this->getId()));
                }
                return true;
            }
            catch(Exception $e){
                App::log($e->getMessage(), 3);
                return false;
            }
        }
    }

    public function delete()
    {
        try{
            $where = $this->getTable()->getAdapter()->quoteInto('id = ?', $this->getId());
            $this->getTable()->delete($where);
            return true;
        }
        catch(Exception $e){
            App::log($e->getMessage(), 3);
            return false;
        }
    }

    public function findByPK($id)
    {
        $this->_dbRow = $this->getDbTable()->fetchRow('id = ' . (int) $id);
        if(! is_object($this->_dbRow)){
            return null;
        }
        $this->setAttributes($this->_dbRow->toArray());
        return $this;
    }

    public function findByCondition($condition)
    {
        $this->_dbRow = $this->getDbTable()->fetchRow($condition);
        if(! is_object($this->_dbRow)){
            return null;
        }
        $this->setAttributes($this->_dbRow->toArray());
        return $this;
    }

    public function setId($id)
    {
        if(! $this->id){
            $this->id = $id;
        }
    }

    public function getDbRow()
    {
        if(! $this->_dbRow instanceof Zend_Db_Table_Row){
            throw new App_Exception("Getting invalid table row in class " . __CLASS__);
        }
        return $this->_dbRow;
    }

    public function __call($name, $args)
    {
        if(preg_match('/^(get|set)(\w+)/', $name, $match) && $attribute = $this->validateAttribute(strtolower(Zend_Filter::filterStatic($match[2], 'Word_CamelCaseToUnderscore')))){
            if('get' == $match[1]){
                return $this->$attribute;
            }
            else{
                if(array_key_exists($attribute, $this->_columns)){
                    $this->$attribute = $this->_attributes[$attribute] = $args[0];
                }
                else{
                    throw new App_Exception('Property "' . get_class($this) . '.' . $name . '" is not defined');
                }
            }
        }
        else{
            throw new App_Exception('Call to undefined method ' . $name . '()');
        }
    }

    public function with($dbTable)
    {
        $info = $this->_table->info();
        $data = FALSE;
        if(in_array($dbTable, $info['dependentTables'])){
            $rowset = $this->getDbRow()->findDependentRowset($dbTable)->toArray();
            $class = str_replace('DbTable', 'Model', $dbTable);
            $module = ucfirst(App::front()->getRequest()->getParam('module'));
            $field = strtolower(str_replace($module . '_DbTable_', '', $dbTable));
            if($rowset and count($rowset) > 0){
                foreach($rowset as $key => $value){
                    $this->{$field}[$key] = new $class();
                    $this->{$field}[$key]->setAttributes($value);
                }
            }
        }
        elseif(in_array($dbTable, $info['referenceMap'])){
        }
        else{
            throw new App_Exception('Could not find dependant tables for class ' . __CLASS__);
        }
        return $this;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        if(isset($this->_attributes[$name])){
            return $this->_attributes[$name];
        }
        else 
            if(array_key_exists($name, $this->getMetaData()->_columns)){
                return (isset($this->_attributes[$name])) ? $this->_attributes[$name] : $this->_columns[$name];
            }
            else{
                throw new App_Exception('Property "' . get_class($this) . '.' . $name . '" is not defined');
                return false;
            }
    }
}
