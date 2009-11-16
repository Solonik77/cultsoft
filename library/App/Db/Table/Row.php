<?php
class App_Db_Table_Row extends Zend_Db_Table_Row_Abstract
{
    /**
     * Constructor.
     *
     * Supported params for $config are:-
     * - table       = class name or object of type Zend_Db_Table_Abstract
     * - data        = values of columns in this row.
     *
     * @param  array $config OPTIONAL Array of user-specified config options.
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    public function __construct(array $config = array())
    {
        parent:: __construct($config);
    }

    public function __call($name, $args)
    {
        if(preg_match('/^(get|set)(\w+)/', $name, $match) && $attribute = $this->validateAttribute(strtolower(Zend_Filter::filterStatic($match[2], 'Word_CamelCaseToUnderscore')))){
            if('get' == $match[1]){
                return $this->$attribute;
            }
            else{
                if(array_key_exists($attribute, $this->_data)){
                    $this->$attribute = $args[0];
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

    public function validateAttribute($name)
    {
        if($this->$name !== false){
            return strtolower($name);
        }
        else{
            return false;
        }
    }

    public function getAttributes()
    {
        return array_keys($this->_data);
    }

    public function getData()
    {
        return $this->_data;
    }

    public function setAttributes($array)
    {
        $row = $this->_data;
        foreach($row as $key => $value){
            if($key != 'id' AND isset($array[$key])){
                $this->$key = $array[$key];
            }
        }
    }

    public function setId($id)
    {
        if(!isset($this->_data['id'])){
            $this->id = $id;
        }
    }

    protected function _refresh()
    {
        $where = $this->_getWhereQuery();
        $row = $this->_getTable()->fetchRow($where)->getCollection()->current();

        if (null === $row) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception('Cannot refresh row as parent is missing');
        }

        $this->_data = $row->toArray();
        $this->_cleanData = $this->_data;
        $this->_modifiedFields = array();
    }
}