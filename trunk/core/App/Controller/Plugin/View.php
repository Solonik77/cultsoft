<?php
/**
* Setting Zend View and Layout to load views
* from STATIC_PATH folders
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class App_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract {
    /**
    * Constructor
    */
    public function __construct ()
    {
    }

    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        try {
            $backoffice_controller = false;
            $template_path = STATIC_PATH . 'templates/' . App::Config()->project->template . '/';
            if (Zend_Registry::get('BACKOFFICE_CONTROLLER') == true and Zend_Registry::get('member_access') == 'ALLOWED') {
                $backoffice_controller = true;
                $template_path = STATIC_PATH . 'system/admin/';
            }
            $view = new Zend_View(array('encoding' => 'UTF-8'));
            $view->strictVars(true);
            $view->setScriptPath($template_path);
            $view->addScriptPath($template_path . 'partial/');
            $view->addHelperPath(CORE_PATH . 'App/View/Helper/', 'App_View_Helper');
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $viewRenderer->setView($view)->setViewBasePathSpec($template_path)->setViewScriptPathSpec((($backoffice_controller !== true) ? 'html/:module/:controller/:action.:suffix' : 'html/:controller/:action.:suffix'))->setViewScriptPathNoControllerSpec((($backoffice_controller !== true) ? 'html/:module/:action.:suffix' : 'html/:action.:suffix'))->setViewSuffix('phtml');
            $layout = Zend_Layout::startMvc(array('layoutPath' => $template_path . 'layouts/' , 'layout' => 'default' , 'mvcSuccessfulActionOnly' => ('development' === APPLICATION_ENV)));
            if (! $backoffice_controller) {
                if ($request->getModuleName() == 'default' and $request->getControllerName() == 'index' and $request->getActionName() == 'index' and file_exists($template_path . 'layouts/homepage.phtml')) {
                    $layout->setLayout('homepage');
                } else
                if (file_exists($template_path . 'layouts/' . $request->getModuleName() . '.phtml')) {
                    $layout->setLayout($request->getModuleName());
                }
            }
            // Set global content type to html with UTF-8 charset
            $view->getHelper('HeadMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
            // Set default reset.css file. Clear all CSS rules.
            $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/css/reset.css');
            // Add default template styles to html header.
            $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . (($backoffice_controller !== true) ? 'static/templates/' . App::Config()->project->template . '/css/styles.css' : 'static/system/admin/css/styles.css'));
            $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/minmax.js');
            // Add latest Jquery library to html header.
            $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery.js');
            $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/swfobject.js');
            $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery/pngfix.js');
            if ($backoffice_controller) {
                $view->getHelper('HeadMeta')->appendHttpEquiv('Designer', 'ne-design (www.ragard-jp.com) KuroAdmin Theme');
                $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/admin/css/elements.css');
                $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/css/smoothness/jquery.ui.css');
                $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery/ui.js');
            }
            $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/init_global.js');
            $requestLang = App::Front()->getParam('requestLang');
            $view->getHelper('DeclareVars')->declareVars(array('requestLang' => App::baseUri() . 'static/upload/images/' , 'requestLang' => $requestLang , 'projectTitle' => App::Config()->project->title->$requestLang , 'baseUrl' => App::baseUri() , 'tplJS' => App::baseUri() . ((! $backoffice_controller) ? 'static/templates/' . App::Config()->project->template . '/clientscripts/' : 'static/system/admin/clientscripts/') , 'tplCSS' => App::baseUri() . ((! $backoffice_controller) ? 'static/templates/' . App::Config()->project->template . '/css/' : 'static/system/admin/css/') , 'tplIMG' => App::baseUri() . ((! $backoffice_controller) ? 'static/templates/' . App::Config()->project->template . '/images/' : 'static/system/admin/images/')));
        }
        catch (Exception $e) {
            throw new App_Exception($e->getMessage());
        }
    }
}
