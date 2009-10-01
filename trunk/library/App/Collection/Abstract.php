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
    
    	/**
	 * Sets the total number of rows and stores the result locally.
	 *
	 * @param   mixed   $result query result
	 * @param   boolean $return_objects True for results as objects, false for arrays
	 * @return  void
	 */
	abstract public function __construct($result, $sql, $link, $return_objects);

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