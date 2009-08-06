<?php
/**
* Default access control list for application.
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class App_Acl extends Zend_Acl {
    // ACL singleton
    private static $instance = null;

    /**
    * Constructor
    */
    public function __construct()
    {
            $this->_permCache = App_Cache::getInstance('permCache');
            /**
            * Getting member roles from system cache
            */
            $acl = $this->getAclRoles();
            $res = current($acl);
            $resources = array();
            if ($res) {
                foreach($res as $key => $value) {
                    if ((strlen($key) > 4) and (substr($key, 0, 4) == 'res_')) {
                        $resources[substr($key, 4)] = (bool) $value;
                        $this->add(new Zend_Acl_Resource(substr($key, 4)));
                    }
                }
            }
            foreach($acl as $role) {
                $this->addRole(new Zend_Acl_Role($role['role']));
                foreach($resources as $key => $value) {
                    if ($role['role'] == 'guest') {
                        $value = false;
                    }
                    if ($role['role'] == 'administrator') {
                        $value = true;
                    }
                    if ($value) {
                        $this->allow($role['role'], $key);
                    } else {
                        $this->deny($role['role'], $key);
                    }
                }
            }

    }

    /**
    * Get cached ACL Roles
    */
    public function getAclRoles()
    {
        $data = null;
        if (! ($data = $this->_permCache->load('AclRoles'))) {
            $model = new Main_Model_DbTable_Acl_Roles();
            $model = $model->fetchAll()->toArray();
            $data = array(0 => array('id' => 0 , 'parent' => 0 , 'role' => 'guest' , 'description' => 'Guest Account'));
            foreach($model as $item) {
                $data[$item['id']] = $item;
            }
            $this->_permCache->save($data);
        }
        return $data;
    }
    
    /**
    * Get cached system info
    */
    public function getAclResources()
    {
        $data = null;
        if (! ($data = $this->_permCache->load('AclResources'))) {
            $model = new Main_Model_DbTable_Acl_Resources();
            $model = $model->fetchAll()->toArray();
            $data = array();
            foreach($model as $item) {
                $data[$item['id']] = $item;
            }
            $this->_permCache->save($data);
        }
        return $data;
    }
}
