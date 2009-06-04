<?php
/**
 * Base Application action controller
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
abstract class App_Controller_Action extends Zend_Controller_Action
{
    public function __construct (Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        // Make urls absolute
        $request->setBaseUrl(App::baseUri());
        $doctypeHelper = new Zend_View_Helper_Doctype();
        // Set global HTML doctype
        $doctypeHelper->doctype('XHTML1_STRICT');
        $requestLang = App::Front()->getParam('requestLang');
        // Set localized project name in page title first
        $this->view->headTitle(App::Config()->project->name->$requestLang);
        if ($this->getRequest()->isXmlHttpRequest()) {
            // AJAX request
            Zend_Layout::disableLayout();
            Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        }
    }
}