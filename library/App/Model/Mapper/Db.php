<?php

abstract class App_Model_Mapper_Db
{
protected $_db;
  public function __construct() {
    $this->_db = App::db();
  }
}
}