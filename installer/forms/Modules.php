<?php
class Install_Form_Modules extends App_Form
{

    function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->createElement('submit', 'next')->setLabel('Next'));

    }
}