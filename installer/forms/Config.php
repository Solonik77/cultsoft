<?php
class Install_Form_Config extends App_Form
{

    function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->setAction('install/config');
        $dbServer = $this->createElement('text', 'db_server', array('maxlength' => 100))->setLabel('Host')->setValue('localhost');
        $dbServer->addValidator('stringLength', false, array(1 , 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbServer);
        
        $dbUser = $this->createElement('text', 'db_username', array('maxlength' => 100))->setLabel('Username')->setValue('root');
        $dbUser->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbUser);
        
        $dbPassword = $this->createElement('password', 'db_password', array('maxlength' => 100))->setLabel('Password');
        $dbPassword->addValidator('stringLength', false, array(0, 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbPassword);
        
        $this->addDisplayGroup(array('db_server', 'db_username', 'db_password'), 'database', array("legend" => "Database connection"));
        
        
        $adminPath = $this->createElement('text', 'admin_path', array('maxlength' => 100))->setLabel('Admin path in url');
        $adminPath->addValidator('stringLength', false, array(4 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminPath);
        $adminUser = $this->createElement('text', 'admin_login', array('maxlength' => 100))->setLabel('Login');
        $adminUser->addValidator('stringLength', false, array(2 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminUser);
        $adminPassword = $this->createElement('password', 'admin_password', array('maxlength' => 100))->setLabel('Password');
        $adminPassword->addValidator('Alnum')->addValidator('stringLength', false, array(5, 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminPassword);

        $adminPasswordConfirm = $this->createElement('password', 'admin_password_confirm', array('maxlength' => 255))->setLabel('Retype password');
        $adminPasswordConfirm->addValidator('Alnum')->addValidator('stringLength', false, array(5, 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminPasswordConfirm); 

        $adminEmail = $this->createElement('text', 'admin_email', array('maxlength' => 100))->setLabel('Email');
        $adminEmail->addValidator('EmailAddress')->addValidator('stringLength', false, array(0, 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminEmail);       
        
        $this->addDisplayGroup(array('admin_path', 'admin_login', 'admin_password', 'admin_password_confirm', 'admin_email'), 'admin_account', array("legend" => "Admin account"));
        
        $encryptionKey = $this->createElement('text', 'encryption_key', array('maxlength' => 100))->setLabel('Encryption Key');
        $encryptionKey->addValidator('Alnum')->addValidator('stringLength', false, array(5, 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');        
        $encryptionKey->setDescription('Engine uses this key to encrypt passwords.');
        $this->addElement($encryptionKey);
        $this->addDisplayGroup(array('encryption_key'), 'encryption_key', array("legend" => "Encryption Key"));
        
        $this->addElement($this->createElement('submit', 'continue')->setLabel('Continue'));
    }
}