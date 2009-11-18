<?php
class App_Db_Table_Rowset extends Zend_Db_Table_Rowset_Abstract
{

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * Retrieve collection first item
     *
     * @returnZend Db Table Row
     */
    public function getFirstItem()
    {
        if($this->valid() === false){
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
        if($this->valid() === false){
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

    public function addItem(App_Db_Table_Row $row)
    {
        $this->addRow($row);
    }

    public function removeItem($position)
    {
        $this->removeRow($position);
    }
    
    public function getData()
    {
      return $this->_data;
    }
    
    public function addRow(App_Db_Table_Row $row)
    {
        echo '<br />';
        print_r($this->getData());
        echo '<br />';
        print_r($row->getData());
        die;
    }
}