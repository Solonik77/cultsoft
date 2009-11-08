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
        foreach($this->_moulesInfo as $module => $info){
            $checkbox = $this->createElement('checkbox', $module)->setLabel($info['module_name'])->setDescription($info['long_description']);
            $checkbox->addValidator('int');
            $this->addElement($checkbox);
            $this->setElementsBelongTo('modules');
        }
        $this->addElement($this->createElement('submit', 'next')->setLabel('Next'));
        return $this;
    }

    public function setModulesInfo($moulesInfo)
    {
        if(isset($moulesInfo['main'])){
            unset($moulesInfo['main']);
        }
        $this->_moulesInfo = $moulesInfo;
    }
}