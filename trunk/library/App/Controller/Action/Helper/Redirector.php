<?php
/**
* Redirector helper.
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/

require_once 'Zend/Controller/Action/Helper/Redirector.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class App_Controller_Action_Helper_Redirector extends Zend_Controller_Action_Helper_Redirector {
    public function selfRedirect()
    {
        $curPath = App::front()->getRequest()->getPathInfo();
        $this->getUseAbsoluteUri(true);
        $this->setExit(false);
        $this->setCloseSessionOnExit(true);
        $this->setGotoUrl($curPath);
        return;
    }
}
