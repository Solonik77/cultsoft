<?php
/**
 * Member database model
 *
 * @author Denysenko Dmytro
 */
class Main_Model_DbTable_Site_Structure extends App_Db_Nestedsets {
    protected $_primary = 'id';

    public function __construct($config = array())
    {
        parent::__construct($config);
    }
}