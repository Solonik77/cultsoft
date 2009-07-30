<?php
/**
 * Blog service
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_Service
{
	private $_blog;

	public function __construct()
	{
		$this->_blog = new Blog_Model_DbTable_Blog();
	}

	public function getAllBlogs()
	{
		return $this->_blog->fetchAll();
	}
}