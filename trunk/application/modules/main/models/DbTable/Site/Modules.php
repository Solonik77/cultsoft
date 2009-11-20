<?php
/**
 * Modules information table
 *
 * @author Denysenko Dmytro
 */
class Main_Model_DbTable_Site_Modules extends App_Db_Table {
    protected $_primary = 'id';

    public function __construct($config = array())
    {
        parent::__construct($config);
    }
}