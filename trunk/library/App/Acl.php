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

        $roles = $this->getRoles();
        $resources = $this->getResources();
        $roles_resources = $this->getRolesResources();
        if (count($resources) > 0) {
            foreach($resources as $value) {
                $this->add(new Zend_Acl_Resource($value['resource']));
            }
        }

        foreach($roles as $role) {
            $this->addRole(new Zend_Acl_Role($role['role']));
        }

        foreach($roles_resources as $key => $value) {
            $isAllow = (bool) $value['is_allow'];
            if ($roles[$value['role_id']]['role'] == 'guest') {
                $isAllow = false;
            }
            if ($roles[$value['role_id']]['role'] == 'administrator') {
                $isAllow = true;
            }

            if ($isAllow) {
                $this->allow($roles[$value['role_id']]['role'], $resources[$value['resource_id']]['resource']);
            } else {
                $this->deny($roles[$value['role_id']]['role'], $resources[$value['resource_id']]['resource']);
            }
        }
    }

    /**
    * Get cached ACL Roles
    */
    public function getRoles()
    {
        $data = null;
        if (! ($data = $this->_permCache->load('AclRoles'))) {
            $model = new Main_DbTable_Acl_Roles();
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
    * Get cached ACL resources
    */
    public function getResources()
    {
        $data = null;
        if (! ($data = $this->_permCache->load('AclResources'))) {
            $model = new Main_DbTable_Acl_Resources();
            $model = $model->fetchAll()->toArray();
            $data = array();
            foreach($model as $item) {
                $data[$item['id']] = $item;
            }
            $this->_permCache->save($data);
        }
        return $data;
    }

    /**
    * Get cached ACL resources
    */
    public function getRolesResources()
    {
        $data = null;
        if (! ($data = $this->_permCache->load('AclRolesResources'))) {
            $model = new Main_DbTable_Acl_Roles_Resources();
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