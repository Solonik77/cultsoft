<?php
/**
 * Member database model
 *
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/engine/license.html
 */
class System_Model_DbTable_Site_Structure extends App_Db_Nestedsets {
	protected $_primary = 'id';
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}
