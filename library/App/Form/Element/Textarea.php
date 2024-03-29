<?php
class App_Form_Element_Textarea extends Zend_Form_Element_Textarea {
    /**
     * Set element name
     *
     * @param string $name
     * @return Zend_Form_Element
     */
    public function setName($name)
    {
        $name = $this->filterName($name, true);
        if ('' === $name) {
            throw new Zend_Form_Exception('Invalid name provided; must contain only valid variable characters and be non-empty');
        }
        $this->_name = $name;
        return $this;
    }
}