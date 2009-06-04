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
    {}
    public function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    {
        $template_path = STATIC_PATH . 'templates/' . App::Config()->project->template . '/';
        $className = App::Front()->getDispatcher()->getControllerClass($request);
        if (($className) and ! class_exists($className, false)) {
            $fileSpec = App::Front()->getDispatcher()->classToFilename($className);
            $dispatchDir = App::Front()->getDispatcher()->getDispatchDirectory();
            $test = $dispatchDir . DIRECTORY_SEPARATOR . $fileSpec;
            if (Zend_Loader::isReadable($test)) {
                include_once $test;
                $class = new Zend_Reflection_Class($request->getModuleName() . '_' . $request->getControllerName() . 'Controller');
                if (($class->getConstant('BACKOFFICE_CONTROLLER') === true) and (App::Auth()->hasIdentity() == true)) {
                    $template_path = STATIC_PATH . 'system/admin/';
                }
            }
        }
        $view = new Zend_View(array('encoding' => 'UTF-8'));
        $view->setScriptPath($template_path);
        $view->addHelperPath(CORE_PATH . 'App/View/Helper/', 'App_View_Helper');
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->setView($view)->setViewBasePathSpec($template_path)->setViewScriptPathSpec(':module/:controller/:action.:suffix')->setViewScriptPathNoControllerSpec(':module/:action.:suffix')->setViewSuffix('phtml');
        Zend_Layout::startMvc(array('layoutPath' => $template_path , 'layout' => 'layout'));
        $view->getHelper('HeadMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $view->getHelper('HeadLink')->appendStylesheet(App::baseUri() . 'static/templates/' . App::Config()->project->template . '/css/style.css');
        $view->getHelper('HeadScript')->appendFile(App::baseUri() . 'static/system/clientscripts/jquery.js');
        $view->getHelper('HeadScript')->appendScript("var action = '';
$('foo_form').action = action;");
    }
}
