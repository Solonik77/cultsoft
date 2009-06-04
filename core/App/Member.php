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
        $data = array('id' => 0 , 'login' => 'Guest' , 'email' => 'guest@example.com' , 'created' => date("Y", TIME_NOW) , 'is_active' => 1);
        $this->_data = (object) $data;
        return $this->_data;
    }
}
