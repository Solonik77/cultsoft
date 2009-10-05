<?php

class App_Db_Table_Select extends Zend_Db_Table_Select
{
    public function __construct(Zend_Db_Table_Abstract $table)
    {
        parent::__construct($table);
    }
}