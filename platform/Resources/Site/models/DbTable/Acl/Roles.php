<?php
/**
 * ACL Roles model
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class Site_Model_DbTable_Acl_Roles extends App_DB_Table
{

    public function __construct ()
    {
        parent::__construct();
        return $this->fetchRow($this->select()->where('id = ?', 1));
    }
}