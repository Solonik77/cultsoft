<?php
class Install_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $pages = new Install_Component_Structure();
        $this->view->headTitle('Engine installer');        
        $this->view->sideNavigationMenu = new App_Navigation_Sequence($pages->getSequence());
    }

   public function indexAction()
    {
        $this->view->pageTitle = 'Pre-installation Check';
        $this->view->pageDescription = '';
    }

    public function licenseAction()
    {
        $this->view->pageTitle = 'License';
        $this->view->pageDescription = '';
    }
}