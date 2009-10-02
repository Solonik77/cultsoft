<?php
class App_Collection_Db extends App_Collection_Abstract
{
    protected $_table;
    /*
     * Zend_Db_Select object
     * */
    protected $_select;

    public function __construct(App_Db_Table_Abstract $table)
    {
        $this->setTable($table);
        $this->_select = $this->getTable()->select();
    }

    public function __destruct()
    {
    }

    public function getTable()
    {
        return $this->_table;
    }

    public function setTable(App_Db_Table_Abstract $table)
    {
        $this->_table = $table;
        return $this;
    }

    public function getSelect()
    {
        return $this->_select;
    }

    public function find($condition)
    {
       $rowset = $this->_table->find($condition);
       if($rowset)
       {
        $this->_items = $rowset;
        $this->_count = count($rowset);
       }
       return $this;
    }
}