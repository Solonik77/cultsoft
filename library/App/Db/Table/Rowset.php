<?php
class App_Db_Table_Rowset extends Zend_Db_Table_Rowset_Abstract implements App_Collection_Interface {
    protected $_isNewCollection = false;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function addItem($row)
    {
        if (! $row instanceof App_Db_Table_Row) {
            throw new App_Exception("Row to add must be instance of App_Db_Table_Row class.");
        }
        $this->addRow($row);
    }

    public function addRow(App_Db_Table_Row $row)
    {
        $this->_data[] = $row->getData();
        $this->_count = count($this->_data);
        $this->_pointer = $this->_count - 1;
    }

    public function createItem(array $data = array(), $defaultSource = null)
    {
        return $this->createRow($data, $defaultSource);
    }

    public function createRow(array $data = array(), $defaultSource = null)
    {
        return $this->_table->createCollectionItem($data, $defaultSource);
    }

    public function getCollection()
    {
        return $this;
    }

    /**
     * Retrieve collection items
     *
     * @return DB rowset
     */
    public function getItems()
    {
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getRows()
    {
        if ($this->valid() === false) {
            return null;
        }
        return $this->_rows;
    }

    /**
     * Retrieve collection first item
     *
     * @return Zend Db Table Row
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
    public function getIsNewCollection()
    {
        return $this->_isNewCollection;
    }

    public function setIsNewCollection()
    {
        $this->_readOnly = false;
        $this->_stored = 1;
        $this->_count = 0;
        $this->_pointer = 0;
        $this->_isNewCollection = true;
        return $this;
    }

    public function removeItemByKey($position)
    {
        $this->removeRow($position);
    }

    public function removeRow($position)
    {
        if ($this->valid() === false) {
            return null;
        }
        if (isset($this->_data[$position])) {
            unset($this->_data[$position]);
            $this->rewind();
        }
    }

    public function updateItem(App_Db_Table_Row $row)
    {
        return $this->updateRow($row);
    }

    public function updateRow(App_Db_Table_Row $row)
    {
        $rowData = $row->getData();
        foreach($this->_data as $key => $value)
        {
            if($value['id'] == $rowData['id'])
            {
                $this->_data[$key] = $rowData;
                break;
            }
        }
    }

    public function save()
    {
        if (count($this->_data) > 0) {
            $data = array();
            App::db()->beginTransaction();
            try {
                foreach($this->_data as $key => $value) {
                    if (array_key_exists('id', $value) && $value['id'] === null) {
                        unset($value['id']);
                        $id = $this->_table->insert($value);
                        $this->_data[$key]['id'] = $id;
                    } else {
                        $id = $value['id'];
                        $this->getTable()->update($value, $this->getTable()->getAdapter()->quoteInto('id = ?', $id));
                    }
                }
                App::db()->commit();
                return true;
            }
            catch(Exception $e) {
                App::db()->rollBack();
                App::log($e->getMessage(), 3);
                return false;
            }
        }
    }

    /**
     * Walk through the collection and run model method or external callback
     * with optional arguments
     *
     * Returns array with results of callback for each item
     *
     * @param string $method
     * @param array $args
     * @return array
     */
    public function walk($callback, array $args = array())
    {
        $results = array();
        $useItemCallback = is_string($callback) && strpos($callback, '::') === false;
        foreach($this->_data as $id => $item) {
            if ($useItemCallback) {
                $cb = array($item , $callback);
            } else {
                $cb = $callback;
                array_unshift($args, $item);
            }
            $results[$id] = call_user_func_array($cb, $args);
        }
        return $results;
    }

    public function each($obj_method, $args = array())
    {
        foreach($args->_data as $k => $item) {
            $args->_data[$k] = call_user_func($obj_method, $item);
        }
    }

    public function reverse()
    {
        $this->_data = array_reverse($this->_data);
        return $this;
    }

    public function shuffle()
    {
        shuffle($this->_data);
        return $this;
    }

    /**
     * Clear collection
     */
    public function clear()
    {
        $this->setIsNewCollection();
        return $this;
    }
}
