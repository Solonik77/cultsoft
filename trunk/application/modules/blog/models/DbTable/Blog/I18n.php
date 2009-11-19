<?php
/**
 * Database table: Blog_I18n
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_DbTable_Blog_I18n extends App_Db_Table {
    protected $_referenceMap = array('Blog' => array('refTableClass' => 'Blog_Model_DbTable_Blog' , 'columns' => array('blog_id') , 'refColumns' => array('id')));
}