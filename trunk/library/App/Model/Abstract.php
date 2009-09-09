<?php

abstract class App_Model_Abstract {
    private $_table;
    private $_columns = array();
    private $_attributes = array();
    private $_dependentTables = array();

    public function __call($name, $args)
    {
        if (preg_match('/^(get|set)(\w+)/', $name, $match) && $attribute = $this->validateAttribute(
                    strtolower(Zend_Filter::filterStatic($match[2], 'Word_CamelCaseToUnderscore'))
                    )) {
            if ('get' == $match[1]) {
                return $this->$attribute;
            } else {
                $this->$attribute = $args[0];
            }
        } else {
            throw new App_Exception('Call to undefined method ' . $name . '()');
        }
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
                if (count($info['dependentTables']) > 0) {
                    foreach($info['dependentTables'] as $table) {
                        if (class_exists($table)) {
                            $dependentTable = new $table;
                            $dependentInfo = $dependentTable->info();
                            $table = strtolower(substr($table, strlen($module) + 9));
                            $this->_dependentTables[$table] = new StdClass;
                            $array = array();
                            foreach($dependentInfo['cols'] as $key => $col) {
                                $array[$col] = null;
                            }
                            $this->_dependentTables[$table]->_columns = $array;
                        }
                    }
                }

                $cols = $info['cols'];
                foreach($cols as $col) {
                    $this->_columns[$col] = $info['metadata'][$col]['DEFAULT'];
                }
            }
        }
        return $this;
    }

    public function setAttributes($array)
    {
        if (count($this->_columns) == 0) {
            $this->getMetaData();
        }

        $dependentTables = $this->getDependentTables();
        $result = array();
        foreach($this->_columns as $key => $value) {
            if (isset($array[$key])) {
                $result[$key] = $array[$key];
            }
        }
        $this->_attributes = $result;
        $result = array();
        foreach($dependentTables as $table => $column) {
            // print_r($array);
            // print_r($column->_columns);
            if (isset($array[$table])) {
                $result[$key] = $array[$key];
            }
        }
    }

    public function getDependentTables()
    {
        if (count($this->_columns) == 0) {
            $this->getMetaData();
        }
        return $this->_dependentTables;
    }

    public function getAttributes()
    {
        return $this->_attributes;
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
