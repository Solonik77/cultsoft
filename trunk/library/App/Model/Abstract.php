<?php

abstract class App_Model_Abstract {
    private $_table;
    private $_attributes = array();

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
        if (count($this->_attributes) == 0) {
            $module = App::front()->getRequest()->getParam('module', 'main');
            $dbTableClass = ucfirst($module) . '_DbTable_' . get_class($this);
            if (class_exists($dbTableClass)) {
                $this->_table = new $dbTableClass;
                $info = $this->_table->info();
                $cols = $info['cols'];
                foreach($cols as $col) {
                    $this->_attributes[$col] = $info['metadata'][$col]['DEFAULT'];
                }
            }
        }
        return $this;
    }

    public function __get($name)
    {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        } else if (array_key_exists($name, $this->getMetaData()->_attributes)) {
            return $this->_attributes[$name];
        } else {
            throw new App_Exception('Property "' . get_class($this) . '.' . $name . '" is not defined');
            return false;
        }
    }
}
