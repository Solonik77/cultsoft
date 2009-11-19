<?php
class App_Db_Table_Rowset extends Zend_Db_Table_Rowset_Abstract
{
    protected $_isNewCollection = FALSE;
    
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

    public function createItem(array $data = array(), $defaultSource = null)
    {     
        return $this->createRow($data, $defaultSource);
    }

    public function createRow(array $data = array(), $defaultSource = null)
    {     
        return $this->_table->createCollectionItem($data, $defaultSource);
    }        
    
    public function addRow(App_Db_Table_Row $row)
    {     
        $this->_data[] = $row->getData();
        $this->_count = count($this->_data);
        $this->_pointer = $this->_count - 1;
    }
    
    public function setIsNewCollection($value)
    {
        $this->_readOnly = false;
        $this->_stored = 1;
        $this->_count = 0;
        $this->_pointer = 0;
        $this->_isNewCollection = (bool) $value;
        return $this;
    }
    
    public function getRows()
    {
           return $this->_rows;
    }
    
    public function getIsNewCollection()
    {
       return $this->_isNewCollection;
    }

    public function save()
    {
        if(count($this->_data) > 0){
            $data = array();
            App::db()->beginTransaction();
            try{
                foreach($this->_data as $key => $value){
              if(array_key_exists('id', $value) && $value['id'] === NULL){               
                unset($value['id']);
                $this->_table->insert($value);
              } else {
                $this->_table->update($value);
              } 
                }
                App::db()->commit();
                return true;
            }
            catch(Exception $e){
                App::db()->rollBack();
                App::log($e->getMessage(), 3);
                return false;
            }
        }
    }
}