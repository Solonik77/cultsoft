<?php
/**
* Settings database model
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class Main_DbTable_Settings extends App_Db_Table_Abstract {
    protected $_primary = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}