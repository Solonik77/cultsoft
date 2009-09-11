<?php
/**
 * Member database model
 *
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class Main_DbTable_Members extends App_Db_Table_Abstract {
    protected $_primary = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all member db fields as objects by his ID
     */
    public function getDataByID($id = 0)
    {
        return $this->fetchRow($this->select()->where('id = ?', $id));
    }
}