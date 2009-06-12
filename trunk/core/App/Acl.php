<?php
/**
 * Default access control list for application.
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/platform/license.html
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
            $acl = App_Cache::getInstance()->getAclRoles();
            $res = current($acl);
            $resources = array();
            foreach ($res as $key => $value) {
                if ((strlen($key) > 4) and (substr($key, 0, 4) == 'res_')) {
                    $resources[substr($key, 4)] = (bool) $value;
                    $this->add(new Zend_Acl_Resource(substr($key, 4)));
                }
            }
            foreach ($acl as $role) {
                $this->addRole(new Zend_Acl_Role($role['role']));
                foreach ($resources as $key => $value) {
                    if ($role['role'] == 'guest') {
                        $value = FALSE;
                    }
                    if ($role['role'] == 'administrator') {
                        $value = TRUE;
                    }
                    if ($value) {
                        $this->allow($role['role'], $key);
                    } else {
                        $acl->deny($role['role'], $key);
                    }
                }
            }
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
