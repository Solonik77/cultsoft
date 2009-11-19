<?php
/**
 * DatabaseTable: blog
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_DbTable_Blog extends App_Db_Table {
    protected $_primary = 'id';
    protected $_dependentTables = array('Blog_Model_DbTable_Blog_I18n');
    protected $_moduleTables = array('blog', 'blog_i18n', 'members', 'blog_members');

    public function getBlogTypes()
    {
        return array('private' => 'Personal blog' , 'collaborative' => 'Collaborative blog (community)');
    }

    public function loadBlogs($sortByField = 'id', $sortOrder = 'asc')
    {
        $sortOrder = strtoupper((($sortOrder === 'asc') ? $sortOrder : 'desc'));
        $cols = array();
        $cols['blog'] = array('id' , 'type' , 'date_created');
        $cols['blog_i18n'] = array('title');
        $fields = array_merge($cols['blog'], $cols['blog_i18n']);
        if (! in_array($sortByField, $fields)) {
            $sortByField = (string) $fields[0];
        }
        $select = $this->select()->setIntegrityCheck(false);
        $select->from('blog', $cols['blog']);
        $select->join('blog_i18n', DB_TABLE_PREFIX . 'blog.id = ' . DB_TABLE_PREFIX . 'blog_i18n.blog_id', $cols['blog_i18n']);
        $select->join('members', DB_TABLE_PREFIX . 'members.id = ' . DB_TABLE_PREFIX . 'blog.member_id', array('first_name' , 'last_name'));
        $select->where(DB_TABLE_PREFIX . 'blog_i18n.lang_id = ?', App::front()->getParam('requestLangId'))->order($sortByField . ' ' . $sortOrder);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(App::config()->items_per_page);
        $paginator->setCurrentPageNumber(App::front()->getRequest()->getParam('page', 1));
        return $paginator;
    }
}