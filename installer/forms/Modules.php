<?php
class Install_Form_Modules extends App_Form
{
    private $_moulesInfo = array();

    function __construct($options = null)
    {
        parent::__construct($options);
        $this->setAction('install/modules');
    }

    public function compose()
    {
        $this->addElement($this->createElement('submit', 'next')->setLabel('Next'));
        return $this;
    }

    public function setModulesInfo($moulesInfo)
    {
        $this->_moulesInfo = moulesInfo;
    }
}