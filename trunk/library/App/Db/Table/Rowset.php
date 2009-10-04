<?php

class App_Db_Table_Rowset extends Zend_Db_Table_Rowset_Abstract
{
    /**
     * Retrieve collection first item
     *
     * @returnZend Db Table Row
     */
    public function getFirstItem()
    {
        if ($this->valid() === false) {
            return null;
        }
        return $this->rewind()->current();            
    }
    
    /**
     * Retrieve collection last item
     *
     * @return Zend Db Table Row
     */
    public function getLastItem()
    {
        if ($this->valid() === false) {
            return null;
        }        
        return $this->getRow($this->count() - 1);
    }
    
        /**
     * Retrieve collection items
     *
     * @return DB  rowset
     */
    public function getItems()
    {
        return $this;
    }
    
    public function getCollection()
    {
        return $this;
    }
    
    
}