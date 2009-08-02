<?php
/**
* Global website email forms.
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class Main_Form_Signin extends App_Form {
    /**
    * Contructor
    *
    * @return Zend_Form object
    */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setAction('/signin')->setMethod('post');
        $email = $this->createElement('text', 'member_email', array('label' => 'E-mail'));
        $email->addValidator('EmailAddress')->addValidator('stringLength', false, array(4 , 100))->setRequired(true)->addFilter('StringToLower')->addFilter('stringTrim');
        $password = $this->createElement('password', 'member_password', array('label' => 'Password'));
        $password->addValidator('StringLength', false, array(7))->setRequired(true)->addFilter('stringTrim');
        $this->addElement($email)->addElement($password)->addElement('checkbox', 'remember_me', array('label' => 'Remember me'))->addElement('submit', 'enter', array('label' => 'Enter'));
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
    }
}
