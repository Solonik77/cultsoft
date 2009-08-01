<?php
/**
* Languages
*
* @author Denysenko Dmytro
*/
require_once 'Zend/Db/Table/Abstract.php';
class System_Model_DbTable_Site_Languages extends App_Db_Table_Abstract {
    protected $_primary = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}
