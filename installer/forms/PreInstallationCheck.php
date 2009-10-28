<?php

class Install_Form_PreInstallationCheck extends App_Form
{
    private $_requirements = array();
    private $_model;
    function __construct($options = null)
    {
        parent::__construct($options);        
    }
    
    public function setModel($model)
    {
       $this->_model = $model;
    }    
   
    public function compose()
    {
        $this->addElement($this->createElement('submit', 'continue')->setLabel('Continue'));
        return $this;
    }
}