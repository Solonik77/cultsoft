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
    
    public function setSystemRequirementsValues($requirementsArray)
    {
       $this->_requirements = $requirementsArray;
    }
    
    public function compose()
    {
        if(count($this->_requirements) > 0)
        {
            foreach($this->_requirements as $key => $requirement)
            {
            if(gettype($key) == 'string'){
                $method = 'get' . $key;
                $this->addElement($this->createElement('hidden', $key)->setValue(intval($this->_model->$method()))
                ->addValidator('Callback', true, (array(
    'callback' => array($this, 'exceptValue'), 
    'options'  => $requirement))));
            } elseif(gettype($key) == 'integer'){               
            $this->addElement($this->createElement('hidden', $requirement)->setValue(intval($this->_model->isPHPExtensionLoded($requirement))));
            }     
            }
        
        }
        $this->addElement($this->createElement('submit', 'continue')->setLabel('Continue'));
        return $this;
    }
    
    private function exceptValue($value, $option)
    {
        phpinfo();
        var_dump($option); die;
    }
}