<?php
/**
* Base Application action controller
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
abstract class App_Controller_Action extends Zend_Controller_Action {
    // Zend_ACL Instance
    private $_acl;

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        // Make urls absolute
        $request->setBaseUrl(App::baseUri());
        // Init ACL in controller
        $this->_acl = App_Acl::getInstance();
        $doctypeHelper = new Zend_View_Helper_Doctype();
        // Set global HTML doctype
        $doctypeHelper->doctype('XHTML1_STRICT');

        $languages = App::config()->languages->toArray();
        $requestLang = App::front()->getParam('requestLang');
        $requestLangId = App::front()->getParam('requestLangId');
        // Set localized project name in page title first
        $this->view->headTitle($languages['project_title'][$requestLangId]);
        $site_pages = new Site_Model_Site_Structure();
        // Create container from array
        $container = new Zend_Navigation($site_pages->getTopMenu());
        $this->view->navigation($container);
        if ($this->getRequest()->isXmlHttpRequest()) {
            // AJAX request
            Zend_Layout::disableLayout();
            Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        }
    }
}
