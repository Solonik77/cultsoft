<?php
class App_Db_Table_Row extends Zend_Db_Table_Row_Abstract
{

    public function __call($name, $args)
    {
        if(preg_match('/^(get|set)(\w+)/', $name, $match) && $attribute = $this->validateAttribute(strtolower(Zend_Filter::filterStatic($match[2], 'Word_CamelCaseToUnderscore')))){
            if('get' == $match[1]){
                return $this->$attribute;
            }
            else{
                if(array_key_exists($attribute, $this->_data)){
                    $this->$attribute = $this->_data[$attribute] = $args[0];
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
    
    public function setData($data)
    {
        return $this->_data = $data;
    }
    

    public function __set($name, $value)
    {
        $this->$name = $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }
        else{
            throw new App_Exception('Property "' . get_class($this) . '.' . $name . '" is not defined');
        }
    }
    
     public function setAttributes($array)
    {
        $row = $this->_data;
        $result = array();    
        foreach($row as $key => $value){
            if($key != 'id' AND isset($array[$key])){
                $this->_data[$key] = $array[$key];
            }
        }
    }
    
    public function setId($id)
    {
        if(!isset($this->_data['id'])){
            $this->id = $this->_data['id'] = $id;
        }
    }
}