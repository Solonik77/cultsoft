<?php
/**
* Base Application action controller
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
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
        if (Zend_Registry::get('BACKOFFICE_CONTROLLER')) {
            $this->view->headTitle(__('Control panel') . ' — ' . $languages['project_title'][$requestLangId]);
            $site_pages = new System_Component_BackofficeStructure();
            $this->view->topMenu = new Zend_Navigation($site_pages->getTopMenu());
            $this->view->footerMenu = new Zend_Navigation($site_pages->getFooterMenu());
            $inflector = new Zend_Filter_Inflector('sidebar/:sidebarblock.:suffix');
            $inflector->setRules(array(':sidebarblock' => array('Word_CamelCaseToDash' , 'StringToLower') , 'suffix' => 'phtml'));
            $filtered = $inflector->filter(array('sidebarblock' => $this->getRequest()->getControllerName()));
            $sidebarBlockFile = APPLICATION_PATH . 'modules/' . $this->getRequest()->getModuleName() . '/views/backoffice/' . $filtered;
            $sidebarBlock = $filtered;
            if (! file_exists($sidebarBlockFile)) {
                $sidebarBlock = 'sidebar/default.phtml';
            }
            $this->view->sidebarBlocks = $sidebarBlock;
        } else {
            $this->view->headTitle($languages['project_title'][$requestLangId]);
            $site_pages = new System_Component_SiteStructure();
            $container = new Zend_Navigation($site_pages->getTopMenu());
            $this->view->navigation($container);
        }
        // Resource autoload
        $module = ucfirst(strtolower($request->getParam('module')));
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH . 'modules/' . $module , 'namespace' => $module));
        $resourceLoader->addResourceTypes(
            array('component' => array('namespace' => 'Component' , 'path' => 'components') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'dbtable' => array('namespace' => 'Model_DbTable' , 'path' => 'models/DbTable') , 'form' => array('namespace' => 'Form' , 'path' => 'forms') , 'model' => array('namespace' => 'Model' , 'path' => 'models') , 'plugin' => array('namespace' => 'Plugin' , 'path' => 'plugins') , 'service' => array('namespace' => 'Service' , 'path' => 'services') , 'helper' => array('namespace' => 'Helper' , 'path' => 'helpers') , 'viewhelper' => array('namespace' => 'View_Helper' , 'path' => 'views/helpers') , 'viewfilter' => array('namespace' => 'View_Filter' , 'path' => 'views/filters')));
        if ($this->getRequest()->isXmlHttpRequest()) {
            // AJAX request
            Zend_Layout::disableLayout();
            Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        }
    }

    /**
    * Redirect to another URL
    *
    * Proxies to {@link Zend_Controller_Action_Helper_Redirector::gotoUrl()}.
    *
    * @param string $url
    * @param array $options Options to be used when redirecting
    * @return void
    */
    protected function _selfRedirect(array $options = array())
    {
        $url = App::front()->getRequest()->getPathInfo();
        $this->_helper->redirector->gotoUrl($url, $options);
    }
}
