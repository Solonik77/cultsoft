<?php
/**
* Member Information
*
* $Id$
*
* @package Core
* @author Denysenko Dmytro
* @copyright(c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class App_Member {
    // Member singleton
    protected static $instance = null;
    protected $_data = null;
    protected $_model = null;

    public static function getInstance()
    {
        if(App_Member::$instance == null) {
            // Create a new instance
            new App_Member();
        }
        return App_Member::$instance;
    }

    /**
    * Constructor
    */
    public function __construct()
    {
        if(App_Member::$instance === null) {
            $this->_model = new Site_Model_DbTable_Members();
            if(App_Member::getAuth()->hasIdentity()) {
                $this->loadMember(App_Member::getAuth()->getIdentity()->id);
            } else {
                $this->loadGuest();
            }
            App_Member::$instance = $this;
        }
    }

    /**
    * Load default user information for guest
    */
    private function loadGuest()
    {
        $this->_data =(object) array('id' => 0, 'login' => 'Guest', 'email' => 'guest@example.com', 'created' => V_Helper_Date::now(), 'is_active' => 1, 'role' => 'guest', 'acl_resource' => array());
        return $this->_data;
    }

    /**
    * Load logged member information
    */
    private function loadMember($id)
    {
        $data = $this->_model->getDataByID($id);
        $data =(object) $data->toArray();
        $roles = App_Cache::getInstance()->getAclRoles();
        foreach($roles as $role) {
            if($role ['id'] === $data->role_id) {
                $data->role = $role ['role'];
                $data->role_description = $role ['description'];
                $data->acl_resource = array();
                foreach($role as $id => $resource) {
                    if((strlen($id) > 4) and(substr($id, 0, 4) == 'res_')) {
                        // Administrator always have all privileges
                        if($role ['id'] == 1) {
                            $resource = 1;
                        }
                        $data->acl_resource [substr($id, 4)] = intval($resource);
                    }
                }
                break;
            }
        }
        return $this->_data = $data;
    }

    /**
    * Get current member role
    */
    public function getRole()
    {
        return $this->getField('role');
    }

    /**
    * Get member field
    */
    public function getField($field)
    {
        return((isset($this->_data->$field)) ? $this->_data->$field : null);
    }

    /**
    * Get member all data
    */
    public function getData()
    {
        return $this->_data;
    }

    /**
    * Auth check
    */
    public static function isAuth()
    {
        return App_Member::getAuth()->hasIdentity();
    }

    /**
    * Auth instance
    */
    public static function getAuth()
    {
        return Zend_Auth::getInstance();
    }
}
