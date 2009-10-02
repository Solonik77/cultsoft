<?php
/**
 * Data Collection
 *
 * @author Denysenko Dmytro
 */
abstract class App_Collection_Abstract implements Countable, SeekableIterator, ArrayAccess
{
    const SORT_ORDER_ASC    = 'ASC';
    const SORT_ORDER_DESC   = 'DESC';
        /**
     * Collection items
     *
     * @var array
     */
    protected $_items = array();
    
    /**
     * Iterator pointer.
     *
     * @var integer
     */
    protected $_pointer = 0;

    /**
     * How many data rows there are.
     *
     * @var integer
     */
    protected $_count;
    
     protected $_isCollectionLoaded;
    
    	/**
	 * Sets the total number of rows and stores the result locally.
	 *
	 * @return  void
	 */
	abstract public function __construct($resource);

	/**
	 * Result destruction cleans up all open result sets.
	 */
	abstract public function __destruct();
    
    
	/**
	 * Iterator: key
	 */
	public function key()
	{
		return $this->_pointer;
	}

	/**
	 * Iterator: next
	 */
	public function next()
	{
		++$this->_pointer;
		return $this;
	}

	/**
	 * Iterator: prev
	 */
	public function prev()
	{
		--$this->_pointer;
		return $this;
	}

	/**
	 * Iterator: rewind
	 */
	public function rewind()
	{
		$this->_pointer = 0;
		return $this;
	}

	/**
	 * Iterator: valid
	 */
	public function valid()
	{
		return $this->offsetExists($this->_pointer);
	}
    
    /*
    * Check if an offset exists
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[(int) $offset]);
    }

    /**
     * Get the row for the given offset
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @return Zend_Db_Table_Row_Abstract
     */
    public function offsetGet($offset)
    {
        $this->_pointer = (int) $offset;

        return $this->current();
    }
    
        /**
     * Does nothing
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Does nothing
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
    }
    
        /**
     * Retireve count of collection loaded items
     *
     * @return int
     */
    public function count()
    {
        $this->load();
        return count($this->_items);
    }
    
        /**
     * Return the current element.
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     */
    public function current()
    {
        if ($this->valid() === false) {
            return null;
        }
        
        return;
    }
    
        /**
     * Take the Iterator to position $position
     * Required by interface SeekableIterator.
     *
     * @param int $position the position to seek to
     */
    public function seek($position)
    {
        $position = (int) $position;
        if ($position < 0 || $position >= $this->_count) {
            require_once 'Zend/Db/Table/Rowset/Exception.php';
            throw new Zend_Db_Table_Rowset_Exception("Illegal index $position");
        }
        $this->_pointer = $position;
        return $this;
    }
    

    /**
     * Retrieve collection loading status
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->_isCollectionLoaded;
    }

    /**
     * Set collection loading status flag
     *
     * @param boolean $flag
     */
    protected function _setIsLoaded($flag = true)
    {
        $this->_isCollectionLoaded = (bool) $flag;
        return $this;
    }
    
   /**
     * Clear collection
     */
    public function clear()
    {
        $this->_setIsLoaded(false);
        $this->_items = array();
        return $this;
    }
    
    public function each($obj_method, $args=array())
    {
        foreach ($args->_items as $k => $item) {
            $args->_items[$k] = call_user_func($obj_method, $item);
        }
    }

}