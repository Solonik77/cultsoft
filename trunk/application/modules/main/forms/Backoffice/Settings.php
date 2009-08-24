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
            $field = $this->createElement('text', 'id_' . $setting["id"])->setLabel($setting["setting_name"])->setValue($setting['setting_value']);
            $field->addFilter('stringTrim')->addFilter('StripTags');
            if (is_int($setting["id"])) {
                $field->addValidator('int');
            }
            $this->addElement($field);
        }
        return $this;
    }
}