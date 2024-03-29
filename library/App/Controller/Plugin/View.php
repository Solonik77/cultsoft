<?php
/**
 * Setting Zend View and Layout to load views
 *
 * @author Denysenko Dmytro
 */
class App_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract {
    private $_isBackofficeController = false;
    private $_templatePath;
    private $_view;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        try {
            $this->_initTemplatePath();
            $this->_initView();
            $this->_initViewRenderer();
            $this->_initZendLayout($request);
            $this->_loadDefaultTemplateResources();
            if ($this->_isBackofficeController) {
                $this->_loadBackofficeTemplateResources();
            } else {
                $this->_loadSiteTemplateResources();
            }
            $this->_declareDefaultVars();
        }
        catch(Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }

    private function _initTemplatePath()
    {
        $this->_isBackofficeController = false;
        $this->_templatePath = APPLICATION_PATH . 'views/' . App::config()->project->template . '/';
        if (Zend_Registry::get('BACKOFFICE_CONTROLLER') == true and Zend_Registry::get('member_access') == 'ALLOWED') {
            $this->_isBackofficeController = true;
            $this->_templatePath = APPLICATION_PATH . 'modules/main/views/backoffice/';
        }
    }

    private function _initView()
    {
        $this->_view = new Zend_View(array('encoding' => 'UTF-8'));
        $this->_view->strictVars(true);
        $this->_view->setScriptPath($this->_templatePath);
        $this->_view->addScriptPath(APPLICATION_PATH . 'modules/main/views/scripts/');
        $this->_view->addScriptPath(APPLICATION_PATH . 'modules/' . App::front()->getRequest()->getModuleName() . '/views/scripts/');
        if ($this->_isBackofficeController and App::front()->getRequest()->getModuleName() != 'main') {
            if (App::front()->getRequest()->getModuleName() != 'main') {
                $this->_view->addScriptPath(APPLICATION_PATH . 'modules/' . App::front()->getRequest()->getModuleName() . '/views/backoffice/');
            }
        }
        $this->_view->addScriptPath($this->_templatePath . 'partial/');

        $this->_view->inlineScript()->appendScript("
            document.createElement('header');
            document.createElement('nav');
            document.createElement('section');
            document.createElement('article');
            document.createElement('aside');
            document.createElement('footer');
        ", 'text/javascript', array('conditional' => ' IE '));
        $this->_view->addHelperPath(LIBRARY_PATH . 'App/View/Helper/', 'App_View_Helper');
        $this->_view->headTitle()->setSeparator(' « ');
        // Configure Zend Pagiantor
        Zend_Paginator::setDefaultScrollingStyle('Elastic');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination_control.phtml');
        $this->_view->addFilterPath('App/View/Filter', 'App_View_Filter');
        // Enable JQuery support
        $this->_view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $this->_view->jQuery()->enable();
        $this->_view->jQuery()->uiEnable();
    }

    private function _initViewRenderer()
    {
        $this->_viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $this->_viewRenderer->setView($this->_view)->setViewBasePathSpec($this->_templatePath)->setViewScriptPathSpec((($this->_isBackofficeController !== true) ? 'html/:module/:controller/:action.:suffix' : 'html/:controller/:action.:suffix'))->setViewScriptPathNoControllerSpec(
        (($this->_isBackofficeController !== true) ? 'html/:module/:action.:suffix' : 'html/:action.:suffix'))->setViewSuffix('phtml');
    }

    private function _initZendLayout(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::startMvc(array('layoutPath' => $this->_templatePath . 'layouts/' , 'layout' => 'default' , 'mvcSuccessfulActionOnly' => ('development' === APPLICATION_ENV)));
        if (! $this->_isBackofficeController) {
            if ($request->getModuleName() == 'default' and $request->getControllerName() == 'index' and $request->getActionName() == 'index' and file_exists($this->_templatePath . 'layouts/homepage.phtml')) {
                $layout->setLayout('homepage');
            } else
            if (file_exists($this->_templatePath . 'layouts/' . $request->getModuleName() . '.phtml')) {
                $layout->setLayout($request->getModuleName());
            }
        }
    }

    private function _loadDefaultTemplateResources()
    {
        // Set global content type to html with UTF-8 charset
        $this->_view->getHelper('HeadMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        // Set default reset.css file. Clear all CSS rules.
        $this->_view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/css/reset.css');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/minmax.js');
        // Add latest Jquery library to html header.
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/swfobject.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery/pngfix.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/init_global.js');
    }

    private function _loadSiteTemplateResources()
    {
        // Add default template styles to html header.
        $this->_view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/view_resources/' . App::config()->project->template . '/css/styles.css');
    }

    private function _loadBackofficeTemplateResources()
    {
        $this->_view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/backoffice/css/style.css');

        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/superfish.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/tooltip.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/tablesorter.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/tablesorter-pager.js');
        $this->_view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/cookie.js');
    }

    private function _declareDefaultVars()
    {
        $languages = App::I18N()->getSiteLanguages();
        $requestLang = App::front()->getParam('requestLang');
        $requestLangId = App::front()->getParam('requestLangId');
        $this->_view->getHelper('DeclareVars')->declareVars(
        array('member' => App_Member::getInstance() , 'uploadedIMG' => App::baseUri() . 'static/upload/images/' , 'requestLang' => $requestLang ,
                'projectTitle' => $languages[$requestLangId]['project_title'] ,
                'baseUrl' => App::baseUri() , 'tplJS' => App::baseUri() . ((! $this->_isBackofficeController) ? 'static/view_resources/' . App::config()->project->template . '/clientscripts/' : 'static/system/backoffice/clientscripts/') , 'tplCSS' => App::baseUri() . ((! $this->_isBackofficeController) ? 'static/view_resources/' . App::config()->project->template . '/css/' : 'static/system/backoffice/css/') , 'tplIMG' => App::baseUri() . ((! $this->_isBackofficeController) ? 'static/view_resources/' . App::config()->project->template . '/images/' : 'static/system/backoffice/images/')));
    }
}
