<?php
/**
 *  Database table: blog_member
 */
class Blog_Model_DbTable_Blog_Member extends App_Db_Table {
    protected $_primary = 'blog_id';
    protected $_referenceMap = array('Blog' => array('refTableClass' => 'Blog_Model_DbTable_Blog' , 'columns' => array('blog_id') , 'refColumns' => array('id')) , 'Member' => array('refTableClass' => 'Main_Model_DbTable_Members' , 'columns' => array('member_id') , 'refColumns' => array('id')));
}