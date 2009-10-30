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
            if(!isset($actions[$action['action']])){
                $actions[$action['action']] = 0;
            }
        }
        unset($actions['index']);
        $this->_session->actions = $actions;
        $action = $this->_request->getActionName();
        if($action != 'index' AND $actions[$action] === 0)
        {
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
        $this->view->form = new Install_Form_Base;
        $sysInfo = new Main_Model_SystemInfo();
        $this->view->check_php_extentions = $sysInfo->checkPHPExtentions();
        if($this->_request->isPost()){
            if($this->view->form->isValid($this->_request->getPost()) AND $sysInfo->isValidFilesystem())
            {
                $this->_session->actions['config'] = 1;
                $this->_redirect('install/config');
            }
        }
    }

    public function configAction()
    {
        $this->view->pageTitle = 'Base Configuration';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Config();
        $this->view->form->setAction('install/create-admin');
        $this->_session->actions['create-admin'] = 1;
    }

    public function createAdminAction()
    {
        $this->view->pageTitle = 'Create admin account';
        $this->view->pageDescription = '';
        $this->view->form = new Install_Form_Administrator();
        $this->view->form->setAction('install/modules');
        $this->_session->actions['modules'] = 1;
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
