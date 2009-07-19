<?php
/**
* Admin Index controller.
* This is dashboard controller.
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class System_AdminDashboardController extends App_Controller_Action {
    CONST BACKOFFICE_CONTROLLER = true;

    public function init()
    {
        $this->view->pageTitle = __('Dashboard');
        $this->view->pageDescription = __('Fast access links, statistics, system information');
        $this->view->headTitle($this->view->pageTitle);
    }

    /**
    * Default system action
    */
    public function indexAction()
    {

    }
}
