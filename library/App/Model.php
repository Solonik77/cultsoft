<?php

abstract class App_Model
{

  public function __call($name, $args) {
    if (preg_match('/^(get|set)(\w+)/', strtolower($name), $match)
    && $attribute = $this->validateAttribute($match[2])) {
      if ('get' == $match[1]) {
        return $this->$attribute;
      } else {
        $this->$attribute = $args[0];
      }
    } else {
      throw new App_Exception('Call to undefined method ' . $name . '()');
    }
  }
  
    public function validateAttribute($name) {
    print_r(get_class_vars(get_class($this))); die;
    if (in_array(strtolower($name),
    array_keys(get_class_vars(get_class($this))))) {
      return strtolower($name);
    }
  }
  
}