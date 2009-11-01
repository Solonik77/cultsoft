<?php
class Install_IndexController extends Zend_Controller_Action
{
    private $_session;

    public function init()
    {
        $pages = new Install_Component_Structure();
        $this->view->headTitle('Engine installer');
        $this->view->sideNavigationMenu = new Zend_Navigation($pages->getSequence());
        $this->_session = new Zend_Session_Namespace('Installer_Session');
        $actions = $this->_session->actions;
        foreach($pages->getSequence() as $action){
            if(! isset($actions[$action['action']])){
                $actions[$action['action']] = 0;
            }
        }
        unset($actions['index']);
        $this->_session->actions = $actions;
        $action = $this->_request->getActionName();
        if($action != 'index' and $actions[$action] === 0){
            $this->_redirect('install');
        }
    }

    public function indexAction()
    {
        $this->view->pageTitle = 'License';
        $this->view->pageDescription = '';
        $form = new Install_Form_License();
        $fileLicense = DOC_ROOT . 'LICENSE.txt';
        if(file_exists($fileLicense) and is_readable($fileLicense)){
            $form->setLicenseContent(file_get_contents($fileLicense));
        }
        if($this->_request->isPost()){
            $this->view->form = $form->compose();
            $form->populate($this->_request->getPost());
            if($form->isValid($this->_request->getPost()) != TRUE){
                unset($this->_session->actions);
                // Errors in input data
                return $this->render();
            }
            else{
                $this->_session->actions['pre-installation-check'] = 1;
                $this->_redirect('install/pre-installation-check');
            }
        }
        else{
            $this->view->form = $form->compose();
        }
    }

    public function preInstallationCheckAction()
    {
        $this->view->pageTitle = 'Pre-installation Check';
        $this->view->pageDescription = '';
        $hasError = FALSE;
        $this->view->form = new Install_Form_Base();
        $sysInfo = new Main_Model_SystemInfo();
        $this->view->sysInfo = $sysInfo;
        if(version_compare($sysInfo->getPhpVersion(), $sysInfo->getRequiredPhpVersion()) < 0){
            $hasError = TRUE;
        }
        $this->view->check_php_extensions = $sysInfo->checkRequiredPHPExtensions();
        $this->view->check_filesystem = $sysInfo->checkWritableSystemDirectories();
        if(! $hasError){
            foreach($this->view->check_php_extensions as $ext){
                if(! $ext['status'] and $ext['hault']){
                    $hasError = TRUE;
                    break;
                }
            }
        }
        if(! $hasError){
            foreach($this->view->check_filesystem as $dir){
                if(! $dir['is_writable']){
                    $hasError = TRUE;
                    break;
                }
            }
        }
        if($this->_request->isPost()){
            if($this->view->form->isValid($this->_request->getPost()) and $hasError === FALSE){
                $this->_session->actions['config'] = 1;
                $this->_redirect('install/config');
            }
        }
    }

    public function configAction()
    {
        $this->view->pageTitle = 'Base Configuration';
        $this->view->pageDescription = '';
        $form = new Install_Form_Config();        
        if($this->_request->isPost()){
        $form->populate($this->_request->getPost());
        if($form->isValid($this->_request->getPost())) {
            
            $config = new Zend_Config(array(), true);
            $config->production = array();
            $config->production->database = array();
            $config->production->database->adapter = "pdo_mysql";
            $config->production->database->host = $_POST['db_server'];
            $config->production->database->username = $_POST['db_username'];
            $config->production->database->password = $_POST['db_password'];
            $config->production->database->dbname = $_POST['db_name'];
            $config->production->database->table_prefix = $_POST['db_table_prefix'] . "_";
            $config->production->backoffice_path = $_POST['admin_path'];
            $config->production->session_save_handler = "file";
            $writer = new Zend_Config_Writer_Ini();
            $writer->setConfig($config)->setFilename(VAR_PATH . 'initial.configuration.ini');
            $writer->write();
            $this->_session->actions['modules'] = 1;
            $this->_redirect('install/modules');
            }
        }
        $this->view->form = $form;
    }

    public function modulesAction()
    {
        $this->view->pageTitle = 'Install modules';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Modules();
        $this->view->form->setAction('install/finish');
        $this->_session->actions['finish'] = 1;
    }

    public function finishAction()
    {
        $this->view->pageTitle = 'Finish installation';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Finish();
    }
}
