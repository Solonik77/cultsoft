<?php
/**
 * Blog
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_DbTable_Blog extends App_Db_Table_Abstract
{
    protected $_primary = 'id';
	protected $_dependentTables = array('Blog_Model_DbTable_I18n_Blog');
}