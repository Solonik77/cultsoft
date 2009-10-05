<?php

class App_Db_Table_Select extends Zend_Db_Table_Select
{
    public function __construct(Zend_Db_Table_Abstract $table)
    {
        parent::__construct($table);
    }

    public function from($name, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if(substr($name, 0, strlen(DB_TABLE_PREFIX)) != DB_TABLE_PREFIX)
        {
            $name = DB_TABLE_PREFIX . $name;
        }
        return parent::from($name, $cols, $schema);
    }

    public function join($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if(substr($name, 0, strlen(DB_TABLE_PREFIX)) != DB_TABLE_PREFIX)
        {
            $name = DB_TABLE_PREFIX . $name;
        }
        return parent::join($name, $cond, $cols, $schema);
    }
}