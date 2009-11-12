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
        $dbName = $this->createElement('text', 'db_name', array('maxlength' => 100))->setLabel('Database name')->setValue('dbname');
        $dbName->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbName);
        $dbUser = $this->createElement('text', 'db_username', array('maxlength' => 100))->setLabel('Username')->setValue('root');
        $dbUser->addValidator('stringLength', false, array(1 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbUser);
        $dbPassword = $this->createElement('password', 'db_password', array('maxlength' => 100))->setLabel('Password');
        $dbPassword->addValidator('stringLength', false, array(0 , 100))->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbPassword);
        $dbTablePrefix = $this->createElement('text', 'db_table_prefix', array('maxlength' => 20))->setLabel('Table prefix')->setValue('prefix');
        $dbTablePrefix->addValidator('Alnum')->addValidator('stringLength', false, array(0 , 20))->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($dbTablePrefix);
        $this->addDisplayGroup(array('db_server' , 'db_name' , 'db_username' , 'db_password' , 'db_table_prefix'), 'database', array("legend" => "Database connection"));
        $session = $this->createElement('select', 'session_handler');
        $session->setRequired(true)->setLabel('Session handler')->addValidator('StringLength', false, array(3 , 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags')->setMultiOptions(array('file' => 'Filesystem' , 'db' => 'Database'));
        $this->addElement($session);
        $this->addDisplayGroup(array('session_handler'), 'session', array("legend" => "Session handler"));
        $adminPath = $this->createElement('text', 'admin_path', array('maxlength' => 100))->setLabel('Admin path in url')->setValue('admin');
        $adminPath->addValidator('stringLength', false, array(4 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $adminPath->setDescription('Value used in site url for access to control pannel: http://example.com/admin/');
        $this->addElement($adminPath);
        $adminUser = $this->createElement('text', 'admin_login', array('maxlength' => 100))->setLabel('Login')->setValue('admin');
        $adminUser->addValidator('stringLength', false, array(2 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminUser);
        $adminPassword = $this->createElement('password', 'admin_password', array('maxlength' => 100))->setLabel('Password');
        $adminPassword->addValidator('Alnum')->addValidator('stringLength', false, array(5 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $adminPasswordValue = (isset($_POST['admin_password'])) ? $_POST['admin_password'] : '';
        $this->addElement($adminPassword);
        $adminPasswordConfirm = $this->createElement('password', 'admin_password_confirm', array('maxlength' => 255))->setLabel('Retype password');
        $adminPasswordConfirm->addValidator('Alnum')->addValidator('stringLength', false, array(5 , 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $adminPasswordConfirm->addValidator('Identical', false, array($adminPasswordValue));
        $this->addElement($adminPasswordConfirm);
        $adminEmail = $this->createElement('text', 'admin_email', array('maxlength' => 100))->setLabel('Email');
        $adminEmail->addValidator('EmailAddress')->addValidator('stringLength', false, array(0 , 255))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $this->addElement($adminEmail);
        $this->addDisplayGroup(array('admin_path' , 'admin_login' , 'admin_password' , 'admin_password_confirm' , 'admin_email'), 'admin_account', array("legend" => "Admin account"));
        $encryptionKey = $this->createElement('text', 'encryption_key', array('maxlength' => 100))->setLabel('Encryption Key');
        $encryptionKey->addValidator('Alnum')->addValidator('stringLength', false, array(5 , 100))->setRequired(true)->addFilter('stringTrim')->addFilter('StripTags');
        $encryptionKey->setDescription('Engine uses this key to encrypt passwords.');
        $this->addElement($encryptionKey);
        $this->addDisplayGroup(array('encryption_key'), 'encryption_key', array("legend" => "Encryption Key"));
        $this->addElement($this->createElement('submit', 'continue')->setLabel('Continue'));
    }
}