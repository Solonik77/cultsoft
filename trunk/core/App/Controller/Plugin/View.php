<?php
/**
 * Setting Zend View and Layout to load views
 * from STATIC_PATH folders
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class App_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract
{

    /**
     * Constructor
     */
    public function __construct ()
    {
    }

    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $backoffice_controller = false;
        $template_path = STATIC_PATH . 'templates/' . App::Config()->project->template . '/';
        if (Zend_Registry::get('BACKOFFICE_CONTROLLER') == true and Zend_Registry::get('member_access') == 'ALLOWED') {
            $backoffice_controller = true;
            $template_path = STATIC_PATH . 'system/admin/';
        }
        $view = new Zend_View(array('encoding' => 'UTF-8'));
        $view->setScriptPath($template_path);
        $view->addHelperPath(CORE_PATH . 'App/View/Helper/', 'App_View_Helper');
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->setView($view)->setViewBasePathSpec($template_path)->setViewScriptPathSpec((($backoffice_controller !== true) ? 'html/:module/:controller/:action.:suffix' : 'html/:controller/:action.:suffix'))->setViewScriptPathNoControllerSpec((($backoffice_controller !== true) ? 'html/:module/:action.:suffix' : 'html/:action.:suffix'))->setViewSuffix('phtml');
        Zend_Layout::startMvc(array('layoutPath' => $template_path , 'layout' => 'layout'));
        // Set global content type to html with UTF-8 charset
        $view->getHelper('HeadMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        // Set default reset.css file. Clear all CSS rules.
        $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/system/css/reset.css');
        // Add default template styles to html head.
        $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . (($backoffice_controller !== true) ? 'static/templates/' . App::Config()->project->template . '/css/styles.css' : 'static/system/admin/css/styles.css'));
        // Add latest Jquery library to html head.
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery.js');
    }
}
