<?php
/**
 * Default access control list for application.
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class App_Acl extends Zend_Acl
{
    // ACL singleton
    private static $instance = null;

    /*
 * Constructor
 */
    public function __construct ()
    {
        if (App_Acl::$instance === null) {
            // Add acl resource
            $this->add(new Zend_Acl_Resource('module_admin'));
            // Add groups to the Role registry using Zend_Acl_Role
            // Guest does not inherit access controls
            $roleGuest = new Zend_Acl_Role('guest');
            $this->addRole($roleGuest);
            // Staff inherits from guest
            $this->addRole(new Zend_Acl_Role('staff'), $roleGuest);
            /**
             * Alternatively, the above could be written:
             * $this->addRole(new Zend_Acl_Role('staff'), 'guest');
             */
            // Editor inherits from staff
            $this->addRole(new Zend_Acl_Role('editor'), 'staff');
            // Administrator does not inherit access controls
            $this->addRole(new Zend_Acl_Role('administrator'));
            $this->allow('administrator');
        }
        App_Acl::$instance = $this;
    }

    public static function getInstance ()
    {
        if (App_Acl::$instance == null) {
            // Create a new instance
            new App_Acl();
        }
        return App_Acl::$instance;
    }
}
