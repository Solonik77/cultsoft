<?php
/**
 * Member Information
 *
 * $Id$
 *
 * @author Denysenko Dmytro
 */
class App_Member {
    // Member singleton
    protected static $instance = null;
    protected $_data;
    protected $_model;
    protected $_acl;

    public static function getInstance()
    {
        if (App_Member::$instance == null) {
            // Create a new instance
            new App_Member();
        }
        return App_Member::$instance;
    }
    private function __clone(){}

    /**
     * Constructor
     */
    final private function __construct()
    {
        if (App_Member::$instance === null) {
            $this->_model = new Main_Model_DbTable_Members();
            $this->_acl = new App_Acl();
            if (App_Member::getAuth()->hasIdentity()) {
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
        $this->_data = (object) array('id' => 0 , 'login' => 'Guest' , 'email' => 'guest@example.com' , 'created' => Vendor_Helper_Date::utc_now() , 'is_active' => 1 , 'language_id' => 1 , 'role' => 'guest' , 'acl_resource' => array());
        return $this->_data;
    }

    /**
     * Load logged member information
     */
    private function loadMember($id)
    {
        $data = (object) $this->_model->find($id)->getCollection()->getFirstItem()->toArray();
        $roles = $this->getAcl()->getRoles();
        foreach($roles as $role) {
            if ($role['id'] === $data->role_id) {
                $data->role = $role['role'];
                $data->role_description = $role['description'];
                $data->acl_resource = array();
                foreach($role as $id => $resource) {
                    if ((strlen($id) > 4) and (substr($id, 0, 4) == 'res_')) {
                        // Administrator always have all privileges
                        if ($role['id'] == 1) {
                            $resource = 1;
                        }
                        $data->acl_resource[substr($id, 4)] = intval($resource);
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
    public function getId()
    {
        return $this->getField('id');
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
        return ((isset($this->_data->$field)) ? $this->_data->$field : null);
    }

    /**
     * Get member all data
     */
    public function getData()
    {
        return $this->_data;
    }

    public function getAcl()
    {
        return $this->_acl;
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