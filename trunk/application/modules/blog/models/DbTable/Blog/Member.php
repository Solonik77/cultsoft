<?php
/**
* Blog Member
*/
class Blog_DbTable_Blog_Member extends App_Db_Table_Abstract {
    protected $_primary = 'blog_id';
    protected $_referenceMap = array('Blog' => array('refTableClass' => 'Blog_DbTable_Blog' , 'columns' => array('blog_id') , 'refColumns' => array('id')) , 'Member' => array('refTableClass' => 'Main_DbTable_Members' , 'columns' => array('member_id') , 'refColumns' => array('id')));
}