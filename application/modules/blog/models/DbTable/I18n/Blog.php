<?php
/**
 * Blog
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_DbTable_I18n_Blog extends App_Db_Table_Abstract
{
    protected $_primary = 'i18n_id';
    protected $_referenceMap = array('Blog' => array('refTableClass' => 'Blog_Model_DbTable_Blog' , 'columns' => array('blog_id') , 'refColumns' => array('id')));
}