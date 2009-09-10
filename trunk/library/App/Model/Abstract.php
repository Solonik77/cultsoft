<?php

abstract class App_Model_Abstract {
    protected $_table;
    private $_columns = array();
    private $_attributes = array();

    public function __construct()
    {
        if ($this->_table) {
            $this->_table = new $this->_table;
        } else {
            throw new App_Exception('Could not set table name for application model ' . __CLASS__);
        }
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function setAttributes($array)
    {
        if (count($this->_columns) == 0) {
            $this->getMetaData();
        }

        $result = array();
        foreach($this->_columns as $key => $value) {
            if (isset($array[$key])) {
                $result[$key] = $array[$key];
            }
        }
        $this->_attributes = $result;
        return $this;
    }

    public function setAttribute($attribute, $value)
    {
        if (count($this->_columns) == 0) {
            $this->getMetaData();
        }
        if (array_key_exists($attribute, $this->_columns)) {
            $this->_attributes[$attribute] = $value;
        }

        return $this;
    }

    public function validateAttribute($name)
    {
        if ($this->$name !== false) {
            return strtolower($name);
        } else {
            return false;
        }
    }

    public function getMetaData()
    {
        if (count($this->_columns) == 0) {
            $module = App::front()->getRequest()->getParam('module', 'main');
            $dbTableClass = ucfirst($module) . '_DbTable_' . get_class($this);
            if (class_exists($dbTableClass)) {
                $this->_table = new $dbTableClass;
                $info = $this->_table->info();
                $cols = $info['cols'];
                foreach($cols as $col) {
                    $this->_columns[$col] = $info['metadata'][$col]['DEFAULT'];
                }
            }
        }
        return $this;
    }

    public function getDbTable()
    {
        return $this->_table;
    }

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof App_Db_Table_Abstract) {
            throw new App_Exception("Invalid table data gateway provided");
        }
        $this->_table = $dbTable;
        return $this;
    }

    public function save()
    {
        if (count($this->_attributes) > 0) {
            $data = array();
            foreach($this->_attributes as $attribute => $value) {
                $data[$attribute] = $value;
            }
            App::db()->beginTransaction();
            try {
                if (null === ($id = $this->getId())) {
                    unset($data['id']);
                    $this->getDbTable()->insert($data);
                } else {
                    $this->getDbTable()->update($data, array('id = ?' => $id));
                }
                App::db()->commit();
                return true;
            }
            catch(Exception $e) {
                App::db()->rollBack();
                App::log($e->getMessage(), 3);
                return false;
            }
        }
    }

    public function setId($id)
    {
        if (!$this->id) {
            $this->id = $id;
        }
    }

    public function __call($name, $args)
    {
        if (preg_match('/^(get|set)(\w+)/', $name, $match) && $attribute = $this->validateAttribute(
                    strtolower(Zend_Filter::filterStatic($match[2], 'Word_CamelCaseToUnderscore'))
                    )) {
            if ('get' == $match[1]) {
                return $this->$attribute;
            } else {
                if (count($this->_columns) == 0) {
                    $this->getMetaData();
                }
                if (array_key_exists($attribute, $this->_columns)) {
                    $this->$attribute = $this->_attributes[$attribute] = $args[0];
                } else {
                    throw new App_Exception('Property "' . get_class($this) . '.' . $name . '" is not defined');
                }
            }
        } else {
            throw new App_Exception('Call to undefined method ' . $name . '()');
        }
    }

    public function __get($name)
    {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        } else if (array_key_exists($name, $this->getMetaData()->_columns)) {
            return $this->_attributes[$name];
        } else {
            throw new App_Exception('Property "' . get_class($this) . '.' . $name . '" is not defined');
            return false;
        }
    }
}
