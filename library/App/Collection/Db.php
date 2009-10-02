<?php
/**
 * Database data collection
 *
 * @author Denysenko Dmytro
 */
class App_Collection_Db extends App_Collection_Abstract
{
    	/**
	 * Sets the total number of rows and stores the result locally.
	 *
	 * @return  void
	 */
	public function __construct($table)
    {
    }

	/**
	 * Result destruction cleans up all open result sets.
	 */
	public function __destruct()
    {
    }
}