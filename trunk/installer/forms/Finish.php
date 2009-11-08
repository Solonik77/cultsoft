<?php
class Install_Form_Finish extends App_Form
{

    function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->createElement('submit', 'redirect_to')->setLabel('Go to frontend'));
        $this->addElement($this->createElement('submit', 'redirect_to')->setLabel('Go to backend'));
    }
}