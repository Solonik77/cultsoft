<?php
/**
 * DatabaseTable: blog
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_DbTable_Blog extends App_Db_Table_Abstract {
    protected $_primary = 'id';
    protected $_dependentTables = array('Blog_Model_DbTable_I18n_Blog');
    protected $_moduleTables = array('blog', 'i18n_blog', 'members', 'blog_members');

    public function getBlogTypes()
    {
        return array('private' => 'Personal blog' , 'collaborative' => 'Collaborative blog (community)');
    }

    public function loadBlogs($sortByField = 'id', $sortOrder = 'asc')
    {
        $sortOrder = strtoupper((($sortOrder === 'asc') ? $sortOrder : 'desc'));
        $cols = array();
        $cols['blog'] = array('id' , 'type' , 'date_created');
        $cols['i18n_blog'] = array('title');
        $fields = array_merge($cols['blog'], $cols['i18n_blog']);
        if (! in_array($sortByField, $fields)) {
            $sortByField = (string) $fields[0];
        }
        $select = $this->select()->setIntegrityCheck(false);
        $select->from('blog', $cols['blog']);
        $select->join('i18n_blog', DB_TABLE_PREFIX . 'blog.id = ' . DB_TABLE_PREFIX . 'i18n_blog.blog_id', $cols['i18n_blog']);
        $select->join('members', DB_TABLE_PREFIX . 'members.id = ' . DB_TABLE_PREFIX . 'blog.member_id', array('first_name' , 'last_name'));
        $select->where(DB_TABLE_PREFIX . 'i18n_blog.lang_id = ?', App::front()->getParam('requestLangId'))->order($sortByField . ' ' . $sortOrder);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(App::config()->items_per_page);
        $paginator->setCurrentPageNumber(App::front()->getRequest()->getParam('page', 1));
        return $paginator;
    }
}