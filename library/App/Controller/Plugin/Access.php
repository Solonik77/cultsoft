<?php
/**
 * Member access Zend Controller plugin
 * Provide access to controller by ACL rules check
 *
 * @author Denysenko Dmytro
 */
class App_Controller_Plugin_Access extends Zend_Controller_Plugin_Abstract
{
    // Zend_ACL Instance
    private $_acl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_acl = App_Member::getInstance()->getAcl();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($request->getModuleName() == 'install' and file_exists(VAR_PATH . 'configuration.ini')){
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoUrl(App::baseUri());
        }
        Zend_Registry::set('member_access', 'ALLOWED');
        Zend_Registry::set('BACKOFFICE_CONTROLLER', false);
        $className = App::front()->getDispatcher()->getControllerClass($request);
        if(($className) and ! class_exists($className, false)){
            $fileSpec = App::front()->getDispatcher()->classToFilename($className);
            $dispatchDir = App::front()->getDispatcher()->getDispatchDirectory();
            $test = $dispatchDir . DS . $fileSpec;
            if(Zend_Loader::isReadable($test)){
                include_once $test;
                $class = new Zend_Reflection_Class($request->getModuleName() . '_' . $request->getControllerName() . 'Controller');
                if($class->getConstant('BACKOFFICE_CONTROLLER') === true){
                    Zend_Registry::set('BACKOFFICE_CONTROLLER', true);
                }
            }
        }
        if(Zend_Registry::get('BACKOFFICE_CONTROLLER') and ! App_Member::getAuth()->hasIdentity()){
            $request->setModuleName('main')->setControllerName('profile')->setActionName('signin');
            Zend_Registry::set('member_access', 'NOT_AUTHORIZED');
            return;
        }
        $role = App_Member::getInstance()->getRole();
        if(! $role){
            $role = 'guest';
        }
        $resource = strtolower($request->getModuleName() . '_' . $request->getControllerName());
        if($this->_acl->has($resource)){
            if(! $this->_acl->isAllowed($role, $resource)){
                Zend_Registry::set('member_access', 'ACCESS_DENY');
                $request->setModuleName('main')->setControllerName('error')->setActionName('deny');
            }
            else{
                Zend_Registry::set('member_access', 'ALLOWED');
            }
        }
    }
}
