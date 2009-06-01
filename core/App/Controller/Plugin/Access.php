<?php
/**
* Member access Zend Controller plugin
* Provide access to controller by ACL rules check
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/

class App_Controller_Plugin_Access extends Zend_Controller_Plugin_Abstract {
    private $_acl = null;
    /**
    * Constructor
    */
    public function __construct()
    {
        $this->_acl = new App_Acl ();
    }
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $request->setParamSources (array ('_POST'));
        $role = (App::Auth ()->hasIdentity ()) ? 'administrator' : 'guest';
        $resource = $request->getModuleName ();

        if ($this->_acl->has ($resource)) {
            if (! $this->_acl->isAllowed ($role, $resource, 'view')) {
                $request->setModuleName ('profile')->setControllerName ('index')->setActionName ('signin');
            }
        }
    }
}
