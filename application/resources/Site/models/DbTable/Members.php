<?php
/**
* Member database model
*
* @package Core
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/platform/license.html
*/
class Site_Model_DbTable_Members extends App_DB_Table {
 public function __construct()
 {
  parent::__construct();
 }

 /**
 * Get all member db fields as objects by his ID
 */
 public function getDataByID($id = 0)
 {
  return $this->fetchRow($this->select()->where('id = ?', $id));
 }
}
