<?php
/**
 * Settings database model
 *
 * @author Denysenko Dmytro


 */
class Main_DbTable_Settings extends App_Db_Table_Abstract {
    protected $_primary = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}