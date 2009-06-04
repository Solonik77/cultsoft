<?php
/**
 * Global website login forms.
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class Site_Form_Login extends Zend_Form
{
    /**
     * Contructor
     *
     * @return Zend_Form object
     */
    public function __construct ()
    {
        parent::__construct($options = null);
        $this->setAction('/signin')->setMethod('post');
        $login = $this->createElement('text', 'member_login', array('label' => 'Login'));
        $login->addValidator('alnum')->addValidator('regex', false, array('/^[a-z]+/'))->addValidator('stringLength', false, array(3 , 40))->setRequired(true)->addFilter('StringToLower');
        $password = $this->createElement('password', 'member_password', array('label' => 'Password'));
        $password->addValidator('StringLength', false, array(7))->setRequired(true);
        $this->addElement($login)->addElement($password)->addElement('checkbox', 'remember_me', array('label' => 'Remember me'))->addElement('submit', 'enter', array('label' => 'Enter'));
        $this->addElement('hash', 'csrf_hash', array('salt' => 'unique'));
    }
}
