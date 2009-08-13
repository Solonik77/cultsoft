<?php

class Main_Form_Backoffice_Settings extends App_Form {
    public function __construct($options = array())
    {
        parent::__construct($options);
    }

    public function compose()
    {
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
        $this->addElement($this->createElement('submit', 'Save configuration')->setLabel('Save configuration'));
        return $this;
    }

    public function setFields($array)
    {
        foreach($array as $setting) {
            $field = $this->createElement('text', $setting["id"])->setLabel($setting["setting_name"])->setValue($setting['setting_value']);
            $this->addElement($field);
        }
        return $this;
    }
}