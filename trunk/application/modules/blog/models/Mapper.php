<?php
/**
 * Blog mapper
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_Mapper
{
	public function save(Blog_Model_Service $blog)
	{
		$this->getTable()->save($blog);
	}

	public function getTable()
	{

	}

	public function fetch($blog = null)
	{

	}

	public function setTable($table)
	{

	}
}