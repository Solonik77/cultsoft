<?php
class Install_Form_Base extends App_Form
{

    function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->createElement('submit', 'continue')->setLabel('Continue'));
    }
}