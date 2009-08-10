<?php

class Main_Form_Backoffice_Settings extends App_Form {
    public function __construct($options = array())
    {
        parent::__construct($options);
        if (count($options) > 0) {
            foreach($options as $option) {
                $this->addElement($this->createElement('text', $option["setting_key"])->setLabel($option["setting_name"])->setValue($option['setting_value']));
            }
        }
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
        $this->addElement($this->createElement('submit', 'Save configuration')->setLabel('Save configuration'));
    }
}
