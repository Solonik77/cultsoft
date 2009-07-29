<?php

class App_Form_Element_Text extends Zend_Form_Element_Text
{

    /**
     * Set element name
     * 
     * @param  string $name 
     * @return Zend_Form_Element
     */
    public function setName($name)
    {
        $name = $this->filterName($name, TRUE);
        if ('' === $name) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid name provided; must contain only valid variable characters and be non-empty');
        }

        $this->_name = $name;
        return $this;
    }
}