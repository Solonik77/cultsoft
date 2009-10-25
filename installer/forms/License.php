<?php
class Install_Form_License extends App_Form
{
    protected $_license;

    function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setLicenseContent($license)
    {
        $this->_license = $license;
    }

    public function getLicenseContent()
    {
        return $this->_license;
    }

    public function compose()
    {
        if($this->_license){
            $license = $this->createElement('textarea', 'license', array('label' => '' , 'rows' => 15, 'readonly' => 'readonly'));
            $license->setValue($this->_license)->setRequired(true);
            $this->addElement($license);
        }
        $this->addElement($this->createElement('checkbox', 'agree')->setLabel('I agree to the above terms and conditions.')->setRequired(true)->addValidator('Between', false, array(1,5)));
        $this->addElement($this->createElement('submit', 'next')->setLabel('Continue'));
        return $this;
    }
}