<?php

class Install_Form_PreInstallationCheck extends App_Form
{
    function __construct($options = null)
    {
        parent::__construct($options);        
    }
   
    public function compose()
    {
        return $this;
    }
}