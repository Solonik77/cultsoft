<?php
/**
 * Member Information
 *
 * $Id$
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class App_Member
{
    // Member singleton
    protected static $instance = null;
    protected $_data = null;
    protected $_model = null;
    public static function getInstance ()
    {
        if (App_Member::$instance == null) {
            // Create a new instance
            new App_Member();
        }
        return App_Member::$instance;
    }
    /**
     * Constructor
     */
    public function __construct ()
    {
        if (App_Member::$instance === null) {
        $this->_model = new Site_Model_DbTable_Members();
            if (App::Auth()->hasIdentity()) {                
                $this->loadMember(App::Auth()->getIdentity()->member_id);                
            } else {
                $this->loadGuest();
            }
            App_Member::$instance = $this;
        }
    }
    /**
     * Load default user information for guest
     */
    private function loadGuest ()
    {
        $data = array('id' => 0 , 'login' => 'Guest' , 'email' => 'guest@example.com' , 'created' =>  V_Helper_Date::now() , 'is_active' => 1, 'role' => 'guest');
        $this->_data = (object) $data;
        return $this->_data;
    }
    
     /**
     * Load logged member information
     */
    private function loadMember ($id)
    {
        die('@todo ' . __CLASS__);
    }
    
    /*
    Get current member role
    */

    public function GetRole()
    {
        return $this->_data->role;
    }
    
    
}