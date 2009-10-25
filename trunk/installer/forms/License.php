<?php
class Install_Form_License extends Install_Form_Base
{

    function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->createElement('submit', 'agree')->setLabel('Agree'));
    }
}